<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPlan;
use App\Models\Message;
use App\Models\MessageLog;
use App\Models\SmsQueue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){

        $user =auth()->user();
        $customers=$user->customers;
        $customer_ids=[];
        foreach ($customers as $key=>$customer){
            $customer_ids[]=$customer->id;
        }
        $inboxes=MessageLog::whereIn('customer_id',$customer_ids)->where('type','inbox')->get();
        $sent=MessageLog::whereIn('customer_id',$customer_ids)->where('type','sent')->get();


        $data['newMessageCount'] = $inboxes->where('created_at', '>=', Carbon::now())->count();
        $data['newSentCount'] = $sent->where('created_at', '>=', Carbon::now())->count();

        $data['totalInbox'] = $inboxes->count();
        $data['totalSent'] = $sent->count();



        $cuatomers = Customer::select(DB::Raw('count(*) as count'),DB::Raw('MONTH(created_at) month'))
            ->groupBy('month')->get()
            ->pluck('count','month');

        $months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $data['months']=$months;

        $chatCuatomers=[];
        for ($i = 1; $i <= 12; $i++) {
            $chatCuatomers[]=isset($cuatomers[trim($i, '"')])?$cuatomers[trim($i, '"')]:0;
        }
        $data['chart_customers']=$chatCuatomers;

        $amount = CustomerPlan::select(DB::Raw("SUM(price) as total_debit"),DB::raw('MONTH(created_at) month'))
            ->groupBy('month')->get()
            ->pluck('total_debit','month');
        $chat_amount=[];
        for ($i = 1; $i <= 12; $i++) {
            $chat_amount[]=isset($amount[trim($i, '"')])?$amount[trim($i, '"')]:0;
        }
        $data['chat_amount']=$chat_amount;
        return view('admin.dashboard',$data);
    }

    public function setLocale($type)
    {
        $availableLang = get_available_languages();

        if (!in_array($type, $availableLang)) abort(400);

        session()->put('locale', $type);

        return redirect()->back()->with('success', 'Language successfully changes');
    }
}
