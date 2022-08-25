<div class="row mb-5">
    <div class="col-5 col-sm-3">
        <div class="nav flex-column nav-tabs h-100 overflow-hidden" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">{{trans('Paypal')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">{{trans('Stripe')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-paytm" role="tab" aria-controls="vert-tabs-paytm" aria-selected="false">{{trans('Paytm')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-mollie" role="tab" aria-controls="vert-tabs-mollie" aria-selected="false">{{trans('Mollie')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-paystack" role="tab" aria-controls="vert-tabs-paystack" aria-selected="false">{{trans('PayStack')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-flutter_wave" role="tab" aria-controls="vert-tabs-flutter_wave" aria-selected="false">{{trans('Flutterwave')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-vogue_pay" role="tab" aria-controls="vert-tabs-vogue_pay" aria-selected="false">{{trans('VoguePay')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-iyzico" role="tab" aria-controls="vert-tabs-iyzico" aria-selected="false">{{trans('Iyzico')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-authorize_net" role="tab" aria-controls="vert-tabs-authorize_net" aria-selected="false">{{trans('Authorize Net')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-coinpay" role="tab" aria-controls="vert-tabs-authorize_net" aria-selected="false">{{trans('CoinPay')}}</a>
            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-offline" role="tab" aria-controls="vert-tabs-authorize_net" aria-selected="false">{{trans('Offline')}}</a>
        </div>
    </div>
    @php $payment_gateway= json_decode(get_settings('payment_gateway')); @endphp
    <div class="col-7 col-sm-9">
        <div class="tab-content" id="vert-tabs-tabContent">
            <div class="tab-pane text-left fade active show" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="paypal_client_id">@lang('Client ID')</label>
                                <input value="{{isset(json_decode(get_settings('payment_gateway'))->paypal_client_id)?json_decode(get_settings('payment_gateway'))->paypal_client_id:''}}" type="text" name="paypal_client_id" class="form-control" id="paypal_client_id"
                                       placeholder="@lang('Client ID')">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="paypal_client_secret">@lang('Client Secret')</label>
                                <input value="{{isset(json_decode(get_settings('payment_gateway'))->paypal_client_secret)?json_decode(get_settings('payment_gateway'))->paypal_client_secret:''}}" type="text" name="paypal_client_secret" class="form-control" id="paypal_client_secret"
                                       placeholder="@lang('Client Secret')">
                            </div>
                        </div>
                    </div>
            </div>

            <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="stripe_pub_key">@lang('Publishable key')</label>
                            <input value="{{isset(json_decode(get_settings('payment_gateway'))->stripe_pub_key)?json_decode(get_settings('payment_gateway'))->stripe_pub_key:''}}" type="text" name="stripe_pub_key" class="form-control" id="stripe_pub_key"
                                   placeholder="@lang('Publishable key')">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="stripe_secret_key">@lang('Secret Key')</label>
                            <input value="{{isset(json_decode(get_settings('payment_gateway'))->stripe_secret_key)?json_decode(get_settings('payment_gateway'))->stripe_secret_key:''}}" type="text" name="stripe_secret_key" class="form-control" id="stripe_secret_key"
                                   placeholder="@lang('Secret Key')">
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane text-left fade " id="vert-tabs-paytm" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="paytm_environment">{{trans('Environment')}}</label>
                            <select id="paytm_environment" name="paytm_environment"
                                    class="form-control">
                                <option
                                    {{isset($payment_gateway->paytm_environment) && $payment_gateway->paytm_environment=='staging'?'selected':''}} value="staging">{{trans('Staging')}}</option>
                                <option
                                    {{isset($payment_gateway->paytm_environment) && $payment_gateway->paytm_environment=='production'?'selected':''}}  value="production">{{trans('Production')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Merchant ID')}}</label>
                            <input
                                value="{{isset($payment_gateway->paytm_mid)?$payment_gateway->paytm_mid:''}}"
                                type="text" name="paytm_mid"
                                class="form-control"
                                placeholder="{{trans('Merchant ID')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Secret Key')}}</label>
                            <input
                                value="{{isset($payment_gateway->paytm_secret_key)?$payment_gateway->paytm_secret_key:''}}"
                                type="text" name="paytm_secret_key"
                                class="form-control"
                                placeholder="{{trans('Secret Key')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Website')}}</label>
                            <input
                                value="{{isset($payment_gateway->paytm_website)?$payment_gateway->paytm_website:''}}"
                                type="text" name="paytm_website"
                                class="form-control"
                                placeholder="{{trans('Website')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Transaction URL')}}</label>
                            <input
                                value="{{isset($payment_gateway->paytm_txn_url)?$payment_gateway->paytm_txn_url:''}}"
                                type="text" name="paytm_txn_url"
                                class="form-control"
                                placeholder="{{trans('Transaction URL')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                for="paytm_status">{{trans('Status')}}</label>
                            <select id="paytm_status" name="paytm_status"
                                    class="form-control">
                                <option {{isset($payment_gateway->paytm_status) && $payment_gateway->paytm_status=='inactive'?'selected':''}}  value="inactive">{{trans('Inactive')}}</option>
                                <option {{isset($payment_gateway->paytm_status) && $payment_gateway->paytm_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane text-left fade " id="vert-tabs-mollie" role="tabpanel"
                 aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Api key')}}</label>
                            <input
                                value="{{isset($payment_gateway->mollie_api_key)?$payment_gateway->mollie_api_key:''}}"
                                type="text" name="mollie_api_key"
                                class="form-control"
                                placeholder="{{trans('Api key')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                for="mollie_status">{{trans('Status')}}</label>
                            <select id="mollie_status" name="mollie_status"
                                    class="form-control">
                                <option {{isset($payment_gateway->mollie_status) && $payment_gateway->mollie_status=='inactive'?'selected':''}}  value="inactive">{{trans('Inactive')}}</option>
                                <option {{isset($payment_gateway->mollie_status) && $payment_gateway->mollie_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane text-left fade " id="vert-tabs-paystack" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Public Key')}}</label>
                            <input
                                value="{{isset($payment_gateway->paystack_public_key)?$payment_gateway->paystack_public_key:''}}"
                                type="text" name="paystack_public_key"
                                class="form-control"
                                placeholder="{{trans('Public Key')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Secret key')}}</label>
                            <input
                                value="{{isset($payment_gateway->paystack_secret_key)?$payment_gateway->paystack_secret_key:''}}"
                                type="text" name="paystack_secret_key"
                                class="form-control"
                                placeholder="{{trans('Secret key')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Payment URL')}}</label>
                            <input
                                value="{{isset($payment_gateway->paystack_payment_url)?$payment_gateway->paystack_payment_url:''}}"
                                type="text" name="paystack_payment_url"
                                class="form-control"
                                placeholder="{{trans('Payment URL')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Merchant Email').'('.trans('Optional').')'}}</label>
                            <input
                                value="{{isset($payment_gateway->paystack_merchant_email)?$payment_gateway->paystack_merchant_email:''}}"
                                type="text" name="paystack_merchant_email"
                                class="form-control"
                                placeholder="{{trans('Merchant Email')}}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                for="paystack_status">{{trans('Status')}}</label>
                            <select id="paystack_status" name="paystack_status"
                                    class="form-control">
                                <option {{isset($payment_gateway->paystack_status) && $payment_gateway->paystack_status=='inactive'?'selected':''}}  value="inactive">{{trans('Inactive')}}</option>
                                <option {{isset($payment_gateway->paystack_status) && $payment_gateway->paystack_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="tab-pane text-left fade " id="vert-tabs-flutter_wave" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Public Key')}}</label>
                            <input
                                value="{{isset($payment_gateway->flutter_wave_public_key)?$payment_gateway->flutter_wave_public_key:''}}"
                                type="text" name="flutter_wave_public_key"
                                class="form-control"
                                placeholder="{{trans('Public Key')}}">
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane text-left fade " id="vert-tabs-vogue_pay" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('Merchant ID')}}</label>
                            <input
                                value="{{isset($payment_gateway->v_merchant_id)?$payment_gateway->v_merchant_id:''}}"
                                type="text" name="v_merchant_id"
                                class="form-control"
                                placeholder="{{trans('Merchant ID')}}">
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane text-left fade " id="vert-tabs-iyzico" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('admin.settings.api_key')}}</label>
                            <input
                                value="{{isset($payment_gateway->iyzico_api_key)?$payment_gateway->iyzico_api_key:''}}"
                                type="text" name="iyzico_api_key"
                                class="form-control"
                                placeholder="{{trans('admin.settings.api_key')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('admin.settings.secret_key')}}</label>
                            <input
                                value="{{isset($payment_gateway->iyzico_secret_key)?$payment_gateway->iyzico_secret_key:''}}"
                                type="text" name="iyzico_secret_key"
                                class="form-control"
                                placeholder="{{trans('admin.settings.secret_key')}}">
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane text-left fade " id="vert-tabs-authorize_net" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('admin.settings.login_id')}}</label>
                            <input
                                value="{{isset($payment_gateway->authorize_net_login_id)?$payment_gateway->authorize_net_login_id:''}}"
                                type="text" name="authorize_net_login_id"
                                class="form-control"
                                placeholder="{{trans('admin.settings.login_id')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('admin.settings.authorize_net_secret_key')}}</label>
                            <input
                                value="{{isset($payment_gateway->authorize_net_secret_key)?$payment_gateway->authorize_net_secret_key:''}}"
                                type="text" name="authorize_net_secret_key" class="form-control"
                                placeholder="{{trans('admin.settings.authorize_net_secret_key')}}">
                        </div>

                        <div class="form-group">
                            <label>{{trans('admin.settings.authorize_net_transaction_key')}}</label>
                            <input
                                value="{{isset($payment_gateway->authorize_net_transaction_key)?$payment_gateway->authorize_net_transaction_key:''}}"
                                type="text" name="authorize_net_transaction_key"
                                class="form-control"
                                placeholder="{{trans('admin.settings.authorize_net_transaction_key')}}">
                        </div>
                    </div>

                </div>
            </div>


            <div class="tab-pane text-left fade " id="vert-tabs-coinpay" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('admin.settings.private_key')}}</label>
                            <input
                                value="{{isset($payment_gateway->private_key)?$payment_gateway->private_key:''}}"
                                type="text" name="private_key"
                                class="form-control"
                                placeholder="{{trans('admin.settings.private_key')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('admin.settings.public_key')}}</label>
                            <input
                                value="{{isset($payment_gateway->public_key)?$payment_gateway->public_key:''}}"
                                type="text" name="public_key" class="form-control"
                                placeholder="{{trans('admin.settings.public_key')}}">
                        </div>

                        <div class="form-group">
                            <label>{{trans('IPN Secret')}}</label>
                            <input
                                value="{{isset($payment_gateway->ipn_secret)?$payment_gateway->ipn_secret:''}}"
                                type="text" name="ipn_secret" class="form-control"
                                placeholder="{{trans('IPN Secret')}}">
                        </div>

                        <div class="form-group">
                            <label>{{trans('Merchant ID')}}</label>
                            <input
                                value="{{isset($payment_gateway->merchant_id)?$payment_gateway->merchant_id:''}}"
                                type="text" name="merchant_id" class="form-control"
                                placeholder="{{trans('Merchant ID')}}">
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane text-left fade " id="vert-tabs-offline" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{trans('admin.settings.account_number')}}</label>
                            <input
                                value="{{isset($payment_gateway->offline_account_number)?$payment_gateway->offline_account_number:''}}"
                                type="text" name="offline_account_number"
                                class="form-control"
                                placeholder="{{trans('admin.settings.account_number')}}">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
