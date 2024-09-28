
import jQuery from 'jquery';
window.$ = jQuery;

window.onload = function(){
    $("#new_token").prop("checked", false);
    $("#pre_token").prop("checked", false);
    $("#adm_new_token").prop("checked", false);
    $("#adm_pre_token").prop("checked", false);

    $('#new_token').on('click', function(){
        $("#formSubmitBox").removeAttr('hidden');
        $("#formViewBox").attr('hidden','hidden');
    });
    $('#pre_token').on('click', function(){
        $("#formViewBox").removeAttr('hidden');
        $("#formSubmitBox").attr('hidden','hidden');
    });

    $('#adm_new_token').on('click', function(){
        $("#OpenViewBox").removeAttr('hidden');
        $("#ClosedViewBox").attr('hidden','hidden');
    });
    $('#adm_pre_token').on('click', function(){
        $("#ClosedViewBox").removeAttr('hidden');
        $("#OpenViewBox").attr('hidden','hidden');
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
        var uID = $(this).attr("uid");
        $('.trID'+uID).attr('class', 'ShowHide trID'+uID);
        $('.trID'+uID).attr('style', 'display: collapse;');
        $('.detailsView').html('');
        ShowDetailsTicket(uID);
    });

    $(document).on('click', '.btn_ADMreply_ticket', function (event){
        event.preventDefault();
        let uID = $(this).attr('user_ID');
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
                ShowDetailsTicket(uID)
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
    });

    function ShowDetailsTicket(uID){
        let baseUrl = '/showUSRTicket';
        $.ajax({
            method: "GET",
            url: baseUrl,
            data: {'userID':uID},
            dataType: 'json',
            success: function (data) {
                $('#showTicketDetails'+uID).html('');
                $('#showTicketDetails'+uID).html(data.message);
            },
            error: function (data, textStatus, errorThrown) {
                alert(data.message);
            }
        });
    }
}

