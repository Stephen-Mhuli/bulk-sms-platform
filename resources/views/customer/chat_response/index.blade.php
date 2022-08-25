@extends('layouts.customer')

@section('title') Chat Response @endsection

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
                        <h2 class="card-title">@lang('customer.list')
                            <span class="ml-2 what-font-size icon-position" data-toggle="tooltip" data-placement="right" title="@lang('customer.chat_response_description')">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </h2>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">@lang('New Response')</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="sender_ids" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Content')</th>
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
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h4 class="modal-title ml-1">@lang('Chat Response')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="close-icon" aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('customer.chat.response.store')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Content</label>
                            <textarea name="response_content" class="form-control" cols="4" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editSenderId" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h4 class="modal-title ml-1">@lang('Edit Chat Response')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="close-icon" aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('customer.chat.response.update')}}" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" id="title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Content</label>
                            <textarea name="response_content" class="form-control" id="contentEdit" cols="4" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
        $('#sender_ids').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.chat.response')}}',
            columns: [
                { "data": "id" },
                { "data": "title" },
                { "data": "content" },
                { "data": "status" },
                { "data": "action" },
            ],
            fnInitComplete: function(oSettings, json) {
                $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20,moreLink: '<a href="#">More</a>',lessLink: '<a href="#">Less</a>'});
            }
        });

        $(document).on('click', '.edit_response', function(e){
            e.preventDefault();

            const id = $(this).attr('data-id');
            const title = $(this).attr('data-title');
            const content = $(this).attr('data-content');
            const status=$(this).attr('data-status');
console.log(content)
            $('#status').val(status).change();
            $('#id').val(id);
            $('#title').val(title);
            $('#contentEdit').val(content);
            $('#editSenderId').modal('show');
        })


    </script>
@endsection

