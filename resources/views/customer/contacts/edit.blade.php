@extends('layouts.customer')

@section('title','Edit Contact')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card mt-3">

                    <div class="card-header d-flex p-0">
                        <h2 class="card-title p-3"><a href="{{route('customer.contacts.index')}}">@lang('customer.contacts')</a></h2>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="contactForm"
                              action="{{route('customer.contacts.update',[$contact])}}">
                            @csrf
                            @method('put')
                            @include('customer.contacts.form')

                            <button type="submit" class="btn btn-primary">@lang('customer.submit')</button>
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
        $('#contactForm').validate({
            rules: {
                first_name: {
                    required: true
                }
            },
            messages: {
                name: { required:"Please provide first name"},
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

