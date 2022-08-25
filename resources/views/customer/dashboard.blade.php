@extends('layouts.customer')

@section('title') {{trans('customer.dashboard')}} @endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">

    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            @if(!$sms_send)
                <div class="row">
                <div class="card custom-card">
                    <h4 class="pb-1 quick_start_header">{{trans('customer.quick_start')}}</h4>
                    <div class="dis-div">
                        <div class="quick_start_topic" id="download_apk_install">
                            <a class="quick_start_ph item_download_apk" href="{{get_settings('link_apk')}}"><i class="fas fa-hand-point-right pr-2"></i>{{trans('customer.download_apk_from_given_link_and_install_it')}}</a>
                        </div>
                        <div class="{{$add_device?'text-cross':''}} quick_start_topic">
                            <a class="quick_start_ph" href="{{route('customer.device.index',['type'=>'add'])}}"><i class="fas fa-hand-point-right pr-2"></i>{{trans('customer.connect_your_phone_with_from_section')}} {{get_settings('app_name')}} {{trans('customer.from')}} "{{trans('customer.add_device')}}" {{trans('customer.section')}}</a>
                        </div>
                        <div class="{{$sending_settings?'text-cross':''}} quick_start_topic">
                            <a class="quick_start_ph" href="{{route('customer.settings.index',['type'=>'sending_settings'])}}"><i class="fas fa-hand-point-right pr-2"></i>{{trans('customer.configure_sending_settings_message')}}</a>
                        </div>
                        <div class="quick_start_topic">
                            <a class="quick_start_ph" href="{{route('customer.smsbox.compose')}}"><i class="fas fa-hand-point-right pr-2"></i>{{trans('customer.start_composing_message')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-send">
                        <div class="inner">
                            <h3>{{$smsDeliveredCount}}</h3>

                            <p>{{trans('customer.sms_delivered')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-sms_queue">
                        <div class="inner">
                            <h3>{{$inboxCount}}</h3>

                            <p>{{trans('customer.sms_received')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-sms_received">
                        <div class="inner">
                            <h3>{{$sentFail}}</h3>

                            <p>{{trans('customer.failed_to_send')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-send">
                        <div class="inner">
                            <h3>{{$sentPending}}</h3>

                            <p>{{trans('customer.sms_pending')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-sms_queued">
                        <div class="inner">
                            <h3>{{$sentQueued}}</h3>

                            <p>{{trans('customer.sms_queue')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-send">
                        <div class="inner">
                            <h3>{{$device_added}}</h3>

                            <p>{{trans('customer.device_added')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-sms_queue">
                        <div class="inner">
                            <h3>{{$total_contact}}</h3>

                            <p>{{trans('customer.total_contact')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-sms_received">
                        <div class="inner">
                            <h3>{{$total_group}}</h3>

                            <p>{{trans('customer.total_group')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-6">
                    <div class="card bg-gradient-info">
                        <div class="card-header border-0">
                            <h3 class="card-title xs-title">
                                <i class="fas fa-th mr-1"></i>
                                {{trans('customer.inbox')}}
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn bg-info btn-sm collapse-btn" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-info btn-sm collapse-btn" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart customer-dashboard-canvas" id="line-chart"></canvas>
                        </div>
                        <!-- /.card-body -->

                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-sm-6">
                    <div class="card bg-gradient-green">
                        <div class="card-header border-0">
                            <h3 class="card-title xs-title">
                                <i class="fas fa-th mr-1"></i>
                                {{trans('customer.response')}}
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn bg-primary btn-sm remove-btn" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-primary btn-sm remove-btn" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart customer-dashboard-canvas" id="weekly-response-chart"></canvas>
                        </div>
                        <!-- /.card-body -->

                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <script>
        "use strict";
        // Sales graph chart
        var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
        var weeklyResponseGraphChartCanvas = $('#weekly-response-chart').get(0).getContext('2d');
        //$('#revenue-chart').get(0).getContext('2d');

        var salesGraphChartData = {
            labels: @json($weekDates),
            datasets: [
                {
                    label: 'SMS',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#efefef',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#efefef',
                    pointBackgroundColor: '#efefef',
                    data: @json($chart_inbox)
                }
            ]
        }

        var salesGraphChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false,
            },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: '#efefef',
                    },
                    gridLines: {
                        display: false,
                        color: '#efefef',
                        drawBorder: false,
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 5000,
                        fontColor: '#efefef',
                    },
                    gridLines: {
                        display: true,
                        color: '#efefef',
                        drawBorder: false,
                    }
                }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        var salesGraphChart = new Chart(salesGraphChartCanvas, {
                type: 'line',
                data: salesGraphChartData,
                options: salesGraphChartOptions
            }
        );

    //    For Expense
        var weeklyResponseGraphChartData = {
            labels: @json($weekDates),
            datasets: [
                {
                    label: 'Response Ratio',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: 'black',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: 'black',
                    pointBackgroundColor: 'black',
                    data: @json($weeklyResponseArray)
                }
            ]
        }

        var weeklyResponseGraphChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false,
            },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: 'black',
                    },
                    gridLines: {
                        display: false,
                        color: 'black',
                        drawBorder: false,
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 5000,
                        fontColor: 'black',
                    },
                    gridLines: {
                        display: true,
                        color: 'black',
                        drawBorder: false,
                    }
                }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        var weeklyResponseGraphChart = new Chart(weeklyResponseGraphChartCanvas, {
                type: 'line',
                data: weeklyResponseGraphChartData,
                options: weeklyResponseGraphChartOptions
            }
        );
    </script>
@endsection

