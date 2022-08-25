<?php

namespace Modules\PaymentGateway\Http\Controllers;

use App\Events\SendMail;
use App\Models\BillingRequest;
use App\Models\CustomerPlan;
use App\Models\Plan;
use Carbon\Carbon;
use Composer\Cache;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\PaymentGateway\PaymentGatewayProvider\ProcessPayment;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        return view('paymentgateway::index');
    }
    public function email_payment_process(Request $request)
    {
        $data['plan'] = Plan::find($request->id);
        return view('customer.default_plan_submit_form.blade',$data);
    }

    public function process(Request $request)
    {
        $data['plan'] = Plan::find($request->id);
        return view('paymentgateway::process', $data);
    }

    public function payNow(Request $request)
    {
        $plan = Plan::find($request->plan);
        if (!$plan) return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Plan not found')]);

        if ($plan->price > 0) {
            $request->validate([
                'payment_type' => 'required|in:flutterwave,vogue_pay,offline,coinpay,paypal,mollie,card,paytm,paystack,stripe_checkout,iyzico,authorize_net,btc,ltct'
            ]);
        }

        $user = auth('customer')->user();
        $pre_plan = $user->plan;
        if (isset($pre_plan) && $pre_plan->plan_id == $request->id) {
            return redirect()->route('customer.billing.index')->with('fail', 'You are already subscribed to this plan');
        }
        if (in_array($request->payment_type,['flutterwave','vogue_pay', 'offline', 'coinpay','paypal','mollie','card','paytm','paystack','stripe_checkout','iyzico','authorize_net','btc','ltct'])) {
            $preBilling = BillingRequest::where(['customer_id' => $user->id, 'status' => 'pending'])->first();
            if ($preBilling) {
                return redirect()->route('customer.billing.index')->with('fail', trans('You already have a pending request. Please wait for the admin reply.'));
            }
            $pendingPlan = CustomerPlan::where('customer_id',$user->id)->where('status','pending')->first();
            if ($pendingPlan){
                $pendingPlan->delete();
            }
        }
        $date = Carbon::now();
        $currentPlan = $user->currentPlan();
        if (isset($currentPlan->renew_date) && $currentPlan->renew_date){
            $diffDate = $currentPlan->renew_date->diff($date);
            $days = $diffDate->format('%a');
            if (isset($currentPlan->plan->recurring_type) && $currentPlan->plan->recurring_type=='monthly' || $currentPlan->plan->recurring_type=='yearly') {
                if ($days <= 5) {
                    $renew_date = true;
                }
            }else{
                if ($days < 2) {
                    $renew_date = true;
                }
            }
        }
        $customerPlanPending = CustomerPlan::where('customer_id',$user->id)->where('status','pending')->first();
        if (!$customerPlanPending){
            $user->plans()->create(['plan_id' => $plan->id, 'sms_limit' => $plan->sms_limit, 'price' => $plan->price,'contact_limit' => $plan->contact_limit,'device_limit' => $plan->device_limit,'daily_receive_limit' => $plan->daily_receive_limit,'daily_send_limit' => $plan->daily_send_limit,'is_current' => 'no','payment_status' => 'unpaid','status' => 'pending','renew_date'=>null,'recurring_type'=>$plan->recurring_type]);
        }
        $planReq = new BillingRequest();
        $planReq->admin_id = $plan->admin_id;
        $planReq->customer_id = $user->id;
        $planReq->plan_id = $plan->id;
        $planReq->transaction_id = $request->transaction_id;
        $planReq->other_info = json_encode($request->only('payment_type'));
        $planReq->save();
        cache()->forget('pending_request');

        if ($plan->recurring_type == 'weekly'){
            $renewDate = Carbon::now()->addDay(7);
        }elseif ($plan->recurring_type == 'monthly'){
            $renewDate = Carbon::now()->addMonth();
        }elseif ($plan->recurring_type == 'yearly'){
            $renewDate = Carbon::now()->addMonth(12);
        }else{
            $renewDate = null;
        }
        if (isset($renew_date) && $renew_date){
            $renew  = $currentPlan->renew_date->addDays($renewDate);
        }else{
            $renew  = $renewDate;
        }

        if ($plan->price <= 0) {
            $planReq->status = 'accepted';
            $planReq->save();

            $pre_plan = $user->plan;
            if ($pre_plan) {
                $user->plan()->update(['is_current' => 'no']);;
            }
            $user->plan()->create(['plan_id' => $planReq->plan_id, 'sms_limit' => $plan->sms_limit, 'available_sms' => $plan->sms_limit, 'price' => $plan->price,'renew_date'=>$renew]);
            return redirect()->route('customer.billing.index')->with('success', trans('Congratulations! Your plan successfully changed'));
        }

        try {
           $emailTemplate = get_email_template('plan_request');
        if ($emailTemplate) {
            $regTemp = str_replace('{customer_name}', $user->first_name.' '.$user->last_name, $emailTemplate->body);
            SendMail::dispatch($user->email, $emailTemplate->subject, $regTemp);
        }
            if (!in_array($request->payment_type,['flutterwave','vogue_pay', 'offline', 'coinpay'])) {
;
                $processPayment = new ProcessPayment();
                $processResult = $processPayment->set_gateway($request->payment_type)
                    ->set_plan($plan)
                    ->plan_request($planReq)
                    ->request($request)
                    ->process();
                if ($processResult->error_message) {
                    return redirect()->route('customer.billing.index')->withErrors(['failed' => $processResult->error_message]);
                }
                if ($processResult->return_view) {
                    return $processResult->return_view;
                } elseif ($processResult->will_redirect && $processResult->redirect_url) {
                    return redirect()->to($processResult->redirect_url);
                } else {
                    return redirect()->route('customer.billing.index')->with('success', trans('Congratulations! Your plan successfully changed'));
                }
            }
            else{
                return redirect()->route('customer.billing.index')->with('success', trans('Congratulations! Your plan successfully changed'));
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid Payments')]);
        }

        //end


    }

    function PayPalPayment($plan, $planReq)
    {
        $credentials = json_decode(get_settings('payment_gateway'));
        $settings = json_decode(get_settings('local_setting'));
        if (!isset($credentials) || (!$credentials->paypal_client_id || !$credentials->paypal_client_secret)) {
            return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Invalid payments')]);
        }
        $apiContext = $this->getPayPalApiContext($credentials->paypal_client_id, $credentials->paypal_client_secret);
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');
        $currency_code = isset($settings->currency_code)?$settings->currency_code:'USD';
        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($plan->price);
        $amount->setCurrency($currency_code); //TODO:: get the currency

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



    public function paymentCancel()
    {
        return redirect()->route('customer.billing.index')->withErrors(['msg' => trans('Payment has been cancelled')]);
    }


    public function checkValidPayment(Request $request){
        $plan = Plan::where('id', $request->plan_id)->first();
        if ($plan->price==$request->price){
            return response()->json(['status'=>'success']);
        }else{
            return abort(404);
        }
    }

    function edie($error_msg)
    {
        \Log::error($error_msg);
        exit();
    }

    public function webhook(Request $request){
        $settings = json_decode(get_settings('payment_gateway'));
        $merchant_id = isset($settings->merchate_id)?$settings->merchate_id:'';
        $ipn_secret = isset($settings->ipn_secret)?$settings->ipn_secret:'';


        $txn_id = isset($request->txn_id)?$request->txn_id:'';
        if(isset($txn_id)){
            $payment = BillingRequest::where("transaction_id", $txn_id)->first();
            $plan = Plan::where("id", $payment->plan_id)->first();
        }else{
            abort('404');
        }

        $order_total = isset($plan->price)?$plan->price:0; //BTC

        if (!isset($request->ipn_mode) || $request->ipn_mode != 'hmac') {
            $this->edie("IPN Mode is not HMAC");
        }

        if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
            edie("No HMAC Signature Sent.");
        }

        $request = file_get_contents('php://input');
        if ($request === false || empty($request)) {
            $this->edie("Error in reading Post Data");
        }

        if (!isset($request->merchant) || $request->merchant != trim($merchant_id)) {
            $this->edie("No or incorrect merchant id.");
        }

        $hmac =  hash_hmac("sha512", $request, trim($ipn_secret));
        if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
            $this->edie("HMAC signature does not match.");
        }

        $amount1 = floatval($request->amount1); //IN USD
        $amount2 = floatval($request->amount2); //IN BTC

        $status = intval($request->status);


        if ($amount1 < $order_total) {
            $this->edie("Amount is lesser than order total");
        }

        if ($status >= 100 || $status == 2) {
            // Payment is complete
            $payment->status = 'accepted';
            $payment->save();
        }
        die("IPN OK");
    }


    public function coinPayment(Request $request){
        $user = auth('customer')->user();
        $plan = Plan::find($request->plan_id);
        if (!$plan) return response()->json(['status'=>'failed','message' => trans('Plan not found')]);

        if (isset($pre_plan) && $pre_plan->plan_id == $request->id) {
            return response()->json(['status'=>'failed','message', 'You are already subscribed to this plan']);
        }
        if ($request->payment_type == 'offline') {
            $preBilling = BillingRequest::where(['customer_id' => $user->id, 'status' => 'pending'])->first();
            if ($preBilling) {
                return response()->json(['status'=>'failed','message'=> trans('You already have a pending request. Please wait for the admin reply.')]);
            }
        }
        $planReq = new BillingRequest();
        $planReq->admin_id = $plan->admin_id;
        $planReq->customer_id = $user->id;
        $planReq->plan_id = $plan->id;
        $planReq->other_info = json_encode($request->only('payment_type'));
        $planReq->save();

        $settings = json_decode(get_settings('payment_gateway'));
        $private_key = isset($settings->private_key)?$settings->private_key:'';
        $public_key = isset($settings->public_key)?$settings->public_key:'';

        $cps_api = new \CoinpaymentsAPI($private_key, $public_key,'json');

// Enter amount for the transaction
        $settings = json_decode(get_settings('local_setting'));
        $currency1=isset($settings->currency_code)?$settings->currency_code:'USD';
        $currency2=isset($request->coin_payment_type)?strtoupper($request->coin_payment_type):'BTC';

// Enter buyer email below
        $amount = $plan->price;


        $url= route('paymentgateway::coin.payment');
        $userName= $user->full_name;
        $userEmail= $user->email;
        $itemName= $plan->title;

        $data=[
            'amount'=>$amount,
            'currency1'=>$currency1,
            'currency2'=>$currency2,
            'buyer_name'=>$userName,
            'buyer_email'=>$userEmail,
            'item_name'=>$itemName,
            'ipn_url'=>$url,
        ];

        $transaction_response = $cps_api->CreateCustomTransaction($data);

        if ($transaction_response['error'] == 'ok') {

            if(isset($transaction_response['result']) && isset($transaction_response['result']['txn_id'])) {
                $planReq->transaction_id = $transaction_response['result']['txn_id'];
                $planReq->save();
            }

            $status_url = isset($transaction_response['result']) && isset($transaction_response['result']['status_url']) ? $transaction_response['result']['status_url'] : '';
            $responseAmount=isset($transaction_response['result']) && isset($transaction_response['result']['amount'])?$transaction_response['result']['amount']:'';

            $data=[
                'status_url'=>$status_url,
                'amount'=>$responseAmount,
                'currency'=>$currency2,
            ];
            return response()->json(['status'=>'success', 'data'=>$data]);
        } else {
            throw new \Exception($transaction_response['error']);
            return response()->json(['status'=>'failed', 'message'=>$transaction_response['error']]);
        }
    }
}
