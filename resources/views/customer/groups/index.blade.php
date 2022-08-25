@extends('layouts.customer')

@section('title') Groups @endsection

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
                        <h2 class="card-title">@lang('customer.list')</h2>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{route('customer.groups.create')}}">@lang('customer.new')</i></a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="groups" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.group_name')</th>
                                <th style="max-width: 500px">@lang('customer.contacts')</th>
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
    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('customer.export.group.contact')}}" id="exportForm" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Export Group Contacts</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="groupId">
                        <div class="form-group">
                            <label for="">Select Label</label>
                            <select name="label[]" class="form-control" id="label">
                                @foreach($labels as $label)
                                    <option value="{{$label->id}}">{{$label->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary confirmExport">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/readmore.min.js')}}"></script>

    <script>
        "use strict";
        $('#groups').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.group.get.all')}}',
            columns: [
                { "data": "name" },
                { "data": "contacts" },
                { "data": "status" },
                { "data": "action" },
            ],
            fnInitComplete: function(oSettings, json) {
                $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20,moreLink: '<a href="#">More</a>',lessLink: '<a href="#">Less</a>'});
            }
        });

        $(document).on('click', '.export_group_contact', function (e) {
            $('.confirmExport').html('Confirm');
            const id = $(this).attr('data-id');
            $('#groupId').val(id);
            $('#exportModal').modal('show');
        });
        $('#exportForm').submit(function (e) {
            setTimeout(function () {
                $('#exportModal').modal('hide');
            }, 700);
        });
        $('#label').select2({
            multiple:true
        })
    </script>
@endsection

