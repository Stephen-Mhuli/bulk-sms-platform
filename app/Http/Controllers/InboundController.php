<?php

namespace App\Http\Controllers;

use App\Events\SendMail;
use App\Models\Contact;
use App\Models\CustomerNumber;
use App\Models\Expense;
use App\Models\Keyword;
use App\Models\KeywordContact;
use App\Models\Label;
use App\Models\Message;
use App\Models\MessageLog;
use App\Models\SmsQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Plivo\RestClient;
use Plivo\XML\Response;
use SignalWire\LaML\MessageResponse as LaML;
use SignalWire\Rest\Client;
use Textlocal;
use Twilio\TwiML\MessagingResponse;
use Vonage\Client\Credentials\Basic;

class InboundController extends Controller
{
    public function save_message_log($data,$user){

      return  $user->message_logs()->create($data);

    }
    public function process($type, Request $request)
    {
        if ($type == 'signalwire') {
            if (!$request->has('MessageSid')) {
                return "error";
            }
            $MessageSid = $request->MessageSid;
            $SmsSid = $request->SmsSid;
            $AccountSid = $request->AccountSid;
            $From = $request->From;
            $To = $request->To;
            $Body = $request->Body;


            $customer_number = CustomerNumber::where('number', $To)
                ->orWhere('number', str_replace('+', '', $To))
                ->orWhere('number', '+'.str_replace('+', '', $To))
                ->first();
            if (!$customer_number) {
                $resp = new LaML();
                echo $resp;
                exit();
            }
            $this->checkKeyword($From, $Body, $customer_number);

            if ($customer_number->forward_to) {
                $this->sendForwardMessage($type, $customer_number->number, $customer_number->forward_to_dial_code . $customer_number->forward_to, $Body);
            }
            if($customer_number->webhook_url){
                $this->trigger_webhook($customer_number,$request->all());
            }
            $resp = new LaML();
            $customer = $customer_number->customer;

            $message = new Message();
            $message->customer_id = $customer->id;
            $message->body = $Body;
            $message->numbers = json_encode(['from' => $From, 'to' => [$To]]);
            $message->type = 'inbox';
            $message->message_obj = json_encode($request->except(['From', 'To', 'Body']));
            $message->save();
            $this->save_message_log(['to'=>$To,'from'=>$From,'body'=> $Body,'message_id'=>$message->id,'type'=>'inbox'],$customer);
            echo $resp;

        } else if ($type == 'twilio') {
            $MessageSid = $request->MessageSid;
            $SmsSid = $request->SmsSid;
            $AccountSid = $request->AccountSid;
            $From = $request->From;
            $To = $request->To;
            $Body = $request->Body;

            $customer_number = CustomerNumber::where('number', $To)
                ->orWhere('number', str_replace('+', '', $To))
                ->orWhere('number', '+'.str_replace('+', '', $To))
                ->first();
            if (!$customer_number) {
                Log::error("invalid number");
                $response = new MessagingResponse();
                echo $response;
                exit();
            }
            $this->checkKeyword($From, $Body, $customer_number);

            $response = new MessagingResponse();

            if ($customer_number->forward_to) {
                $response=$this->sendForwardMessage($type, $From, $customer_number->forward_to_dial_code . $customer_number->forward_to, $Body);
            }
            if($customer_number->webhook_url){
                $this->trigger_webhook($customer_number,$request->all());
            }
            $customer = $customer_number->customer;

            $message = new Message();
            $message->customer_id = $customer->id;
            $message->body = $Body;
            $message->numbers = json_encode(['from' => $From, 'to' => [$To]]);
            $message->type = 'inbox';
            $message->message_obj = json_encode($request->only(['MessageSid', 'SmsSid', 'AccountSid']));
            $message->save();
            $this->save_message_log(['to'=>$To,'from'=>$From,'body'=> $Body,'message_id'=>$message->id,'type'=>'inbox'],$customer);
            echo $response;

        } else if ($type == 'nexmo') {
            $inbound = \Vonage\SMS\Webhook\Factory::createFromGlobals();

            $requestData['vonage_message_id']=$MessageSid = $inbound->getMessageId();
            $requestData['from']=$From = $inbound->getFrom();
            $requestData['to']=$To = $inbound->getTo();
            $requestData['body']=$Body = $inbound->getText();

            $customer_number = CustomerNumber::where('number', $To)
                ->orWhere('number', str_replace('+', '', $To))
                ->orWhere('number','+'. str_replace('+', '', $To))
                ->first();
            if (!$customer_number) {
                Log::error('Number not found for ' . $To);
                exit();
            }
            $this->checkKeyword($From, $Body, $customer_number);

            if ($customer_number->forward_to) {
                $this->sendForwardMessage($type, $customer_number->number, $customer_number->forward_to_dial_code . $customer_number->forward_to, $Body);
            }
            if($customer_number->webhook_url){
                $this->trigger_webhook($customer_number,$requestData);
            }
            $customer = $customer_number->customer;
            $message = new Message();
            $message->customer_id = $customer->id;
            $message->body = $Body;
            $message->numbers = json_encode(['from' => $From, 'to' => [$To]]);
            $message->type = 'inbox';
            $message->message_obj = json_encode(['message_id' => $MessageSid]);
            $message->save();
            $this->save_message_log(['to'=>$To,'from'=>$From,'body'=> $Body,'message_id'=>$message->id,'type'=>'inbox'],$customer);

        } else if ($type == 'telnyx') {
            $json = json_decode(file_get_contents("php://input"), true);

            $From = $json["data"]["payload"]["from"]["phone_number"];
            $To = $json["data"]["payload"]["to"][0]["phone_number"];
            $Body = $json["data"]["payload"]["text"];
            $event_type = $json['data']['event_type'];

            if(isset($event_type) && $event_type != "message.received"){
                exit();
            }

            $customer_number = CustomerNumber::where('number', $To)
                ->orWhere('number', str_replace('+', '', $To))
                ->orWhere('number', '+'.str_replace('+', '', $To))
                ->first();
            if (!$customer_number) {
                $resp = new LaML();
                echo $resp;
                exit();
            }

           $this->checkKeyword($From, $Body, $customer_number);

            if ($customer_number->forward_to) {
                $this->sendForwardMessage($type, $customer_number->number, $customer_number->forward_to_dial_code . $customer_number->forward_to, $Body);
            }
            if($customer_number->webhook_url){
                $this->trigger_webhook($customer_number,$json);
            }

            $resp = new LaML();
            $customer = $customer_number->customer;

            $message = new Message();
            $message->customer_id = $customer->id;
            $message->body = $Body;
            $message->numbers = json_encode(['from' => $From, 'to' => [$To]]);
            $message->type = 'inbox';
            $message->message_obj = json_encode([]);
            $message->save();
           $messageLog =  $this->save_message_log(['to'=>$To,'from'=>$From,'body'=> $Body,'message_id'=>$message->id,'type'=>'inbox'],$customer);

            if (isset($json["data"]["cost"])){
                $expense= new Expense();
                $expense->type='receive';
                $expense->cost=$json["data"]["cost"]['amount'];
                $expense->message_log_id=$messageLog->id;
                $expense->customer_id=$message->customer_id;
                $expense->save();
            }
            echo $resp;

        } else if ($type == 'plivo') {

            $requestData['from']=$From = $_REQUEST["From"];
            $requestData['to']=$To = $_REQUEST["To"];
            $requestData['body']=$Body = $_REQUEST["Text"];
            $customer_number = CustomerNumber::where('number', $To)
                ->orWhere('number', str_replace('+', '', $To))
                ->orWhere('number', '+'.str_replace('+', '', $To))
                ->first();
            if (!$customer_number) {
                Log::info("not found in customer number");
                $resp = new Response();
                echo $resp->toXML();
                exit();
            }
            $this->checkKeyword($From, $Body, $customer_number);

            if ($customer_number->forward_to) {
                $this->sendForwardMessage($type, $customer_number->number, $customer_number->forward_to_dial_code . $customer_number->forward_to, $Body);
            }
            if($customer_number->webhook_url){
                $this->trigger_webhook($customer_number,$requestData);
            }

            $resp = new Response();
            $customer = $customer_number->customer;

            $message = new Message();
            $message->customer_id = $customer->id;
            $message->body = $Body;
            $message->numbers = json_encode(['from' => $From, 'to' => [$To]]);
            $message->type = 'inbox';
            $message->message_obj = json_encode([]);
            $message->save();
            $this->save_message_log(['to'=>$To,'from'=>$From,'body'=> $Body,'message_id'=>$message->id,'type'=>'inbox'],$customer);
            echo $resp->toXML();
        }

        if (isset($customer) && $customer->email) {
            $notification = $customer->settings()->where('name','email_notification')->first();
            if (isset($notification->value) && $notification->value == 'true' && $Body) {
                SendMail::dispatch($customer->email, 'New Message', $Body);
            }
        }

    }

