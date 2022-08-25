@extends('layouts.auth_customer')

@section('title') {{get_settings('app_name')}} - Demo Login @endsection

@section('extra-style')
    <style>
        .custom{
            padding-top: 50px;
        }
        .demo-remove{
            display: none;!important;
        }
        .page-banner {
            position: relative;
            margin-top: 16px;
            margin-bottom: 16px;
            height: 320px;
            background-color: #F6F5FC;
            color: #645F88;
            border-radius: 30px;
            z-index: 10;
        }
        .login-box, .register-box {
            min-width: 700px;
        }
        .card{
            padding:0;
        }
    </style>

@endsection

@section('content')
    <div class="card-body">
        <div class="container">
            <div class="page-banner">
                <div class="custom">
                    <h1 class="text-center" style="color: rgba(100, 95, 136, 0.75);">PicoMSG - Demo Login</h1>
                    <div class="divider mx-auto"></div>
                </div>
                <div class="row justify-content-center align-items-center h-75">

                    <div class="col-lg-6">
                        <nav aria-label="Breadcrumb">
                            <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                                <li class="breadcrumb-item"><a target="_blank" href="{{route('admin.login')}}" class="btn btn-outline-primary pl-4 pr-4"><i class="fas fa-sign-in-alt"></i></a></li>
                            </ul>
                        </nav>
                        <p class="text-center">Admin Login</p>
                    </div>
                    <div class="col-lg-6">
                        <nav aria-label="Breadcrumb">
                            <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                                <li class="breadcrumb-item"><a target="_blank" href="{{route('login')}}" class="btn btn-outline-primary pl-4 pr-4"><i class="fas fa-sign-in-alt"></i></a></li>
                            </ul>
                        </nav>
                        <p class="text-center">Customer Login</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
