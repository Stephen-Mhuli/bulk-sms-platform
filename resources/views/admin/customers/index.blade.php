@extends('layouts.admin')

@section('title') Customers @endsection

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
                        <h2 class="card-title">@lang('admin.customers.list')</h2>
                        <a class="btn btn-primary float-right" href="{{route('admin.customers.create')}}">@lang('admin.form.button.new')</i></a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="customers" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.table.name')</th>
                                <th>@lang('admin.table.email')</th>
                                <th>@lang('admin.table.status')</th>
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
        $('#customers').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.customer.get.all')}}',
            columns: [
                { "data": "full_name" },
                { "data": "email","name":"customers.email" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection

