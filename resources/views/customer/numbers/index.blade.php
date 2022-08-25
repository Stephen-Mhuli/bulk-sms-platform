@extends('layouts.customer')

@section('title') Numbers @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <style>
        .select2-container--default .select2-selection--single {
            min-height: 38px;
            border-radius: 4px 0 0 4px;
        }
    </style>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('customer.numbers_list')}}</h2>
                        <a class="btn btn-primary float-right" href="{{route('customer.numbers.purchase')}}">{{trans('customer.purchase')}}</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="numbers" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('customer.number')}}</th>
                                <th>{{trans('customer.cost')}}</th>
                                <th>{{trans('customer.forward_to')}}</th>
                                <th>{{trans('customer.purchase_date')}}</th>
                                <th>{{trans('customer.expire_date')}}</th>
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

{{--    Modal--}}
    <div class="modal fade" id="modal-forward-edit">
        <div class="modal-dialog">
            <form id="modal-forward-edit-form" method="post" action="{{route('customer.numbers.update-forward')}}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h4 class="modal-title">{{trans('customer.forward_to')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id" id="customer_number_id" >
                            <label for="forward_to">@lang('customer.forward_to')</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <select class="form-control" name="forward_to_dial_code" id="forward_to_dial_code">
                                        @foreach(getCountryCode() as $key=>$code)
                                            <option value="+{{$code['code']}}">+{{$code['code']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="text" name="forward_to" class="form-control" id="forward_to"
                                       placeholder="@lang('customer.forward_to')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="submit"
                                class="btn btn-primary btn-sm">{{trans('customer.update')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm"
                                data-dismiss="modal">{{trans('customer.cancel')}}</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </form>
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

    <script>
        "use strict";
        $('#numbers').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.numbers.get.numbers')}}',
            columns: [
                { "data": "number","name":"number"},
                { "data": "cost" },
                { "data": "forward_to" },
                { "data": "purchased_at" },
                { "data": "expire_date" },
                { "data": "action" },
            ]
        });

        $(document).on('click','.change-forward-to',function (e) {
            e.preventDefault();
            const id=$(this).attr('data-id');
            const forwardTo=$(this).attr('data-forward-to');
            const dialCode=$(this).attr('data-forward-to-code');
            $('#forward_to').val(forwardTo);
            $('#forward_to_dial_code').val(dialCode).change();
            $('#customer_number_id').val(id);
            $('#modal-forward-edit').modal('show');
        });

        $('#forward_to_dial_code').select2();
    </script>
@endsection

