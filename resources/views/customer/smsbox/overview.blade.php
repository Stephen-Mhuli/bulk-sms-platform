@extends('layouts.customer')

@section('title','Overview | SmsBox')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <script>
        let orderDataTable = '';
    </script>
    <style>
        .dropdown-menu{
            left: -22px!important;
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
                        <div class="row">
                            <div class="col-lg-5">
                                <h2 class="card-title">@lang('customer.messages')</h2>
                            </div>
                            <div class="col-lg-7">
                                <a href="{{route('customer.smsbox.compose')}}" class="btn btn-info float-right ml-2" type="button">New</a>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle drop-btn float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i>  Filter
                                    </button>
                                    <div class="dropdown-menu pull-left" aria-labelledby="dropdownMenuButton">
                                        <form method="get" id="filtering-form">
                                            <div class="row m-3">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="confirm_message">@lang('customer.from_time')</label>
                                                        <input type="date" value="{{request()->get('from_date')}}" name="from_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="confirm_message">@lang('customer.to_time')</label>
                                                        <input type="date" value="{{request()->get('to_date')}}" name="to_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="type">@lang('customer.type')</label>
                                                        <select class="form-control" name="type" id="compose_type">
                                                            <option {{request()->get('type') == 'inbox'?'selected':''}} value="inbox">Inbox</option>
                                                            <option {{request()->get('type') == 'sent'?'selected':''}} value="sent">Sent</option>
                                                            <option {{request()->get('type') == 'trash'?'selected':''}}  value="trash">Trash</option>
                                                            <option {{request()->get('type') == 'draft'?'selected':''}} value="draft">Draft</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group {{request()->get('type') != 'sent'?'d-none':''}}" id="sent_status">
                                                        <label for="status">@lang('customer.status')</label>
                                                        <select class="form-control" name="status">
                                                            <option {{request()->get('status') == 'pending'?'selected':''}} value="pending">Pending</option>
                                                            <option {{request()->get('status') == 'succeed'?'selected':''}} value="succeed">Succeed</option>
                                                            <option {{request()->get('status') == 'failed'?'selected':''}} value="failed">Failed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="overview_list" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.time')</th>
                                <th>@lang('customer.message')</th>
                                <th>@lang('customer.from')</th>
                                <th>@lang('customer.to')</th>
                                <th>@lang('customer.type')</th>
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

    <script !src="">
        "use strict";
        const queryString=window.location.search;
        orderDataTable = $('#overview_list').DataTable({
            processing: true,
            ajax: {
                "url": '{{route('customer.smsbox.overview.get.data')}}'+queryString,
                "dataSrc": "data",
                "type": "GET",
                "data": function(d){
                    d.form = $("#filtering-form").serializeArray();
                }
            },
            columns: [
                { "data": "updated_at" },
                { "data": "body"},
                { "data": "from" },
                { "data": "to" },
                { "data": "type" },
                { "data": "status" },
                { "data": "action" },
            ],
            fnInitComplete: function(oSettings, json) {
                $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20,moreLink: '<a href="#">More</a>',lessLink: '<a href="#">Less</a>'});
            }
        });
    </script>
    <script>
        $('#compose_type').change(function(e) {
          const type  = $('#compose_type').val();
          if(type == 'sent'){
              $('#sent_status').removeClass('d-none');
          }else {
              $('#sent_status').addClass('d-none');
          }
        });
        $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20});
        $(".show-more-message").css('overflow', 'hidden').readmore({collapsedHeight: 20});
    </script>
@endsection

