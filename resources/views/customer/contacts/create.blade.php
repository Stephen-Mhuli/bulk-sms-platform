@extends('layouts.customer')

@section('title','Contacts')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <style>
        .select2-container--default .select2-selection--single {
            min-height: 38px;
            border-radius: 4px 0 0 4px;
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.new_contact')</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.contacts.index')}}">@lang('customer.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="contactForm" action="{{route('customer.contacts.store')}}">
                        @csrf
                        <div class="card-body">
                            @include('customer.contacts.form')
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('customer.submit')</button>
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
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script !src="">
        "use strict";
        $.validator.addMethod("phone_number", function(value, element) {
            return new RegExp(/^[0-9\-\+]{9,15}$/).test(value);
        }, 'Invalid phone number');

        $('#contactForm').validate({
            rules: {
                number: {
                    required: true,
                    phone_number:true
                }
            },
            messages: {

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

        $('#forward_to_dial_code,#contact_dial_code').select2();
    </script>
@endsection

