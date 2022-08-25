<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


    public function index()
    {
        $user = auth()->guard('customer')->user();
        $data['newMessageCount'] = $user->receive_messages()->where('created_at', '>=', Carbon::now())->count();

        $data['inboxCount'] = $user->message_logs()->where('type', 'inbox')->count();
        $data['smsDeliveredCount'] = $user->message_logs()->where('type', 'sent')->whereStatus('succeed')->count();
        $data['sentFail'] = $user->message_logs()->where('status', 'failed')->count();
        $data['sentPending'] = $user->sms_queues()->where('status', 'fetched')->count();
        $data['sentQueued'] = $user->sms_queues()->where('status', 'running')->count();
        $data['device_added'] = $user->devices()->count();
        $data['total_contact'] = $user->contacts()->count();
        $data['total_group'] = $user->groups()->count();

        $inboxes = $user->receive_messages()
            ->select(DB::Raw('count(*) as count'),DB::Raw('DATE(created_at) day'))
            ->where('created_at', '>', Carbon::now()->startOfWeek())
            ->groupBy('day')->get()
            ->pluck('count','day');
        $weekDates=[];
        foreach (getLastNDays(7) as $day){
            $day=Carbon::createFromTimeString(str_replace('"','',$day." 0:00:00"));
            $weekDates[]= $day->format('m-d-Y');
        }

        $data['weekDates']=$weekDates;
        $chatInboxes=[];
        foreach (getLastNDays(7) as $day){
            $chatInboxes[]=isset($inboxes[trim($day, '"')])?$inboxes[trim($day, '"')]:0;
        }
        $data['chart_inbox']=$chatInboxes;
        $data['todayExpense']= auth('customer')->user()->expenses()->whereDate('created_at', now())->sum('cost');
        $data['weeklyExpense']= auth('customer')->user()->expenses()->whereDate('created_at','>', Carbon::now()->startOfWeek())->sum('cost');
        $data['totalExpense']= auth('customer')->user()->expenses()->sum('cost');
        $weeklySent = $user->message_logs()
            ->select(DB::raw("COUNT(*) as count"),DB::Raw('DATE(updated_at) day'))
            ->where('updated_at', '>', Carbon::now()->startOfWeek())
            ->where('type','sent')
            ->groupBy('day')->pluck('count','day');
        $weeklyReceived = $user->message_logs()
            ->select(DB::raw("COUNT(*) as count"),DB::Raw('DATE(created_at) day'))
            ->where('created_at', '>', Carbon::now()->startOfWeek())
            ->where('type','inbox')
            ->groupBy('day')->pluck('count','day');

        $weeklyResponseArray=[];
        foreach (getLastNDays(7) as $day){
            $day=trim($day,'"');
            if(isset($weeklySent[$day]) && isset($weeklyReceived[$day]) && $weeklyReceived[$day]>0 ){
                $weeklyResponseArray[]= round(($weeklyReceived[$day]/$weeklySent[$day] ) * 100, 2);
            }else{
                $weeklyResponseArray[]=0;
            }
        }
        $data['weeklyResponseArray']=$weeklyResponseArray;
        $data['add_device']= $user->devices()->first();
        $data['sending_settings']= $user ->settings()->where('name','sending_settings')->first();
        $data['sms_send'] = $user->message_logs()->where('type', 'sent')->first();
        return view('customer.dashboard', $data);
    }
}
