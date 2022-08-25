@extends('layouts.customer')

@section('title') Groups @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.list')</h2>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{route('customer.device-group.create')}}"><i class="fas fa-plus-square">&nbsp;@lang('customer.new')</i></a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="groups" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.group_name')</th>
                                <th style="max-width: 500px">{{trans('customer.from_numbers')}}</th>
                                <th>@lang('customer.status')</th>
                                <th>@lang('customer.action')</th>
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
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/readmore.min.js')}}"></script>

    <script>
        "use strict";
        $('#groups').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.device.group')}}',
            columns: [
                { "data": "name" },
                { "data": "device_name" },
                { "data": "status" },
                { "data": "action" },
            ],
            fnInitComplete: function(oSettings, json) {
                $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20,moreLink: '<a href="#">More</a>',lessLink: '<a href="#">Less</a>'});
            }
        });


    </script>
@endsection

