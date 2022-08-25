@extends('layouts.customer')

@section('title') {{trans('customer.ticket.ticket')}} @endsection

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
                        <h2 class="card-title">{{trans('customer.ticket.ticket')}}</h2>
                        <div class="float-right">
                                <button type="button" class="btn btn-primary mb-2" data-toggle="modal"
                                        data-target="#basicModal">@lang('customer.new')</i>
                                </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="plans" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('customer.ticket.subject')}}</th>
                                <th>{{trans('customer.ticket.description')}}</th>
                                <th>{{trans('customer.ticket.status')}}</th>
                                <th>{{trans('customer.ticket.action')}}</th>
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
    <div class="modal fade show" id="basicModal" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog" role="document">
            <form action="{{route('customer.ticket.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Subject</label>
                            <input type="text" class="form-control" name="subject" placeholder="Enter Subject">
                        </div>
                        <div class="form-group">
                            <label for="">Upload Document</label>
                            <input type="file" class="form-control p-1" name="document">
                        </div>

                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description" class="form-control" id="" cols="7" rows="7"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
            ajax:'{{route('customer.ticket.get.all')}}',
            columns: [
                { "data": "subject" },
                { "data": "description" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

