
import jQuery from 'jquery';
window.$ = jQuery;

// import toastre from 'toastr';
// window.tst = toastre;

window.onload = function(){
    $("#new_token").prop("checked", false);
    $("#pre_token").prop("checked", false);
    $("#adm_new_token").prop("checked", false);
    $("#adm_pre_token").prop("checked", false);
}

$('#new_token').on('click', function(){
    $("#formSubmitBox").removeAttr('hidden');
    $("#formViewBox").attr('hidden','hidden');
});

$('#pre_token').on('click', function(){
    $("#formViewBox").removeAttr('hidden');
    $("#formSubmitBox").attr('hidden','hidden');

    let baseUrl = "/AllcloseTicket";
    $.ajax({
        method: "GET",
        enctype: 'multipart/form-data',
        url: baseUrl,
        dataType: 'json',
        success: function (data) {
            $('.ClosedTicketUSR').html('');
            $('.ClosedTicketUSR').html(data.message);
        },
        error: function (data, textStatus, errorThrown) {
            alert(data.message);
        }
    });
});

$('#adm_new_token').on('click', function(){
    $("#OpenViewBox").removeAttr('hidden');
    $("#ClosedViewBox").attr('hidden','hidden');

    $('.adminCard').load(document.URL +  ' .adminCard', function(){
        $('.adminCard').fadeIn('slow');
    });
});

$('#adm_pre_token').on('click', function(){
    $("#ClosedViewBox").removeAttr('hidden');
    $("#OpenViewBox").attr('hidden','hidden');
    let baseUrl = "/AllcloseTicket";
    $.ajax({
        method: "GET",
        enctype: 'multipart/form-data',
        url: baseUrl,
        dataType: 'json',
        success: function (data) {
            $('.ClosedTicketADM').html('');
            $('.ClosedTicketADM').html(data.message);
        },
        error: function (data, textStatus, errorThrown) {
            alert(data.message);
        }
    });
});

$.ajaxSetup({headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content")}});

$(document).on('click', '#btn_sub_ticket', function (event){
    event.preventDefault();
    let baseUrl = "/submitTicket";
    var form = $('#submitTicketform')[0];
    var formdata = new FormData(form);
    $.ajax({
        method: "POST",
        enctype: 'multipart/form-data',
        url: baseUrl,
        dataType: 'json',
        data: formdata,
        contentType: false,
        cache: false,
        processData:false,
        success: function (data) {
            $('.userCard').load(document.URL +  ' .userCard', function(){
                $('.userCard').fadeIn('slow');
                alert(data.message);
            });
        },
        error: function (data, textStatus, errorThrown) {
            alert(data.message);
        }
    });
});

$(document).on('click', '#btn_reply_ticket', function (event){
    event.preventDefault();
    let baseUrl = "/replyTicket";
    var form = $('#replyTicketform')[0];
    var formdata = new FormData(form);
    $.ajax({
        method: "POST",
        enctype: 'multipart/form-data',
        url: baseUrl,
        dataType: 'json',
        data: formdata,
        contentType: false,
        cache: false,
        processData:false,
        success: function (data) {
            $('.userCard').load(document.URL +  ' .userCard', function(){
                $('.userCard').fadeIn('slow');
                alert(data.message);
            });
        },
        error: function (data, textStatus, errorThrown) {
            alert(data.message);
        }
    });
});

$(document).on('click', '.BtnUSR', function (event){
    event.preventDefault();
    $('.ShowHide').attr('style', 'display: none;');
    let uID = $(this).attr("uid");
    let sub = $(this).attr("subTitle");
    $('.trID'+uID).attr('class', 'ShowHide trID'+uID);
    $('.trID'+uID).attr('style', 'display: collapse;');
    $('.detailsView').html('');
    let baseUrl = '/showUSRTicket';
    let sDiv = 'showTicketDetails'+uID;
    ShowDetailsTicket(baseUrl,uID,'Open',sub,sDiv);
});

$(document).on('click', '.btn_ADMreply_ticket', function (event){
    event.preventDefault();
    let uID = $(this).attr('user_ID');
    let sub = $(this).attr("subTitle");
    let baseUrl = "/ADMreplyTicket";
    var form = $('#ADMreplyTicketform')[0];
    var formdata = new FormData(form);
    $.ajax({
        method: "POST",
        enctype: 'multipart/form-data',
        url: baseUrl,
        dataType: 'json',
        data: formdata,
        contentType: false,
        cache: false,
        processData:false,
        success: function (data) {
            let baseUrl = '/showUSRTicket';
            let sDiv = 'showTicketDetails'+uID;
            ShowDetailsTicket(baseUrl,uID,'Open',sub,sDiv);
            alert(data.message);
        },
        error: function (data, textStatus, errorThrown) {
            alert(data.message);
        }
    });
});

$(document).on('click', '.btn_ADMcancel_ticket', function (event){
    event.preventDefault();
    let uID = $(this).attr('user_ID');
    let baseUrl = "/ADMcloseTicket";
    if(confirm("Please wait until changed!!")){
        $.ajax({
            method: "POST",
            enctype: 'multipart/form-data',
            url: baseUrl,
            dataType: 'json',
            data: {'usrId':uID},
            success: function (data) {
                $('.adminCard').load(document.URL +  ' .adminCard', function(){
                    $('.adminCard').fadeIn('slow');
                    alert(data.message);
                });
            },
            error: function (data, textStatus, errorThrown) {
                alert(data.message);
            }
        });
    }
});

$(document).on('click', '.AllBtnUSR', function (event){
    event.preventDefault();
    $('.AllShowHide').attr('style', 'display: none;');
    var uID = $(this).attr("uid");
    let sub = $(this).attr("subTitle");
    let rw = $(this).attr("rowID");
    $('.AlltrID'+uID+'_'+rw).attr('class', 'AllShowHide AlltrID'+uID+'_'+rw);
    $('.AlltrID'+uID+'_'+rw).attr('style', 'display: collapse;');
    $('.AlldetailsView').html('');
    let baseUrl = '/showUSRTicket';
    let sDiv = 'AllshowTicketDetails'+uID+'_'+rw;
    ShowDetailsTicket(baseUrl,uID,'Closed',sub,sDiv);
});

function ShowDetailsTicket(baseUrl,uID,stat,subject,showDiv){
    $.ajax({
        method: "GET",
        url: baseUrl,
        data: {'userID':uID, 'status':stat, 'subj':subject},
        dataType: 'json',
        success: function (data) {
            $('#'+showDiv).html('');
            $('#'+showDiv).html(data.message);
        },
        error: function (data, textStatus, errorThrown) {
            alert(data.message);
        }
    });
}


