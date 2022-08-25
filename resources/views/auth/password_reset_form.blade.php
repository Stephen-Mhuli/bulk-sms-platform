@extends('layouts.auth_customer')

@section('title','Forget password')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">@lang('passwords.title')</p>

        <form action="{{route('password.sent')}}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input name="email" type="email" class="form-control" placeholder="@lang('passwords.email')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">@lang('auth.form.button.reset')</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <!-- /.social-auth-links -->
        <div class="row mt-3">
            <div class="col-8">
                <p class="mb-1">
                    <a class="forgot-text" href="{{route('login')}}">@lang('auth.form.sign_in')</a>
                </p>
            </div>
            @if ($registration_status=='enable')
            <div class="col-4">
                <p class="mb-0">
                    <a href="{{route('signup')}}" class="text-center forgot-text">@lang('auth.form.registration')</a>
                </p>
            </div>
            @endif
        </div>
    </div>

@endsection
