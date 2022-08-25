@extends('layouts.customer')

@section('title') Campaign @endsection

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
                            <a href="{{route('customer.campaign.report')}}" class="btn btn-info" target="_blank">Reports</a>
                            <a class="btn btn-primary" href="{{route('customer.campaign.create')}}">@lang('customer.new')</i></a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="contacts" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.title')</th>
                                <th>@lang('customer.start_date')</th>
                                <th>@lang('customer.end_date')</th>
                                <th>@lang('customer.start_time')</th>
                                <th>@lang('customer.end_time')</th>
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

    <script>
        "use strict";
        $('#contacts').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.campaings')}}',
            columns: [
                { "data": "title","name":"campaigns.title" },
                { "data": "start_date" },
                { "data": "end_date" },
                { "data": "start_time" },
                { "data": "end_time" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection


