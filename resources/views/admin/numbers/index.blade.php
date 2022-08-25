@extends('layouts.admin')

@section('title') Numbers @endsection

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
                        <h2 class="card-title">@lang('admin.numbers.list')</h2>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{route('admin.numbers.create')}}">@lang('admin.form.button.new')</a>
                            <a class="btn btn-info" href="{{route('admin.number.requests')}}">@lang('admin.form.button.request')</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="numbers" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.table.number')</th>
                                <th>@lang('admin.form.platform')</th>
                                <th>@lang('admin.form.purchase_price')</th>
                                <th>@lang('admin.form.sell_price')</th>
                                <th>@lang('admin.form.status')</th>
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
        $('#numbers').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.number.get.all')}}',
            columns: [
                { "data": "number" },
                { "data": "from" },
                { "data": "purch_price" },
                { "data": "sell_price" },
                { "data": "status" },
                { "data": "created_at" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