    function sendForwardMessage($type, $from, $to, $message)
    {
        try {

            if ($type == 'signalwire') {
                $credentials = json_decode(get_settings('signalwire'));
                if (!$credentials->sw_project_id || !$credentials->sw_token || !$credentials->sw_space_url)
                    exit();

                try {
                    $client = new Client($credentials->sw_project_id, $credentials->sw_token, array("signalwireSpaceUrl" => $credentials->sw_space_url));
                    $message = $client->messages
                        ->create($to,
                            array("from" => $from, "body" => $message)
                        );
                } catch (\Exception $e) {

                }

            } elseif ($type == 'twilio') {

                $client=new MessagingResponse();
                $client->message($from. ": " . $message,['to'=>$to]);
                return $client;

            } elseif ($type == 'nexmo') {
                $credentials = json_decode(get_settings('nexmo'));
                if (!$credentials->nx_api_key || !$credentials->nx_api_secret)
                    exit();

                $api_key = $credentials->nx_api_key;
                $api_secret = $credentials->nx_api_secret;
                $client = new \Vonage\Client(new Basic($api_key, $api_secret));
                $message = $client->message()->send([
                    'to' => $to,
                    'from' => $from,
                    'text' => $message
                ]);
            } elseif ($type == 'telnyx') {
                $credentials = json_decode(get_settings('telnyx'));
                if (!$credentials->tl_api_key)
                    exit();

                \Telnyx\Telnyx::setApiKey($credentials->tl_api_key);
                try {
                    \Telnyx\Message::Create(['from' => $from, 'to' => $to, 'text' => $message]);
                } catch (\Exception $ex) {

                }
            } elseif ($type == 'plivo') {
                $credentials = json_decode(get_settings('plivo'));
                if (!$credentials->pl_auth_id || !$credentials->pl_auth_token)
                    exit();

                $client = new RestClient($credentials->pl_auth_id, $credentials->pl_auth_token);
                $message_created = $client->messages->create(
                    $from,
                    [$to],
                    $message
                );
            } elseif ($type == 'textlocal') {
                $credentials = json_decode(get_settings('textlocal'));
                if (!$credentials->text_local_api_key || !$credentials->text_local_sender)
                    exit();

                $textlocal = new Textlocal(false, false, $credentials->text_local_api_key);

                $numbers = $to;
                $sender = $credentials->text_local_sender;
                $message = $message;

                $response = $textlocal->sendSms($numbers, $message, $sender);

            }
        } catch (\Exception $ex) {
            Log::error("forward message error");
        }
    }

