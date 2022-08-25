@extends('layouts.customer')

@section('title','Import | Number')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endsection
<style>
    .tab {
        display: none;
    }

    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    .step.active {
        opacity: 1;
    }

    .step.finish {
        background-color: #2e5cb8;
    }

    .card {
        overflow: auto;
    }
</style>
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.import_contact')</h2>
                    </div>
                    <form method="post" role="form" id="importForm"
                          action="{{route('customer.contact.import.contacts.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body tab">
                            <div class="form-group">
                                <label for="import_name">@lang('customer.group_name') *</label>
                                <input value="" type="text" name="import_name"
                                       class="form-control" id="import_name"
                                       placeholder="@lang('customer.group_name')">
                                <span class="text-danger" id="err_msg"></span>
                            </div>
                            <div class="form-group mt-4">
                                <label for="import_contact_csv">@lang('customer.upload_csv')</label>
                                <input type="file" class="form-group" name="import_contact_csv" id="import_contact_csv">
                                <div class="float-right"><a target="_blank"
                                                            href="{{route('customer.download.sample',['type'=>'group'])}}"
                                                            class="text-muted">@lang('customer.download_sample')</a>
                                </div>
                            </div>
                            <span class="text-danger" id="err_msg_csv"></span>
                        </div>

                        <div class="card-body tab">
                            <table class="table table-striped table-bordered dt-responsive nowrap">
                                <thead>
                                <tr>
                                    <th>@lang('customer.number')</th>
                                    <th>@lang('customer.name')</th>
                                    <th>@lang('customer.email')</th>
                                    <th>@lang('customer.address')</th>
                                    <th>@lang('customer.city')</th>
                                    <th>@lang('customer.state')</th>
                                    <th>@lang('customer.zip_code')</th>
                                    <th>@lang('customer.company')</th>
                                    <th>@lang('customer.note')</th>
                                </tr>
                                </thead>
                                <tbody id="import_contacts">

                                </tbody>
                            </table>
                        </div>
                        <div class="text-right" style="margin-right: 23px;">
                            <button class="btn btn-default" type="button" id="prevBtn" onclick="nextPrev(-1)">Previous
                            </button>
                            <button class="btn btn-primary" type="button" id="nextBtn" onclick="nextPrev(1)">Next
                            </button>
                        </div>
                        <div style="text-align:center;">
                            <span class="step"></span>
                            <span class="step"></span>
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

        $(function () {
            $('.select2').select2();
        });


        var currentTab = 0;
        showTab(currentTab);

        function showTab(n) {
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            var x = document.getElementsByClassName("tab");
            if (n == 1 && !validateForm()) return false;
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
                document.getElementById("importForm").submit();
                return false;
            }
            showTab(currentTab);
        }

        function validateForm() {
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            for (i = 0; i < y.length; i++) {
                if (y[i].value == "") {
                    y[i].className += " invalid";
                    valid = false;
                }
            }
            return valid;
        }

        function fixStepIndicator(n) {
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            x[n].className += " active";
        }

        $(function () {
            $('#nextBtn').on('click', function () {
                const import_contact_csv = $('#import_contact_csv').val();
                const input_name = $('#import_name').val();
                if (input_name == '') {
                    $('#import_name').css('border', '1px solid red');
                    $('#err_msg').html('<small class="float-left"> Please enter the name</small>')
                } else {
                    $('#import_name').css('border', '1px solid #ced4da');
                    $('#err_msg').html(' ')
                }
                if (import_contact_csv == '') {
                    $('#err_msg_csv').html('<small class="float-left"> Please upload the file</small>')
                } else {
                    $('#err_msg_csv').html(' ')
                }
                let form = $('#importForm');
                formValidate(form);
                let formData = new FormData(form[0]);
                formData.append('file', $('input[type=file]')[0].files[0]);
                $('#import_contacts').html("<tr class='text-center'> <td colspan='8'> <span> <i class='fas fa-spinner fa-pulse'></i> Generating</span></tr> </td>")
                $.ajax({
                    method: "POST",
                    url: "{{route('customer.contact.import.contacts.show')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.status == 'success') {
                            let html = '';
                            $.each(res.data, function (index, value) {
                                html += `
                            <tr>
                               <td>${value.number}</td>
                               <td>${value.full_name}</td>
                               <td>${value.email}</td>
                               <td>${value.address}</td>
                               <td>${value.city}</td>
                               <td>${value.state}</td>
                               <td>${value.zip_code}</td>
                               <td>${value.company}</td>
                               <td>${value.note}</td>
                            </tr>
                             `
                            });
                            $('#import_contacts').html(html);
                        }
                    }
                });
            });
        });

        function formValidate(form_name) {
            $(form_name).validate({
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

        }
    </script>
@endsection
