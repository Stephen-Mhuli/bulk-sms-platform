@extends('layouts.admin')

@section('title','Edit Plan')

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
                        <h2 class="card-title p-3"><a href="{{route('admin.plans.index')}}">@lang('admin.plans.plan')</a></h2>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="numberForm"
                              action="{{route('admin.plans.update',[$plan])}}">
                            @csrf
                            @method('put')
                            @include('admin.plans.form')

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

