<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Http\Request;

class SentController extends Controller
{
    public function index(){
        $data['messages'] = $messages = auth('customer')->user()->sent_messages()->orderBy('created_at','desc')->paginate(10);
        $contactDevice = [];
        $deviceDetails = [];
        $deviceModel = [];
        foreach ($messages as $message){
            $contactDevice[]=$message->formatted_number_from;
        }
        $devices = Device::whereIn('id', $contactDevice)->get();

        foreach ($devices as $device){
            $deviceDetails[$device->id] = $device->name;
            $deviceModel[$device->id] = $device->model;
        }
        $data['device_model'] = $deviceModel;
        $data['device_name'] = $deviceDetails;
        return view('customer.smsbox.sent',$data);
    }
    public function move_trash(Request $request){
        $request->validate([
            'ids'=>'required'
        ]);
        $ids=explode(',', $request->ids);

        auth('customer')->user()->sent_messages()->whereIn('id',$ids)->delete();

        return back()->with('success', 'Message successfully moved to trash');

    }
}
