@extends('layouts.customer')

@section('title','Settings')

@section('extra-css')
    <style>
        .error{
            color:red
        }
    </style>
@endsection

@section('content')
    <section class="content-header">

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card">
                    <input type="hidden" value="{{request()->get('type')}}" id="url-type">
                    <div class="card-header d-flex p-0">
                        <div class="row">
                            <h2 class="card-title pl-4 pr-3 pb-3"><a
                                    href="{{route('customer.settings.index')}}">{{trans('customer.settings')}}</a></h2>
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item"><a class="nav-link active nav-link-hover" href="#profile_tab"
                                                        data-toggle="tab">{{trans('customer.profile')}}</a>
                                </li>
                                <li class="nav-item"><a class="nav-link nav-link-hover" href="#password_tab"
                                                        data-toggle="tab">{{trans('customer.password')}}</a>
                                </li>
                                <li class="nav-item"><a class="nav-link nav-link-hover" href="#sms_template_tab"
                                                        data-toggle="tab" id="sms_template_click">{{trans('customer.sms_template')}}</a>
                                </li>
                                <li class="nav-item"><a class="nav-link nav-link-hover" href="#notification_tab"
                                                        data-toggle="tab">{{trans('customer.general')}}</a></li>

                                <li class="nav-item"><a class="nav-link nav-link-hover" href="#sending_settings_tab" id="sending_settings_click"
                                                        data-toggle="tab">{{trans('customer.sending_settings')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile_tab">
                                <form method="post" role="form" id="profile_form"
                                      action="{{route('customer.settings.profile_update')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @include('customer.settings.profile_form')

                                    <button type="submit" class="btn btn-primary">{{trans('customer.submit')}}</button>
                                </form>
                            </div>

                            <div class="tab-pane" id="password_tab">
                                <form method="post" role="form" id="password_form"
                                      action="{{route('customer.settings.password_update')}}">
                                    @csrf
                                    @include('customer.settings.password_form')

                                    <button type="submit" class="btn btn-primary">{{trans('customer.submit')}}</button>
                                </form>
                            </div>
                            <div class="tab-pane" id="sms_template_tab">
                                <form method="post" role="form" id="sms_template"
                                      action="{{route('customer.settings.password_update')}}">
                                    @csrf
                                    @include('customer.settings.sms_template')

                                </form>
                            </div>
                            <div class="tab-pane" id="notification_tab">
                                <div class="row">
                                    <div class="col-sm-10 ml-2">

                                        @include('customer.settings.notification_form')

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="sending_settings_tab">
                                <div class="row">
                                    <div class="col-sm-10 ml-2">
                                        <form  method="post" role="form" id="sending_settings_form"
                                              action="{{route('customer.settings.sending.update')}}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            @include('customer.settings.sending_settings')
                                            <button type="submit"
                                                    class="btn btn-primary">{{trans('customer.submit')}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- ./card -->


            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="smsTemplateModal">
        <div class="modal-dialog">
            <form action="{{route('customer.sms.template')}}" method="post" id="templateForm">
                @csrf
                <input type="hidden" id="template_id" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="title"></h4>
                        <button type="button" class="close" style="outline: white !important;" data-dismiss="modal"
                                aria-label="Close">
                            <span class="close-icon" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{trans('customer.title')}}</label>
                            <input id="template_subject" value="{{old('title')?old('title'):''}}" type="text"
                                   class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label for="">{{trans('customer.status')}}</label>
                            <select id="template_status" name="status" class="form-control">
                                <option
                                    {{old('status') && old('status')=='active'?'selected':''}} value="active">{{trans('customer.active')}}</option>
                                <option
                                    {{old('status') && old('status')=='inactive'?'selected':''}} value="inactive">{{trans('customer.inactive')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">{{trans('customer.template_body')}}</label>
                            <textarea id="template_body" name="body" autofocus class="form-control" cols="5"
                                      rows="5">{{old('body')?old('body'):''}}</textarea>
                            <small class="float-right" id="count"></small>
                        </div>
                        <div class="form-group">
                            @foreach(sms_template_variables() as $key=>$t)
                                <button type="button" data-name="{{$key}}"
                                        class="btn btn-sm btn-info add_tool mt-2">{{ucfirst(str_replace('_',' ',$t))}}</button>
                            @endforeach
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.js')}}"></script>

    <script !src="">
        "use strict";
        let $validate;
        $validate = $('#profile_form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
            },
            messages: {
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a vaild email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                first_name: {required: "Please provide first name"},
                last_name: {required: "Please provide last name"}
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        $('#notification_switch').on('change', function (e) {
            const isChecked = $(this).is(':checked');
            $.ajax({
                method: 'post',
                url: '{{route('customer.settings.notification_update')}}',
                data: {_token: '{{csrf_token()}}', isChecked},
                success: function (res) {
                    notify('success', res.message);
                }
            })
        });

        $(document).ready(function () {
            bsCustomFileInput.init();
        });

        $('#templateForm').validate({
            rules: {
                title: {
                    required: true,
                },
                body: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                title: {
                    required: "Please enter template title",
                },
                body: {
                    required: "Please enter template body",
                },
                status: {required: "Please select template status"},
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $(document).on('click', '#addNewTemplate', function (e) {
            $('#smsTemplateModal').modal('show');
            $('#title').text($(this).attr('data-title'));
        });

        $('.add_tool').on('click', function (e) {
            var curPos =
                document.getElementById("template_body").selectionStart;

            let x = $("#template_body").val();
            let text_to_insert = $(this).attr('data-name');
            $("#template_body").val(
                x.slice(0, curPos) + text_to_insert + x.slice(curPos));

        });


        $(document).on('click', '.template-edit', function (e) {
            $('#smsTemplateModal').modal('show');
            const value = JSON.parse($(this).attr('data-value'));

            $('#template_id').val(value.id);
            $('#template_subject').val(value.title);
            $('#template_body').text(value.body);
            $('#title').text('SMS Template Edit');
            $("#template_status").val(value.status);

        });
        $(document).on('keyup or click', '#template_body', function (e) {
            const character = $(this).val().length;

            var messageValue = $(this).val();
            var div = parseInt(parseInt(messageValue.length - 1) / 160) + 1;
            if (div <= 1) {
                $("#count").text("Characters left: " + (160 - messageValue.length));
            } else $("#count").text("Characters left: " + (160 * div - messageValue.length) + "/" + div);
        });

        $('#webhookSubmit').on('click', function (e) {
            const type = $('#webhook_type').val();
            const url = $('#webhook_url').val();

            $.ajax({
                method: 'post',
                url: '{{route('customer.settings.webhook_update')}}',
                data: {_token: '{{csrf_token()}}', type: type, url: url},
                success: function (res) {
                    notify('success', res.message);
                }
            })
        })

        $('#dataPostIngSubmit').on('click', function (e) {
            const type = $('#data_posting_type').val();
            const url = $('#data_posting_url').val();

            $.ajax({
                method: 'post',
                url: '{{route('customer.settings.data_posting')}}',
                data: {_token: '{{csrf_token()}}', type: type, url: url},
                success: function (res) {
                    notify('success', res.message);
                }
            })
        })
        $('#offDay').select2({
            placeholder: 'Select an offday',
            multiple: true
        }).val(@json(isset($sending_settings['offdays'])? json_decode($sending_settings['offdays']):[])).change();

        $('#sending_settings_form').validate({
            rules: {
                daily_send_limit: {
                    required: true,
                    max:{{isset($customer_plan->daily_send_limit)?$customer_plan->daily_send_limit:0}}
                },
                minute_limit:{
                    required:true,
                    min:1
                }
            }
        });

        $('.message_limit').on('keyup or paste', function (e){
            let  message_limit = $(this).val();
            $('#message_limit').text(message_limit?message_limit:'0');
        });
        $('.minutes').on('keyup or paste', function (e){
            let minute_limit = $(this).val()
            $('#minutes').text(minute_limit?minute_limit:'0');
        });
        $(function () {
        const type =  $('#url-type').val()
        if (type == 'settings'){
            $('#sms_template_click').trigger('click');
        }else if(type == 'sending_settings') {
            $('#sending_settings_click').trigger('click');
        }
        })
    </script>


@endsection

