<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingRequest;
use App\Models\Customer;
use App\Models\CustomerPlan;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return view('admin.plans.index');
    }

    public function getAll()
    {

        $customers = auth()->user()->plans()->select(['id', 'title', 'sms_limit', 'price', 'status','contact_limit', 'created_at','device_limit','daily_receive_limit', 'daily_send_limit','recurring_type']);
        return datatables()->of($customers)
            ->addColumn('created_at', function ($q) {
                return $q->created_at->format('d-m-Y');
            })
            ->addColumn('price', function ($q) {
                return formatNumberWithCurrSymbol($q->price);
            })
            ->addColumn('action', function (Plan $q) {
                return "<a class='btn btn-sm btn-info' data-toggle='tooltip' data-placement='top' title='Edit' href='" . route('admin.plans.edit', [$q->id]) . "'>"."<i class='fas fa-edit'></i>"."</a>";
            })
            ->addColumn('status', function ($q) {
                if ($q->status == 'Active'){
                    return '<span class="pl-2 pr-2 pt-1 pb-1 bg-success" style="border-radius:25px;">'.$q->status.'</span>';
                }else {
                    return '<span class="pl-2 pr-2 pt-1 pb-1 bg-danger" style="border-radius:25px;">'.$q->status.'</span>';
                }
            })
            ->rawColumns(['status','action','price'])
            ->toJson();
    }


    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:plans',
            'price' => 'required|numeric',
            'sms_limit' => 'required|numeric',
            'contact_limit' => 'required|numeric',
            'device_limit' => 'required|numeric',
            'daily_receive_limit' => 'required|numeric',
            'daily_send_limit' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'recurring_type' => 'required|in:weekly,monthly,yearly'
        ]);

        auth()->user()->plans()->create($request->all());

        return back()->with('success', 'Plan successfully created');
    }

    public function edit(Plan $plan)
    {
        $data['plan'] = $plan;
        return view('admin.plans.edit', $data);
    }

    public function update(Plan $plan, Request $request)
    {
        $request->validate([
            'title' => 'required|unique:plans,title,' . $plan->id,
            'price' => 'required|numeric',
            'sms_limit' => 'required|numeric',
            'contact_limit' => 'required|numeric',
            'device_limit' => 'required|numeric',
            'daily_receive_limit' => 'required|numeric',
            'daily_send_limit' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'recurring_type' => 'required|in:onetime,weekly,monthly,yearly'
        ]);

        $valid_data = $request->only('title', 'sms_limit', 'price', 'status','contact_limit','device_limit','daily_receive_limit', 'daily_send_limit','recurring_type');

        //update the model
        $plan->update($valid_data);

        return back()->with('success', 'Plan successfully updated');
    }

    public function requests()
    {
        return view('admin.plans.requests');
    }

    public function get_requests()
    {

        $requests = auth()->user()->plan_requests;
        return datatables()->of($requests)
            ->addColumn('title', function (BillingRequest $q) {
                return $q->plan->title;
            })
            ->addColumn('price', function (BillingRequest $q) {
                return formatNumberWithCurrSymbol($q->plan->price);
            })
            ->addColumn('transaction_id', function (BillingRequest $q) {
                return $q->transaction_id;
            })
            ->addColumn('other_info', function (BillingRequest $q) {
                if ($q->other_info) {
                    $array = (array)json_decode($q->other_info);
                    $obj = json_encode(array_combine(array_map("ucfirst", array_keys($array)), array_values($array)));
                } else
                    $obj = "";
                return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" . str_replace(['_', '"', "{", "}"], [' ', ' ', '', ''], $obj) . "</div>";
            })
            ->addColumn('status', function (BillingRequest $q) {
                return $q->status;
            })
            ->addColumn('action', function (BillingRequest $q) {
                return '<button class="mr-1 btn btn-sm btn-info" data-message="Are you sure you want to assign <b>\'' . $q->plan->title . '\'</b> to \'' . $q->customer->full_name . '\' ?"
                                        data-action=' . route('admin.customer.plan.change') . '
                                        data-input={"id":"' . $q->plan_id . '","customer_id":"' . $q->customer_id . '","from":"request","billing_id":"' . $q->id . '","status":"accepted"}
                                        data-toggle="modal" data-target="#modal-confirm"  ><i class="fas fa-check-square"></i></button>' .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to reject <b>\'' . $q->plan->title . '\'</b> for \'' . $q->customer->full_name . '\' ?"
                                        data-action=' . route('admin.customer.plan.change') . '
                                        data-input={"id":"' . $q->plan_id . '","customer_id":"' . $q->customer_id . '","from":"request","billing_id":"' . $q->id . '","status":"rejected"}
                                        data-toggle="modal" data-target="#modal-confirm"  ><i class="fas fa-times-circle"></i></button>';
            })
            ->addColumn('customer', function (BillingRequest $q) {
                return "<a href='" . route('admin.customers.edit', [$q->customer_id]) . "'>" . $q->customer->full_name . "</a>";
            })
            ->rawColumns(['action', 'customer', 'other_info'])
            ->toJson();
    }

}
