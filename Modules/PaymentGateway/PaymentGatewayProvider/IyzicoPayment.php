<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class IyzicoPayment implements PaymentInterface
{
    public $paymentId;
    public $request;
    public $plan;
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
        if (!isset($credentials) || (!$credentials->iyzico_api_key || !$credentials->iyzico_secret_key)) {
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
        try {

            $requestData=$this->request;

            $plan = $this->plan;
            $credentials = $this->getCredentials();
            $user = auth('customer')->user();
            $setCardNumber = ('0123456789') . '' . $user->id;
            $options = new \Iyzipay\Options();
            $options->setApiKey("$credentials->iyzico_api_key");
            $options->setSecretKey("$credentials->iyzico_secret_key");
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");


            $request = new \Iyzipay\Request\CreatePaymentRequest();
            $request->setLocale(\Iyzipay\Model\Locale::TR);
            $request->setConversationId("123456789");
            $request->setPrice("$plan->price");
            $request->setPaidPrice("$plan->price");
            $request->setCurrency(\Iyzipay\Model\Currency::TL);
            $request->setInstallment(1);
            $request->setBasketId("$plan->id");
            $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
            $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);


            $paymentCard = new \Iyzipay\Model\PaymentCard();
            $paymentCard->setCardHolderName("$requestData->iyzico_card_holder_name");
            $paymentCard->setCardNumber("$requestData->iyzico_card_number");
            $paymentCard->setExpireMonth("$requestData->iyzico_card_expired_date");
            $paymentCard->setExpireYear("$requestData->iyzico_card_expired_year");
            $paymentCard->setCvc("$requestData->iyzico_card_cvc");
            $paymentCard->setRegisterCard(0);
            $request->setPaymentCard($paymentCard);


            $buyer = new \Iyzipay\Model\Buyer();
            $buyer->setId("ID-.''.$user->ID");
            $buyer->setName("$user->first_name");
            $buyer->setSurname("$user->last_name");
            $buyer->setGsmNumber("+905350000000");
            $buyer->setEmail("$user->email");
            $buyer->setIdentityNumber("74300864791");
            $buyer->setLastLoginDate(Carbon::now());
            $buyer->setRegistrationDate(Carbon::now());
            $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
            $buyer->setIp("85.34.78.112");
            $buyer->setCity("null");
            $buyer->setCountry("null");
            $buyer->setZipCode("0000");
            $request->setBuyer($buyer);


            $shippingAddress = new \Iyzipay\Model\Address();
            $shippingAddress->setContactName("Jane Doe");
            $shippingAddress->setCity("Istanbul");
            $shippingAddress->setCountry("Turkey");
            $shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
            $shippingAddress->setZipCode("34742");
            $request->setShippingAddress($shippingAddress);

            $billingAddress = new \Iyzipay\Model\Address();
            $billingAddress->setContactName("Jane Doe");
            $billingAddress->setCity("Istanbul");
            $billingAddress->setCountry("Turkey");
            $billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
            $billingAddress->setZipCode("34742");
            $request->setBillingAddress($billingAddress);

            $basketItems = array();
            $planId= 'USER-PLAN-'.$user->id;
            $firstBasketItem = new \Iyzipay\Model\BasketItem();
            $firstBasketItem->setId("$planId");
            $firstBasketItem->setName("Binocular");
            $firstBasketItem->setCategory1("Collectibles");
            $firstBasketItem->setCategory2("Accessories");
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice("$plan->price");
            $basketItems[0] = $firstBasketItem;

            $request->setBasketItems($basketItems);

        $payment = \Iyzipay\Model\Payment::create($request, $options);
//        dd($payment);

        if ($payment->getStatus()=='failure' || $payment->getStatus()=='failure'){
            $this->error_message= $payment->getErrorMessage();
            throw new \Exception($payment->getErrorMessage());
        }

        } catch (\Exception $ex) {
            Log::error($ex);
            $this->error_message= $ex->getMessage();
        }
    }

}
