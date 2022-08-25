@extends('layouts.admin')

@section('title') {{trans('admin.ticket.ticket')}} @endsection

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
                        <h2 class="card-title">{{trans('admin.ticket.ticket')}}</h2>
                        <div class="float-right">

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="plans" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.ticket.subject')</th>
                                <th>@lang('admin.ticket.description')</th>
                                <th>@lang('admin.ticket.customer')</th>
                                <th>@lang('admin.ticket.status')</th>
                                <th>@lang('admin.ticket.action')</th>
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
            ajax:'{{route('admin.ticket.get.all')}}',
            columns: [
                { "data": "subject" },
                { "data": "description" },
                { "data": "customer_id" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

