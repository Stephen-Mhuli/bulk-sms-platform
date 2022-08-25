@extends('layouts.customer')

@section('title','Filtered Result')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-12 p-3">
                <!-- Custom Tabs -->
                <div class="card mt-3">

                    <div class="card-header p-0">
                        <h2 class="p-3 d-block">Filtered Result @if($contacts) <button type="button" class="btn btn-primary float-right " data-toggle="modal" data-target="#modal-default">
                                Save This list
                            </button> @endif</h2>

                    </div>
                    <div class="card-body table-responsive p-0" style="height: 550px;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Number</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Email</th>
                                <th>SMS Sent</th>
                                <th>SMS Receive</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($contacts)
                            @foreach($contacts as $key=>$contact)
                                <tr>
                                    <td>{{$contact->first_name}}</td>
                                    <td>{{$contact->last_name}}</td>
                                    <td>{{$contact->number}}</td>
                                    <td>{{$contact->address}}</td>
                                    <td>{{$contact->city}}</td>
                                    <td>{{$contact->state}}</td>
                                    <td>{{$contact->zip_code}}</td>
                                    <td>{{$contact->email}}</td>
                                    <td>{{isset($contact->delivered_at)?$contact->delivered_at:''}}</td>
                                    <td>{{isset($contact->received_sms_date)?$contact->received_sms_date:''}}</td>
                                </tr>
                            @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="10">No Data Available</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer pull-right">
                        <ul class="pagination pagination-sm float-right">
                            <li class="page-item"><a class="page-link" href="{{$previous_url}}">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="{{$next_url}}">Next</a></li>
                        </ul>
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create New Group</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Group Name</label>
                        <input type="text" name="name" id="group_name" class="form-control" placeholder="Enter Group Name">
                        <input type="hidden" id="contact_ids" name="contact_ids[]" value="">
                        <input type="hidden" name="status" value="active">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="addNewGroupBtn" class="btn btn-primary">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>

    <script !src="">
        "use strict";
        $('#groupForm').validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {required: "Please provide  name"},
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
        });

        const filterData = @json($filter_data);
        $(document).on('click', '#addNewGroupBtn', function (e) {
            const name = $('#group_name').val();

            $.ajax({
                method: "post",
                url: "{{route('customer.create.new.group')}}",
                data: {
                    _token:'{{csrf_token()}}',
                    name:name,
                    filter:JSON.stringify(filterData)
                },
                success: function (res) {
                    if (res.status == 'success') {
                        toastr.success(res.message, 'success', {timeOut: 9000});
                        $('#modal-default').modal('hide');
                        location.href='{{route('customer.groups.index')}}';
                    }else{
                        toastr.error(res.message, 'failed', {timeOut: 9000});
                    }
                }

            });
        });

    </script>
@endsection

