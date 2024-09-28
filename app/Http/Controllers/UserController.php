<?php

namespace App\Http\Controllers;

use App\Mail\ADMMailSender;
use App\Mail\USRMailSender;
use App\Models\Eticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function storeTicket(Request $request)
    {
        if (request()->ajax()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'sub_title' => 'required|string|max:255',
                    'description' => 'required|string|max:255',
                ],
                [],
                [
                    'sub_title' => 'subject title',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => strval(implode("<br>", $validator->errors()->all()))]);
            } else {
                $getOpenToken = Eticket::where('userID', $request['user_id'])->where('token_type', 'Open')->first();
                if($getOpenToken =="" || $getOpenToken == null)
                {
                    $inputTicket = array(
                        'userID' => $request['user_id'],
                        'subject' => $request['sub_title'],
                        'descriptions' => $request['description'],
                        'token_type' => 'Open',
                    );

                    $step = Eticket::create($inputTicket);
                    $step->save();

                    $content = [
                        'subject' => $request['sub_title'],
                        'cust_ID' => 'Customer ID: '.$request['user_id'],
                        'body' => $request['description'],
                    ];

                    $admin = User::where('role', 'ADM')->get();
                    foreach($admin as $ad){
                        Mail::to($ad->email)->send(new USRMailSender($content));
                    }

                    return response()->json(['success' => true, 'message' => 'Your ticket submitted successfully!!']);
                }
                else{
                    return response()->json(['success' => false, 'message' => 'Your one ticket is already opened!!']);
                }
            }
        }
    }

    public function updateTicket(Request $request)
    {
        if (request()->ajax()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'reply_text' => 'required|string|max:255',
                ],
                [],
                [
                    'reply_text' => 'description',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => strval(implode("<br>", $validator->errors()->all()))]);
            } else {
                $getsubject = Eticket::where('userID', $request['reply_id'])->where('token_type', 'Open')->first();
                if($getsubject !="" || $getsubject != null)
                {
                    $replyTicket = array(
                        'userID' => $request['reply_id'],
                        'subject' => $getsubject['subject'],
                        'descriptions' => $request['reply_text'],
                        'token_type' => 'Open',
                    );

                    $step = Eticket::create($replyTicket);
                    $step->save();

                    return response()->json(['success' => true, 'message' => 'Your ticket submitted successfully!!']);
                }
                else{
                    return response()->json(['success' => false, 'message' => 'You have no open ticket is available!!']);
                }
            }
        }
    }

    public function detailsTicket(Request $request)
    {
        if (request()->ajax())
        {
            $uID = $request['userID'];
            $conversation = Eticket::leftJoin('users as u', 'u.id', '=', 'etickets.userID')
            ->leftJoin('users as adm', 'adm.id', '=', 'etickets.replyID')
            ->where('userID', $uID)->where('token_type', 'Open')->get(['etickets.*', 'u.name as custNm', 'adm.name as adminNm']);

            $htmlString ='';
            foreach ($conversation as $conv)
            {
                if($conv->replyID == 0 && $conv->replyID == null)
                {
                    // BEGIN From Me Message
                    $htmlString .= '<div class="comment">
                                        <div class="comment-author-ava"><img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="Avatar"></div>
                                        <div class="comment-body">
                                            <p class="comment-text text-black text-justify">'.$conv->descriptions.'</p>
                                            <div class="comment-footer"><span class="comment-meta">'.$conv->custNm.'</span></div>
                                        </div>
                                    </div>';
                    // END From Me Message
                }
                else
                {
                    // BEGIN From Them Message
                    $htmlString .= '<div class="comment">
                                        <div class="comment-author-ava"><img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="Avatar"></div>
                                        <div class="comment-body">
                                            <p class="comment-text text-black text-justify">'.$conv->descriptions.'</p>
                                            <div class="comment-footer"><span class="comment-meta">'.$conv->adminNm.'</span></div>
                                        </div>
                                    </div>';
                    // END From Them Message
                }
            }

            // Reply Form
            $htmlString .= '<h5 class="mb-30 padding-top-1x">Reply Message</h5>
            <form name="ADMreplyTicketform" id="ADMreplyTicketform" method="post">
                <input type="text" id="ADMuser_id" name="ADMuser_id" class="form-control" value="'.$uID.'" hidden>
                <div class="form-group">
                    <textarea class="form-control form-control-rounded" id="ADMreply_text" name="ADMreply_text" rows="8" placeholder="Write your message here..." required=""></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div style="padding-top:1em; display: flex; justify-content: flex-start">
                            <button class="btn btn-outline-danger btn_ADMcancel_ticket" id="btn_ADMcancel_ticket" user_ID="'.$uID.'">Closed Ticket</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding-top:1em; display: flex; justify-content: flex-end">
                            <button class="btn btn-outline-primary btn_ADMreply_ticket" id="btn_ADMreply_ticket" type="submit" user_ID="'.$uID.'">Reply</button>
                        </div>
                    </div>
                </div>
            </form>';

            return response()->json(['success' => true, 'message' => $htmlString]);
        }
    }

    public function ADMupdateTicket(Request $request)
    {
        if (request()->ajax()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'ADMreply_text' => 'required|string|max:255',
                ],
                [],
                [
                    'ADMreply_text' => 'description',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => strval(implode("<br>", $validator->errors()->all()))]);
            } else {
                $getsubject = Eticket::where('userID', $request['ADMuser_id'])->where('token_type', 'Open')->first();
                if($getsubject !="" || $getsubject != null)
                {
                    $ADMreplyTicket = array(
                        'userID' =>$request['ADMuser_id'],
                        'replyID' => Auth::user()->id,
                        'subject' => $getsubject['subject'],
                        'descriptions' => $request['ADMreply_text'],
                        'token_type' => 'Open',
                    );

                    $step = Eticket::create($ADMreplyTicket);
                    $step->save();

                    return response()->json(['success' => true, 'message' => 'Message sent done!!']);
                }
                else{
                    return response()->json(['success' => false, 'message' => 'This user has no available open ticket!!']);
                }

            }
        }
    }

    public function ADMclosedTicket(Request $request)
    {
        if (request()->ajax()) {
            $openTicket = Eticket::where('userID', $request['usrId'])->where('token_type', 'Open')->get();
            foreach($openTicket as $data)
            {
                $data->token_type = 'Closed';
                $data->update();
            }

            $user = User::where('id', $request['usrId'])->first();

            $content = [
                'cust_name' => $user->name,
                'cust_ID' => $request['usrId'],
                'admin_name' => Auth::user()->name,
            ];
            Mail::to($user->email)->send(new ADMMailSender($content));

            return response()->json(['success' => true, 'message' => 'Closed the ticket successfully!!']);
        }
    }
}
