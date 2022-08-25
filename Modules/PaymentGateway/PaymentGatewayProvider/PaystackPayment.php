<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use App\Models\BillingRequest;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Unicodeveloper\Paystack\Paystack;

class PaystackPayment implements PaymentInterface
{
    public $planReq;
    public $redirect_url;
    public $error_message;
    public $return_view;
    public $will_redirect= false;

    public function __construct()
    {

    }

    public function pay()
    {
        // TODO: Implement pay() method.
    }

    public function getCredentials()
    {
        $credentials = json_decode(get_settings('payment_gateway'));

        if (!isset($credentials->paystack_status) || !$credentials->paystack_merchant_email || !$credentials->paystack_payment_url || !$credentials->paystack_public_key || !$credentials->paystack_secret_key || $credentials->paystack_status != 'active') {
            throw new \Exception(trans('Invalid Payment'));
        }
        return $credentials;
    }

    public function request($request)
    {
        $this->request = $request;
        return $this;
    }

    public function plan($plan)
    {
        $this->plan = $plan;
        return $this;
    }

    public function plan_request($planReq)
    {
        $this->planReq = $planReq;
        return $this;
    }
    public function will_redirect()
    {
        // TODO: Implement will_redirect() method.
        return $this->will_redirect;
    }

    public function redirect_url()
    {
        // TODO: Implement redirect_url() method.
        return $this->redirect_url;
    }
    public function return_view()
    {
        // TODO: Implement redirect_url() method.
        return $this->return_view;
    }
    public function error_message()
    {
        // TODO: Implement error_message() method.
        return $this->error_message;
    }
    public function process()
    {
        $paystackData = $this->payStackPayment($this->planReq, $this->request);
        if ($paystackData) {
            $this->redirect_url = $paystackData->redirectNow();
            $this->will_redirect = true;
            $this->return_view = null;
        }
    }


    function payStackPayment($planReq, $request)
    {
        $credentials = json_decode(get_settings('payment_gateway'));
        $plan = Plan::find($planReq->plan_id);
        if (!isset($credentials->paystack_public_key) || !$credentials->paystack_secret_key || $credentials->paystack_status != 'active') {
            throw new \Exception(trans('layout.message.invalid_payment'));
        }
        $settings = json_decode(get_settings('local_setting'));
        $data = [
            'secretKey' => $credentials->paystack_secret_key,
            'publicKey' => $credentials->paystack_public_key,
            'paymentUrl' => $credentials->paystack_payment_url
        ];

        if ($credentials->paystack_merchant_email) {
            $data['merchantEmail'] = $credentials->paystack_merchant_email;
        }
        Config::set('paystack', $data);

        $paystack = new Paystack();
        $user = auth()->user();
        $request->email = $user->email;
        $request->orderID = "PLN_" . $plan->id;
        $request->amount = $plan->price * 100;
        $request->quantity = 1;
        $request->currency = isset($settings->currency_code)?$settings->currency_code:'USD';
        $request->reference = $paystack->genTranxRef();
        $request->callback_url = route('paymentgateway::payment.paystack.process');
        $request->metadata = json_encode(['user_plan' => $planReq->id]);
        return $paystack->getAuthorizationUrl();

    }

    public function processPaystackPayment(Request $request)
    {
        $credentials = json_decode(get_settings('payment_gateway'));

        if (!isset($credentials->paystack_public_key) || !$credentials->paystack_secret_key || $credentials->paystack_status != 'active') {
            throw new \Exception(trans('Invalid Request'));
        }

        $data = [
            'secretKey' => $credentials->paystack_secret_key,
            'publicKey' => $credentials->paystack_public_key,
            'paymentUrl' => $credentials->paystack_payment_url
        ];

        if ($credentials->paystack_merchant_email) {
            $data['merchantEmail'] = $credentials->paystack_merchant_email;
        }
        Config::set('paystack', $data);

        $paymentDetails = paystack()->getPaymentData();

        if (isset($paymentDetails['data']) && isset($paymentDetails['data']['id'])) {
            $user_plan = isset($paymentDetails['data']['metadata']['user_plan']) ? $paymentDetails['data']['metadata']['user_plan'] : '';
            if ($user_plan) {
                $userPlan = BillingRequest::find($user_plan);
                if (!$userPlan) {
                    Log::info("user plan not found -" . $user_plan);
                    exit;
                };
            }
            Log::info("Meta data not found");
            $this->error_message= 'Meta data not found';
            exit;
        } else {
            $this->error_message= trans('Invalid payment');
        }
    }


}
