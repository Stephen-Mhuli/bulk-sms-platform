<?php
namespace Modules\PaymentGateway\PaymentGatewayProvider;

interface PaymentInterface {
    public function pay();
    public function getCredentials();
    public function process();
    public function will_redirect();
    public function redirect_url();
    public function return_view();
    public function error_message();
}
