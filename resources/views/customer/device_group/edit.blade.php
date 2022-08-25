@extends('layouts.customer')

@section('title','Edit Group')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card mt-3">

                    <div class="card-header d-flex p-0">
                        <h2 class="card-title p-3"><a
                                href="{{route('customer.groups.index')}}">@lang('customer.groups')</a></h2>

                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="groupForm"
                              action="{{route('customer.device-group.update',[$group])}}">
                            @csrf
                            @method('put')
                            @include('customer.device_group.form')

                            <button type="submit" class="btn btn-primary">@lang('customer.update')</button>
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
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

    <script !src="">
        "use strict";
        $('#groupForm').validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {required: "Please provide  name"},
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
        $('.select2').select2({
            multiple:true
        }).val(@json($device_group_names)).change();
    </script>
@endsection

