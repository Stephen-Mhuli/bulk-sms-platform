<?php

namespace App\Http\Controllers\Admin;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\AuthorizationToken;
use App\Models\BillingRequest;
use App\Models\Customer;
use App\Models\CustomerPlan;
use App\Models\CustomerSettings;
use App\Models\Label;
use App\Models\Number;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index');
    }

    public function getAll()
    {
        $customers = Customer::select(['id', 'first_name', 'last_name', 'email', 'status', 'created_at']);

        return datatables()->of($customers)
            ->addColumn('full_name', function ($q) {
                return $q->full_name;
            })
            ->addColumn('action', function (Customer $q) {
                return "<a class='btn btn-sm btn-info' data-toggle='tooltip' data-placement='top' title='Edit' href='" . route('admin.customers.edit', [$q->id]) . "'>"."<i class='fas fa-edit'></i>"."</a>  &nbsp; &nbsp;".
                    '<button class="btn btn-sm btn-primary tt" data-message="You will be logged in as customer?"
                                        data-action='.route('admin.customer.login.ass').'
                                        data-input={"id":'.$q->id.'}
                                        data-toggle="modal" data-target="#modal-confirm" data-toggle="tooltip" data-placement="top" title="Login as"><i class="fas fa-sign-in-alt"></i></button>' ;
            })
            ->addColumn('status', function ($q) {
                if ($q->status == 'Active'){
                    return '<span class="pl-2 pr-2 pt-1 pb-1 bg-success" style="border-radius:25px;">'.$q->status.'</span>';
                }else {
                    return '<span class="pl-2 pr-2 pt-1 pb-1 bg-danger" style="border-radius:25px;">'.$q->status.'</span>';
                }
            })
            ->rawColumns(['status','action'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:customers',
            'password' => 'required',
            'status' => 'required'
        ]);

        $request['email_verified_at']=now();

        $customer=auth()->user()->customers()->create($request->all());

        $access_token= $customer->createToken($customer->email)->plainTextToken;
        $preToken = AuthorizationToken::where('customer_id', $customer->id)->first();
        $authorization = isset($preToken) ? $preToken : new AuthorizationToken();
        $authorization->access_token = $access_token;
        $authorization->customer_id=$customer->id;
        $authorization->refresh_token = $access_token;
        $authorization->save();

        $setting= new CustomerSettings();
        $setting->customer_id = $customer->id;
        $setting->name = 'email_notification';
        $setting->value = 'false';
        $setting->save();

        $label = new Label();
        $label->title='new';
        $label->customer_id=$customer->id;
        $label->color='red';
        $label->status='active';
        $label->save();

        //Assigning plan to customer
        $plan = Plan::first();
        $customer->plans()->create(['plan_id' => $plan->id,
            'sms_limit' => $plan->sms_limit,
            'contact_limit' => $plan->contact_limit,
            'daily_send_limit' => $plan->daily_send_limit,
            'daily_receive_limit' => $plan->daily_receive_limit,
            'device_limit' => $plan->device_limit,
            'is_current' => 'yes',
            'status' => 'accepted',
            'price' => $plan->price]);

        return back()->with('success', 'Customer successfully created');
    }

    public function edit(Customer $customer)
    {
        $data['customer'] = $customer;
        $data['currentPlan'] = $customer->currentPlan();
        $data['availableNumbers'] = auth()->user()->available_numbers;
        $data['activePlans'] = auth()->user()->active_plans;
        return view('admin.customers.edit', $data);
    }

    public function update(Customer $customer, Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:customers,email,' . $customer->id,
            'status' => 'required'
        ]);

        //Check for password availability
        if (!$request->password) unset($request['password']);

        //update the model
        $customer->update($request->all());

        return back()->with('success', 'Customer successfully updated');
    }

    public function assignNumber(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = auth()->user()->customers()->where('id', $request->customer_id)->first();
        if (!$customer) return back()->with('fail', 'Customer not found');

        $number = Number::find($request->id);
        if (!$number) return back()->with('fail', 'Number not found');

        $isAssigned = $customer->numbers()->where('number_id', $number->id)->first();
        if ($isAssigned) return back()->with('fail', 'Number already assigned to this customer');

        $time = Carbon::now()->addMonths(1);

        $customer->numbers()->create(['number_id' => $number->id, 'number' => $number->number, 'expire_date' => $time, 'cost' => $number->sell_price]);
        return back()->with('success', 'Number successfully added to the customer');
    }

    public function removeNumber(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = auth()->user()->customers()->where('id', $request->customer_id)->first();
        if (!$customer) return back()->with('fail', 'Customer not found');

        $number = Number::find($request->id);
        if (!$number) return back()->with('fail', 'Number not found');

        $isAssigned = $customer->numbers()->where('number_id', $number->id)->first();
        if (!$isAssigned) return back()->with('fail', 'Number haven\'t assigned to this customer');

        $isAssigned->delete();

        return back()->with('success', 'Number successfully removed from the customer');
    }

    public function changePlan(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'customer_id' => 'required',
        ]);

        $customer = auth()->user()->customers()->where('id', $request->customer_id)->first();
        if (!$customer) return back()->with('fail', 'Customer not found');

        $plan = Plan::find($request->id);
        if (!$plan) return back()->with('fail', 'Plan not found');
        $date = Carbon::now();
        $currentPlan = $customer->currentPlan();
        $pendingPlan = CustomerPlan::where('customer_id',$customer->id)->where('status','pending')->first();

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

        $pre_plan = $customer->currentPlan();
        $isAssigned = $pre_plan->plan_id == $plan->id;
        if ($isAssigned && !isset($renew_date)) return back()->with('fail', 'This Plan is already assigned to this customer');

        if (isset($request->from)) {

            if ($request->from == 'request' && $request->billing_id && in_array($request->status, ['accepted', 'rejected'])) {
                $billingRequest = BillingRequest::find($request->billing_id);
                if (!$billingRequest)
                    return back()->with('fail', 'Billing request not found');
                $billingRequest->delete();

            } else
                return back()->with('fail', 'Invalid data for billing request');
        }

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
            if ($plan->recurring_type == 'weekly'){
                $renewDate = $currentPlan->renew_date->addDay(7);
            }elseif ($plan->recurring_type == 'monthly'){
                $renewDate = $currentPlan->renew_date->addMonth(1);
            }elseif ($plan->recurring_type == 'yearly'){
                $renewDate = $currentPlan->renew_date->addMonth(12);
            }
            $renew  = $renewDate;
        }else{
            $renew  = $renewDate;
        }

        $emailTemplate = get_email_template('plan_accepted');
        if ($request->status=='accepted' &&  $emailTemplate) {
            $regTemp = str_replace('{customer_name}', $customer->first_name.' '.$customer->last_name, $emailTemplate->body);
            SendMail::dispatch($customer->email, $emailTemplate->subject, $regTemp);
        }

        //delete previous plan
        //TODO: suggestion: might need to change plan status in future without deleting plan
        if ($pre_plan && $request->status == 'accepted') {
            $customer->plans()->update(['is_current' => 'no']);
        }

        if (isset($renew_date) || $pendingPlan){
            if ($request->status == 'rejected'){
                $pendingPlan->renew_date = null;
                $pendingPlan->is_current ='no';
                $pendingPlan->payment_status = 'unpaid';
                $pendingPlan->status = 'rejected';
                $pendingPlan->save();
            }else{
                $pendingPlan->renew_date = $renew;
                $pendingPlan->is_current ='yes';
                $pendingPlan->payment_status = 'paid';
                $pendingPlan->status = 'accepted';
                $pendingPlan->save();
            }

        }else{
            $customer->plans()->create(['plan_id' => $plan->id, 'sms_limit' => $plan->sms_limit,'price' => $plan->price,'contact_limit' => $plan->contact_limit,'device_limit' => $plan->device_limit,'daily_receive_limit' => $plan->daily_receive_limit,'daily_send_limit' => $plan->daily_send_limit,'is_current' => 'yes','payment_status' => 'paid','status' => 'accepted','renew_date'=>$renew,'recurring_type'=>$plan->recurring_type]);
        }

        // TODO:: send email here


        return back()->with('success', 'Plan successfully updated for the customer');
    }

    public function loginAs(Request $request){
        if(!$request->id) abort(404);
        auth('customer')->loginUsingId($request->id);
        return redirect()->route('customer.dashboard')->with('success',trans('You are now logged as customer'));
    }

}
