@extends('layouts.customer')

@section('title') Campaign Report @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Campaign report @if(isset($requestData) && isset($requestData['response_code'])) of status <span class="text-danger">{{$requestData['response_code']}}</span>@endif</h2>
                    </div>
                    <form action="{{route('customer.campaign.report')}}" method="get" class="pl-4 mt-4">
                        <div class="row mr-3">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Select Campaign</label>
                                    <select name="campaign_id" class="form-control" id="">
                                        @foreach($campaigns as $campaign)
                                            <option {{isset($requestData) && isset($requestData['campaign_id']) && $requestData['campaign_id']==$campaign->id?'selected':''}} value="{{$campaign->id}}">{{$campaign->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Select Response Code</label>
                                    <input type="number" name="response_code" placeholder="Enter Code" class="form-control" value="{{isset($requestData) && isset($requestData['response_code'])?$requestData['response_code']:''}}">
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2 mb-2">
                                <button class="btn btn-primary mt-4" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>

                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="campaignReports" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.from')</th>
                                <th>@lang('customer.to')</th>
                                <th>@lang('customer.message')</th>
                                <th>@lang('customer.response_code')</th>
                                <th>@lang('customer.updated_at')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($reports && $reports->isNotEmpty())
                                @foreach($reports as $report)
                                    <tr>
                                        <td>{{$report->from}}</td>
                                        <td>{{$report->from}}</td>
                                        <td>
                                            <div class="show-more">
                                                {{$report->body}}
                                            </div>
                                        </td>
                                        <td>{{$report->response_code}}</td>
                                        <td>{{formatDate($report->updated_at)}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="5"><strong>No Data Available</strong></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="row pt-1">
                            <div class="col-sm-12 text-right mt-4">
                                @if($reports)
                                    {{ $reports->withQueryString() }}
                                @endif
                            </div>
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


