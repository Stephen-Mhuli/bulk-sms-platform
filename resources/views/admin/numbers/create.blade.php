@extends('layouts.admin')

@section('title','Numbers')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('admin.numbers.new_number')</h2>
                        <a class="btn btn-info float-right" href="{{route('admin.numbers.index')}}">@lang('admin.form.button.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="numberForm" action="{{route('admin.numbers.store')}}">
                        @csrf
                        <div class="card-body">
                            @include('admin.numbers.form')
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('admin.form.button.submit')</button>
                        </div>
                    </form>
                </div>


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
        $('#from').select2();
    </script>
@endsection

