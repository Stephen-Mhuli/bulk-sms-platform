@extends('layouts.auth_customer')

@section('title','Forget password')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">@lang('passwords.title')</p>

        <form action="{{route('password.reset.confirm')}}" method="post">
            @csrf
            <input type="hidden" name="customer" value="{{$id}}">
            <input type="hidden" name="type" value="{{$type}}">
            <input type="hidden" name="token" value="{{$token}}">
            <div class="input-group mb-3">
                <input name="password" type="password" class="form-control" placeholder="@lang('passwords.new_password')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-key"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="password_confirmation" type="password" class="form-control" placeholder="@lang('passwords.confirm_new_password')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-key"></span>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">@lang('auth.form.button.submit')</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>

@endsection
