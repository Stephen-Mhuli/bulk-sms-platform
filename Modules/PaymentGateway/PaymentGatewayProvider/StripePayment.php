<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use App\Models\BillingRequest;
use Illuminate\Support\Facades\Log;

class StripePayment implements PaymentInterface
{
    public $planReq;
    public $redirect_url;
    public $error_message;
    public $return_view;
    public $will_redirect = false;

    public function __construct()
    {

    }

    public function pay()
    {
        // TODO: Implement pay() method.
    }

    public function plan_request($planReq)
    {
        $this->planReq = $planReq;
        return $this;
    }

    public function getCredentials()
    {
        $credentials = json_decode(get_settings('payment_gateway'));
        if (!$credentials->stripe_pub_key || !$credentials->stripe_secret_key) {
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
        try {
            $plan = $this->plan;
            $request = $this->request;
            $payment = $this->stripePayment($plan, $request);
            if (isset($payment->id)) {
                $planReq = $this->plan_request();
                $planReq->status = 'accepted';
                $planReq->save();
                $user = auth('customer')->user();
                $customer = $user;
                $pre_plan = $customer->plan;
                if ($pre_plan) {
                    $customer->plan()->delete();
                }
                $customer->plan()->create(['plan_id' => $planReq->plan_id, 'sms_limit' => $plan->sms_limit, 'available_sms' => $plan->sms_limit, 'price' => $plan->price]);

                BillingRequest::where(['customer_id' => $user->id, 'status' => 'pending'])->update(['status' => 'rejected']);
            }
            $this->redirect_url = null;
            $this->return_view = null;

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            $this->error_message= $ex->getMessage();
        }
    }

    /*Custom Function*/
    function stripePayment($plan, $req)
    {
        $credentials = json_decode(get_settings('payment_gateway'));
        if (!$credentials->stripe_pub_key || !$credentials->stripe_secret_key) {
            throw new \Exception(trans('Invalid payment'));
        }

        $stripe = new \Stripe\StripeClient($credentials->stripe_secret_key);
        $settings = json_decode(get_settings('local_setting'));
        return $stripe->charges->create([
            'amount' => $plan->price * 100,
            'currency' => isset($settings->currency_code)?$settings->currency_code:'USD',
            'source' => $req->stripeToken,
            'description' => 'User:' . auth('customer')->user()->email . ' changed plan to ' . $plan->title,
        ]);
    }
}
