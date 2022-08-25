@extends('layouts.admin')

@section('title','Settings')

@section('extra-css')
    @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
    <link rel="stylesheet" href="{{Module::asset('paymentgateway:css/paymentgateway.css')}}">
    @endif

    <style>
        #email_temp .nav-link{
            width: 100% !important;
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

                    <div class="card-header d-flex p-0">
                        <div class="row">
                            <h2 class="card-title p-3"><a href="{{route('admin.settings.index')}}">@lang('admin.settings.setting')</a></h2>
                            <ul class="nav nav-pills ml-auto pt-3">
                                <li class="nav-item"><a class="nav-link active nav-link-hover" href="#profile_tab"
                                                        data-toggle="tab">@lang('admin.settings.profile')</a>
                                </li>
                                <li class="nav-item"><a class="nav-link nav-link-hover" href="#application_tab"
                                                        data-toggle="tab">@lang('admin.settings.application')</a></li>
                                <li class="nav-item"><a class="nav-link nav-link-hover" href="#smtp_tab"
                                                        data-toggle="tab">@lang('admin.settings.smtp')</a></li>

                                @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                    <li class="nav-item"><a class="nav-link nav-link-hover" href="#payment_gateway_tab"
                                                            data-toggle="tab" id="payment_gateway_nav">@lang('paymentgateway::layout.payment_gateway')</a>
                                    </li>
                                @endif

                                <li class="nav-item"><a class="nav-link nav-link-hover" href="#emailTemplate"
                                                        data-toggle="tab">{{trans('admin.settings.email_template')}}</a>

                                <li class="nav-item"><a class="nav-link nav-link-hover" href="#local_setting_tab"
                                                        data-toggle="tab">{{trans('admin.settings.local_setting')}}</a>
                                </li>
                                <li class="nav-item"><a class="nav-link nav-link-hover" href="{{url('translations')}}"
                                                        target="_blank">{{trans('admin.settings.translations')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile_tab">
                                <form method="post" role="form" id="profile_form"
                                      action="{{route('admin.settings.profile_update')}}" enctype="multipart/form-data">
                                    @csrf
                                    @include('admin.settings.form')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.form.button.submit')</button>
                                </form>
                            </div>

                            <div class="tab-pane" id="application_tab">
                                <form method="post" role="form" id="application_form"
                                      action="{{route('admin.settings.app_update')}}" enctype="multipart/form-data">
                                    @csrf

                                    @include('admin.settings.app_update_form')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.form.button.submit')</button>
                                </form>
                            </div>


                            <div class="tab-pane" id="smtp_tab">
                                <div class="container">
                                    <div class="row">
                                        <div class="card custom-card">
                                            <p><i class="fa fa-info-circle mr-2"></i>@lang('admin.smtp_description')</p>
                                        </div>
                                    </div>
                                </div>
                                <form method="post" role="form" id="smtp_form"
                                      action="{{route('admin.settings.smtp_update')}}" enctype="multipart/form-data">
                                    @csrf

                                    @include('admin.settings.smtp_form')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.form.button.submit')</button>
                                </form>
                            </div>

                            @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                <div class="tab-pane" id="payment_gateway_tab">
                                    <div class="container">
                                        <div class="row">
                                            <div class="card custom-card">
                                                <p><i class="fa fa-info-circle mr-2"></i>@lang('admin.payment_description')</p>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="post" role="form" id="payment_gateway_form"
                                          action="{{route('paymentgateway::payment.settings.store')}}"
                                          enctype="multipart/form-data">
                                        @csrf

                                        @include('paymentgateway::settings.payment_gateway')

                                        <div class="text-right">
                                            <button id="submit_payment_gateway" type="button"
                                                    class="btn btn-primary">@lang('admin.form.button.submit')</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            <div class="tab-pane" id="local_setting_tab">
                                <div class="container">
                                    <div class="row">
                                        <div class="card custom-card">
                                            <p><i class="fa fa-info-circle mr-2"></i>@lang('admin.local_settings_description')</p>
                                        </div>
                                    </div>
                                </div>
                                <form method="post" role="form" id="local_setting_form"
                                      action="{{route('admin.settings.local.setting')}}" enctype="multipart/form-data">
                                    @csrf

                                    @include('admin.settings.local_setting_form')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.form.button.submit')</button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="emailTemplate">
                                <div class="container">
                                    <div class="row">
                                        <div class="card custom-card">
                                            <p><i class="fa fa-info-circle mr-2"></i>@lang('admin.sms_template_description')</p>
                                        </div>
                                    </div>
                                </div>
                                @include('admin.settings.email_template')
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
        $(document).ready(function () {
            bsCustomFileInput.init();
        });

        $('#gateway').select2({
            multiple:false
        }).on('change', function (e) {
            e.preventDefault();
            const type = $(this).val();
            $('.api-section').hide();
            $('#' + type + "_section").show();
        });
        $('#timezone').select2();
        @if(Module::has('PaymentGateway'))
        $('#submit_payment_gateway').on('click', function (e) {
            e.preventDefault();
            const form = $('#payment_gateway_form');
            const formData=form.serialize();
            const url=form.attr('action');
            $.ajax({
                method: 'post',
                url: url,
                data: formData,
                success: function (res) {
                    if (res.status == 'success') {
                        notify('success', res.message);
                    }
                }
            })
        });
        @endif
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

@endsection

