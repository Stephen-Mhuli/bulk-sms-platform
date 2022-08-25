@extends('layouts.admin')

@section('title','Edit Customers')

@section('extra-css')
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <!-- Custom Tabs -->
                <div class="card">
                    <div class="card-header p-0">
                        <div class="row">
                            <h2 class="card-title pl-3"><a href="{{route('admin.customers.index')}}">@lang('admin.customers.customer')</a></h2>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active mr-2 nav-link-hover" href="#edit_tab" data-toggle="tab">@lang('admin.customers.edit')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-hover" href="#plan_tab" data-toggle="tab">@lang('admin.customers.plan')</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="edit_tab">
                                <form method="post" role="form" id="customerForm"
                                      action="{{route('admin.customers.update',[$customer])}}">
                                    @csrf
                                    @method('put')
                                    @include('admin.customers.form')

                                    <button type="submit" class="btn btn-primary">@lang('admin.form.button.submit')</button>
                                </form>
                            </div>
                            <div class="tab-pane" id="plan_tab">

                                <div class="card" id="planListSection">
                                    <div class="card-header">
                                        <h3 class="card-title">@lang('admin.customers.plan')</h3>
                                        <div class="card-tools d-none">
                                            <ul class="pagination pagination-sm float-right">
                                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <div class="row">
                                            @isset($currentPlan->plan)
                                            <div class="col-sm-4">
                                                <ul class="current-plan list-inline mt-2 mt-sm-5">

                                                    <li>
                                                        <div class="title">@lang('admin.table.current'):</div>
                                                        <div class="value">{{$currentPlan->plan->title}}</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">@lang('admin.table.cost'):</div>
                                                        <div class="value">{{formatNumberWithCurrSymbol($currentPlan->plan->price)}}</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">@lang('admin.table.daily_send_limit'):</div>
                                                        <div class="value">{{$currentPlan->daily_send_limit}}</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">@lang('admin.table.daily_receive_limit'):</div>
                                                        <div class="value">{{$currentPlan->daily_receive_limit}}</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">@lang('admin.table.contact_limit'):</div>
                                                        <div class="value">{{$currentPlan->contact_limit}}</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">@lang('admin.table.device_limit'):</div>
                                                        <div class="value">{{$currentPlan->device_limit}}</div>
                                                    </li>

                                                    @if(!$currentPlan->daily_send_limit)
                                                        <div class="no-value">@lang('admin.customers.no_plan')</div>
                                                    @endif
                                                </ul>

                                            </div>
                                            @endisset
                                            <div class="col-sm-8">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('admin.table.title')</th>
                                                        <th>@lang('admin.table.daily_send_limit')</th>
                                                        <th>@lang('admin.table.daily_receive_limit')</th>
                                                        <th>@lang('admin.table.cost')</th>
                                                        <th class="table-action">@lang('admin.table.action')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($activePlans->isEmpty())
                                                        <tr>
                                                            <td colspan="3" class="text-center">@lang('admin.table.empty')</td>
                                                        </tr>
                                                    @endif
                                                    @foreach($activePlans as $plan)
                                                        <tr>
                                                            <td>{{$plan->title}} @if(isset($currentPlan->plan_id) && $currentPlan->plan_id==$plan->id) (@lang('admin.table.current')) @endif</td>
                                                            <td>{{$plan->daily_send_limit}}</td>
                                                            <td>{{$plan->daily_receive_limit}}</td>
                                                            <td>{{formatNumberWithCurrSymbol($plan->price)}}</td>
                                                            <td>
                                                                @if(isset($currentPlan->plan_id) && $currentPlan->plan_id==$plan->id)
                                                                    <button disabled class="btn btn-primary btn-sm" title="Active Plan"><i class="fas fa-check disabled"></i></i>
                                                                    </button>
                                                                @else
                                                                <button
                                                                    data-message="{!! trans('admin.message.assign_plan',['plan'=>'<b> '.$plan->title.'</b>']) !!}"
                                                                    data-action="{{route('admin.customer.plan.change')}}"
                                                                    data-input='{"id":"{{$plan->id}}","customer_id":"{{$customer->id}}"}'
                                                                    data-toggle="modal" data-target="#modal-confirm"
                                                                    id="changePlan" class="btn btn-primary btn-sm"
                                                                    type="button" title="Change"><i class="fas fa-random"></i>
                                                                </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>

                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
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
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script !src="">
        "use strict";
        let $validate;
        $validate = $('#customerForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
            },
            messages: {
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a vaild email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                first_name: {required: "Please provide first name"},
                last_name: {required: "Please provide last name"}
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
    </script>
    @if(!isset($customer))
        <script !src="">
            "use strict";
            $validate.rules('add', {
                password: {
                    required: true,
                    minlength: 5
                },
            })
        </script>
    @endif
@endsection

