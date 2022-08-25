<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BillingRequest;
use App\Models\CustomerPlan;
use App\Models\Number;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;


class BillingController extends Controller
{
    public function change_billing(){
        $data['plans']=Plan::where('status','active')->get();
        $data['customer_plan'] = auth('customer')->user()->currentPlan();
        return view('customer.billings.billing_change',$data);
    }
    public function index(){
        $data['customer_plan'] = auth('customer')->user()->currentPlan();
        $customer = auth('customer')->user();
        $remaincontact = $customer->contacts()->count();
        $remaindevice = $customer->devices()->count();
        $remainDailySent = $customer->message_logs()->where('type','sent')->where('status','!=','failed')->whereDate('created_at', now())->count();
        $remainDailyReceive = $customer->message_logs()->where('type','inbox')->whereDate('created_at',now())->count();
        $data['remaincontact'] = $remaincontact;
        $data['remaindevice'] = $remaindevice;
        $data['remainDailySent'] = $remainDailySent;
        $data['remainDailyReceive'] = $remainDailyReceive;
        $data['customerPlans'] = CustomerPlan::where('customer_id',$customer->id)->orderBy('id', 'DESC')->get();
        $requestPlan = $customer->billing_requests()->where('status','pending')->first();
        $data['renewDate'] = $customerPlanPending = $customer->currentPlan()->where('status','pending')->first();
        if (isset($requestPlan->plan_id) && isset($customerPlanPending->plan_id) && $requestPlan->plan_id==$customerPlanPending->plan_id){
            $data['paymentPlan'] = true;
        }
        return view('customer.billings.index',$data);
    }

    public function update(Request $request){
        $request->validate([
            'id'=>'required|exists:plans'
        ]);
        $plan=Plan::find($request->id);
        if(!$plan){
            return redirect()->back()->with('fail','You plan not found');
        }
        $pre_plan=auth('customer')->user()->plan;
        if(isset($pre_plan) && $pre_plan->plan_id==$request->id){
            return redirect()->back()->with('fail','You are already subscribed to this plan');
        }
        $customer=auth('customer')->user();
        $preBilling=BillingRequest::where(['customer_id'=>$customer->id,'status'=>'pending'])->first();
        if($preBilling){
            return redirect()->back()->with('fail','You already have a pending request. Please wait for the admin reply.');
        }
        $planReq=new BillingRequest();
        $planReq->admin_id=$plan->admin_id;
        $planReq->customer_id=$customer->id;
        $planReq->plan_id=$plan->id;
        $planReq->save();

        // TODO:: send email to customer here

        return redirect()->back()->with('success','We have received your request. We Will contact with you shortly');
    }
    public function pending_plan_submit_form(Request $request){
        $data['plan'] = Plan::find($request->id);
        return view('customer.default_plan_submit_form',$data);
    }
    public function pending_plan(Request $request){
        $user = auth('customer')->user();
        $pendingPlan = CustomerPlan::where('customer_id',$user->id)->where('status','pending')->first();
        if (isset($pendingPlan->plan_id) && $pendingPlan->plan_id != $request->plan_id){
            $data['pendingPlan'] = $pendingPlan;
            $data['requestPlanId'] = $request->plan_id;
            return response()->json(['status' => 'success', 'message' => 'success','data' => $data]);
        }else{
            return response()->json(['status' => 'failed', 'message' => 'failed']);
        }
    }

}
