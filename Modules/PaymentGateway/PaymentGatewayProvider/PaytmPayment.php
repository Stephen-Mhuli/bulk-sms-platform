<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use App\Models\BillingRequest;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use paytm\paytmchecksum\PaytmChecksum;

class PaytmPayment implements PaymentInterface
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
        if (!$credentials->paytm_environment || !$credentials->paytm_mid || !$credentials->paytm_secret_key || !$credentials->paytm_website || !$credentials->paytm_txn_url) {
            throw new \Exception('Credentials not found. Please contact with the administrator');
        }
        return $credentials;
    }

    public function request($request)
    {
        $this->request = $request;
        return $this;
    }

    public function plan($plan){
        $this->plan = $plan;
        return $this;
    }

    public function plan_request($planReq){
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
        try {
        $credentials = $this->getCredentials();
        $paytmData = $this->payTmPayment($this->plan, $this->request, $this->planReq, $credentials);
            $this->return_view = view('paymentgateway::paytm', $paytmData);
//        return view('paymentgateway::paytm', $paytmData);
        } catch (\Exception $ex) {
            Log::error($ex);
            dd($ex->getMessage());
            return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid Payment')]);
        }
    }


    function payTmPayment($plan, $req, $planReq, $credentials)
    {
        $recurring_type = 'week';
        $expired = now()->addWeek();
        $settings = json_decode(get_settings('local_setting'));
        $paytmParams = array();

        $orderId = "PLANORDERID_" . $planReq->id;
        $mid = $credentials->paytm_mid;
        $paytmParams["body"] = array(
            "requestType" => "Payment",
            "mid" => $mid,
            "websiteName" => $credentials->paytm_website,
            "orderId" => $orderId,
            "callbackUrl" => route('paymentgateway::payment.paytm.redirect'),
            "subscriptionAmountType" => "FIX",
            "subscriptionFrequency" => "2",
            "subscriptionFrequencyUnit" => strtoupper($recurring_type),
            "subscriptionExpiryDate" => $expired,
            "subscriptionEnableRetry" => "1",
            "txnAmount" => array(
                "value" => $plan->price,
                "currency" => isset($settings->currency_code)?$settings->currency_code:'USD',
            ),
            "userInfo" => array(
                "custId" => "CUST_" . $planReq->customer_id,
            ),
        );


        /*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys
*/
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $credentials->paytm_secret_key);

        $paytmParams["head"] = array(
            "signature" => $checksum
        );
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        if ($credentials->paytm_environment == 'staging') {
            /* for Staging */
            $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=" . $mid . "&orderId=" . $orderId;

        }

        if ($credentials->paytm_environment == 'production') {
            /* for Production */
            $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=" . $mid . "&orderId=" . $orderId;

        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);

        $paytmBody = $paytmParams["body"];
        $paytmUserInfo = $paytmBody['userInfo'];



        $response = json_decode($response);
        if (!isset($response->body) || !isset($response->body->resultInfo) || $response->body->resultInfo->resultStatus != 'S') {
            throw new \Exception(trans('Invalid Payment'));
        }


        $data['environment'] = $credentials->paytm_environment;
        $data['response'] = $response;
        $data['mid'] = $mid;
        $data['order_id'] = $orderId;
        return $data;

    }

    function processPaytmRedirect(Request $request)
    {
        if (!$this->request->ORDERID || !$this->request->TXNID || !$this->request->TXNAMOUNT || !$this->request->STATUS) {
            return redirect()->route('login')->withErrors(['msg' => trans('layout.message.invalid_payment')]);
        }

        $credentials = json_decode(get_settings('payment_gateway'));
        if (!$credentials->paytm_secret_key) {
            return redirect()->route('login')->withErrors(['msg' => trans('invalid Payment')]);
        }

        $paytmParams = $_POST;

        $paytmChecksum = $_POST['CHECKSUMHASH'];
        unset($paytmParams['CHECKSUMHASH']);

        $isVerifySignature = PaytmChecksum::verifySignature($paytmParams, $credentials->paytm_secret_key, $paytmChecksum);
        if (!$isVerifySignature) return redirect()->route('login')->withErrors(['msg' => trans('Invalid Payment')]);


        $orderId = $request->ORDERID;
        $orderId = explode('_', $orderId)[1];

        $billingRequest= BillingRequest::find($orderId);
        if (!$billingRequest) return redirect()->route('login')->withErrors(['msg' => trans('Invalid Payment')]);
        $plan= Plan::where('id',$billingRequest->id)->first();
        if ($request->TXNAMOUNT != format_number($plan->price, 2)) return redirect()->route('login')->withErrors(['msg' => trans('Invalid Payment')]);

        if ($request->STATUS != 'TXN_SUCCESS') return redirect()->route('login')->withErrors(['msg' => trans('Invalid Payment')]);

        $billingRequest->status = 'accepted';
        $billingRequest->save();

        return redirect()->route('login')->with('success', trans('Payment Success'));

    }
}
