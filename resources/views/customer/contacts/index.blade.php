@extends('layouts.customer')

@section('title') Contacts @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.list')</h2>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{route('customer.contacts.create')}}">@lang('customer.new')</i></a>
                            <a class="btn btn-info" href="{{route('customer.contact.import.contacts')}}">@lang('customer.import')</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="contacts" class="table table-striped table-bordered dt-responsive nowrap">
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

    <script>
        "use strict";
        $('#contacts').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.contact.get.all')}}',
            columns: [
                { "data": "number" },
                { "data": "name" },
                { "data": "email" },
                { "data": "address" },
                { "data": "city" },
                { "data": "state" },
                { "data": "zip_code" },
                { "data": "company" },
                { "data": "note" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

