<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use App\Models\BillingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Payment;

class PayPalPayment implements PaymentInterface
{
    public $paymentId;
    public $request;
    public $plan;
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

    public function plan_request($planReq){
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
        if (!isset($credentials) || (!$credentials->paypal_client_id || !$credentials->paypal_client_secret)) {
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
            $payment = $this->PayPalPayment($this->plan, $this->planReq);
            if ($payment) {
                $this->redirect_url = $payment->getApprovalLink();
                $this->will_redirect = true;
                $this->return_view = null;
            } else{
                $this->error_message = trans('Invalid payment');
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $this->error_message= $ex->getMessage();
        }

    }

    //custom functions

    public function paymentSuccess(Request $request)
    {
        $request = $this->request;
        $credentials = $this->getCredentials();
        $apiContext = $this->getPaypalApiContext($credentials->paypal_client_id, $credentials->paypal_client_secret);
        $paymentId = $this->paymentId;
        $user_plan_id = $this->plan->id;
        $user = $request->user;
        if (!$paymentId || !$user_plan_id || !$user) {
            return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid payment')]);
        }

        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (\Exception $ex) {
            return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid payment')]);
        }

        if (!$payment) return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid payment')]);

        $url = $payment->getRedirectUrls();
        $parsed_url = parse_url($url->getReturnUrl());
        $query_string = $parsed_url["query"];
        parse_str($query_string, $array_of_query_string);

        if ($array_of_query_string["plan"] != $user_plan_id || $array_of_query_string["user"] != $user || $array_of_query_string['paymentId'] != $paymentId) {
            return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid payment')]);
        }

        $billingRequest = BillingRequest::where(['id' => $user_plan_id, 'customer_id' => auth('customer')->id()])->where(function ($q) use ($paymentId) {
            $q->whereNotIn('transaction_id', [$paymentId])->orWhereNull('transaction_id');
        })->first();

        if (!$billingRequest) {
            return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid payment')]);
        }

        $billingRequest->status = 'accepted';
        $billingRequest->save();

        $customer = auth('customer')->user();
        $pre_plan = $customer->plan;
        if ($pre_plan) {
            $customer->plan()->delete();
        }
        $customer->plan()->create(['plan_id' => $billingRequest->plan_id, 'sms_limit' => $billingRequest->plan->sms_limit, 'available_sms' => $billingRequest->plan->sms_limit, 'price' => $billingRequest->plan->price]);
        BillingRequest::where(['customer_id' => $user, 'status' => 'pending'])->update(['status' => 'rejected']);
        return redirect()->route('customer.billing.index')->with('success', trans('Congratulations! Your plan successfully changed'));
    }

    function PayPalPayment($plan, $planReq)
    {
        $credentials = $this->getCredentials();

        $apiContext = $this->getPayPalApiContext($credentials->paypal_client_id, $credentials->paypal_client_secret);
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($this->plan->price);
        $amount->setCurrency('USD'); //TODO:: get the currency

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(route('paymentgateway::payment.process.success', ['plan' => $planReq->id, 'user' => $planReq->customer_id]))
            ->setCancelUrl(route('paymentgateway::payment.process.cancel'));

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($apiContext);
            return $payment;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING

            if (isset(json_decode($ex->getData())->error_description)){
                return redirect()->route('customer.billing.index')->withErrors(['failed' => json_decode($ex->getData())->error_description]);
            }
            Log::error($ex->getData());
        }
        return null;
    }

    function getPayPalApiContext($client_id, $secret_key)
    {

        return new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $client_id,     // ClientID
                $secret_key      // ClientSecret
            )
        );
    }

}
