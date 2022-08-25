@extends('layouts.admin')

@section('title') Number Requests @endsection

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
                        <h2 class="card-title">@lang('admin.numbers.request')</h2>
                        <div class="float-right">
                        <a class="btn btn-primary" href="{{route('admin.numbers.create')}}">@lang('admin.form.button.new')</a>
                        <a class="btn btn-primary" href="{{route('admin.numbers.index')}}">@lang('admin.form.button.number')</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="requests" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.numbers.customer')</th>
                                <th>@lang('admin.numbers.number')</th>
                                <th>@lang('admin.form.platform')</th>
                                <th>@lang('admin.form.purchase_price')</th>
                                <th>@lang('admin.form.sell_price')</th>
                                <th>@lang('admin.form.status')</th>
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
        $('#requests').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.number.get.requests')}}',
            columns: [
                { "data": "customer" },
                { "data": "number" },
                { "data": "from" },
                { "data": "purch_price" },
                { "data": "sell_price" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

