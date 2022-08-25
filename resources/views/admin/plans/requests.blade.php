@extends('layouts.admin')

@section('title') Plan Requests @endsection

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
                        <h2 class="card-title">@lang('admin.plans.request')</h2>
                        <div class="float-right">
                        <a class="btn btn-primary" href="{{route('admin.plans.create')}}">@lang('admin.form.button.new')</i></a>
                        <a class="btn btn-info" href="{{route('admin.plans.index')}}">@lang('admin.form.button.back')</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="plans" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.customers.customer')</th>
                                <th>@lang('admin.table.title')</th>
                                <th>@lang('admin.table.price')</th>
                                <th>@lang('admin.table.transaction_id')</th>
                                <th>@lang('admin.table.other_info')</th>
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
    <script src="{{asset('js/readmore.min.js')}}"></script>

    <script>
        "use strict";
        $('#plans').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.plan.get.requests')}}',
            columns: [
                { "data": "customer" },
                { "data": "title" },
                { "data": "price" },
                { "data": "transaction_id" },
                { "data": "other_info" },
                { "data": "status" },
                { "data": "action" },
            ],
            search:{
                search: 'pending'
            },fnInitComplete: function(oSettings, json) {
                $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20,moreLink: '<a href="#">More</a>',lessLink: '<a href="#">Less</a>'});
            }
        });

    </script>
@endsection

