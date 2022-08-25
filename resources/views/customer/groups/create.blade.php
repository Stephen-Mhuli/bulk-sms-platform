@extends('layouts.customer')

@section('title','Groups')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.new_group')
                            <span class="ml-2 what-font-size icon-position" data-toggle="tooltip" data-placement="right" title="@lang('customer.group_create_description')">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </h2>
                        <a class="btn btn-info float-right"
                           href="{{route('customer.groups.index')}}">@lang('customer.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="groupForm" action="{{route('customer.groups.store')}}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @include('customer.groups.form')
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

        $('#groupForm').validate({
            rules: {
                name: {
                    required: true,
                },
                status: {
                    required: true,
                }
            },
            messages: {
                name: "Name is required"
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

        $(function () {
            //Initialize Select2 Elements

            $('.select2').select2({
                ajax: {
                    delay: 500,
                    url: '{{route('customer.contact.get.search')}}',
                    data: function (params) {
                        // Query parameters will be ?search=[term]&page=[page]
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endsection