    public function gatewayStatus(Request $request)
    {

        $sms_gateway_data = [
            'message_sent_date' => $request->sent_date,
            'part_number' => $request->part_number,
            'message_cost' => $request->message_cost,
            'message_status' => $request->message_status,
            'dlr_date' => $request->dlr_date,
        ];
        try {
            if ($request->message_id && $request->status) {
                $queue = SmsQueue::where('sms_gateway_message_id', $request->message_id)->first();
                $queue->sms_gateway_status = $request->status;
                $queue->sms_gateway_value = json_encode($sms_gateway_data);
                $queue->save();
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        return response()->json([], 200);

    }

    function trigger_webhook($customer_number,$webhookData){
        if (isset($customer_number) && isset($customer_number->webhook_url) && isset($customer_number->webhook_method) && $customer_number->webhook_method=='post') {
            $client=new \GuzzleHttp\Client(['verify' => false ]);
            $client->post($customer_number->webhook_url,[
                'form_params'=>$webhookData
            ]);

        }else if(isset($customer_number) && isset($customer_number->webhook_url) && isset($customer_number->webhook_method) && $customer_number->webhook_method=='get'){
            $client=new \GuzzleHttp\Client(['verify' => false ]);
            $client->get($customer_number->webhook_url,[
                'query'=>$webhookData
            ]);
        }
    }

    public function webhookDeliver(Request $request){

        try {
            $json = json_decode(file_get_contents("php://input"), true);

            if (isset($json["data"]) && isset($json["data"]["id"])) {
                $responseCode = isset($json["data"]["errors"]) && isset($json["data"]["errors"][0]) && isset($json["data"]["errors"][0]["code"]) ? $json["data"]["errors"][0]["code"] : null;
                $messageQueue = SmsQueue::where('response_id', $json["data"]["id"])->firstOrFail();
                $messageLog = MessageLog::where('response_id', $json["data"]["id"])->firstOrFail();
                if ($responseCode) {
                    $messageQueue->response_code = $responseCode;
                    $messageLog->update(['response_code'=>$responseCode]);
                } else {
                    $messageQueue->delivered_at = now();
                }
                $messageQueue->save();

                if (isset($json["data"]["errors"])){
                    $messageLog->update(['status'=>'failed']);
                    $messageQueue->update(['status'=>'failed']);
                }
            }
            return response()->json(['status'=>'success']);
        }catch (\Exception $ex) {
            Log::info($ex->getMessage());
            return response()->json(['status'=>'failed']);
        }
    }

    function checkKeyword($from, $body, $customer_number)
    {
        $keyword_contact = null;

        $contact = Contact::where('number', $from)
            ->orWhere('number', str_replace('+', '', $from))
            ->orWhere('number', '+' . str_replace('+', '', $from))
            ->first();
        $label = Label::where('customer_id', $customer_number->customer_id)->where('title', 'new')->first();
        if (!$label) {
            $label = new Label();
            $label->title = 'new';
            $label->status = 'active';
            $label->customer_id = $customer_number->customer_id;
            $label->color = 'red';
            $label->save();
        }
        if (!$contact) {
            $contact = new Contact();
            $contact->customer_id = $customer_number->customer_id;
            $contact->number = $from;
            $contact->label_id = $label->id;
            $contact->save();
        }
        $keyword = null;

        if ($customer_number) {
            $keyword = Keyword::where(DB::raw('lower(word)'), 'like', '%' . strtolower($body) . '%')->where('customer_number_id', $customer_number->number_id)->first();
        }
        if ($keyword && $contact && $customer_number) {
            $keyword_contact = KeywordContact::where('keyword_id', $keyword->id)->where('contact_id', $contact->id)->where('customer_id', $customer_number->customer_id)->first();
        }


        if ($keyword && isset($keyword->type) && $keyword->type == 'opt_out') {
            $keyword_contact = KeywordContact::where('contact_id', $contact->id)->where('customer_id', $customer_number->customer_id)->first();
            if ($keyword_contact) {
                $keyword_contact->delete();
                $contact->delete();
            }
            exit;
        } else if ($keyword && isset($keyword->type) && $keyword->type == 'opt_in') {
            $keywordContact = new KeywordContact();
            $keywordContact->customer_id = $keyword->customer_id;
            $keywordContact->keyword_id = $keyword->id;
            $keywordContact->contact_id = $contact->id;
            $keywordContact->save();
            exit();
        }
        if ($keyword_contact) {
            Log::info($contact->number . " and " . $keyword->word . " already subscribe");
            exit;
        }
    }
}
