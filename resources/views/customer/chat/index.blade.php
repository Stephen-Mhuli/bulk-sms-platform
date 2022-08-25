@extends('layouts.customer')

@section('title') Chats @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <style>
        .search-box {
            background: #fafafa;
            padding: 0px 0px;
            border-radius: 14px;
        }

        .input-wrapper {
            padding: 13px;
            background: #eee;
            border-radius: 20px;
        }

        i {
            /*color: grey;*/
            vertical-align: middle;
        }

        input {
            border: none;
            border-radius: 30px;
            width: 80%;
            height: 35px;
            background: #eeeeee;
        }

        ::placeholder {
            font-weight: 300;
            margin-left: 20px;
        }

        :focus {
            outline: none;
        }

        .friend-drawer {
            display: flex;
            vertical-align: baseline;
            background: #fff;
            transition: .3s ease;
        }

        --grey {
            background: #eee;
        }

        .text {
            margin-left: 12px;
            width: 100%;
        }


        p {
            margin: 0;
        }

        --onhover:hover {
            background: blue;
            cursor: pointer;
        }

        hr {
            margin: 5px auto;
            width: 60%;
        }

        .chat-bubble--left {
            padding: 10px 14px;
            background: #eee;
            margin: 10px;
            border-radius: 9px;
            position: relative;

        :after {
            content: '';
            position: absolute;
            top: 50%;
            width: 0;
            height: 0;
            border: 20px solid transparent;
            border-bottom: 0;
            margin-top: -10px;
        }

        --left {

        :after {
            left: 0;
            border-right-color: #eee;
            border-left: 0;
            margin-left: -20px;
        }

        }

        --right {

        :after {
            right: 0;
            border-left-color: blue;
            border-right: 0;
            margin-right: -20px;
        }

        }
        }
        .chat-bubble--right {
            padding: 10px 14px;
            background: #d3d7de;
            margin: 10px;
            border-radius: 9px;
            position: relative;
        }

        .offset-md-9 {

        .chat-bubble {
            background: blue;
            color: #fff;
        }

        }
        .chat-box-tray {
            display: flex;
            padding: 10px 15px;
            align-items: center;
            margin-top: 65px;
            bottom: 0;
        }
        input {
            margin: 0 10px;
            padding: 6px 2px;
        }

        .chat-box-body {
            height: calc(100vh - 330px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .chat-box-side {
            height: calc(100vh - 403px);
            overflow-y: auto;
        }

        .icon {
            display: none;
            color: black;
        }

        @media (max-width: 700px) {
            .icon {
                display: block !important;
            }
        }

        .selectBox {
            height: 40px;
            border: 0;
            margin-right: 16px;
            border-radius: 10px;
            padding: 5px;
        }

        .label {
            width: 100% !important;
        }

        .list-group-item-light.list-group-item-action.active {
            background-color: #3c8dbc;
            border-color: #3c8dbc;
        }

        .loading {
            position: relative;
            top: 50%;
            right: 0;
            bottom: 0;
            left: 0;
            background: #fff;
        }
        .loader {
            left: 50%;
            margin-left: -4em;
            font-size: 10px;
            border: .8em solid rgba(218, 219, 223, 1);
            border-left: .8em solid rgba(58, 166, 165, 1);
            animation: spin 1.1s infinite linear;
        }
        .loader, .loader:after {
            border-radius: 50%;
            width: 8em;
            height: 8em;
            display: block;
            position: absolute;
            top: 50%;
            margin-top: -4.05em;
        }

        @keyframes spin {
            0% {
                transform: rotate(360deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }
        .send i{
            font-size: 28px;
        }
        .send i:hover{
           color:#007bff;
        }
        .message-sent-time {
            color: black;
            float: right;
            margin: -10px 11px 0px 0px;
            font-size: 12px;
        }
.chat_heading{
    padding: 0px !important;
}
        .response_value{
            padding: 10px 0px 10px 20px;
            cursor: pointer;
            color: black !important;
            border-bottom: 0.5px solid #e0e2e6;
        }
        #showResponse{
            width: 300px;
            position: absolute;
            z-index: 99;
            background: white;
            color: black;
            bottom: 105px;
            min-height: 100px;
            max-height: 250px;
            overflow-y: auto;
            padding: 10px 0px 10px 0px;
        }
        .type_message_section{
            width: 100%;
        }
        .card{
            border-radius: 14px;
        }
        .inbox-heading{
            padding: 10px 0px 4px 15px;
        }
        .card-body{
            padding: 0px 10px !important;
        }
        .daterangepicker.show-calendar{
            bottom: auto !important;
            top: 28% !important;
            left: 15% !important;
        } .search-chat-head{
              border-bottom: hidden !important;
              background: white !important;
          }
        .search-chat-head:hover{
            border-bottom: hidden !important;
            background: white !important;
        }
        .search-section .input-group-text{
            border-radius: 15px 0px 0px 14px;
        }
        .search-section input{
            border-radius: 0px 15px 15px 0px;
        }

        #filterBy {
            border-radius: 15px;
        }
        #sortBy {
            border-radius: 15px;
        }
        #dropdownMenuButton{
            font-size: 13px !important;
            padding: 9px !important;
        }
        .filter-section {
            list-style-type: none;
        }
        .chat-number-section .date-section{
            padding: 7px;
            border-radius: 18px;
            background: #eeeeeeb8;
        }
        .chat-number-section .number-section{
            padding: 7px;
            border-radius: 18px;
            background: #eeeeeeb8;
        }
        .c-pointer{
            cursor: pointer;
        }
        .chat-number-section {
            overflow-x: hidden;
        }
        .exception_modal_btn {
            border-radius: 20px;
        }
        .grade-list{
            max-height: 200px;
            overflow-y: scroll;
        }
        .paginate-icon{
            font-size: 22px !important;
        }
        #search{
            padding:5px 3px 5px 5px !important;
        }
        #gradeList {
            max-height: 200px;
            overflow-y: scroll;
        }
        .settings-tray{
            border-bottom: 1px solid grey;
        }
        .btn-danger{
            background-color: #f51026 !important;
            border-color: #f51026 !important;
        }
        #addException{
            color: black !important;
            background-color: yellow !important;
            border-color: yellow !important;
        }
        .ajax-loader{
            min-width: 100%;
            min-height: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
        }


        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background-color: #ebebeb;
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            -webkit-border-radius: 10px;
            border-radius: 10px;
            background: #6d6d6d;
        }
        .dateTime{
           font-size: 12px;
            color: #808080;
            position: relative;
            top: -15px;
            left: 25%;
        }
        .number-section-label{
            position: absolute;
            right: 0;
            top: 17px;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid" id="wrapper">
        <div class="row">
            <div class="col-md-4 col-xl-4 col-sm-5">
                <div class="card p-2 mt-3">
                    <div class="card-heading">
                        <div class="inbox-heading"><h5><i class="fa fa-inbox mr-3"></i>Inbound Message</h5></div>
                    </div>
                    <div class="card-body">
                        <div class="border-end bg-white" id="sidebar-wrapper">
                            <div class="list-group list-group-flush">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item list-group-item-action search-chat-head list-group-item-light chat_heading">
                                        <div class="row">
                                            <div class="col-12 mt-3">
                                               <div class="search-section">
                                                   <div class="input-group">
                                                       <div class="input-group-prepend">
                                                           <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                       </div>
                                                       <input type="text" class="form-control float-right" placeholder="Search..." id="search">
                                                   </div>
                                               </div>
                                            </div>
                                            <div class="col-6 col-md-12 col-lg-6 mt-3">
                                                <div class="dropdown filter-section">
                                                    <button class="btn btn-default dropdown-toggle filter-by" type="button"
                                                            id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        Filter By
                                                    </button>

                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                        <li><a class="dropdown-item filter-by" data-type="date"
                                                               href="#">Date</a></li>
                                                        <li class=""><a class="dropdown-item dropdown-toggle" href="#">Label</a>
                                                            <ul class="dropdown-menu" style="top: 25px!important; left: 160px!important;" id="gradeList">
                                                                @foreach($labels as $label)
                                                                    <li><a style="color: {{$label->color}}" href="#" data-id="{{$label->id}}"
                                                                           class="dropdown-item filtered_by_label">{{ucfirst($label->title)}}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </div>


                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="hidden" class="form-control float-right"
                                                               id="reservation">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-12 col-lg-6 mt-3 mt-md-1 mt-lg-3">
                                                <select name="sort_by" class="form-control" id="sortBy">
                                                    <option value="">Sort By</option>
                                                    <option selected value="new">Newest</option>
                                                    <option value="old">Oldest</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ajax-loader d-none">
                                        <img src="{{asset('images/ezgif-1-7aba96d47e.gif')}}" alt="">

                                    </div>
                                    <div class="chat-box-side mt-3 chat-number-section pr-2" id="numb" data-current-page="2">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-center pt-3">
                                <button class="btn btn-sm mr-3 disabled" id="previous-page"><i class="fa fa-chevron-left paginate-icon text-success"></i></button>
                                <span>Pages <span class="ml-2" id="cPage">1</span></span>
                                <button class="btn btn-sm ml-3" id="next-page"><i class="fa fa-chevron-right paginate-icon text-success"></i></button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xl-8 col-sm-7">
                <!-- Page content wrapper-->
                <div class="card p-0 mt-3">
                    <div id="page-content-wrapper w-100">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="settings-tray">
                                        <div class="friend-drawer no-gutters friend-drawer--grey">
                                            <div class="text">
                                                <p class="text-muted mt-2" id="mess"></p>
                                                <div class="form-group label pt-2 d-none" id="label">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <button class="btn exception_modal_btn btn-warning btn-sm" data-type="add" id="addException" type="button">Suppress</button>
                                                            <button class="btn exception_modal_btn btn-danger btn-sm d-none" data-type="delete" id="removeException" type="button">Suppress</button>
                                                            <div style="display: initial">
                                                                <a class="ml-2" id="contactAddress" target="_blank"></a>
                                                                <button class="btn btn-sm btn-success ml-3" id="sendNumberInfo">Push</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 text-center" id="contactName">

                                                        </div>
                                                        <div class="col-lg-3">
                                                            <select class="form-control label" name="label" id="select-label">
                                                                @foreach($labels as $label)
                                                                    <option style="color: {{isset($label->color)?$label->color:''}}" value="{{$label->id}}">{{ucfirst($label->title)}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <a type="button"
                                                               class="btn btn-info save d-none text-white">@lang('admin.form.button.save')</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat-box-body" id="to-chat" data-current-chats="2"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 p-0">
                                    <div class="position-relative">
                                        <div class="chat-box-tray bg-send">
                                            <i class="fa fa-envelope"></i>
                                            <div class="mx-2 type_message_section">
                                            <textarea class="type_message form-control" autocomplete="off" type="text"
                                                placeholder="Type your message here..." cols="2" rows="2" id="message"></textarea>
                                                <small class="float-right" id="count">Characters left: 160</small>
                                            </div>

                                            <select class="selectBox" id="number">
                                                @foreach($numbers as $number)
                                                    <option value="{{$number->number}}">{{$number->number}}</option>
                                                @endforeach
                                            </select>
                                            <a href="#!" class="send text-white"><i class="fa fa-paper-plane"></i></a>
                                        </div>
                                        <div id="showResponse" class="d-none">
                                            @if($chat_responses->isNotEmpty())
                                                @foreach($chat_responses as $chat_response)
                                                    <h6 data-title="{{isset($chat_response->content)?$chat_response->content:''}}"
                                                        class="response_value">{{$chat_response->title}}</h6>
                                                @endforeach
                                            @else
                                                <h6 class="response_value">No Data Available</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="exceptionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exception</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-dark exception_message"></p>
                    <div class="d-none form-group mt-3" id="check_new_contact">
                        <input type="checkbox" class="float-left" id="checkInput" name="check_new_contact" style="width: 5% !important;"> <label for="checkInput" class="ml-2 mt-2">Do you want to add this number in your contact list?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveException"  class="btn btn-primary">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="active_number">

    <div class="modal" id="addNewContact" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-dark">Do you want to add this number in your contact list?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="addNewContactBtn"  class="btn btn-primary">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
  @php $settings = auth('customer')->user()->settings->where('name', 'data_posting')->first();
  $settings = isset($settings) && isset($settings->value)?json_decode($settings->value):'';
  @endphp
    <!-- Modal -->
    <div class="modal fade" id="sendCInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Send</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Select Method</label>
                        <select name="" class="form-control" id="sendUrlMethod">
                            <option {{isset($settings->type) && $settings->type=='get'?'selected':''}} value="get">GET</option>
                            <option {{isset($settings->type) && $settings->type=='post'?'selected':''}} value="post">POST</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Enter Url</label>
                        <input type="text" id="sendUrl" value="{{isset($settings->url)?$settings->url:''}}" placeholder="Enter Url"  class="form-control">
                        <small class="text-danger" id="altMessage"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="confirmUrlSend" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="filtered_by_label">
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap-4-navbar.js')}}"></script>

    <script>
        $(document).on('click','.filter-by', function (e){
            e.preventDefault();
           const type = $(this).attr('data-type');
           if(type=='date'){
               $('#reservation').trigger('click');
           }
        });
        $(function (){
            $('#reservation').daterangepicker();
        })
        $(document).on('click', '#sendNumberInfo', function (e){
            const number = $(this).attr('data-number');
            $('#sendCInfoModal').modal('show');
            $('#confirmUrlSend').attr('data-number', number);
        })
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }
        });
        let already_sent = false;

        $(document).on('click', '.chat', function (e) {

            e.preventDefault();
            $('#to-chat').html(`<div class="loading"><div class="loader"></div></div>`);
            $('#mess').html("");
            const number = $(this).attr('data-to-number');
            if (number) {
                $('#label').removeClass("d-none");
            }

            $('.send').attr('data-to-number', number);
            $('.save').attr('data-to-number', number).addClass('d-none');
            $('#active_number').attr('data-to-number', number);
            $('.chat').removeClass('active');
            $(this).addClass('active');

            $.ajax({
                url: '{{route('customer.chat.get.data')}}',
                method: "GET",
                data: {
                    number: number,
                },
                success: function (res) {
                    already_sent = false;
                    if (res.status == 'success') {
                        let html = '';
                        let last_to_number = 0;
                        const messages=res.data.messages.sort((a, b)=> new Date(a.updated_at).getTime() - new Date(b.updated_at).getTime());
                        $.each(messages, function (index, value) {
                            let created_at = (new Date(value.created_at)).toLocaleString(undefined,{year: 'numeric', month: 'short', day: '2-digit',hour: 'numeric', minute: 'numeric'});
                            if (value.type == 'sent') {
                                html += `<div class="row no-gutters"><div class="col-md-3 offset-md-9" data-toggle="tooltip" data-placement="left" title="From : ${value.from}"><div class="chat-bubble chat-bubble--right">${value.body}</div><small class="message-sent-time">${created_at}</small></div></div>`;
                            } else {
                                html += `<div class="row no-gutters"><div class="col-md-3" data-toggle="tooltip" data-placement="right" title="To : ${value.to}"><div class="chat-bubble chat-bubble--left">${value.body}</div><small class="message-sent-time">${created_at}</small></div></div>`;
                                last_to_number = value.to;
                            }

                        });
                        if(res.data.number){
                            $('#addException').addClass('d-none');
                            $('#removeException').removeClass('d-none');
                        }else{
                            $('#addException').removeClass('d-none');
                            $('#removeException').addClass('d-none');
                        }
                        if(!res.data.label && !res.data.id){
                            $('.save').attr('have-label', 'no');
                            $('#check_new_contact').removeClass('d-none');
                        }else{
                            $('#check_new_contact').addClass('d-none');
                            $('.save').attr('have-label', 'yes');
                        }

                        $('#to-chat').html(html).scrollTop($('#to-chat')[0].scrollHeight);
                        $('#to-chat').attr('data-current-chats',res.data.page);
                        $('.send').attr('data-id', res.data.id);
                        $('#contactName').html(`<p class="text-dark"> <b>${res.data.name?res.data.name:''}</b> </p><p class="text-dark"> ${number?number:number} </p><small class="text-dark"> ${res.data.address?res.data.address:''} </small>`);
                        if(res.data.zillow_url) {
                            $('#contactAddress').attr('href', res.data.zillow_url);
                        }else{
                            $('#contactAddress').attr('href', '#');
                        }
                        $('#addException').attr('data-number', number);
                        $('#removeException').attr('data-number', number);
                        $('#select-label').val(res.data.label);
                        $('#sendNumberInfo').attr('data-number', number);
                        $('[data-toggle="tooltip"]').tooltip();
                        $('#number').val(last_to_number).change();
                    }
                }
            });
        });

        $(document).on('click', '.send', function (e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            const numb = $(this).attr('data-to-number');
            if (!numb) {
                $('#mess').html('<span style="color: red;margin-left:15px;" >Please select a recipient number</span>')
                return true;
            }

            const number_to = {id: id, type: 'contact'};
            const from_number = $('#number').val();
            const body = $('#message').val();
            if (!body) {
                $('#mess').html('<span style="color: red;margin-left: 15px">Type your message and try again</span>')
                return true;
            }
            $.ajax({
                url: '{{route('customer.smsbox.compose.sent')}}',
                method: "POST",
                data: {
                    to_numbers: [JSON.stringify(number_to)],
                    from_number: from_number,
                    body: body,
                    from_type:'phone_number',
                    _token: '{{csrf_token()}}'
                },
                success:function (res){
                    if(res.status=='success'){
                        const html=`<div class="row no-gutters"><div class="col-md-3 offset-md-9"><div class="chat-bubble chat-bubble--right">${body}</div></div></div>`;
                        $('#to-chat').append(html).scrollTop($('#to-chat')[0].scrollHeight);
                        $('#message').val('');
                    }
                }
            });
        });

        $(document).on('change', '#select-label', function (e) {
            $('.save').removeClass('d-none');
        })
        $(document).on('click', '.save', function (e) {

            const label = $('#select-label').val();
            const number = $(this).attr('data-to-number');
            const contact = $(this).attr('have-label');
            if(contact=='no'){
                $('#addNewContact').modal('show');
                $('#addNewContactBtn').attr('data-label', label).attr('data-number', number);
                return true;
            }
            $.ajax({
                method: "POST",
                url: '{{route('customer.chat.label.update')}}',
                data: {
                    label: label,
                    number: number,
                    _token: '{{csrf_token()}}'
                },
                success: function (res) {
                    if (res.status == 'success') {
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-success',
                            title: 'Notification',
                            body: res.message,
                        });
                        $('#search').trigger('keyup')
                    }
                }
            });
        });

        $(document).on('click', '#addNewContactBtn', function (e) {

            const label =  $(this).attr('data-label');
            const number = $(this).attr('data-number');

            $.ajax({
                method: "POST",
                url: '{{route('customer.add.new.contact')}}',
                data: {
                    label: label,
                    number: number,
                    _token: '{{csrf_token()}}'
                },
                success: function (res) {
                    if (res.status == 'success') {
                        $('#addNewContact').modal('hide');
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-success',
                            title: 'Notification',
                            body: res.message,
                        });
                        const pageNumber=  $('#numb').attr('data-current-page');
                        $('#search').attr('search-page-number', parseInt(pageNumber) -1);
                        $('#search').trigger('keyup');
                    }
                }
            });
        });

        function generateLabel(labels,toNumber,$preLabel){
            let html='';
            $.each(labels, function (index, value) {
                html += `<button data-to-number="${toNumber}" have-label="${$preLabel?'yes':'no'}" data-label="${value.id}" class="dropdown-item label update_label"><span style="color: ${value.color}">${value.title}</span></button>`;
            });
            return html;
        }
        $('#search').on('keyup', function (e) {
            e.preventDefault();
            const search = $(this).val();
            const date = $('#reservation').val();
            const type = $('#sortBy').val();
            const label_id = $('#filtered_by_label').val();
            const prePage = $(this).attr('search-page-number');

            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    search: search,
                    date: date,
                    type: type,
                    label_id: label_id,
                    page: prePage?prePage:1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        if (res.data.numbers != []) {
                            $.each(res.data.numbers, function (index, value) {
                                html += ` <div class="row">
                                            <div class="col-md-12 col-xl-12 col-sm-12 col-12 text-center chat c-pointer"
                                                 data-to-number="${value.number}">
                                                <div class="card number-section">
                                                    <h6>${value.full_name?value.full_name:value.number}</h6>
                                                    <h6>${value.body}</h6>
                                                    <div class="number-section-label">
                                                        <button type="button"
                                                                class="btn light dropdown-toggle float-right"
                                                                style="width:100%;border-radius:100px;background: ${value.color}"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                           ${value.label.substr(0, 7)}
                                                        </button>
                                                        <div class="dropdown-menu float-right"
                                                             x-placement="bottom-start"
                                                             style="position: absolute; will-change: transform; top: 0px; left:-15px!important; transform: translate3d(0px, 38px, 0px); padding-left: 15px; ">
                                                            ${generateLabel(res.data.labels, value.number, value.label)}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="dateTime"><small>${value.created_at}</small></span>`;
                            });
                        } else {
                            html = '<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        $('#numb').html(html);

                        if(res.data.page !='end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        }else{
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                }
            });
        });


        $('#sortBy').on('change', function (e) {
            e.preventDefault();
            const type = $(this).val();
            const search = $('#search').val();
            const date = $('#reservation').val();
            const label_id = $('#filtered_by_label').val();
            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    type: type,
                    search: search,
                    date: date,
                    label_id: label_id,
                    page: 1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        if (res.data.page != 'end') {

                            $.each(res.data.numbers, function (index, value) {
                                html += ` <div class="row">
                                            <div class="col-md-12 col-xl-12 col-sm-12 col-12 text-center chat c-pointer"
                                                 data-to-number="${value.number}">
                                                <div class="card number-section">
                                                    <h6>${value.full_name?value.full_name:value.number}</h6>
                                                    <h6>${value.body}</h6>
                                                    <div class="number-section-label">
                                                        <button type="button"
                                                                class="btn light dropdown-toggle float-right"
                                                                style="width:100%;border-radius:100px;background: ${value.color}"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                           ${value.label.substr(0, 7)}
                                                        </button>
                                                        <div class="dropdown-menu float-right"
                                                             x-placement="bottom-start"
                                                             style="position: absolute; will-change: transform; top: 0px; left: -15px!important; transform: translate3d(0px, 38px, 0px); padding-left: 15px; ">
                                                            ${generateLabel(res.data.labels, value.number, value.label)}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="dateTime"><small>${value.created_at}</small></span>`;
                            });
                        } else {
                            html = '<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        $('#numb').html(html);
                        if(res.data.page !='end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        }else{
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                }
            });
        });

        $(document).on('click', '.applyBtn',function (e) {
            e.preventDefault();
            const date = $('#reservation').val();
            const type = $('#sortBy').val();
            const search = $('#search').val();
            const label_id = $('#filtered_by_label').val();
            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    date: date,
                    type: type,
                    search: search,
                    label_id: label_id,
                    page: 1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        if (res.data.page !='end') {

                            $.each(res.data.numbers, function (index, value) {
                                html += ` <div class="row">
                                            <div class="col-md-12 col-xl-12 col-sm-12 col-12 text-center chat c-pointer"
                                                 data-to-number="${value.number}">
                                                <div class="card number-section">
                                                    <h6>${value.full_name?value.full_name:value.number}</h6>
                                                    <h6>${value.body}</h6>
                                                    <div class="number-section-label">
                                                        <button type="button"
                                                                class="btn light dropdown-toggle float-right"
                                                                style="width:100%;border-radius:100px;background: ${value.color}"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                           ${value.label.substr(0, 7)}
                                                        </button>
                                                        <div class="dropdown-menu float-right"
                                                             x-placement="bottom-start"
                                                             style="position: absolute; will-change: transform; top: 0px; left: -15px!important; transform: translate3d(0px, 38px, 0px); padding-left: 15px; ">
                                                            ${generateLabel(res.data.labels, value.number, value.label)}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="dateTime"><small>${value.created_at}</small></span>`;
                            });
                        } else {
                            html = '<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        $('#numb').html(html);
                        if(res.data.page !='end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        }else{
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                }
            });
        });

        $(document).on('click', '.filtered_by_label',function (e) {
            e.preventDefault();
            const label_id = $(this).attr('data-id');
            $('#filtered_by_label').val(label_id);
            const date = $('#reservation').val();
            const type = $('#sortBy').val();
            const search = $('#search').val();

            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    label_id: label_id,
                    search: search,
                    date: date,
                    type: type,
                    page: 1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        if(res.data.page !='end') {

                            $.each(res.data.numbers, function (index, value) {
                                html += ` <div class="row">
                                            <div class="col-md-12 col-xl-12 col-sm-12 col-12 text-center chat c-pointer"
                                                 data-to-number="${value.number}">
                                                <div class="card number-section">
                                                    <h6>${value.full_name?value.full_name:value.number}</h6>
                                                    <h6>${value.body}</h6>
                                                    <div class="number-section-label">
                                                        <button type="button"
                                                                class="btn light dropdown-toggle float-right"
                                                                style="width:100%;border-radius:100px;background: ${value.color}"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                           ${value.label.substr(0, 7)}
                                                        </button>
                                                        <div class="dropdown-menu float-right"
                                                             x-placement="bottom-start"
                                                             style="position: absolute; will-change: transform; top: 0; left: -15px!important; transform: translate3d(0px, 38px, 0px); padding-left: 15px; ">
                                                            ${generateLabel(res.data.labels, value.number, value.label)}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="dateTime"><small>${value.created_at}</small></span>`;
                            });
                        }else{
                            html ='<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        if(res.data.page !='end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        }else{
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                        $('#numb').html(html);
                    }
                }
            });
        });


        function difference(first, sec) {
            return Math.abs(first - sec);
        }
        let currentPageNumber=1;
        $('#next-page').on('click', function (e){
            e.preventDefault();
            $('.ajax-loader').removeClass('d-none');
            const chats = $('#numb').attr('data-current-page');
            const page = chats;
            const search = $('#search').val();
            const label_id = $('#filtered_by_label').val();
            const date = $('#reservation').val();
            const type = $('#sortBy').val();

            if(chats != 'end') {
                getPaginationData(page, search, label_id, date, type)
                currentPageNumber++;
                $('#next-page').removeClass('disabled').removeAttr('disabled', 'disabled');
                $('#previous-page').removeClass('disabled').removeAttr('disabled', 'disabled');
            }else{
                $('#next-page').addClass('disabled').attr('disabled', 'disabled');
            }
        });

        $('#previous-page').on('click', function (e) {
            e.preventDefault();
            $('.ajax-loader').removeClass('d-none');
            const chats = $('#numb').attr('data-current-page');
            if (chats > 1 || chats=='end') {

                const page = chats=='end'?currentPageNumber-1:parseInt(chats)-2;
                const search = $('#search').val();
                const label_id = $('#filtered_by_label').val();
                const date = $('#reservation').val();
                const type = $('#sortBy').val();
                getPaginationData(page, search, label_id, date, type)
                currentPageNumber--;
                $('#previous-page').removeClass('disabled').removeAttr('disabled', 'disabled');
                $('#next-page').removeClass('disabled').removeAttr('disabled', 'disabled');

            } else {
                $('#previous-page').addClass('disabled').attr('disabled', 'disabled');
                $('#next-page').addClass('disabled').attr('disabled', 'disabled');
            }
        });

        function getPaginationData(page, search, label_id, date, type){
            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "GET",
                data: {
                    page: page,
                    search: search,
                    label_id: label_id,
                    date: date,
                    type: type,
                },
                success: function (res) {
                    already_sent = false;
                    if (res.status == 'success') {
                        if (res.data.numbers) {
                            let html = '';
                            let labels = '';

                            $.each(res.data.numbers, function (index, value) {
                                html += ` <div class="row">
                                            <div class="col-md-12 col-xl-12 col-sm-12 col-12 text-center chat c-pointer"
                                                 data-to-number="${value.number}">
                                                <div class="card number-section">
                                                    <h6>${value.full_name?value.full_name:value.number}</h6>
                                                    <h6>${value.body}</h6>
                                                    <div class="number-section-label">
                                                        <button type="button"
                                                                class="btn light dropdown-toggle float-right"
                                                                style="width:100%;border-radius:100px;background: ${value.color}"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                           ${value.label.substr(0, 7)}
                                                        </button>
                                                        <div class="dropdown-menu float-right"
                                                             x-placement="bottom-start"
                                                             style="position: absolute; will-change: transform; top: 0px; left: -15px!important; transform: translate3d(0px, 38px, 0px); padding-left: 15px; ">
                                                            ${generateLabel(res.data.labels, value.number, value.label)}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="dateTime"><small>${value.created_at}</small></span>`;
                            });
                            $('#numb').html(html);
                        }
                        if(res.data.page !='end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        }else{
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                    $('.ajax-loader').addClass('d-none');
                },
                error: function (res) {
                    $('.ajax-loader').addClass('d-none');
                }
            });
        }


        $(document).on('click', '.update_label', function (e) {
            e.preventDefault();
            const label = $(this).attr('data-label');
            const number = $(this).attr('data-to-number');
            const contact = $(this).attr('have-label');
            if (contact == 'no') {
                $('#addNewContact').modal('show');
                $('#addNewContactBtn').attr('data-label', label).attr('data-number', number);
                return true;
            }
            $.ajax({
                method: "POST",
                url: '{{route('customer.chat.label.update')}}',
                data: {
                    label: label,
                    number: number,
                    _token: '{{csrf_token()}}'
                },
                success: function (res) {
                    if (res.status == 'success') {
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-success',
                            title: 'Notification',
                            body: res.message,
                        });
                        const pageNumber=  $('#numb').attr('data-current-page');
                        $('#search').attr('search-page-number', parseInt(pageNumber) -1);
                        $('#search').trigger('keyup');
                    }
                }
            });
        });



        $('#to-chat').on('scroll', function () {
            if (this.scrollTop < 20) {
                if (!already_sent) {
                    already_sent = true;
                    const chats = $('#to-chat').attr('data-current-chats');
                    const number = $('#active_number').attr('data-to-number');

                    if (chats != 'end') {
                        $.ajax({
                            url: '{{route('customer.chat.get.chats')}}',
                            method: "GET",
                            data: {
                                chats: chats,
                                number: number,
                            },
                            success: function (res) {
                                already_sent = false;
                                if (res.status == 'success') {
                                    let html = '';
                                    $.each(res.data.messages, function (index, value) {
                                        let created_at = (new Date(value.created_at)).toLocaleString();
                                        if (value.type == 'sent') {
                                            html += `<div class="row no-gutters"><div class="col-md-3 offset-md-9" data-toggle="tooltip" data-placement="left" title="From : ${value.from}"><div class="chat-bubble chat-bubble--right">${value.body}</div><small class="message-sent-time">${created_at}</small></div></div>`;
                                        } else {
                                            html += `<div class="row no-gutters"><div class="col-md-3" data-toggle="tooltip" data-placement="right" title="To : ${value.to}"><div class="chat-bubble chat-bubble--left">${value.body}</div></div></div>`;
                                        }
                                    });
                                    $('#to-chat').prepend(html);
                                    $('#to-chat').attr('data-current-chats', res.data.page);
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            }
                        });
                    }
                }
            }
        });

        $(document).on('click', '.response_value', function (e){
           let value = $(this).attr('data-title');
            var curPos = document.getElementById("message").selectionStart;
            let x = $("#message").val();
            $("#message").val(x.slice(0, curPos) + value + x.slice(curPos)).focus();
            checkCharecter();
            $('#showResponse').addClass('d-none');
        });

        function checkCharecter(){
            var messageValue = $('#message').val();
            var div = parseInt(parseInt(messageValue.length - 1) / 160) + 1;
            if (div <= 1) {
                $("#count").text("Characters left: " + (160 - messageValue.length));
            } else {
                $("#count").text("Characters left: " + (160 * div - messageValue.length) + "/" + div);
            }
        }
        $(document).on('click', '#message', function (e) {
            checkCharecter();
            $('#showResponse').removeClass('d-none');
        });

        $(document).on('keyup', '#message', function (e) {
            checkCharecter();
            $('#showResponse').addClass('d-none');
        });

        $(document).mouseup(function (e) {
            var container = $("#message");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $("#showResponse").addClass('d-none');
            }
        });

        $(document).on('click', '.exception_modal_btn', function (e){
            $('#exceptionModal').modal('show');
            const number = $(this).attr('data-number');
            const type = $(this).attr('data-type');
            $('#saveException').attr('data-number', number).attr('data-type', type);
            if(type=='add'){
                $('.exception_message').text('Are you sure to add this number in Exception list')
            }else{
                $('.exception_message').text('Are you sure to remove this number in Exception list')
            }

        });

        $(document).on('click', '#saveException', function (e) {

            const number = $(this).attr('data-number');
            const type = $(this).attr('data-type');
            const check_add_contact = $('#checkInput:checked').val();

            $.ajax({
                type:'post',
                url: '{{route('customer.exception')}}',
                data: {
                    _token:'{{csrf_token()}}',
                    number:number,
                    type:type,
                    check_add_contact:check_add_contact,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        if (res.type == 'add') {
                            $('#removeException').attr('data-number', number).removeClass('d-none');
                            $('#addException').addClass('d-none');
                            $('#exceptionModal').modal('hide');
                            $('#check_new_contact').addClass('d-none');
                            $(document).Toasts('create', {
                                autohide: true,
                                delay: 3000,
                                class: 'bg-success',
                                title: 'Notification',
                                body: 'Suppress added successfully',
                            });
                        } else {
                            $('#removeException').addClass('d-none');
                            $('#addException').removeClass('d-none').attr('data-number', number);
                            $('#exceptionModal').modal('hide');
                            $('#check_new_contact').addClass('d-none');
                            $(document).Toasts('create', {
                                autohide: true,
                                delay: 3000,
                                class: 'bg-success',
                                title: 'Notification',
                                body: 'Suppress removed successfully',
                            });
                        }
                    }

                }
            })
        });
        $('#sendUrl').on('click', function (e){
            $('#altMessage').text(' ')
            $('#sendUrl').css('border', '1px solid white');
        })
        $(document).on('click', '#confirmUrlSend', function (e){
            e.preventDefault();
            $('#confirmUrlSend').html(' <i class="fa fa-spinner fa-spin"></i> Loading')
            const number = $(this).attr('data-number');
            const data_url=$('#sendUrl').val();
            const method=$('#sendUrlMethod').val();
            if (!data_url) {
                $('#sendUrl').css('border', '1px solid red');
                $('#altMessage').text('Enter url first')
                $(document).Toasts('create', {
                    autohide: true,
                    delay: 3000,
                    class: 'bg-danger',
                    title: 'Notification',
                    body: 'Please enter url first',
                });
                return true;
            }else{
                $('#sendUrl').css('border', '1px solid white');
            }
            $.ajax({
                type:'POST',
                url:'{{route('customer.send.contact.data')}}',
                data:{
                    _token:'{{csrf_token()}}',
                    number:number, url:data_url,url_method:method
                },
                success: function (res) {
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 3000,
                            class: 'bg-success',
                            title: 'Notification',
                            body: 'Data sent successfully',
                        });

                        $('#confirmUrlSend').text('Confirm')
                    $('#sendCInfoModal').modal('hide');
                }
            });
        });

        $('#sortBy').trigger('change');

    </script>
@endsection
