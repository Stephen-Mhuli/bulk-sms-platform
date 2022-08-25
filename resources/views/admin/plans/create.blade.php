@extends('layouts.admin')

@section('title','Plans')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('admin.create_plan')</h2>
                        <a class="btn btn-info float-right" href="{{route('admin.plans.index')}}">@lang('admin.form.button.back')</i></a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="planForm" action="{{route('admin.plans.store')}}">
                        @csrf
                        <div class="card-body">
                            @include('admin.plans.form')
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
        $('#planForm').validate({
            rules: {
                title: {
                    required: true
                },
                limit: {
                    required: true
                },
                price: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                title: { required:"Please provide plan title"},
                limit:  { required:"Please provide sms limit"},
                price: { required:"Please provide plan price"},
                status:  { required:"Please select a status"}
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

