@extends('layouts.customer')

@section('title','Edit Campaign')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">

    <style>
        .select2-container--default .select2-selection--single {
            min-height: 38px;
            border-radius: 4px 0 0 4px;
        }
        .active{
            margin: 0 auto;
            padding: 10px 30px;
            background: #7181844d;
            color: #121213;
            border-radius: 5px;
        }
        .campaign_side_bar{
            padding: 10px 30px;
        }
        .js-irs-2{
            display: none !important;
        }
        #range_5{
            display: none !important;
        }
        .irs-handle .single{
            cursor: pointer !important;
        }
        .active_btn{
            background: #ec0b0b !important;
            border-color: inherit !important;
        }

    </style>
@endsection


@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card mt-3">

                    <div class="card-header d-flex p-0">
                        <h2 class="card-title p-3"><a href="{{route('customer.campaign.index')}}">@lang('customer.campaign')</a></h2>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="contactForm"
                              action="{{route('customer.campaign.update',[$campaign])}}">
                            @csrf
                            @method('put')
                            @include('customer.campaign.form')

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
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script !src="">
        "use strict";
        $.validator.addMethod("phone_number", function(value, element) {
            return new RegExp(/^[0-9\-\+]{9,15}$/).test(value);
        }, 'Invalid phone number');

        $('#campaignForm').validate({
            rules: {
                title: {
                    required: true,
                },
                'from_number[]': {
                    required: true,
                },
                start_time: {
                    required: true,
                },
                end_time: {
                    required: true,
                },
            },
            messages: {
                title: {
                    required: 'Please enter campaign title',
                },
                'from_number[]': {
                    required:'Please select an from number',
                },
                from_number: {
                    required:'Please select campaign start time',
                },
                end_time: {
                    required:'Please select campaign end time',
                },

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
        $(document).on('click', '.campaign_side_bar', function (e){
            const type= $(this).attr('data-type');
            $('.campaign_side_bar').removeClass('active');
            $(this).addClass('active');
            $('.campaign_section').hide();
            $('#' + type + '_section').show();
            if (type=='rate'){
                $('button[type="submit"]').removeClass('disabled');
            }else{
                $('button[type="submit"]').addClass('disabled');
            }

        });

        $(".group, .active_btn").on('click', function (e){
            var curPos =document.getElementById("phone_numbers").selectionStart;
            console.log(curPos);
            let phone_numbers = $("#phone_numbers").val();
            let text_to_insert = $(this).attr('data-value');
            let insert_text = JSON.parse(text_to_insert)
            const pre_btn = $(this).hasClass('active_btn')
            if (pre_btn){
                $(this).removeClass('active_btn').addClass('group');
                $.each(insert_text,function (key,data){
                    // console.log(key,insert_text.length-1);
                    if(key!=insert_text.length-1){
                        data=data.trim()+', ';
                    }else{
                        data=data.trim();
                    }
                    $('#phone_numbers').val($("#phone_numbers").val().replaceAll(data,''));
                });

            }else {
                $("#phone_numbers").val(
                    phone_numbers.slice(0, curPos) + insert_text+', ' + phone_numbers.slice(curPos));

                $(this).addClass('active_btn').removeClass('group');
            }

        })
    </script>
    <script>
        $(function () {
            $('#range_5').ionRangeSlider({
                min     : 50,
                max     : 15000,
                type    : 'single',
                step    : 50,
                postfix : ' ',
                prettify: false,
                hasGrid : true
            })
        });
        $(function () {
            "use strict";
            $('.date_range').daterangepicker({
                autoUpdateInput: true,
                singleDatePicker: true,
                timePicker: true,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            });
        });

        $('#campaignFromNumber').select2({
            tags: false,
            placeholder:'Select an from number'
        });

        $(document).on('change', '#template', function (e){
            const template_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{route('customer.get.sms.template')}}",
                data: {template_id: template_id,},
                success: function (res) {
                    if (res.status=='success'){
                        $('#sms_template_body').val(res.data);
                    }
                }
            })
        });
        $('.sms_template_variable').on('click', function (e) {
            var curPos =
                document.getElementById("sms_template_body").selectionStart;

            let phone_numbers = $("#sms_template_body").val();
            let text_to_insert = $(this).attr('data-name');
            $("#sms_template_body").val(
                phone_numbers.slice(0, curPos) + text_to_insert + phone_numbers.slice(curPos));

        });

        $(document).on('keyup or click', '#sms_template_body', function (e){
            const character = $(this).val().length;

            var messageValue = $(this).val();
            var div = parseInt(parseInt(messageValue.length - 1) / 160) + 1;
            if (div <= 1) {
                $("#count").text("Characters left: " + (160 - messageValue.length));
            } else $("#count").text("Characters left: " + (160 * div - messageValue.length) + "/" + div);
        });
    </script>
@endsection

