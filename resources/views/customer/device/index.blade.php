@extends('layouts.customer')

@section('title') {{trans('customer.devices')}} @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <style>
        .modal-qr-code {
            height: 200px;
            width: 220px;
            margin: 0 auto;
        }

        .modal-qr-code img {
            width: 100%;
            height: 100%;
        }


        #qrcode {
            width: 100%;
            height: 100%;
            margin: 0 auto;
            text-align: center;
        }

        #qrcode a {
            font-size: 0.8em;
        }

        .qr-url, .qr-size {
            padding: 0.5em;
            border: 1px solid #ddd;
            border-radius: 2px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        canvas {
            height: 100%;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.list')
                            <span class="ml-2 what-font-size icon-position" data-toggle="tooltip" data-placement="right" title="{{trans('customer.device_description')}}">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </h2>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary generate-qr-code" data-toggle="modal"
                                    data-target="#exampleModalCenter">
                                {{trans('customer.add_new_device')}}
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="keywords" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('customer.device_name')}}</th>
                                <th>{{trans('customer.device_model')}}</th>
                                <th>{{trans('customer.android_v')}}</th>
                                <th>{{trans('customer.app_v')}}</th>
                                <th>{{trans('customer.total_sent_message')}}</th>
                                <th>{{trans('customer.status')}}</th>
                                <th>{{trans('customer.action')}}</th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{trans('customer.add_new_device')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="close-icon" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p>Just follow the below steps to connect your Android phone with your system.</p>
                        <ul class="device-text">
                            <li><b>Step 1 :</b> You have to download the latest version APK @if(get_settings('link_apk')) <a href="{{get_settings('link_apk')}}">from here</a>@endif. This APK is not available in Play Store.</li>
                            <li><b>Step 2 :</b> Install the APK on your phone. After installation, you have to allow the required permissions.</li>
                            <li><b>Step 3 :</b> On Login screen, you can login using your credentials or by scanning the QR code.</li>
                            <li><b>Step 4 :</b> Now compose your message and start sending messages.</li>
                        </ul>
                    </div>
                    <div class="modal-qr-code">
                        <canvas id="qrcode"></canvas>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary d-none" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="add_type" value="{{request()->get('type')}}">
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/readmore.min.js')}}"></script>
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js" ></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        "use strict";
        $('#keywords').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{route('customer.get.all.device')}}',
            columns: [
                {"data": "name"},
                {"data": "model"},
                {"data": "android_version"},
                {"data": "app_version"},
                {"data": "total_sent_message"},
                {"data": "status"},
                {"data": "action"},
            ],
        });

        $('.generate-qr-code').on('click', function () {
            $('#qrcode').empty();

            const token = "{{auth('customer')->user()->authorize_token?auth('customer')->user()->authorize_token->access_token:""}}";
            const host_name = window.location.origin;
            const code = JSON.stringify({server: host_name, type: "add", token: token});
            new QRious({element: document.getElementById("qrcode"), value: code, size: 200});
        });
        $(document).ready(function() {
            const add_type = $('#add_type').val();
            if (add_type == 'add'){
                $('.generate-qr-code').trigger('click');
            }
        });
    </script>
@endsection

