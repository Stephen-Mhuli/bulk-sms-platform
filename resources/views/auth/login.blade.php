@extends('layouts.auth_customer')

@section('title','Login')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">@lang('auth.login.title')</p>

        <form id="login_form" action="{{route('authenticate')}}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input name="email" type="email" class="form-control" placeholder="@lang('auth.login.form.email')"
                       id="email">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="password" type="password" class="form-control"
                       placeholder="@lang('auth.login.form.password')" id="password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="icheck-primary">
                        <input name="remember_me" type="checkbox" id="remember">
                        <label for="remember">
                            @lang('auth.login.form.remember_me')
                        </label>
                    </div>
                </div>
                @if(env('APP_DEMO'))
                    <div class="col-lg-6">
                        <div class="icheck-primary">
                            <button type="button" class="btn btn-info float-right mb-2"
                                    id="copy-btn">@lang('auth.login.form.copy')</button>
                        </div>
                    </div>
                @endif
            <!-- /.col -->
                @if(get_settings('recaptcha_site_key'))
                    <button class="g-recaptcha btn btn-primary btn-block"
                            data-sitekey="{{get_settings('recaptcha_site_key')}}"
                            data-callback='onSubmit'
                            data-action='submit'>@lang('auth.form.button.sign_in')</button>
                @else
                    <button type="submit" class="btn btn-primary btn-block">@lang('auth.form.button.sign_in')</button>
            @endif
            <!-- /.col -->
            </div>
        </form>

        <!-- /.social-auth-links -->
        <div class="row mt-3">
            <div class="col-8">
                <p class="mb-1">
                    <a class="forgot-text" href="{{route('password.request')}}">@lang('auth.form.forget_password')</a>
                </p>
            </div>
            @if ($registration_status=='enable')
                <div class="col-4">
                    <p class="mb-0">
                        <a href="{{route('signup')}}"
                           class="text-center forgot-text">@lang('auth.form.registration')</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

@endsection
@section('extra-script')
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        function onSubmit(token) {
            document.getElementById("login_form").submit();
        }

        $("#copy-btn").click(function () {
            $("#email").val("customer@demo.com");
            $("#password").val("123456");
        });
    </script>
@endsection
