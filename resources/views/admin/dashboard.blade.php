@extends('layouts.admin')

@section('title') Dashboard @endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">

    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-send">
                        <div class="inner">
                            <h3>{{$newMessageCount}}</h3>

                            <p>@lang('admin.dashboard.today_inbox')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-sms_queue">
                        <div class="inner">
                            <h3>{{$newSentCount}}</h3>

                            <p>@lang('admin.dashboard.today_sent')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-sms_received">
                        <div class="inner">
                            <h3>{{$totalInbox}}</h3>

                            <p>@lang('admin.dashboard.total_inbox')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-sms_sent">
                        <div class="inner">
                            <h3>{{$totalSent}}</h3>

                            <p>@lang('admin.dashboard.total_sent')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                      </div>
                </div>
                <!-- ./col -->
            </div>


            <div class="row">

                <div class="col-sm-6">
                    <div class="card bg-gradient-info">
                        <div class="card-header border-0">
                            <h3 class="card-title xs-title">
                                <i class="fas fa-th mr-1"></i>
                                @lang('admin.customers.customer')
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
                            <canvas class="chart admin-dashboard-canvas" id="line-chart" ></canvas>
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
                                @lang('admin.dashboard.revenue')
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn bg-green btn-sm remove-btn" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-green btn-sm remove-btn" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart admin-dashboard-canvas" id="sent-chart"></canvas>
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
        var sentCanvas = $('#sent-chart').get(0).getContext('2d');
        //$('#revenue-chart').get(0).getContext('2d');

        var salesGraphChartData = {
            labels: @json($months),
            datasets: [
                {
                    label: 'Customer',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#efefef',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#efefef',
                    pointBackgroundColor: '#efefef',
                    data: @json($chart_customers)
                }
            ]
        }

        var sentGraphChartData = {
            labels: @json($months),
            datasets: [
                {
                    label: 'amount',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: 'black',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: 'black',
                    pointBackgroundColor: 'black',
                    data: @json($chat_amount)
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

        var sentGraphChartOptions = {
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
        };


        var salesGraphChart = new Chart(salesGraphChartCanvas, {
                type: 'line',
                data: salesGraphChartData,
                options: salesGraphChartOptions
            }
        );

        var sentGraphChart = new Chart(sentCanvas, {
                type: 'line',
                data: sentGraphChartData,
                options: sentGraphChartOptions
            }
        );
    </script>
@endsection

