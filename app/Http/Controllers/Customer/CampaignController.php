<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\CampaignCreateJob;
use App\Models\Campaign;
use App\Models\Exception;
use App\Models\Message;
use App\Models\Number;
use App\Models\SmsQueue;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    public function index()
    {

        return view('customer.campaign.index');
    }

    public function report(Request $request)
    {
        $customer = auth('customer')->user();
        if ($request->campaign_id)
        $campaign = $customer->campaings()->where('id', $request->campaign_id)->firstOrFail();
        $messageLogs = $customer->message_logs()->select(['from', 'to', 'body','response_code', 'updated_at']);

        if ($request->campaign_id) {
            $messageLogs = $messageLogs->where('campaign_id', $campaign->id);
        }
        if ($request->response_code){
            $messageLogs = $messageLogs->where('response_code', $request->response_code);
        }
        if ($request->campaign_id) {
            $data['reports'] = $messageLogs->simplePaginate(20);
        }else{
            $data['reports']='';
        }
        $data['campaigns']=$customer->campaings;
        $data['requestData']=$request->only('campaign_id','response_code');
        return view('customer.campaign.report',$data);
    }

    public function getAll()
    {
        $campaings = auth('customer')->user()->campaings()->orderByDesc('id')->select(['id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'status', 'import_fail_message']);
        return datatables()->of($campaings)
            ->addColumn('title', function ($q) {
                $sent_sms = $q->sms_queue()->where('schedule_completed', 'yes')->count();
                return $q->title . '(' . $sent_sms . '/' . count($q->sms_queue) . ')';
            })
            ->addColumn('start_date', function ($q) {
                return $q->start_date->format('Y-m-d');
            })
            ->addColumn('end_date', function ($q) {
                return $q->end_date->format('Y-m-d');
            })
            ->addColumn('status', function ($q) {
                $endDate = Carbon::parse($q->end_date->toDateString() . ' ' . $q->end_time);
                $timeDiff = $endDate->diffInMinutes(now(), false);
                if ($timeDiff > 0) {
                    $complete_smsqueue = $q->sms_queue()->where('schedule_completed', 'yes')->count();
                    $smsqueue = $q->sms_queue()->count();
                    if ($complete_smsqueue==$smsqueue) {
                        return ' <button type="button" class="btn light btn-sm btn-primary">Completed</button>';
                    }else{
                        return ' <button type="button" class="btn light btn-sm btn-warning">Incompleted</button>';
                    }
                }

                if ($q->import_fail_message) {
                    return ' <button type="button" class="btn light btn-sm btn-danger">Import Failed</button> <br>' . $q->import_fail_message;
                }

                if ($q->status == 'running') {
                    return '  <button type="button" class="btn light btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Running
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to change this campaign status?" data-action=' . route('customer.campaign.status', ['id' => $q->id, 'status' => 'paused']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Pause
                                     </button>
                                </div>';
                } elseif ($q->status == 'failed') {
                    return '  <button type="button" class="btn light btn-sm btn-danger">Failed</button> <br>' . $q->import_fail_message;
                } elseif ($q->status == 'importing') {
                    return "<span> <i class=\"fas fa-spinner fa-pulse\"></i> importing</span>";
                } else {
                    return '<button type="button" class="btn light btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Pause
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to change this campaign status?" data-action=' . route('customer.campaign.status', ['id' => $q->id, 'status' => 'running']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Running
                                     </button>
                                </div>';
                }
            })->addColumn('action', function ($q) {
                return '<a href="'.route('customer.campaign.statistic',[$q->id]).'" target="_blank" class="btn light btn-sm btn-info mr-2">Statistic </a>'.'<button class="btn btn-sm btn-danger" data-message="Are you sure, you want to delete this campaign? <br> <br> <small>N.B: This will delete all messages including sent and queue related to this campaign.</small>"
                                        data-action=' . route('customer.campaign.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>';
            })->rawColumns(['title', 'action', 'status'])->toJson();
    }


    public function create()
    {
        $customer = auth('customer')->user();

        $data['templates'] = SmsTemplate::where('customer_id', $customer->id)->where('status','active')->get();
        $data['groups'] = $customer->groups()->withCount('contacts')->get();
        $data['users_from_devices']=$customer->activeDevices()->get();
        return view('customer.campaign.create', $data);
    }

    public function getTemplate(Request $request)
    {

        $customer = auth('customer')->user();
        $template = SmsTemplate::where('id', $request->template_id)->where('customer_id', $customer->id)->first();

        return response()->json(['status' => 'success', 'data' => $template->body]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'to_number' => 'required',
            'start_date' => 'required',
            'template_body' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'from_devices' => 'required|array',
        ]);

        $user = auth()->guard('customer')->user();
        $devices = $user->activeDevices($request->from_devices)->get();
        if ($devices->isEmpty()) {
            return back()->with('fail', 'Device has been removed or inactive');
        }
        $deviceUniqueIds=$devices->pluck('device_unique_id','id');

        $totalTo = array_map( 'trim',array_unique(preg_split('/,/', $request->to_number, -1, PREG_SPLIT_NO_EMPTY)));
        $onException=Exception::where('customer_id',auth('customer')->id())->whereIn('number',$totalTo)->pluck('number')->toArray();
        $to = array_diff($totalTo, $onException);
//dd($to);
        $current_plan = auth('customer')->user()->currentPlan();
        if (!$current_plan)
            return back()->with('fail', 'Customer doesn\'t have any plan right now');

        $deviceIds=$devices->pluck('id')->toArray();
        $customer = auth('customer')->user();
        $campaign = new Campaign();
        $campaign->title = $request->title;
        $campaign->customer_id = $customer->id;
        $campaign->device_ids =json_encode($deviceIds);
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
        $lastKey=end($from);
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
            $send_speed = floor($total_minute/$totalToNumbersCount);
            /*End*/

            //create new message
            $newMessage = new Message();
            $newMessage->customer_id = $customer->id;
            $newMessage->body =  json_encode($request->template_body);
            $newMessage->numbers = json_encode(['from' => $key, 'to' => $toNumbers,'device_unique_id'=>$deviceUniqueIds[$key]]);
            $newMessage->campaign_id = $campaign->id;
            $newMessage->type = 'sent';
            $newMessage->read = 'no';
            $newMessage->save();

            CampaignCreateJob::dispatch($key, $toNumbers, $campaign, $newMessage, $totalToNumbersCount,$totalFromNumbersCount, $difference_date, $startDate, $startTime, $send_speed, auth('customer')->user(),$lastKey);
        }

        return redirect()->route('customer.campaign.index')->with('success', trans('Campaign created successfully'));

    }


    public function destroy(Campaign $campaign)
    {
        if ($campaign->sms_queue) {
            $campaign->sms_queue()->delete();
        }
        if ($campaign->messages) {
            $campaign->messages()->delete();
        }
        $campaign->delete();

        return redirect()->route('customer.campaign.index')->with('success', 'Congratulations ! Campaign successfully deleted');
    }

    public function status(Request $request)
    {
        $request->validate([
            'status' => 'required|in:running,paused',
        ]);

        $customer = auth('customer')->user();
        $campaign = Campaign::where('customer_id', $customer->id)->where('id', $request->id)->firstOrFail();
        $campaign->status = $request->status;
        $campaign->save();

        SmsQueue::where('campaign_id', $campaign->id)->where('schedule_completed','no')->where('status', $request->status == 'paused' ? 'running' : 'paused')->update(['status' => $request->status]);

        return redirect()->route('customer.campaign.index')->with('success', 'Congratulations ! Campaign status updated');

    }
    public function statistic($id){
        $campaign = auth('customer')->user()->campaings()->where('id', $id)->firstOrFail();
        $data['messageRunningLogs'] = auth('customer')->user()->sms_queues()->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('schedule_completed','no')->where('status','running')->paginate(20, ['*'], 'running');
        $data['messagePausedLogs'] = auth('customer')->user()->sms_queues()->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('status','paused')->paginate(20, ['*'], 'paused');
        $data['messageFailedLogs'] = auth('customer')->user()->sms_queues()->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('schedule_completed','yes')->where('status','failed')->paginate(20, ['*'], 'failed');
        $data['messageDeliveredLogs'] = auth('customer')->user()->sms_queues()->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('status','!=','failed')->where('schedule_completed','yes')->whereColumn('created_at', '<', 'updated_at')->whereNull('response_code')->paginate(20, ['*'], 'delivered');

        return view('customer.campaign.statistic', $data);
    }
}
