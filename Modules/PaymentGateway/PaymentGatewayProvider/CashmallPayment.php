<?php

namespace Modules\PaymentGateway\PaymentGatewayProvider;

use App\Models\BillingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Payment;

class CashmallPayment implements PaymentInterface
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
        if (!isset($credentials) || (!$credentials->paypal_client_id || !$credentials->paypal_client_secret)) {
            return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid payment')]);
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
            function CashMaal_API($cmd, $req = array()) {
                // Add below your API key
                $CashMaal_payout_API = 'ADD_PAYOUT_SECRET_KEY_HERE';
                $req['cmd'] = $cmd;
                $req['p_secretkey'] = $CashMaal_payout_API;
                $req['user_ip'] = $_SERVER['REMOTE_ADDR'];
                // Generate the query string
                $post_data = http_build_query($req, '', '&');

                // Calculate the HMAC signature on the POST data


                // Create cURL handle and initialize (if needed)
                static $ch = NULL;
                if ($ch === NULL) {
                    $ch = curl_init('https://www.cashmaal.com/Pay/'.$cmd.'.php');
                    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                }

                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

                // Execute the call and close cURL handle
                return $data = curl_exec($ch);

            }
            //--------## END cashmaal.com API function ##



            $CashMaal_API=CashMaal_API("payout_v2", $req=Array("to_email" => "myClient@gmail.com","currency_is" => "USD","sending_amount" => "1","order_id" => "","addi_info" => "this is test payment"));

            $CashMaal_API_Json=json_decode($CashMaal_API, true);

            if($CashMaal_API_Json['status'] == 1){ // its mean Payment Sent Successfully


            }else{
                echo "Error: ".$CashMaal_API_Json['error'];
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $this->error_message= $ex->getMessage();
        }

    }

}
