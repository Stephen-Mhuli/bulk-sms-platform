<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use Illuminate\Support\Facades\Log;

class StripeCheckoutPayment implements PaymentInterface
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
//        dd('lol');
        try {
            $credentials= $this->getCredentials();
            $settings = json_decode(get_settings('local_setting'));
            \Stripe\Stripe::setApiKey($credentials->stripe_secret_key);
            $success = route('customer.billing.index');

            $stripe = new \Stripe\StripeClient(
                $credentials->stripe_secret_key
            );
           $product= $stripe->products->create([
                'name' => 'PLAN_'.$this->planReq->id,
            ]);

           $price= $stripe->prices->create([
                'unit_amount' => $this->plan->price *100,
                'currency' => isset($settings->currency_code)?$settings->currency_code:'USD',
                'product' => $product->id,
            ]);

            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price' => $price->id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $success,
                'cancel_url' => route('paymentgateway::payment.process.cancel'),
            ]);
            $this->redirect_url = $checkout_session->url;
            $this->will_redirect = true;
            $this->return_view = null;

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            $this->error_message= $ex->getMessage();
        }
    }

    /*Custom Function*/

}
