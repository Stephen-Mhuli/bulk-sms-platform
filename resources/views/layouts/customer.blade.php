<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@yield('title')</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel="shortcut icon" href="{{asset('uploads/'.get_settings('app_favicon'))}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('css/layouts.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single{
            height: 37px !important;
        }
        .select2-container--default{
            width: 100% !important;
        }
    </style>
    @if(Module::has('PaymentGateway'))
        <link rel="stylesheet" href="{{Module::asset('paymentgateway:css/paymentgateway.css')}}">
    @endif
    @yield('extra-css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars menu-icon"></i></a>
            </li>
        </ul>


        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <li class="nav-item dropdown user-menu">
                <a href="{{route('customer.settings.index')}}" class="nav-link dropdown-toggle nav-img" data-toggle="dropdown"
                   aria-expanded="true">
                    <img src="{{asset('uploads/'.auth('customer')->user()->profile_picture)}}"
                         class="user-image img-circle elevation-2" alt="img">
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right dropdown-profile">
                    <!-- User image -->
                    <li class="user-header border-bottom-1">
                        <img src="{{asset('uploads/'.auth('customer')->user()->profile_picture)}}"
                             class="img-circle elevation-2" alt="{{auth('customer')->user()->full_name}}">

                        <p>
                            {{auth('customer')->user()->full_name}}
                            <small>Member since {{date('M. Y')}}</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="{{route('customer.settings.index')}}"
                           class="btn btn-info btn-flat">{{trans('customer.profile')}}</a>
                        <a href="{{route('customer.logout')}}"
                           class="btn btn-flat float-right text-white bg-sms_received">{{trans('customer.sign_out')}}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary">
        <!-- Brand Logo -->
        <a href="{{route('customer.dashboard')}}" class="brand-link">
            @if(get_settings('app_logo'))
            <img class="layout-logo" src="{{asset('uploads/'.get_settings('app_logo'))}}" alt="">
            @endif
            <span class="brand-text font-weight-light">{{get_settings('app_name')}}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{asset('uploads/'.auth('customer')->user()->profile_picture)}}"
                         class="customer-profile img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{route('customer.settings.index')}}"
                       class="info-link d-block">{{auth('customer')->user()->full_name}}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                @include('layouts.includes.customer_sidebar')
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right">
            <strong>{{trans('customer.copyright')}} &copy; {{date('Y')}} <a target="_blank"
                                                                            href="#" class="footer-text">{{get_settings('app_name')}}</a>.</strong> {{trans('customer.all_rights_reserved')}}
            .
        </div>

    </footer>
</div>
<!-- ./wrapper -->

<!-- Confirmation modal -->
<div class="modal fade" id="modal-confirm">
    <div class="modal-dialog">
        <form id="modal-form">
            @csrf
            <div id="customInput"></div>
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h4 class="modal-title">{{trans('customer.confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer p-2">
                    <button id="modal-confirm-btn" type="submit"
                            class="btn btn-primary btn-sm">{{trans('customer.confirm')}}</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="cancel"
                            data-dismiss="modal">{{trans('customer.cancel')}}</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- REQUIRED SCRIPTS -->
<script>
    "use strict";
</script>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
<script src="{{asset('js/adminlte.min.js')}}"></script>
<script src="{{asset('js/readmore.min.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.js')}}"></script>
<script>
    jQuery('button[type="submit"]').on('click', function (e) {
        e.preventDefault();
        var form = $(this).parents('form:first');
        if (form && form.valid()) {
            $(this).attr('disabled', 'disabled').addClass('disabled')
            $(this).html(' <i class="fa fa-spinner fa-spin"></i> Loading');
            form.submit();
        }else{
            return false;
        }
    });
    jQuery('#modal-confirm-btn').on('click', function (e) {
        var form = $(this).parents('form:first');
        if (form.valid()) {
            $(this).attr('disabled', 'disabled').addClass('disabled')
            $(this).html(' <i class="fa fa-spinner fa-spin"></i> Loading');
            form.submit();
        }
    });
</script>
<script>
    $( document ).ready(function() {
        $('.form-control-sm').attr('placeholder', 'Type here to search...');
    });
</script>
<script>
    $(document).on('click','.close', (e)=> {
        e.preventDefault();
        $('.modal-backdrop').addClass('d-none')
    });
</script>
<script>
    $(document).on('click','.item_download_apk', (e)=> {
        e.preventDefault()
       const download_apk = $('#download_apk').attr('download_apk');
        localStorage.setItem("download_apk", "true");
        window.location.href = download_apk;
    });
    $(function () {
        const download_apk =localStorage.getItem("download_apk");
        if(download_apk){
            $('#download_apk_install').addClass('text-cross');
        }
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script>
    $(document).on('click','#cancel', (e)=> {
        e.preventDefault();
        $('.modal-backdrop').addClass('d-none')
    });
</script>
@if(session()->has('success') || session()->has('fail') || count($errors)>0)
    <x-alert :type="session()->get('success')?'success':'danger'" :is-errors="$errors"
             :message="session()->get('success')??session()->get('fail')"/>
@endif

@yield('extra-scripts')
</body>
</html>
