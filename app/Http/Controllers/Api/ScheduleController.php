<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerPlan;
use App\Models\Device;
use App\Models\Message;
use App\Models\MessageLog;
use App\Models\SmsQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function getQueues(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'device_unique_id' => 'required',
            'timezone' => 'required',
            'limit' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->messages()], 400);
        }
        $sendingSetting = auth()->user()->sending_settings()->first();
        if (!$sendingSetting) {
            return response()->json(['message' => 'Invalid message limit configuration'], 400);
        }

        $messages = SmsQueue::where(['schedule_completed' => 'no'])
            ->whereNotNull('schedule_datetime')
            ->whereNull('delivered_at')
            ->where('schedule_datetime', '<', now()->timezone($request->timezone))
            ->where('status', 'running')
            ->where('device_unique_id', $request->device_unique_id)
            ->orderBy('schedule_datetime')
            ->limit($request->limit?:10)
            ->get();

        $queueIds = [];
        foreach ($messages as $message) {
            $queueIds[] = $message->id;
        }
        SmsQueue::whereIn('id', $queueIds)->update(['status' => 'fetched']);
        $queue_messages = [];

        foreach ($messages as $key => $message) {
            $queue_messages[$key]['id'] = $message->id;
            $queue_messages[$key]['to'] = $message->to;
            $queue_messages[$key]['schedule_datetime'] = $message->schedule_datetime;
            $queue_messages[$key]['body'] = $message->body;
        }

        return response()->json(['queues' => $queue_messages], 200);
    }

    public function updateQueueStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
            'queue_id' => 'required',
            'status' => 'required|in:pending,fetched,running,paused,failed,delivered',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->messages()], 400);
        }
        $updateData = ['status' => $request->status];
        if ($request->status == 'delivered') {
            $updateData['delivered_at'] = now();
        }

        if (in_array($request->status, ['failed', 'delivered'])) {
            $updateData['schedule_completed'] = 'yes';
        }

        if(in_array($request->status,['pending','running','fetched','paused'])){
            $messageLogStatus['status']='pending';
        }
        if($request->status == 'failed'){
            $messageLogStatus['status']='failed';
            $messageLogStatus['response_code']=$request->error_code;
        }
        if($request->status == 'delivered'){
            $messageLogStatus['status']='succeed';
        }

        $smsQueue=SmsQueue::where('device_unique_id', $request->device_id)->where('id', $request->queue_id)->first();
        $smsQueue->update($updateData);
        $messageLogStatus['queue_id']=$request->queue_id;
        MessageLog::where(['type'=>'sent','device_unique_id'=>$request->device_id,'to'=>$smsQueue->to,'created_at'=>$smsQueue->created_at,'from'=>$smsQueue->from,'message_id'=>$smsQueue->message_id])->update($messageLogStatus);
        return response()->json(['message' => "Queue status updated successfully"]);

    }

    public function inbound(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'body' => 'required',
            'device_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->messages()], 400);
        }
        $device = auth()->user()->devices()->where('device_unique_id', $request->device_id)->first();

        if (!$device) {
            return response()->json(['message' => ["device" => "Invalid device"]], 400);
        }

        $inbox = MessageLog::where('customer_id',$device->customer_id)->where('type','inbox')->whereDate('created_at', \Carbon\Carbon::today())->count();
        $plan = CustomerPlan::where('customer_id',$device->customer_id)->first();
        if (($inbox) >= $plan->daily_receive_limit) {
            return response()->json(['message' => "Your have extended your daily received limit"]);
        }

        $from = $request->from;
        $body = $request->body;

        $message = new Message();
        $message->customer_id = auth()->id();
        $message->body = $body;
        $message->numbers = json_encode(['from' => $from, 'to' => [$device->id]]);
        $message->type = 'inbox';
        $message->save();

        auth()->user()->message_logs()->create(['to' => $device->id, 'status' => 'succeed', 'device_unique_id' => $device->device_unique_id, 'from' => $from, 'body' => $body, 'message_id' => $message->id, 'type' => 'inbox']);

        return response()->json(['message' => "Inbound stored successfully"]);

    }
}
