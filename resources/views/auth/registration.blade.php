@extends('layouts.auth_customer')

@section('title','Sign up')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">@lang('auth.registration.title')</p>

        <form id="login_registration" action="{{route('signup')}}" method="post">
            @csrf
            @if(request()->get('plan'))
                <input type="hidden" name="plan_id" value="{{request()->get('plan')}}">
            @endif
            <div class="input-group mb-3">
                <input name="first_name" type="text" class="form-control" placeholder="@lang('auth.registration.form.first_name')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="last_name" type="text" class="form-control" placeholder="@lang('auth.registration.form.last_name')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="email" type="email" class="form-control" placeholder="@lang('auth.registration.form.email')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="password" type="password" class="form-control" placeholder="@lang('auth.registration.form.password')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8 text-sm">
                {!! trans('auth.terms_condition',['terms'=>'<a href="#" class="forgot-text">Terms and Condition</a>']) !!}
                </div>
                <!-- /.col -->
                <div class="col-4">
                    @if(get_settings('registration_status')=='enable')
                        @if(get_settings('recaptcha_site_key'))
                            <button class="g-recaptcha btn btn-primary btn-block"
                                    data-sitekey="{{get_settings('recaptcha_site_key')}}"
                                    data-callback='onSubmit'
                                    data-action='submit'>@lang('auth.form.button.sign_up')</button>
                        @else
                            <button type="submit"
                                    class="btn btn-primary btn-block">@lang('auth.form.button.sign_up')</button>
                        @endif
                    @endif
                </div>
                <!-- /.col -->
            </div>
        </form>
        <!-- /.social-auth-links -->
<div class="row mt-3">
    <div class="col-6">
        <p class="mb-1">
            <a class="forgot-text" href="{{route('password.request')}}">@lang('auth.form.forget_password')</a>
        </p>
    </div>
    <div class="col-6">
        <p class="mb-0">
            <a href="{{route('login')}}" class="text-center forgot-text">@lang('auth.form.sign_in')</a>
        </p>
    </div>
</div>


    </div>

@endsection
@section('extra-script')
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        function onSubmit(token) {
            document.getElementById("login_registration").submit();
        }
    </script>
@endsection
