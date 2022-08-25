<?php
namespace Modules\PaymentGateway\PaymentGatewayProvider;

class PaymentGateway{

    public $body;

    public $to_numbers;

    public $from_number;

    public $sms_provider;

    public $message;

    public $total_send_fail;

    public $failed_sms;


    public function get_credentials(){
        $credentials = json_decode(get_settings($this->sms_provider));
        if(
            ($this->sms_provider=='signalwire' && !$credentials->sw_project_id ) ||
            ($this->sms_provider=='twilio' && (!$credentials->tw_sid || !$credentials->tw_token))

        ){
            throw new \Exception('Credentials not found. Please contact with the administrator');
        }

        return $credentials;

    }
}
