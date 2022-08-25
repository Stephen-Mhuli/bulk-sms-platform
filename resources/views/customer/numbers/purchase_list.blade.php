@extends('layouts.customer')

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
                        <h2 class="card-title">{{trans('customer.choose_a_number')}}</h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="numbers" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('customer.number')}}</th>
                                <th>{{trans('customer.cost')}}</th>
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
            ajax:'{{route('customer.numbers.purchase.list_get')}}',
            columns: [
                { "data": "number" ,"name":"number"},
                { "data": "cost" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

