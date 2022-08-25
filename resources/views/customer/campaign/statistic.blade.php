@extends('layouts.customer')

@section('title') Campaign Statistic @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-6 col-sm-6 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Running</h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="campaignReports" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.from')</th>
                                <th>@lang('customer.to')</th>
                                <th>@lang('customer.message')</th>
                                <th>@lang('customer.schedule_at')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($messageRunningLogs->isNotEmpty())
                                @foreach($messageRunningLogs as $message_log)
                                    <tr>
                                        <td>{{$message_log->from}}</td>
                                        <td>{{$message_log->to}}</td>
                                        <td>
                                            <div class="show-more">
                                                {{$message_log->body}}
                                            </div>
                                        </td>
                                        <td>{{($message_log->schedule_datetime)}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="5"><strong>No Data Available</strong></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="mt-2">
                            {{$messageRunningLogs->appends(['running' => $messageRunningLogs->currentPage()])->links()}}
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-lg-6 col-sm-6 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Paused</h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="campaignReports" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.from')</th>
                                <th>@lang('customer.to')</th>
                                <th>@lang('customer.message')</th>
                                <th>@lang('customer.schedule_at')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($messagePausedLogs->isNotEmpty())
                                @foreach($messagePausedLogs as $message_log)
                                    <tr>
                                        <td>{{$message_log->from}}</td>
                                        <td>{{$message_log->to}}</td>
                                        <td>
                                            <div class="show-more">
                                                {{$message_log->body}}
                                            </div>
                                        </td>
                                        <td>{{formatDate($message_log->schedule_datetime)}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="5"><strong>No Data Available</strong></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="mt-2">
                            {{$messagePausedLogs->appends(['paused' => $messagePausedLogs->currentPage()])->links()}}
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-lg-6 col-sm-6 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Failed</h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="messageFailedLogs" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.from')</th>
                                <th>@lang('customer.to')</th>
                                <th>@lang('customer.message')</th>
                                <th>@lang('customer.response_code')</th>
                                <th>@lang('customer.failed_at')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($messageFailedLogs->isNotEmpty())
                                @foreach($messageFailedLogs as $message_log)
                                    <tr>
                                        <td>{{$message_log->from}}</td>
                                        <td>{{$message_log->to}}</td>
                                        <td>
                                            <div class="show-more">
                                                {{$message_log->body}}
                                            </div>
                                        </td>
                                        <td>{{$message_log->response_code}}</td>
                                        <td>{{formatDate($message_log->updated_at)}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="5"><strong>No Data Available</strong></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="mt-2">
                            {{$messageFailedLogs->appends(['failed' => $messageFailedLogs->currentPage()])->links()}}
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-lg-6 col-sm-6 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Delivered</h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="campaignReports" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.from')</th>
                                <th>@lang('customer.to')</th>
                                <th>@lang('customer.message')</th>
                                <th>@lang('customer.schedule_at')</th>
                                <th>@lang('customer.delivered_at')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($messageDeliveredLogs->isNotEmpty())
                                @foreach($messageDeliveredLogs as $message_log)
                                    <tr>
                                        <td>{{$message_log->from}}</td>
                                        <td>{{$message_log->to}}</td>
                                        <td>
                                            <div class="show-more">
                                                {{$message_log->body}}
                                            </div>
                                        </td>
                                        <td>{{($message_log->schedule_datetime)}}</td>
                                        <td>{{formatDate($message_log->updated_at)}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="5"><strong>No Data Available</strong></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="mt-2">
                            {{$messageDeliveredLogs->appends(['delivered' => $messageDeliveredLogs->currentPage()])->links()}}
                        </div>
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
    <script>
        $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20});
    </script>
@endsection


