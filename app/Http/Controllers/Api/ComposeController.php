<?php

namespace App\Http\Controllers\Api;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Number;
use App\Models\SenderId;
use App\Models\WhatsAppNumber;
use App\SmsProvider\SendSMS;
use App\WhatsAppProvider\SendMessageProcess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComposeController extends Controller
{
    public function getSenderIds()
    {
        $user = auth()->user();
        $senderIds = SenderId::where('customer_id', $user->id)->get();
        return response()->json(['status' => 'success', 'data' => $senderIds]);
    }

    public function sentCompose(Request $request)
    {
        $request->validate([
            'device_id' => 'required',
            'to_numbers' => 'required',
            'body' => 'required',
        ]);

        $user = auth()->user();
        $device = $user->activeDevices($request->device_id)->first();
        if (!$device) {
            return response()->json(['message' => 'Device has been removed or inactive'],400);
        }
        $toNumbers = explode(',', $request->to_numbers);
        $sent = auth()->user()->message_logs()->where('type','sent')->whereDate('created_at', Carbon::today())->count();
        $plan = $user->currentPlan();
        if (!$user->currentPlan) {
            return response()->json(['message' => 'Your don\'t have any active plan']);
        }

        if (($sent + count($toNumbers)) > $plan->daily_send_limit) {
            return response()->josn(['message' => 'Your have extended your daily send limit']);
        }
        $messageFiles = [];
        if ($request->mms_files) {
            foreach ($request->mms_files as $key => $file) {
                $messageFiles[] = $fileName = time() . $key . '.' . $file->extension();
                $file->move(public_path('uploads/'), $fileName);
            }
            $request['message_files'] = json_encode($messageFiles);
        }

        if (isset($request->isSchedule)) {
            $sd = Carbon::createFromTimeString($request->schedule);
            $request['schedule_datetime'] = $sd;
        } else {
            $request['schedule_datetime'] = now();
        }
        $allToNumbers = [];
        $allGroupIds = [];
        $allContactIds = [];

        foreach($toNumbers as $to_number){
            $allToNumbers[]=$to_number;
        }

        $allToNumbers = array_unique($allToNumbers);

        $request['to_numbers'] = $allToNumbers;
        $request['numbers'] = json_encode(['from' => $device->id, 'to' => $allToNumbers]);
        $request['type'] = 'sent';

        $current_plan = $plan;

        //subtracting one sms TODO:: will need to count text and sub that also calculate today send
        $pre_available_sms = $current_plan->daily_send_limit;
        $new_available_sms = $pre_available_sms - count($allToNumbers);

        //if not enough sms then return
        if ($new_available_sms < 0)
            return response()->json(['message' => 'Doesn\'t have enough sms. Please upgrade your plan'],400);

        DB::beginTransaction();
        try {
            $newMessage = auth()->user()->messages()->create($request->all());

            $sms_queue = [];
            foreach ($request->to_numbers as $to) {
                $newMessageFiles = null;
                if ($messageFiles) {
                    $newMessageFiles = $messageFiles;

                    array_walk($newMessageFiles, function (&$value, $index) {
                        $value = asset('uploads/' . $value);
                    });
                }
                $sms_queue[] = [
                    'message_id' => $newMessage->id,
                    'from' => $device->id,
                    'device_unique_id' => $device->device_unique_id,
                    'to' => $to,
                    'schedule_datetime' => $request->schedule_datetime,
                    'body' => $request->body,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'type' => 'sent',
                ];

            }

            auth()->user()->sms_queues()->createMany($sms_queue);
            auth()->user()->message_logs()->createMany($sms_queue);

            DB::commit();
            return response()->json(['message' => 'Message queued successfully']);

        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()],400);
        }
    }

}
