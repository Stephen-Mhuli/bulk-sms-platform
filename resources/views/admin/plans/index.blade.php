@extends('layouts.admin')

@section('title') Plans @endsection

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
                        <h2 class="card-title">@lang('admin.plans.plan')</h2>
                        <div class="float-right">
                        <a class="btn btn-primary" href="{{route('admin.plans.create')}}">@lang('admin.form.button.new')</a>
                        <a class="btn btn-info" href="{{route('admin.plan.requests')}}">@lang('admin.form.button.request')<span class="plan_pending_count pending_plan_req_btn">{{pendingPlanRequest()}}</span></a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="plans" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.table.title')</th>
                                <th>@lang('admin.table.contact_limit')</th>
                                <th>@lang('admin.table.device_limit')</th>
                                <th>@lang('admin.table.daily_receive_limit')</th>
                                <th>@lang('admin.table.daily_send_limit')</th>
                                <th>@lang('admin.table.price')</th>
                                <th>@lang('admin.table.recurring_type')</th>
                                <th>@lang('admin.table.status')</th>
                                <th>@lang('admin.table.created_at')</th>
                                <th>@lang('admin.table.action')</th>
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

    <script>
        "use strict";
        $('#plans').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.plan.get.all')}}',
            columns: [
                { "data": "title" },
                { "data": "contact_limit" },
                { "data": "device_limit" },
                { "data": "daily_receive_limit" },
                { "data": "daily_send_limit" },
                { "data": "price" },
                { "data": "recurring_type" },
                { "data": "status" },
                { "data": "created_at" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

