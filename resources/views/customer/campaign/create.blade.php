@extends('layouts.customer')

@section('title','Campaign')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">

    <style>
        .select2-container--default .select2-selection--single {
            min-height: 38px;
            border-radius: 4px 0 0 4px;
        }

        .active {
            margin: 0 auto;
            padding: 10px 30px;
            background: #7181844d;
            color: #121213;
            border-radius: 5px;
        }

        .campaign_side_bar {
            padding: 10px 20px;
        }

        .js-irs-2 {
            display: none !important;
        }

        #range_5 {
            display: none !important;
        }

        .irs-handle .single {
            cursor: pointer !important;
        }

        .active_btn {
            background: #ec0b0b !important;
            border-color: inherit !important;
        }
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
            background-color: #d4d9da !important;
        }
        #custom_tabs_one_tabContent .tab-pane{
            padding: 0px !important;
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
                        <h2 class="card-title">@lang('customer.new_campaign')
                            <span class="ml-2 what-font-size icon-position" data-toggle="tooltip" data-placement="right" title="@lang('customer.before_create_a_campaign_message')">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </h2>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="campaignForm" action="{{route('customer.campaign.store')}}">
                        @csrf
                        <div class="card-body">
                            @include('customer.campaign.form')
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-primary float-right disabled d-none">@lang('customer.submit')</button>
                        </div>
                    </form>
                </div>


            </div>
            <!-- /.card -->
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

        let isLoading = false;
        $.validator.addMethod("phone_number", function (value, element) {
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
                    required: 'Please select an from number',
                },
                start_time: {
                    required: 'Please select campaign start time',
                },
                end_time: {
                    required: 'Please select campaign end time',
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
        $(document).on('click', '.campaign_side_bar', function (e) {
            const type = $(this).attr('data-type');
            $('.campaign_side_bar').removeClass('active');
            $(this).addClass('active');
            $('.campaign_section').hide();
            $('#' + type + '_section').show();
            if (type == 'resource') {
                $('button[type="submit"]').removeClass('disabled').removeClass('d-none');
            } else {
                $('button[type="submit"]').addClass('disabled').addClass('d-none');
            }

        });

        $(".group, .active_btn").on('click', function (e) {
            var curPos = document.getElementById("phone_numbers").selectionStart;
            let phone_numbers = $("#phone_numbers").val();
            let id = $(this).attr('data-id');
            const pre_btn = $(this).hasClass('active_btn');
            let that = $(this);

            let preData = that.attr('data-value');

            if (!isLoading && !preData) {
                isLoading = true;
                $('.group').addClass('disabled').attr('disabled','disabled');

                $.ajax({
                    method: 'get',
                    url: '{{route('customer.group.get.numbers')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.status == 'success') {
                            let insert_text = res.data;
                            let numbersInString='';
                            $.each(insert_text, function (key, data) {
                                if (key != insert_text.length - 1) {
                                    data = data.trim() + ', ';
                                } else {
                                    data = data.trim();
                                }
                                numbersInString+=data;
                            });

                            if (pre_btn) {
                                that.removeClass('active_btn').addClass('group');
                                $('#phone_numbers').val($("#phone_numbers").val().replaceAll(numbersInString, ''));

                            } else {
                                $("#phone_numbers").val(phone_numbers.slice(0, curPos) + numbersInString + ', ' + phone_numbers.slice(curPos));
                                that.addClass('active_btn').removeClass('group');
                            }
                            that.attr('data-value',numbersInString);
                            isLoading = false;
                            $('.group').removeClass('disabled').removeAttr('disabled');
                        }
                    }
                })
            } else {
                isLoading = true;
                $('.group').addClass('disabled').attr('disabled','disabled');

                if (preData) {
                    let insert_text = preData.split(", ");
                    let numbersInString='';
                    $.each(insert_text, function (key, data) {
                        if (key != insert_text.length - 1) {
                            data = data.trim() + ', ';
                        } else {
                            data = data.trim();
                        }
                        numbersInString+=data;
                    });

                    if (pre_btn) {
                        that.removeClass('active_btn').addClass('group');
                        $('#phone_numbers').val($("#phone_numbers").val().replaceAll(numbersInString, ''));

                    } else {
                        $("#phone_numbers").val(phone_numbers.slice(0, curPos) + numbersInString + ', ' + phone_numbers.slice(curPos));
                        that.addClass('active_btn').removeClass('group');
                    }
                }

                isLoading = false;
                $('.group').removeClass('disabled').removeAttr('disabled');
            }
        })
    </script>
    <script>
        $(function () {
            $('#range_5').ionRangeSlider({
                min: 1,
                max: 500,
                type: 'single',
                step: 1,
                postfix: ' ',
                prettify: false,
                hasGrid: true
            })
        });
        $(function () {
            "use strict";
            $('.date_range').daterangepicker({
                autoUpdateInput: true,
                singleDatePicker: true,
                timePicker: false,
                locale: {
                    format: 'YYYY/MM/DD'
                }
            });
        });

        $('#campaignFromDevices').select2({
            tags: true,
            tokenSeparators: [",", " "],
        })

        $('#template').select2({
            placeholder: "Select an template",
            allowClear: true
        }).on('select2:select', function (e) {
            let data = e.params.data;
            const name = $(data.element).attr('data-name');
            const body = $(data.element).attr('data-body');
            const id = $(data.element).attr('data-id');


            $('#custom_tabs_one_tabContent').append(`
                     <div class="tab-pane fade " id="custom_tabs_one_home_tab_${id}" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <textarea name="template_body[]" class="form-control" id="sms_template_body_${id}" cols="4"  rows="10">${body}</textarea>
                    </div>`);

            $('#custom_tabs_one_tab').append(`
                        <li class="nav-item">
                            <a class="nav-link select_template" id="nav_tab_${id}"  data-toggle="pill" href="#custom_tabs_one_home_tab_${id}" role="tab" data-id="${id}" aria-controls="custom-tabs-one-home" aria-selected="true">${name}</a>
                        </li>`);
            $('.select_template').last().trigger('click');
        }).on('select2:unselect', function (e){
            let data = e.params.data;
            const id = $(data.element).attr('data-id');

            $('#custom_tabs_one_home_tab_' + id).remove();
            $('#nav_tab_' + id).remove();
        });

        $(document).on('click', '.select_template', function (e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            console.log(id)
            $('#template_active_nav').val(id);
        });
        $('#campaignFromNumber').select2({
            tags: false,
            placeholder: 'Select an from number'
        });

        function typeInTextarea(newText, el = document.activeElement) {
            const [start, end] = [el.selectionStart, el.selectionEnd];
            el.setRangeText(newText, start, end, 'select');
        }
        $('.sms_template_variable').on('click', function (e) {
            let text_to_insert = $(this).attr('data-name');
            const id = $('#template_active_nav').val();
            console.log(id)
            if (id){
                typeInTextarea(text_to_insert,document.getElementById('sms_template_body_' + id));
            }
            $('#sms_template_body_' + id).focus();
        });

        $(document).on('keyup or click', '#sms_template_body', function (e) {
            const character = $(this).val().length;

            var messageValue = $(this).val();
            var div = parseInt(parseInt(messageValue.length - 1) / 160) + 1;
            if (div <= 1) {
                $("#count").text("Characters left: " + (160 - messageValue.length));
            } else $("#count").text("Characters left: " + (160 * div - messageValue.length) + "/" + div);
        });
        $('#fromType').on('change',function (e) {
            const type = $(this).val();
            $('.from-number-section').hide();
            $('#' + type + "_section").show();
        });
    </script>
@endsection

