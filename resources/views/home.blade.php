@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(Auth::user()->role == 'ADM')
                <div class="card border border-info-subtle">
                    <div class="card-header border-bottom border-info-subtle">{{ __('Admin Dashboard') }}</div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div id="adm_chk_box">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="AdmCHKOption" id="adm_new_token">
                                    <label class="form-check-label" for="AdmCHKOption">Open Ticket</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="AdmCHKOption" id="adm_pre_token">
                                    <label class="form-check-label" for="AdmCHKOption">Closed Ticket</label>
                                </div>
                            </div>
                            <div id="OpenViewBox" hidden>
                                <div class="container padding-bottom-3x mb-2">
                                    <div class="row">
                                        <div class="adminCard">
                                            <div class="padding-top-2x mt-2 hidden-lg-up"></div>
                                            <div class="table-responsive margin-bottom-2x">
                                                <table class="table margin-bottom-none USRlistTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Date Submitted</th>
                                                            <th>Last Updated</th>
                                                            <th>User Name</th>
                                                            <th>Type</th>
                                                            <th>Priority</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($getTicket as $tl)
                                                            <tr>
                                                                <td>{{date_format($tl->created_at,"d-m-Y")}}</td>
                                                                <td>{{date_format($tl->updated_at,"d-m-Y")}}</td>
                                                                <td><button type="button" class="BtnUSR btn btn-link" uid="{{$tl->userID}}">{{$tl->custNm}}</button></td>
                                                                <td>{{$tl->subject}}</td>
                                                                <td><span class="text-warning">High</span></td>
                                                                <td><span class="text-primary">{{$tl->token_type}}</span></td>
                                                            </tr>
                                                            <tr style="display: none;" class="trID{{$tl->userID}}">
                                                                <td colspan="6">
                                                                    <div class="detailsView" id="showTicketDetails{{$tl->userID}}"></div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="ClosedViewBox" hidden>
                                <div class="text-center">
                                    <label>Comming Soon !!</label>
                                </div>
                            </div>
                        </div>
                </div>
            @else
                <div class="card border border-info-subtle">
                    <div class="card-header border-bottom border-info-subtle">{{ __('User Dashboard') }}</div>
                        <div class="card-body userCard">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if ($usrStat=='Closed' || $usrStat == "")
                                <div id="senderDiv">
                                    <div id="chk_box">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="CHKOption" id="new_token">
                                            <label class="form-check-label" for="new_token">Create Ticket</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="CHKOption" id="pre_token">
                                            <label class="form-check-label" for="pre_token">Closed Ticket</label>
                                        </div>
                                    </div>
                                    <div id="formSubmitBox" hidden>
                                        <form name="submitTicketform" id="submitTicketform" method="post">
                                            <input type="text" id="user_id" name="user_id" class="form-control" value="{{Auth::user()->id}}" hidden>
                                            <div class="form-group">
                                                <label for="userName">Name</label>
                                                <input type="text" id="user_name" name="user_name" class="form-control" value="{{Auth::user()->name}}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="subjectTitle">Subject</label>
                                                <input type="text" id="sub_title" name="sub_title" class="form-control" required="">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Description</label>
                                                <textarea name="description" class="form-control" required="" rows="8"></textarea>
                                            </div>
                                            <div style ="padding-top:1em; display: flex; justify-content: flex-end">
                                                <button type="submit" id="btn_sub_ticket" class="btn btn-primary algin-right">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="formViewBox" hidden>
                                        <div class="text-center">
                                            <label>Comming Soon !!</label>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div id="receiverDiv">
                                    <div class="container padding-bottom-3x mb-2">
                                        <div class="row">
                                            <div>
                                                <div class="padding-top-2x mt-2 hidden-lg-up"></div>
                                                <div class="table-responsive margin-bottom-2x">
                                                    <table class="table margin-bottom-none">
                                                        <thead>
                                                            <tr>
                                                                <th>Date Submitted</th>
                                                                <th>Last Updated</th>
                                                                <th>Type</th>
                                                                <th>Priority</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>{{date_format($getTicket[0]->created_at,"d-m-Y")}}</td>
                                                                <td>{{date_format($getTicket[0]->updated_at,"d-m-Y")}}</td>
                                                                <td>{{$getTicket[0]->subject}}</td>
                                                                <td><span class="text-warning">High</span></td>
                                                                <td><span class="text-primary">{{$getTicket[0]->token_type}}</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @foreach ($getTicket as $ls)
                                                    <!-- Messages-->
                                                    @if($ls->replyID == 0 || $ls->replyID == null)
                                                        <div class="comment">
                                                            <div class="comment-author-ava"><img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="Avatar"></div>
                                                            <div class="comment-body">
                                                                <p class="comment-text text-black text-justify">{{$ls->descriptions}}</p>
                                                                <div class="comment-footer"><span class="comment-meta">{{$ls->custNm}}</span></div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="comment">
                                                            <div class="comment-author-ava"><img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="Avatar"></div>
                                                            <div class="comment-body">
                                                                <p class="comment-text text-black text-justify">{{$ls->descriptions}}</p>
                                                                <div class="comment-footer"><span class="comment-meta">{{$ls->adminNm}}</span></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <!-- Reply Form-->
                                                <h5 class="mb-30 padding-top-1x">Reply Message</h5>
                                                <form name="replyTicketform" id="replyTicketform" method="post">
                                                    <input type="text" id="reply_id" name="reply_id" class="form-control" value="{{Auth::user()->id}}" hidden>
                                                    <div class="form-group">
                                                        <textarea class="form-control form-control-rounded" id="reply_text" name="reply_text" rows="8" placeholder="Write your message here..." required=""></textarea>
                                                    </div>
                                                    <div style="padding-top:1em; display: flex; justify-content: flex-end">
                                                        <button class="btn btn-outline-primary" id="btn_reply_ticket" type="submit">Reply</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
