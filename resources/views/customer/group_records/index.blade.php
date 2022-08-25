@extends('layouts.customer')

@section('title','Groups')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        .daterangepicker.show-calendar{
            top: 1140px !important;
            bottom: auto !important;
        }

    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card mb-5">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.group_list')</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.groups.index')}}">@lang('customer.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="get" role="form" id="groupForm" action="{{route('customer.group.filter.records')}}" enctype="multipart/form-data">

                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-sm-4 col-4"><label for="">Group List</label></div>
                                <div class="col-sm-8 col-8">
                                    <input type="hidden" name="group_ids" id="group_ids">
                                    <select class="form-control" multiple="multiple" id="groups">
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}">{{$group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">First Name</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="first_name_type" class="form-control" >
                                        <option value="=">Equal</option>
                                        <option value="!=">Not Equal</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3">
                                    <textarea name="first_name" class="form-control" id="" cols="3" rows="3"></textarea>
                                </div>


                                <div class="col-sm-2 mt-3"><label for="">Last Name</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="last_name_type" class="form-control" >
                                        <option value="=">Equal</option>
                                        <option value="!=">Not Equal</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3">
                                    <textarea name="last_name" class="form-control" id="" cols="3" rows="3"></textarea>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">Phone</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="phone_number_type" class="form-control" >
                                        <option value="=">Equal</option>
                                        <option value="!=">Not Equal</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3">
                                    <textarea name="phone_number" class="form-control" id="" cols="3" rows="3"></textarea>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">Address</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="address_type" class="form-control" >
                                        <option value="=">Equal</option>
                                        <option value="!=">Not Equal</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3">
                                    <textarea name="address" class="form-control" id="" cols="3" rows="3"></textarea>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">City</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="city_type" class="form-control" >
                                        <option value="=">Equal</option>
                                        <option value="!=">Not Equal</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3">
                                    <textarea name="city" class="form-control" id="" cols="3" rows="3"></textarea>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">State</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="state_type" class="form-control" >
                                        <option value="=">Equal</option>
                                        <option value="!=">Not Equal</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3">
                                    <textarea name="state" class="form-control" id="" cols="3" rows="3"></textarea>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">Zip</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="zip_code_type" class="form-control" >
                                        <option value="=">Equal</option>
                                        <option value="!=">Not Equal</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3">
                                    <textarea name="zip_code" class="form-control" id="" cols="3" rows="3"></textarea>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">Email</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="email_type" class="form-control" >
                                        <option value="=">Equal</option>
                                        <option value="!=">Not Equal</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3">
                                    <textarea name="email" class="form-control" id="" cols="3" rows="3"></textarea>
                                </div>

                                <div class="col-sm-4 mt-3"><label for="">Label</label></div>

                                <div class="col-sm-8 mt-3">
                                    <select name="label" class="form-control" id="">
                                        <option value="new">New</option>
                                        <option value="not_interested">Not Interested</option>
                                        <option value="wrong_number">Wrong Number</option>
                                        <option value="retail">Retail</option>
                                        <option value="hot_lead">Hot Lead</option>
                                    </select>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">SMS Sent</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="sent_type" id="sent_type" class="form-control" >
                                        <option value="within">Within The Last</option>
                                        <option value="between">Between</option>
                                        <option value="older_than">Older Than</option>
                                        <option value="empty">Empty</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3 days_section" >
                                    <div class="form-group"  id="days_section">
                                        <input value="0" type="number" class="form-control" name="sms_sent_days">
                                        <small class="float-right">Days</small>
                                    </div>
                                </div>

                                <div class="col-sm-4 mt-3 between_date d-none">
                                    <input type='text' name="between_from" class="form-control between_dates"/>
                                </div>
                                <div class="col-sm-4 mt-3 between_date d-none">
                                    <input type='text' name="between_to" class="form-control between_dates"/>
                                </div>

                                <div class="col-sm-2 mt-3"><label for="">SMS Received</label></div>
                                <div class="col-sm-2 mt-3">
                                    <select name="sms_received_type" id="sms_received_type" class="form-control" >
                                        <option value="within">Within The Last</option>
                                        <option value="between">Between</option>
                                        <option value="older_than">Older Than</option>
                                        <option value="empty">Empty</option>
                                    </select>
                                </div>
                                <div class="col-sm-8 mt-3 sms_received_days_section" >
                                    <div class="form-group"  id="sms_received_days_section">
                                        <input value="0" type="number" class="form-control" name="sms_received_days">
                                        <small class="float-right">Days</small>
                                    </div>
                                </div>

                                <div class="col-sm-4 mt-3 sms_received_between_date d-none">
                                    <input type='text' name="sms_received_between_from" class="form-control between_dates"/>
                                </div>
                                <div class="col-sm-4 mt-3 sms_received_between_date d-none">
                                    <input type='text' name="sms_received_between_to" class="form-control between_dates"/>
                                </div>

                            </div>
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
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script>
        // $('#groups').select({
        //     multiple:true
        // });
        $(document).on('change', '#sent_type', function (e){
           const type = $(this).val();
           if (type=='between'){
               $('.between_date').removeClass('d-none');
               $('#days_section').addClass('d-none');
               $('.days_section').addClass('d-none');
           }else if(type=='empty'){
               $('#days_section').addClass('d-none');
               $('.between_date').addClass('d-none');
               $('#between_date').addClass('d-none');
           } else{
               $('.between_date').addClass('d-none');
               $('#days_section').removeClass('d-none');
               $('.days_section').removeClass('d-none');
           }
        });
        $(document).on('change', '#sms_received_type', function (e){
            const type = $(this).val();
            if (type=='between'){
                $('.sms_received_between_date').removeClass('d-none');
                $('#sms_received_days_section').addClass('d-none');
                $('.sms_received_days_section').addClass('d-none');
            }else if(type=='empty'){
                $('.sms_received_days_section').addClass('d-none');
                $('#sms_received_days_section').addClass('d-none');
                $('.sms_received_between_date').addClass('d-none');
            } else{
                $('.sms_received_between_date').addClass('d-none');
                $('#sms_received_days_section').removeClass('d-none');
                $('.sms_received_days_section').removeClass('d-none');
            }
        });

        $('.between_dates').daterangepicker({
            autoUpdateInput: true,
            singleDatePicker: true,
            timePicker: true,
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            }
        });

        $('#groups').on('change',function (e) {
            e.preventDefault();
           $('#group_ids').val(JSON.stringify($(this).val()));
        })
    </script>
@endsection

