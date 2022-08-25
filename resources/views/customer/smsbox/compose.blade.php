@extends('layouts.customer')

@section('title','Compose | SmsBox')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        #select2-toNumbers-results, #select2-fromNumber-results{
            overflow-y: auto;
            max-height: 200px;
        }
    </style>
@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{trans('customer.quick_send')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="{{route('customer.smsbox.overview')}}">{{trans('customer.smsbox')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('customer.quick_send')}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- /.col -->
            <div class="col-lg-8 col-md-7 mx-auto">
                <div class="card card-primary card-outline">
                    <form id="compose_form" action="{{route('customer.smsbox.compose.sent')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">{{trans('customer.compose_new_message')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="device_id">Select Device</label>
                                <select name="device_id" class="form-control select2" id="device_id">
                                    @foreach($devices as $device)
                                    <option device_name="{{$device->name}}" value="{{$device->id}}">{{$device->name}} ({{$device->model}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="pre_draft">
                                @isset($draft)
                                    <input type='hidden' id='draft_id' name='draft_id' value='{{$draft->id}}'/>
                                @endisset
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">

                                        <select name="to_numbers[]" id="toNumbers" class="select2 compose-select"
                                                multiple="multiple"
                                                data-placeholder="{{trans('customer.recipient')}}:">

                                            @if(isset($draft) && $draft->formatted_number_to)
                                                @foreach($draft->formatted_number_to_array as $to)
                                                    <option selected value="{{$to}}">{{$to}}</option>
                                                @endforeach
                                            @endif
                                            @isset($users_to_contacts)
                                                <optgroup label="Contacts">
                                                    @foreach($users_to_contacts as $to)
                                                        <option value="{{json_encode($to)}}">{{$to['value']}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endisset

                                            @isset($users_to_groups)
                                                <optgroup label="Groups">
                                                    @foreach($users_to_groups as $to)
                                                        <option value="{{json_encode($to)}}">{{$to['value']}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                            <textarea name="body" id="compose-textarea" class="form-control compose-body"
                                      placeholder="{{trans('customer.enter_message')}}">{{isset($draft)?$draft->body:''}}</textarea>
                            </div>
                            <div class="form-group d-none">
                                <label for="mms_files">{{trans('customer.choose_file')}}:</label>
                                <input type="file" accept="image/*" id="mms_files" class="form-control" name="mms_files[]" multiple>
                            </div>
                            <div class="form-group">

                                <div class="icheck-success d-inline">
                                    <input {{isset($draft) && $draft->schedule_datetime?'checked':''}} name="isSchedule"
                                           type="checkbox" id="isScheduled">
                                    <label for="isScheduled">
                                        {{trans('customer.schedule')}}
                                    </label>
                                </div>

                                <input style="display: {{isset($draft) && $draft->schedule_datetime?'block':'none'}}"
                                       name="schedule"
                                       value="{{isset($draft) && $draft->schedule_datetime?$draft->schedule_datetime->format('m/d/Y h:i A'):''}}"
                                       id="schedule" type='text'
                                       class="form-control"/>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="float-right">
                                <button id="draft" type="button" class="btn btn-default"><i
                                        class="fas fa-pencil-alt"></i> {{trans('customer.draft')}}
                                </button>
                                <button type="submit" class="btn btn-primary"><i
                                        class="far fa-envelope"></i> {{trans('customer.send')}}
                                </button>
                            </div>
                            <button id="reset" type="button" class="btn btn-default"><i
                                    class="fas fa-times"></i> {{trans('customer.reset')}}
                            </button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-lg-4 col-md-5">
                <section class="chatbox">
                    <div class="header-number">
                        <i class="fa fa-arrow-left back-icon"></i>
                        <span id="from_number_mobaile_view"></span>
                        <div class="header-icon">
                            <i class="fa fa-phone"></i>
                            <i class="fa fa-search"></i>
                            <i class="fa fa-ellipsis-v"></i>
                        </div>
                    </div>

                    <section class="chat-window" id="msg_mobaile_view"></section>

                    <form class="chat-input">
                        <i class="fa fa-plus-circle plus-icon"></i>
                        <span>Type a message</span>
                        <div class="chat-input-div">
                            <i class="fa fa-paper-plane"></i>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <input type="hidden" id="meg-time">

@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

    <script !src="">
        "use strict";
        var select2 = $('#toNumbers').select2({
            minimumInputLength: 1,
            tags: true,
            tokenSeparators: [",", " "],
        })

        $('#fromNumber').select2({
            theme: 'bootstrap4'
        });

        $('#from_number').select2({
            multiple:false,
            placeholder:'Select a from number',
        });

        $(function () {
            "use strict";
            $('#schedule').daterangepicker({
                autoUpdateInput: true,
                singleDatePicker: true,
                timePicker: true,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            });
        });

        $('#isScheduled').on('change', function (e) {
            const checked = $(this).is(':checked');
            if (checked) {
                $('#schedule').show();
            } else {
                $('#schedule').hide();
            }
        })

        $('#reset').on('click', function (e) {
            e.preventDefault();
            $(select2).val('').trigger('change');
            $("#compose-textarea").val('');
            let checked = $("#isScheduled").is(':checked');
            if (checked) {
                $('#isScheduled').click().prop("checked", false);
            }
        })

        $('#draft').on('click', function (e) {
            e.preventDefault();
            const from = $('#device_id').val();
            const to = $('#toNumbers').val();
            const body = $('#compose-textarea').val();
            const checked = $("#isScheduled").is(':checked');
            const draft_id = $("#draft_id").val();
            let schedule = '';
            if (checked) {
                schedule = $('#schedule').val();
            }
            $.ajax({
                method: 'post',
                url: '{{route('customer.smsbox.draft.store')}}',
                data: {_token: '{{csrf_token()}}', from, to, body, checked, schedule, draft_id},
                success: function (res) {
                    if (res.status == 'success') {
                        notify('success', res.message);
                        var id = res.data.id;
                        $('#pre_draft').html("<input type='hidden' id='draft_id' name='draft_id' value='" + id + "'/>");

                    } else {
                        notify('danger', res.message);
                    }
                }
            })

        })
        $("#compose-textarea").on("keyup change", function(e) {
            e.preventDefault()
            const checked = $("#isScheduled").is(':checked');
            let dateTime ='';
            if (checked) {
                dateTime = $('#schedule').val();
            }else {
                dateTime = $('#meg-time').val()
            }
            let data = $('#compose-textarea').val();
            let compose = data.replace(/\n/g,"<br />");
            $("#msg_mobaile_view").html(`<article class="msg-container msg-remote" id="msg-0">
                                <div class="mag-time">${dateTime}</div>
                                <div class="msg-box">
                                    <div class="flr">
                                        <div class="messages">
                                            <div class="msg">${compose}</div>
                                        </div>
                                    </div>
                                </div>
                                <span>J</span>
                            </article>`);

        });
        $('#schedule').on('change', function (e) {
            e.preventDefault()
            let data = $('#compose-textarea').val();
            let compose = data.replace(/\n/g,"<br />");
            const checked = $("#isScheduled").is(':checked');
            let dateTime ='';
            if (checked) {
                dateTime = $('#schedule').val();
            }else {
                dateTime = $('#meg-time').val()
            }
            if (compose){
                $("#msg_mobaile_view").html(`<article class="msg-container msg-remote" id="msg-0">
                                <div class="mag-time">${dateTime}</div>
                                <div class="msg-box">
                                    <div class="flr">
                                        <div class="messages">
                                            <div class="msg">${compose}</div>
                                        </div>
                                    </div>
                                </div>
                                <span>J</span>
                            </article>`);
            }
        });
        $('#isScheduled').on('change', function (e) {
            e.preventDefault()
            let data = $('#compose-textarea').val();
            let compose = data.replace(/\n/g,"<br />");
            const checked = $("#isScheduled").is(':checked');
            let dateTime ='';
            if (checked) {
                dateTime = $('#schedule').val();
            }else {
                dateTime = $('#meg-time').val()
            }
            if (compose){
                $("#msg_mobaile_view").html(`<article class="msg-container msg-remote" id="msg-0">
                                <div class="mag-time">${dateTime}</div>
                                <div class="msg-box">
                                    <div class="flr">
                                        <div class="messages">
                                            <div class="msg">${compose}</div>
                                        </div>
                                    </div>
                                </div>
                                <span>J</span>
                            </article>`);
            }

        });

        $('#phone_number').trigger('change');

    </script>
    <script>
        const myDate = new Date();
        let daysList = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        let monthsList = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Aug', 'Oct', 'Nov', 'Dec'];


        let date = myDate.getDate();
        let month = monthsList[myDate.getMonth()];
        let year = myDate.getFullYear();
        let day = daysList[myDate.getDay()];

        let today = `${date} ${month} ${year}, ${day}`;

        let amOrPm;
        let twelveHours = function () {
            if (myDate.getHours() > 12) {
                amOrPm = 'PM';
                let twentyFourHourTime = myDate.getHours();
                let conversion = twentyFourHourTime - 12;
                return `${conversion}`

            } else {
                amOrPm = 'AM';
                return `${myDate.getHours()}`
            }
        };
        let hours = twelveHours();
        let minutes = myDate.getMinutes();

        let currentTime = `${hours}:${minutes} ${amOrPm}`;
        $('#meg-time').val(today + ' ' + currentTime)
    </script>
@endsection

