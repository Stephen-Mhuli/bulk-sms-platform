<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingRequest;
use App\Models\Customer;
use App\Models\Number;
use App\Models\NumberRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NumberController extends Controller
{
    public function index(){
        return view('admin.numbers.index');
    }


    public function getAll(){
        $customers=auth()->user()->numbers()->select(['id','number','from','purch_price','sell_price','status','created_at']);
        return datatables()->of($customers)
            ->addColumn('created_at',function($q){
                return $q->created_at->format('d-m-Y');
            })
            ->addColumn('action',function($q){
                return "<a class='btn btn-sm btn-info' href='".route('admin.numbers.edit',[$q->id])."'>Edit</a> &nbsp; &nbsp;".
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this number?"
                                        data-action='.route('admin.numbers.destroy',[$q]).'
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm">Delete</button>' ;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function create(){
        return view('admin.numbers.create');
    }

    public function store(Request $request){
        $request->validate([
            'number'=>'required|unique:numbers|regex:/^[0-9\-\+]{9,15}$/',
            'from'=>'required|in:signalwire,twilio,nexmo,telnyx,plivo,africastalking,nrs,message_bird,infobip,cheapglobalsms,plivo_powerpack,easysendsms,twilio_copilot,twilio_copilot,bulksms,ones_two_u,clickatel,route_mobile,hutch',
            'purch_price'=>'required|numeric',
            'sell_price'=>'required|numeric',
            'status'=>'required|in:active,inactive'
        ]);

        auth()->user()->numbers()->create($request->all());

        return back()->with('success','Number successfully created');
    }

    public function edit(Number $number){
        $data['number']=$number;
        return view('admin.numbers.edit',$data);
    }

    public function update(Number $number,Request $request){
        $request->validate([
            'purch_price'=>'required|numeric',
            'sell_price'=>'required|numeric',
            'status'=>'required|in:active,inactive'
        ]);

       $valid_data=$request->only('purch_price','sell_price','status');

        //update the model
        $number->update($valid_data);

        return back()->with('success','Number successfully updated');
    }

    public function requests(){
        return view('admin.numbers.requests');
    }

    public function get_requests()
    {
        $requests=auth()->user()->number_requests;

        return datatables()->of($requests)
            ->addColumn('customer',function($q){
                return "<a href='" . route('admin.customers.edit', [$q->customer_id]) . "'>".$q->customer->full_name."</a>";
            })
            ->addColumn('number',function($q){
                return $q->number->number;
            })
            ->addColumn('from',function($q){
                return $q->number->from;
            })
            ->addColumn('purch_price',function($q){
                return $q->number->purch_price;
            })
            ->addColumn('sell_price',function($q){
                return $q->number->sell_price;
            })
            ->addColumn('status',function($q){
                return $q->status;
            })
            ->addColumn('action',function(NumberRequest $q){
                return '<button class="mr-1 btn btn-sm btn-info" data-message="Are you sure you want to add <b>\''.$q->number->number.'\'</b> to \''.$q->customer->full_name.'\' ?"
                                        data-action='.route('admin.number.requests.response').'
                                        data-input={"id":"'.$q->id.'","status":"accepted"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Approve</button>'.
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to reject the request ?"
                                        data-action='.route('admin.number.requests.response').'
                                        data-input={"id":"'.$q->id.'","status":"rejected"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Reject</button>';
            })
            ->rawColumns(['action','customer'])
            ->toJson();
    }

    public function request_response(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'status' => 'required|in:accepted,rejected',
        ]);
        $number_request = auth()->user()->number_requests()->where('id', $request->id)->first();
        if (!$number_request) return back()->with('fail', 'Request not found');

        if($number_request->status!='pending') return back()->with('fail', 'Request is not pending');

        $customer = auth()->user()->customers()->where('id', $number_request->customer_id)->first();
        if (!$customer) return back()->with('fail', 'Customer not found');

        $number = Number::find($number_request->number_id);
        if (!$number) return back()->with('fail', 'Number not found');

        $isAssigned = $customer->numbers()->where('number_id', $number->id)->first();
        if ($isAssigned) return back()->with('fail', 'Number already assigned to this customer');

        $number_request->status=$request->status;
        $number_request->save();

        if($request->status=='rejected') return back()->with('success', 'Request successfully rejected for the customer');

        $time = Carbon::now()->addMonths(1);
        $customer->numbers()->create(['number_id' => $number->id, 'number' => $number->number,'expire_date' => $time, 'cost' => $number->sell_price]);

        return back()->with('success', 'Number successfully added to the customer');
    }

    public function destroy(Number $number){
       if($number->customer_numbers->isNotEmpty()){
          return back()->with('fail','Sorry number is already in used');
       }
       $number->delete();
       return back()->with('success','Number successfully deleted');
    }

}
