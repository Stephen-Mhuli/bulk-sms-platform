<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNetPayment implements PaymentInterface
{
    public $paymentId;
    public $request;
    public $plan;
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

    public function request($request)
    {
        $this->paymentId = $request->paymentId;
        $this->request = $request;
        return $this;
    }

    public function plan($plan)
    {
        $this->plan = $plan;
        return $this;
    }

    public function getCredentials()
    {
        $credentials = json_decode(get_settings('payment_gateway'));
        if (!isset($credentials) || (!$credentials->authorize_net_login_id || !$credentials->authorize_net_secret_key || !$credentials->authorize_net_transaction_key)) {
            throw new \Exception('Credentials not found. Please contact with the administrator');
        }




        return $credentials;
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
        $credentials=$this->getCredentials();

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($credentials->authorize_net_login_id);
        $merchantAuthentication->setTransactionKey($credentials->authorize_net_transaction_key);

        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("PLAN_".$this->planReq->id);
        $order->setDescription("Plan Purchase");

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($this->request->authorize_net_card_number);
        $creditCard->setExpirationDate($this->request->authorize_net_exp_year.'-'.$this->request->authorize_net_exp_month);
        $creditCard->setCardCode($this->request->authorize_net_cvc);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);


        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($this->plan->price);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);


        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransactionRequest($transactionRequestType);


        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() != "Ok") {
                $tresponse = $response->getTransactionResponse();
                $this->error_message= "Transaction Failed ";

                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $this->error_message= $tresponse->getErrors()[0]->getErrorText();
                } else {
                    $this->error_message=$response->getMessages()->getMessage()[0]->getText();
                }
            }
        } else {
            $this->error_message='No response returned';
        }
    }

}
