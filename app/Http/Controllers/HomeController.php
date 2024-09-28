<?php

namespace App\Http\Controllers;

use App\Models\Eticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
    public function index()
    {
        $usrStat = '';
        $getTicket = [];

        if(Auth::user()->role == 'USR')
        {
            /* user part */
            $usrcount = Eticket::where('token_type', 'Open')->where('userID', Auth::user()->id)->count();
            if($usrcount>0)
            {
                $usrStat = 'Opened';
                $getTicket = Eticket::leftJoin('users as u', 'u.id', '=', 'etickets.userID')
                ->leftJoin('users as adm', 'adm.id', '=', 'etickets.replyID')
                ->where('userID', Auth::user()->id)->where('token_type', 'Open')->get(['etickets.*', 'u.name as custNm', 'adm.name as adminNm']);
            }
            else{
                $usrStat = 'Closed';
            }
            /* end user part */
        }
        else{
            /* admin part */
            $admcount = Eticket::where('token_type', 'Open')->count();
            if($admcount>0)
            {
                $usrStat = 'Opened';
                $getTicket = Eticket::groupBy('userID')->groupBy('token_type')
                ->leftJoin('users as u', 'u.id', '=', 'etickets.userID')
                ->where('token_type', 'Open')
                ->selectRaw("max(etickets.userID) as userID")
                ->selectRaw("max(etickets.replyID) as replyID")
                ->selectRaw("max(etickets.subject) as subject")
                ->selectRaw("max(etickets.token_type) as token_type")
                ->selectRaw("max(etickets.created_at) as created_at")
                ->selectRaw("max(etickets.updated_at) as updated_at")
                ->selectRaw('max(u.name) as custNm')
                ->get();
            }
            else{
                $usrStat = 'Closed';
            }
            /* end admin part */
        }

        return view('home', compact(['getTicket', 'usrStat']));
    }
}
