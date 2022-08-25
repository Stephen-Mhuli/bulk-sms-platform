@extends('layouts.admin')

@section('title','Edit Number')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card">

                    <div class="card-header d-flex p-0">
                        <h2 class="card-title p-3"><a href="{{route('admin.numbers.index')}}">@lang('admin.numbers.number')</a></h2>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="numberForm"
                              action="{{route('admin.numbers.update',[$number])}}">
                            @csrf
                            @method('put')
                            @include('admin.numbers.form')

                            <button type="submit" class="btn btn-primary">@lang('admin.form.button.submit')</button>
                        </form>
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
    <script !src="">
        "use strict";
        $('#numberForm').validate({
            rules: {
                number: {
                    required: true
                },
                from: {
                    required: true
                },
                purch_price: {
                    required: true
                },
                sell_price: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                number: { required:"Please provide phone number"},
                from:  { required:"Please provide platform"},
                purch_price: { required:"Please provide purchase price"},
                sell_price:  { required:"Please provide selling price"}
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
    </script>
@endsection

