@extends('layouts.customer')

@section('title') Checkout @endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>
    @php $credentials = json_decode(get_settings('payment_gateway')); @endphp
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <form action="{{route('paymentgateway::process.paynow')}}" method="post" id="payment-form">
                            @csrf
                            <div class="card-header">
                                <h5 class="m-0">{{trans('customer.billing')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <input type="hidden" name="plan" value="{{request()->post('id')}}">
                                    <div class="col-sm-6">
                                        <div id="accordion"
                                             class="accordion accordion_primary {{$plan->price<=0?'d-none':''}}">
                                            @if (isset($credentials) && ($credentials->paypal_client_id && $credentials->paypal_client_secret))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapsePaypal" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="paypal" name="payment_type" type="radio"
                                                               id="paypalRadio">
                                                    </div>
                                                    <span>{{trans('Paypal')}}</span>
                                                </div>
                                                <div id="collapsePaypal" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <span>{{trans('paymentgateway::layout.paypal_text')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (isset($credentials) && ($credentials->stripe_pub_key && $credentials->stripe_secret_key))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapseStripe" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="card" name="payment_type" type="radio"
                                                               id="cardRadio">
                                                    </div>
                                                    <span>{{trans('Credit/Debit Card')}}</span>
                                                </div>
                                                <div id="collapseStripe" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <div id="card-element"
                                                             class="border-1-gray p-3 border-radius-1"></div>
                                                        <div id="card-errors" role="alert"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (isset($credentials) && ($credentials->paytm_status=='active' && $credentials->paytm_environment && $credentials->paytm_mid && $credentials->paytm_secret_key && $credentials->paytm_website && $credentials->paytm_txn_url))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapsePaytm" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="paytm" name="payment_type" type="radio"
                                                               id="paytmRadio">
                                                    </div>
                                                    <span>{{trans('Paytm')}}</span>
                                                </div>
                                                <div id="collapsePaytm" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <span>{{trans('Paytm')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (isset($credentials) && ($credentials->mollie_status=='active' && $credentials->mollie_api_key))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapseMollie" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="mollie" name="payment_type" type="radio"
                                                               id="mollieRadio">
                                                    </div>
                                                    <span>{{trans('Mollie')}}</span>
                                                </div>
                                                <div id="collapseMollie" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <span>{{trans('Mollie')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (isset($credentials) && ($credentials->paystack_status=='active' && $credentials->paystack_merchant_email && $credentials->paystack_payment_url && $credentials->paystack_secret_key && $credentials->paystack_public_key))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapsePaystack" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="paystack" name="payment_type" type="radio"
                                                               id="paystackRadio">
                                                    </div>
                                                    <span>{{trans('Paystack')}}</span>
                                                </div>
                                                <div id="collapsePaystack" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <span>{{trans('Paystack')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (isset($credentials) && ($credentials->flutter_wave_public_key))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapseflutterwave" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="flutterwave" name="payment_type" type="radio"
                                                               id="flutterwaveRadio">
                                                    </div>
                                                    <span>{{trans('Flutterwave')}}</span>
                                                </div>
                                                <div id="collapseflutterwave" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <span>{{trans('Flutterwave')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (isset($credentials) && ($credentials->v_merchant_id))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapsevogue_pay" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="vogue_pay" name="payment_type" type="radio"
                                                               id="vogue_pay">
                                                    </div>
                                                    <span>{{trans('VoguePay')}}</span>
                                                </div>
                                                <div id="collapsevogue_pay" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <span>{{trans('VoguePay')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (isset($credentials) && ($credentials->stripe_pub_key && $credentials->stripe_secret_key))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapsestripe_checkout" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="stripe_checkout" name="payment_type" type="radio"
                                                               id="stripe_checkout">
                                                    </div>
                                                    <span>{{trans('Stripe Checkout')}}</span>
                                                </div>
                                                <div id="collapsestripe_checkout" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <span>{{trans('Stripe Checkout')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (isset($credentials) && ($credentials->iyzico_api_key || $credentials->iyzico_secret_key))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapseiyzico" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="iyzico" name="payment_type" type="radio"
                                                               id="iyzico">
                                                    </div>
                                                    <span>{{trans('Iyzico')}}</span>
                                                </div>
                                                <div id="collapseiyzico" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                        <div class="container">
                                                         <div class="row">
                                                             <div class="col-sm-12">
                                                                 <label for="">Card Holder Name</label>
                                                                 <input type="text" placeholder="Jhon Doe" name="iyzico_card_holder_name" class="form-control">
                                                             </div>
                                                             <div class="col-sm-12 mt-2">
                                                                 <label for="">Card Number</label>
                                                                 <input type="text" placeholder="4242 4242 xxxx xxxx" name="iyzico_card_number" class="form-control">
                                                             </div>
                                                             <div class="col-sm-4 mt-2">
                                                                 <label for="">Month</label>
                                                                 <input type="text" placeholder="06" name="iyzico_card_expired_date" class="form-control">
                                                             </div>
                                                             <div class="col-sm-4 mt-2">
                                                                 <label for="">Year</label>
                                                                 <input type="text" placeholder="2025" name="iyzico_card_expired_year" class="form-control">
                                                             </div>
                                                             <div class="col-sm-4 mt-2">
                                                                 <label for="">CVC</label>
                                                                 <input type="text" placeholder="123" name="iyzico_card_cvc" class="form-control">
                                                             </div>
                                                         </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset(json_decode(get_settings('payment_gateway'))->authorize_net_login_id) && isset(json_decode(get_settings('payment_gateway'))->authorize_net_secret_key) && isset(json_decode(get_settings('payment_gateway'))->authorize_net_transaction_key))
                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapseauthorizenet" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="authorize_net" name="payment_type" type="radio"
                                                               id="authorize_net">
                                                    </div>
                                                    <span>{{trans('Authorize Net')}}</span>
                                                </div>
                                                <div id="collapseauthorizenet" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text ">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <label>
                                                                        <span>Card Number</span>
                                                                    </label>
                                                                    <input id="authorize_net_card_number" placeholder="43xx 67xx 93xx xxxx" name="authorize_net_card_number" type="text" class="form-control"
                                                                           size="20" value="" autocomplete="off">
                                                                </div>
                                                                <div class="col-sm-6 mt-2">
                                                                    <label>
                                                                        Expiration Month
                                                                    </label>
                                                                    <input class="form-control" placeholder="12" name="authorize_net_exp_month" type="text" size="2"
                                                                           id="expMonth">
                                                                </div>
                                                                <div class="col-sm-6 mt-2">
                                                                    <label for="">Expiration Year</label>
                                                                    <input type="text" name="authorize_net_exp_year" placeholder="2021" class="form-control" size="2"
                                                                           id="expYear">
                                                                </div>
                                                                <div class="col-sm-12 mt-2">
                                                                    <label> CVC </label>
                                                                    <input id="cvv" name="authorize_net_cvc" placeholder="123" class="form-control" size="4"
                                                                           type="text" value="" autocomplete="off">
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                                @if (isset($credentials) && isset($credentials->public_key) && isset($credentials->private_key))
                                                    <div class="accordion_item">
                                                        <div class="accordion_header collapsed" data-toggle="collapse"
                                                             data-target="#collapsecoin_pay" aria-expanded="false"
                                                             aria-controls="collapseOne">
                                                            <div class="d-none">
                                                                <input value="coinpay" name="payment_type" type="radio"
                                                                       id="coin_pay">
                                                            </div>
                                                            <span>{{trans('CoinPay')}}</span>
                                                        </div>
                                                        <div id="collapsecoin_pay" class="collapse accordion_body"
                                                             aria-labelledby="headingOne"
                                                             data-parent="#accordion">
                                                            <div class="accordion_body_text ">
                                                                <h4>Select Type</h4>
                                                                <div class="form-group">
                                                                    <input type="radio"  name="coin_payment_type" value="btc" id="btc">
                                                                    <label for="btc" class="ml-2 c-pointer">BTC</label>

                                                                    <input type="radio"  name="coin_payment_type" class="ml-3" value="ltct" id="ltct">
                                                                    <label for="ltct" class="ml-2 c-pointer">LTCT(Litecoin)</label>
                                                                </div>

                                                                <div class="form-group" id="showCoinPayInfo">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif


                                            <div class="accordion_item">
                                                <div class="accordion_header collapsed" data-toggle="collapse"
                                                     data-target="#collapseOffline" aria-expanded="false"
                                                     aria-controls="collapseOne">
                                                    <div class="d-none">
                                                        <input value="offline" name="payment_type" type="radio"
                                                               id="cardOffline">
                                                    </div>
                                                    <span>{{trans('Offline (Bank)')}}</span>
                                                </div>
                                                <div id="collapseOffline" class="collapse accordion_body"
                                                     aria-labelledby="headingOne"
                                                     data-parent="#accordion">
                                                    <div class="accordion_body_text">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <div>Account Number : {{isset(json_decode(get_settings('payment_gateway'))->offline_account_number)?json_decode(get_settings('payment_gateway'))->offline_account_number:''}}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="account_number">{{trans('Transaction ID')}}
                                                                        *</label>
                                                                    <input type="text" name="transaction_id"
                                                                           class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-sm-6 plan-summary">
                                        <div class="card-header">
                                            <h4 class="card-title">{{trans('Plan summary')}}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <ul>
                                                        <li><strong>{{trans('Plan Title')}}</strong></li>
                                                        <li><strong>{{trans('Start Date')}}</strong></li>
                                                        <li><strong>{{trans('Expiry Date')}}</strong></li>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-6">
                                                    <ul>
                                                        <li>{{$plan->title}}</li>
                                                        <li>{{now()->format('M-d-Y')}}</li>
                                                        <li>{{now()->addMonth()->format('M-d-Y') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <ul>
                                                        <li><strong>{{trans('Total Cost')}}</strong></li>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-6">
                                                    <ul>
                                                        <li>{{formatNumberWithCurrSymbol($plan->price)}}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right" id="payNowBtnSection">
                                @if($plan->price<=0)
                                    <button type="button" id="paynow"
                                            class="btn btn-primary">{{trans('Confirm')}}</button>
                                @else
                                    <button disabled type="button" id="paynow"
                                            class="btn btn-primary">{{trans('Pay Now')}}</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('extra-scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script src="https://pay.voguepay.com/js/voguepay.js"></script>
    <script>
        $(document).on('click', '#paynow', function (e) {
            if ($('[name=payment_type]:checked').val() == 'coinpay') {
                e.preventDefault();
                $('#paynow').addClass('disabled');
                const plan_id = '{{$plan->id}}';
                const payment_type = $('[name=payment_type]:checked').val();
                const coin_payment_type = $('[name=coin_payment_type]:checked').val();

                $.ajax({
                    method: 'POST',
                    url: '{{route('paymentgateway::process.coin.payment')}}',
                    data: {
                        "_token": '{{csrf_token()}}',
                        plan_id: plan_id,
                        payment_type: payment_type,
                        coin_payment_type: coin_payment_type,
                    },

                    success: function (res) {
                        if (res.status == 'success') {
                            $('#showCoinPayInfo').html(` <h5>Amount :  <span>${res.data.amount} ${res.data.currency}</span></h5>`)
                            $('#payNowBtnSection').html(`<a class="btn btn-primary" type="button" href="${res.data.status_url}">Process</a>`)
                        }
                        $('#paynow').removeClass('disabled');
                    },
                    error:function (res){
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-danger',
                            title: 'Notification',
                            body: res.message,
                        });
                        $('#paynow').removeClass('disabled');

                    }
                })
            }
        });
    </script>

    <script !src="">
        "use strict";
        // Create a Stripe client.
        var stripe = Stripe('{{isset(json_decode(get_settings('payment_gateway'))->stripe_pub_key)?json_decode(get_settings('payment_gateway'))->stripe_pub_key:''}}');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.on('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            var cardRadio = document.getElementById('cardRadio');
            if (cardRadio.checked) {
                stripe.createToken(card).then(function (result) {
                    if (result.error) {
                        // Inform the user if there was an error.
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            } else {
                form.submit();
            }
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>

    <script !src="">
        function checkRadioButton() {
            let isOneChecked = false;
            let testimonialElements = $('[name="payment_type"]');
            for (let i = 0; i < testimonialElements.length; i++) {
                let element = testimonialElements.eq(i);
                if (element.is(':checked')) {
                    isOneChecked = true;
                    break;
                }
            }
            if (isOneChecked)
                $('#paynow').removeAttr('disabled');
            else
                $('#paynow').attr('disabled', 'true');

            @if ($plan->price <= 0)
            $('#paynow').val("{{trans('Confirm')}}");
            @endif

            if ($('[name=payment_type]:checked').val() == 'flutterwave' || $('[name=payment_type]:checked').val() == 'vogue_pay' || $('[name=payment_type]:checked').val() == 'coinpay') {
                $('#paynow').attr('type', 'button');
            } else {
                $('#paynow').attr('type', 'submit');
            }


            if ($('[name=payment_type]:checked').val() == 'offline') {
                $('#collapseOffline input').each(function (index, value) {
                    $(value).attr('required', 'true');
                })
            } else {
                $('#collapseOffline input').each(function (index, value) {
                    $(value).removeAttr('required');
                })
            }

            $('[name=reference]').removeAttr('required');

        }

        $('#collapsePaypal,#collapseStripe,#collapseOffline,#collapsePaytm,#collapseMollie,#collapsePaystack,#collapseflutterwave,#collapsevogue_pay,#collapsestripe_checkout,#collapseiyzico,#collapseauthorizenet,#collapsecoin_pay').on('show.bs.collapse', function (e) {
            $('[name="payment_type"]').removeAttr('checked');
            let type = $(this).parent().find('[name="payment_type"]');
            type.attr('checked', 'true');
            type.trigger('change');
            checkRadioButton();
        });

        $('#collapsePaypal,#collapseStripe,#collapseOffline,#collapsePaytm,#collapseMollie,#collapsePaystack,#collapseflutterwave,#collapsevogue_pay,#collapsestripe_checkout,#collapseiyzico,#collapseauthorizenet,#collapsecoin_pay').on('hide.bs.collapse', function (e) {
            let type = $(this).parent().find('[name="payment_type"]');
            type.removeAttr('checked');
            type.trigger('change');
            checkRadioButton();
        });

    </script>

    <script>
        function closedFunction() {
            alert('Window Closed By Authorities');
        }

        function successFunction(transaction_id) {
            console.log(transaction_id);
            $.ajax({
                type: "POST",
                url: "{{route('paymentgateway::check.payment.validity')}}",
                data: {
                    "_token": '{{csrf_token()}}',
                    plan_id: '{{$plan->id}}',
                    price: 20,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        console.log('lol')
                        $('#payment-form').submit();
                    }
                }

            });
            alert('Transaction was successfully carried out, Ref: '+transaction_id);
        }

        function failedFunction(transaction_id) {
            alert('Transaction was not successful, Ref: '+transaction_id)
        }

        $(document).on('click', '#paynow', function (e){
            if ($('[name=payment_type]:checked').val() == 'flutterwave') {
                @if(isset(json_decode(get_settings('payment_gateway'))->flutter_wave_public_key) && json_decode(get_settings('payment_gateway'))->flutter_wave_public_key)
                FlutterwaveCheckout({
                    public_key: "{{json_decode(get_settings('payment_gateway'))->flutter_wave_public_key}}",
                    tx_ref: "PICO-",
                    amount: '{{$plan->price}}',
                    currency: "USD",
                    country: "US",
                    payment_options: " ",
                    // redirect_url: // specified redirect URL
                    //     "https://google.com",
                    meta: {
                        consumer_id: {{auth('customer')->user()->id}},
                        consumer_mac: "92a3-912ba-1192a",
                    },
                    customer: {
                        email: "{{auth('customer')->user()->email}}",
                        phone_number: "08102909304",
                        name: "{{auth('customer')->user()->first_name}}",
                    },
                    callback: function (data) {
                        console.log(data.status, data.amount);
                        if (data.status=='successful'){
                            $.ajax({
                                method: "get",
                                url: "{{route('paymentgateway::check.payment.validity')}}",
                                data: {
                                    "plan_id": '{{$plan->id}}',
                                    "price": data.amount,
                                },
                                success: function (res) {
                                    if (res.status == 'success') {
                                        $('#payment-form').submit();
                                    }
                                }

                            })
                        }
                    },
                    onclose: function() {
                        // close modal
                    },
                    customizations: {
                        title: "{{$plan->title}}",
                        description: " ",
                        logo: "https://assets.piedpiper.com/logo.png",
                    },
                });
                @endif
            }else if($('[name=payment_type]:checked').val() == 'vogue_pay'){
                @if(isset(json_decode(get_settings('payment_gateway'))->v_merchant_id) && json_decode(get_settings('payment_gateway'))->v_merchant_id)

                    //Initiate voguepay inline payment
                    Voguepay.init({
                        v_merchant_id: '{{json_decode(get_settings('payment_gateway'))->v_merchant_id}}',
                        total: '{{$plan->price}}',
                        notify_url:'google.com',
                        cur: 'NGN',
                        merchant_ref: '{{'plan-'.''.$plan->id}}',
                        memo:'Payment for '+'{{$plan->title}}',
                        developer_code: '5a61be72ab323',
                        items: [
                            {
                                name: "{{$plan->title}}",
                                description: "Description 1",
                                price: '{{$plan->price}}'
                            },
                        ],
                        customer: {
                            name: '{{auth('customer')->user()->first_name}}',
                            address: 'Customer address',
                            city: 'Customer city',
                            state: 'Customer state',
                            zipcode: 'Customer zip/post code',
                            email: '{{auth('customer')->user()->email}}',
                            phone: 'Customer phone'
                        },
                        closed:closedFunction,
                        success:successFunction,
                        failed:failedFunction
                    });
                @endif

                //Demo Link: https://codepen.io/sakarious/pen/OJmYVxM
            }
        });
    </script>

@endsection

