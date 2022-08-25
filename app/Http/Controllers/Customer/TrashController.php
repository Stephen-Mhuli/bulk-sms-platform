<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index(){
        $data['trashes']=auth('customer')->user()->messages()->onlyTrashed()->get();
        return view('customer.smsbox.trash',$data);
    }
    public function remove_trash(Request $request){
        $request->validate([
            'ids'=>'required'
        ]);
        $ids = explode(',', $request->ids);


        auth('customer')->user()->messages()->whereIn('id',$ids)->forceDelete();
        auth('customer')->user()->message_logs()->whereIn('message_id',$ids)->delete();
        auth('customer')->user()->sms_queues()->whereIn('message_id',$ids)->delete();

        return back()->with('success', 'Message removed from the trash successfully');

    }
}
