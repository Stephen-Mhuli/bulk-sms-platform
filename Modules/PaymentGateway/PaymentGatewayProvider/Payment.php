<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

class Payment
{
    public $gateway;

    public function __construct(PaymentInterface $payment)
    {
        $this->gateway=$payment;
    }

    public function trigger(){
        $this->gateway->process();
        return $this;
    }

}
