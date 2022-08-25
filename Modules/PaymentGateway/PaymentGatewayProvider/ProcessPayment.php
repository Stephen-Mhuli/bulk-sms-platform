<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

class ProcessPayment
{


    private $gateway;
    public $plan;
    public $planReq;
    public $request;
    public $redirect_url;
    public $error_message;
    public $return_view;
    public $will_redirect= false;

    public function __construct()
    {

    }

    public function set_gateway($gateway)
    {
        $this->gateway = $gateway;
        return $this;
    }

    public function set_plan($plan)
    {
        $this->plan = $plan;
        return $this;
    }
    public function plan_request($planReq){
        $this->planReq = $planReq;
        return $this;
    }

    public function request($request)
    {
        $this->request = $request;
        return $this;
    }

    public function process()
    {
        if ($this->gateway == 'paypal') {
            $gateway = new PayPalPayment();
            $gateway->plan($this->plan);
            $gateway->request($this->request);
            $gateway->plan_request($this->planReq);
        } else if ($this->gateway == 'stripe') {
            $gateway = new StripePayment();
            $gateway->plan($this->plan);
            $gateway->request($this->request);
            $gateway->plan_request($this->planReq);
        }else if ($this->gateway == 'paytm') {
            $gateway = new PaytmPayment();
            $gateway->plan($this->plan);
            $gateway->request($this->request);
            $gateway->plan_request($this->planReq);
        }else if ($this->gateway == 'paystack') {
            $gateway = new PaystackPayment();
            $gateway->plan($this->plan);
            $gateway->request($this->request);
            $gateway->plan_request($this->planReq);
        }else if ($this->gateway == 'mollie') {
            $gateway = new MolliePayment();
            $gateway->plan($this->plan);
            $gateway->request($this->request);
            $gateway->plan_request($this->planReq);
        }else if ($this->gateway == 'stripe_checkout') {
            $gateway = new StripeCheckoutPayment();
            $gateway->plan($this->plan);
            $gateway->request($this->request);
            $gateway->plan_request($this->planReq);
        }else if ($this->gateway == 'iyzico') {
            $gateway = new IyzicoPayment();
            $gateway->plan($this->plan);
            $gateway->request($this->request);
            $gateway->plan_request($this->planReq);
        }else if ($this->gateway == 'authorize_net') {
            $gateway = new AuthorizeNetPayment();
            $gateway->plan($this->plan);
            $gateway->request($this->request);
            $gateway->plan_request($this->planReq);
        }

        $payment = new Payment($gateway);
        $payment->trigger();

        $this->redirect_url =$payment->gateway->redirect_url();
        $this->will_redirect =$payment->gateway->will_redirect();
        $this->error_message =$payment->gateway->error_message();
        $this->return_view =$payment->gateway->return_view();

        return $this;

    }

}
