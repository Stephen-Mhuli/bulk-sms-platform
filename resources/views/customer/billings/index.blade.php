@extends('layouts.customer')

@section('title') Billings @endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('customer.billing')}}</h2>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="content-title">Daily send limit</div>
                                        <span class="use-limit">{{$remainDailySent}}</span><span class="limit">/{{isset($customer_plan->daily_send_limit)?$customer_plan->daily_send_limit:0}}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="content-title">Daily Received</div>
                                        <span class="use-limit">{{$remainDailyReceive}}</span><span class="limit">/{{isset($customer_plan->daily_receive_limit)?$customer_plan->daily_receive_limit:0}}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="content-title">Contacts</div>
                                        <span class="use-limit">{{$remaincontact}}</span><span class="limit">/{{isset($customer_plan->contact_limit)?$customer_plan->contact_limit:0}}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="content-title">Devices</div>
                                        <span class="use-limit">{{$remaindevice}}</span><span class="limit">/{{isset($customer_plan->device_limit)?$customer_plan->device_limit:0}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="plan-header">Your Plan</div>
                                @if(isset($customer_plan->plan) && $customer_plan->plan)
                                    <h2 class="plan-title">{{$customer_plan->plan->title}}</h2>
                                @else
                                    <h2 class="plan-title">&nbsp;</h2>
                                @endif

                                @if(isset($customer_plan->renew_date) && $customer_plan->renew_date)
                                <div class="content-title">Expiry at {{$customer_plan->renew_date->format('M d, Y')}}</div>
                                @else
                                    <div class="content-title">&nbsp;</div>
                                @endif
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <a class="btn-info btn" href="{{route('customer.billing.change.billing')}}">Change Plan</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row billing-row">
                        <div class="col-lg-12">
                            <div class="row mb-2 pt-2">
                                <div class="col-lg-1">
                                    <div class="billing-data">No</div>
                                </div>
                                <div class="{{!isset($paymentPlan) && isset($renewDate)?'col-lg-1':"col-lg-2"}}">
                                    <div class="billing-data">Plan</div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="billing-data">Amount</div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="billing-data">Payment Status</div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="billing-data">Status</div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="billing-data">Purchase Date</div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="billing-data">Expiry Date</div>
                                </div>
                                @if(!isset($paymentPlan) && isset($renewDate))
                                    <div class="billing-data">Action</div>
                                @endif
                            </div>
                            @if($customerPlans->isNotEmpty())
                                @foreach($customerPlans as $key=>$customerPlan)
                                    <div class="row custom-row">
                                        <div class="col-lg-12">
                                            <div class="card billing-card">
                                                <div class="row">
                                                    <div class="col-lg-1">
                                                        <div class="billing-data">#{{$customerPlan->id}}</div>
                                                    </div>
                                                    <div class="{{!isset($paymentPlan) && isset($renewDate)?'col-lg-1':"col-lg-2"}}">
                                                        <div class="billing-data">{{$customerPlan->plan->title}}</div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="billing-data">{{formatNumberWithCurrSymbol($customerPlan->plan->price)}}</div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="billing-data">{{ucfirst($customerPlan->payment_status)}}</div>
                                                    </div>
                                                    <div class="col-lg-2 text-center">
                                                        @if($customerPlan->status == 'pending')
                                                            <div class="text-warning light"> <i class="far fa-clock"></i> Pending</div>
                                                        @elseif($customerPlan->status == 'rejected')
                                                            <div class="text-danger light"> <i class="fa fa-times"></i> Rejected</div>
                                                        @elseif($customerPlan->status == 'accepted')
                                                            <div class="text-success light"> <i class="fa fa-check"></i> Accepted</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="billing-data">{{$customerPlan->created_at->format('M d, Y')}}</div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        @if(isset($customerPlan->renew_date) && $customerPlan->renew_date)
                                                            <div class="billing-data">{{$customerPlan->renew_date->format('M d, Y')}}</div>
                                                        @endif
                                                    </div>
                                                    @if($customerPlan->status == 'pending' && !isset($paymentPlan))
                                                        @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                                            <button
                                                                data-message="{!! trans('customer.renew_plan',['plan'=>$customerPlan->plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.renew_plan_nb')}}</span>"
                                                                data-action="{{route('paymentgateway::process')}}"
                                                                data-input='{"id":"{{$customerPlan->plan_id}}"}'
                                                                data-toggle="modal" data-target="#modal-confirm"
                                                                type="button"
                                                                class="btn btn-primary btn-sm">{{trans('customer.pay')}}
                                                            </button>
                                                        @else
                                                            <button
                                                                data-message="{!! trans('customer.renew_plan',['plan'=>$customerPlan->plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.renew_plan_nb')}}</span>"
                                                                data-action="{{route('customer.billing.update')}}"
                                                                data-input='{"id":"{{$customerPlan->plan_id}}"}'
                                                                data-toggle="modal" data-target="#modal-confirm"
                                                                type="button"
                                                                class="btn btn-primary btn-sm">{{trans('customer.pay')}}
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row custom-row">
                                    <div class="col-lg-12">
                                        <div class="card billing-card">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing-data">No plan request available</div>
                                                </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

