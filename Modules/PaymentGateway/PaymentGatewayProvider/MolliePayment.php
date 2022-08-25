<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use App\Models\BillingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MolliePayment implements PaymentInterface
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

        if (!isset($credentials->mollie_status) || !$credentials->mollie_api_key || $credentials->mollie_status != 'active') {
            throw new \Exception('Credentials not found. Please contact with the administrator');
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
        $mollieData = $this->molliePayment($this->planReq);
        if ($mollieData && $mollieData->id) {
            $this->redirect_url = $mollieData->getCheckoutUrl();
            $this->will_redirect = true;
            $this->return_view = null;
        }
    }


    function molliePayment($planReq)
    {

        $credentials = $this->getCredentials();

        if (!isset($credentials->mollie_status) || !$credentials->mollie_api_key || $credentials->mollie_status != 'active') {
            throw new \Exception(trans('Invalid Payment'));
        }
        $settings = json_decode(get_settings('local_setting'));
        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($credentials->mollie_api_key);
        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => isset($settings->currency_code)?$settings->currency_code:'USD',
                "value" => $planReq->price . ""
            ],
            "description" => "For plan upgrade #" . $planReq->id,
            "redirectUrl" => route('paymentgateway::payment.mollie.success'),
            "webhookUrl" => route('paymentgateway::payment.changeplan.mollie.webhook', ['id' => $planReq->id]),
        ]);

        return $payment;
    }

    public function processMollieSuccess()
    {
        return redirect()->route('customer.billing.index')->with('success', trans('Congratulations! Your plan successfully changed'));
    }

    public function processMollieWebhook($userPlanId, Request $request)
    {
        if (!$userPlanId) {
            Log::info("user plan not found");
            exit;
        };

        $userPlan = BillingRequest::find($userPlanId);

        if (!$userPlan) {
            Log::info("user plan not found -" . $userPlanId);
            exit;
        };

        $credentials = json_decode(get_settings('payment_gateway'));

        if (!isset($credentials->mollie_status) || !$credentials->mollie_api_key || $credentials->mollie_status != 'active') {
            Log::info(trans('Invalid Payment'));
            exit();
        }

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($credentials->mollie_api_key);
        $payment = $mollie->payments->get($request->id);

    }

}

