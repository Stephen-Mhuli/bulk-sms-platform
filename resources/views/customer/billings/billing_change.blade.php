@extends('layouts.customer')

@section('title') Billings Change @endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title m-0">{{trans('customer.billing_change')}}</h5>
                            <a class="btn btn-info float-right"
                               href="{{route('customer.billing.index')}}">@lang('customer.back')</a>
                        </div>
                        <div class="card-body">
                            <div id="plans" class="plans-wrapper mt-3">
                                @foreach($plans as $plan)
                                    <div class="columns {{isset($customer_plan->plan_id) && $customer_plan->plan_id==$plan->id?'plan-active':''}}">
                                        <ul class="price">
                                            <li class="bg-sms_queued">{{$plan->title}} <span
                                                    class="plan-title-current">{{isset($customer_plan->plan_id) && $customer_plan->plan_id==$plan->id?'(Current)':''}}</span>
                                            </li>
                                            <li>{{$plan->contact_limit}} {{trans('customer.contact_limit')}}</li>
                                            <li>{{$plan->device_limit}} {{trans('customer.device_limit')}}</li>
                                            <li>{{$plan->daily_receive_limit}} {{trans('customer.daily_receive_limit')}}</li>
                                            <li>{{$plan->daily_send_limit}} {{trans('customer.daily_send_limit')}}</li>
                                            <li class="price-tag">{{formatNumberWithCurrSymbol($plan->price)}}</li>
                                            <li>
                                                @if(isset($customer_plan->plan_id) && $customer_plan->plan_id!=$plan->id)

                                                    @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                                        <button
                                                            data-message="<span class='text-sm text-muted'>{{trans('customer.update_plan_nb')}}</span>"
                                                            data-action="{{route('paymentgateway::process')}}"
                                                            data-input='{"id":"{{$plan->id}}"}'
                                                            data-toggle="modal" data-target="#modal-confirm"
                                                            type="button"
                                                            data-id="{{$plan->id}}"
                                                            class="btn btn-primary btn-sm choose_btn_{{$plan->id}} d-none">{{trans('customer.choose')}}
                                                        </button>
                                                        <button data-id="{{$plan->id}}" class="btn btn-primary btn-sm choose-btn">{{trans('customer.choose')}}</button>
                                                    @else
                                                        <button
                                                            data-message="<span class='text-sm text-muted'>{{trans('customer.update_plan_nb')}}</span>"
                                                            data-action="{{route('customer.billing.update')}}"
                                                            data-input='{"id":"{{$plan->id}}"}'
                                                            data-toggle="modal" data-target="#modal-confirm"
                                                            type="button"
                                                            data-id="{{$plan->id}}"
                                                            class="btn btn-primary btn-sm choose_btn_{{$plan->id}} d-none">{{trans('customer.choose')}}
                                                        </button>
                                                        <button data-id="{{$plan->id}}" class="btn btn-primary btn-sm choose-btn">{{trans('customer.choose')}}</button>
                                                    @endif
                                                @else
                                                    <button
                                                        type="button"
                                                        class="btn btn-primary btn-sm disabled" disabled>{{trans('customer.choose')}}
                                                    </button>
                                                @endif
                                            </li>

                                        </ul>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pendingPlanModal">
        <div class="modal-dialog" role="document">
            <form action="{{route('customer.billing.pending.plan.submit.form')}}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You already have a pending plan. Are you sure you want to update your plan?</p>
                        <input type="hidden" name="pending_plan_id" id="pending_plan_id" value="" >
                        <input type="hidden" name="id" id="request_plan" value="" >
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('extra-scripts')
        <script>
            $(document).on('click', '.choose-btn', function (e) {
                e.preventDefault()
                const plan_id = $(this).attr('data-id');
                $.ajax({
                    url: '{{route('customer.billing.pending.plan')}}',
                    method: "GET",
                    data: {
                        plan_id: plan_id,
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $('#pendingPlanModal').modal('show');
                            $('#pending_plan_id').val(res.data.pendingPlan.id);
                            $('#request_plan').val(res.data.requestPlanId);
                        }else {
                            $('.choose_btn_'+ plan_id).trigger('click')
                        }
                    }
                });
            });
        </script>
@endsection
