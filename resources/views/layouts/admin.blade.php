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
    <link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/layouts.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .select2-container .select2-selection--single{
            height: 37px !important;
        }
        .lang-colour i{
            color: black;
            font-size: 24px;
        }
    </style>
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
            <li class="nav-item dropdown">
                <a class="nav-link lang-colour" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-language"></i>
                </a>
                <div class="dropdown-menu" style="left: inherit; right: 0px; text-align: center">
                    <span class="dropdown-item dropdown-header">Language</span>
                    @foreach(get_available_languages() as $lang)
                        <a href="{{route('set.locale',['type'=>$lang])}}" class="dropdown-item">
                            <i class="fa fa-language mr-2"></i> {{$lang}}
                        </a>
                    @endforeach

                </div>
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="{{route('admin.settings.index')}}" class="nav-link dropdown-toggle nav-img" data-toggle="dropdown" aria-expanded="true">
                    <img src="{{asset('uploads/'.auth()->user()->profile_picture)}}" class="user-image img-circle elevation-2" alt="img">
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right dropdown-profile">
                    <!-- User image -->
                    <li class="user-header border-bottom-1">
                        <img  src="{{asset('uploads/'.auth()->user()->profile_picture)}}"  class="img-circle elevation-2" alt="img">

                        <p>
                            {{auth()->user()->name}}
                            <small>{{trans('customer.member_since')}} {{date('M. Y')}}</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="{{route('admin.settings.index')}}" class="btn btn-info btn-flat">{{trans('customer.profile')}}</a>
                        <a href="{{route('admin.logout')}}" class="btn btn-flat float-right text-white bg-sms_received">{{trans('customer.sign_out')}}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary">
        <!-- Brand Logo -->
        <a href="{{route('admin.dashboard')}}" class="brand-link">
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
                    <img  src="{{asset('uploads/'.auth()->user()->profile_picture)}}"  class="img-circle elevation-2 customer-profile " alt="User Image">
                </div>
                <div class="info">
                    <a href="{{route('admin.settings.index')}}" class="info-link d-block">{{auth()->user()->name}}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                @include('layouts.includes.admin_sidebar')
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
            <strong>{{trans('customer.copyright')}} &copy; {{date('Y')}} <a target="_blank" href="https://picotech.com.bd" class="footer-text">{{get_settings('app_name')}}</a>.</strong> {{trans('customer.all_rights_reserved')}}.
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
                <button id="modal-confirm-btn" type="button" class="btn btn-primary btn-sm">{{trans('customer.confirm')}}</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{trans('customer.cancel')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.js')}}"></script>
<script>
    jQuery('button[type="submit"]').on('click', function (e) {
        var form = $(this).parents('form:first');
        if (form.valid()) {
            $(this).attr('disabled', 'disabled').addClass('disabled')
            $(this).html(' <i class="fa fa-spinner fa-spin"></i> Loading');
            form.submit();
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
    $(document).on('click','.gateway-bb', function (e){
        const type = $(this).attr('data-type');
        localStorage.setItem("gateway_type", type);
    });
    $(document).on('click','.sending-setting', function (e){
        const type = $(this).attr('data-type');
        localStorage.setItem("sending_setting", type);
    });

</script>
<script>
    if ('{{request()->segment(2)== 'settings'}}') {
        const gateway = localStorage.getItem("gateway_type");
        const sending_setting_nav = localStorage.getItem("sending_setting");

        if (gateway) {
            $("#" + gateway).trigger('click');
            $('.gateway-bb').addClass('active');
        }
        if (sending_setting_nav) {
            $("#" + sending_setting_nav).trigger('click').addClass('active');
            $('.sending-setting').addClass('active');
        }
    }else {
        localStorage.clear();
    }
</script>
<script>
    $( document ).ready(function() {
        $('.form-control-sm').attr('placeholder', 'Type here to search...');
    });
</script>

@if(session()->has('success') || session()->has('fail') || count($errors)>0)
<x-alert :type="session()->get('success')?'success':'danger'" :is-errors="$errors" :message="session()->get('success')??session()->get('fail')"/>
@endif

@yield('extra-scripts')
</body>
</html>
