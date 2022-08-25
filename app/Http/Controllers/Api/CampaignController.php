<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CampaignCreateJob;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\Exception;
use App\Models\Message;
use App\Models\Number;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $customer = auth()->user();

        $campaigns = Campaign::select(['id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'status', 'import_fail_message'])
            ->where('customer_id', $customer->id)->get();

        return response()->json(['status' => 'success', 'data' => $campaigns]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'to_number' => 'required',
            'start_date' => 'required',
            'template_body' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'from_devices' => 'required|array',
        ]);
        $customer = auth()->user();

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->messages()], 404);
        }
        DB::beginTransaction();
        try {
            $user = auth()->user();

            $devices = $user->activeDevices($request->from_devices)->get();
            if ($devices->isEmpty()) {
                return response()->json(['status' => 'failed', 'message' => trans('Device has been removed or inactive')]);
            }
            $deviceUniqueIds = $devices->pluck('device_unique_id', 'id');

            $totalTo = array_map('trim', array_unique(preg_split('/,/', $request->to_number, -1, PREG_SPLIT_NO_EMPTY)));
            $onException = Exception::where('customer_id', auth()->user()->id)->whereIn('number', $totalTo)->pluck('number')->toArray();
            $to = array_diff($totalTo, $onException);
//dd($to);
            $current_plan = auth()->user()->currentPlan();
            if (!$current_plan)
                return response()->json(['status' => 'failed', 'message' => trans('Customer doesn\'t have any plan right now')]);

            $request['send_speed']='1';

            $deviceIds = $devices->pluck('id')->toArray();
            $customer = auth()->user();
            $campaign = new Campaign();
            $campaign->title = $request->title;
            $campaign->customer_id = $customer->id;
            $campaign->device_ids = json_encode($deviceIds);
            $campaign->from_devices = json_encode($devices->pluck('device_unique_id')->toArray());
            $campaign->to_number = json_encode($to);
            $campaign->start_date = $request->start_date;
            $campaign->end_date = $request->end_date;
            $campaign->start_time = $request->start_time;
            $campaign->end_time = $request->end_time;
            $campaign->template_id = $request->template_id;
            $campaign->message_body = json_encode($request->template_body);
            $campaign->message_send_rate = $request->send_speed;
            $campaign->status = 'importing';
            $campaign->save();
            $from = $deviceIds;


            $totalToNumbersCount = 0;
            $totalFromNumbersCount = count($from);
            $generatedToNumbers = [];
            $lastKey = end($from);
            for ($i = 0; $i < count($to); $i += count($from)) {
                for ($j = 0; $j < count($from); $j++) {
                    if (isset($to[$i + $j])) {
                        $generatedToNumbers[$from[$j]][] = trim($to[$i + $j]);
                        $totalToNumbersCount++;
                    }
                }
            }


            foreach ($generatedToNumbers as $key => $toNumbers) {
                /*Start*/
                $startDate = (new Carbon($request->start_date))->subDay();
                $endDate = new Carbon($request->end_date);
                $startTime = new Carbon($request->start_time);
                $endTime = new Carbon($request->end_time);
                $difference_time = $startTime->diffInSeconds($endTime);
                $difference_date = $startDate->diffInDays($endDate);
                $total_minute = $difference_time * $difference_date;
                $send_speed = floor($total_minute / $totalToNumbersCount);
                /*End*/

                //create new message
                $newMessage = new Message();
                $newMessage->customer_id = $customer->id;
                $newMessage->body = json_encode($request->template_body);
                $newMessage->numbers = json_encode(['from' => $key, 'to' => $toNumbers, 'device_unique_id' => $deviceUniqueIds[$key]]);
                $newMessage->campaign_id = $campaign->id;
                $newMessage->type = 'sent';
                $newMessage->read = 'no';
                $newMessage->save();

                CampaignCreateJob::dispatch($key, $toNumbers, $campaign, $newMessage, $totalToNumbersCount, $totalFromNumbersCount, $difference_date, $startDate, $startTime, $send_speed, auth()->user(), $lastKey);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => trans('Campaign created successfully')]);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function statistic($id)
    {
        $customer = auth()->user();
        $campaign = $customer->campaings()->where('id', $id)->firstOrFail();
        $runningMessageLogs = $customer->sms_queues()->select('body', 'from', 'to', 'delivered_at', 'schedule_completed', 'status')->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('schedule_completed', 'no')->where('status', 'running')->get();
        $pausedMessageLogs = $customer->sms_queues()->select('body', 'from', 'to', 'delivered_at', 'schedule_completed', 'status')->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('status', 'paused')->get();
        $failedMessageLogs = $customer->sms_queues()->select('body', 'from', 'to', 'delivered_at', 'schedule_completed', 'status')->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('schedule_completed', 'yes')->where('status', 'failed')->get();
        $deliveredMessageLogs = $customer->sms_queues()->select('body', 'from', 'to', 'delivered_at', 'schedule_completed', 'status')->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('status', '!=', 'failed')->where('schedule_completed', 'yes')->whereColumn('created_at', '<', 'updated_at')->whereNull('response_code')->get();

        $data = [
            'running_message_logs' => $runningMessageLogs,
            'paused_message_logs' => $pausedMessageLogs,
            'failed_message_logs' => $failedMessageLogs,
            'delivered_message_logs' => $deliveredMessageLogs,
        ];

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function getTemplate(){
        $customer=auth()->user();
       $smsTemplate= SmsTemplate::where('customer_id', $customer->id)->where('status','active')->get();

       return response()->json(['status'=>'success', 'data'=>$smsTemplate]);
    }

}
