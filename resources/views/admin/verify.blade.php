@extends('layouts.auth_admin')

@section('title','Purchase Code Verification')
@section('content')
    <div class="@isset($code) d-none @endisset card-body login-card-body">
        <p class="login-box-msg">Purchase Code Verify</p>

        <form id="verification_form" action="{{route('verify')}}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input name="purchase_code" type="text" class="form-control" placeholder="Enter purchase code"
                       id="purchase_code">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-key"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary btn-block">@lang('auth.form.button.submit')</button>
            </div>
        </form>
    </div>
@endsection

@section('extra-script')

    @isset($code)
        <script>
            $("[name='purchase_code']").val('{{$code}}');
            $('#verification_form').append('<input type="hidden" name="verify" value="true">').submit();
        </script>
    @endisset
@endsection
