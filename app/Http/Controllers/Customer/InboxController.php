<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Device;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        $data['messages'] = $messages = auth('customer')->user()->receive_messages()->paginate(10);
        $contactNumbers = [];
        $contactDetails = [];
        $contactDevice = [];
        $deviceDetails = [];
        $deviceModel = [];
        foreach ($messages as $message){
            $contactNumbers[]=$message->formatted_number_from;
        }
        foreach ($messages as $message){
            $contactDevice[]=$message->formatted_number_to;
        }

        $devices = Device::whereIn('id', $contactDevice)->get();
        $contacts = Contact::whereIn('number', $contactNumbers)->get();

        foreach ($contacts as $contact){
            $contactDetails[$contact->number]=$contact->address;
        }
        foreach ($devices as $device){
            $deviceDetails[$device->id] = $device->name;
            $deviceModel[$device->id] = $device->model;
        }
        $data['device_model'] = $deviceModel;
        $data['device_name'] = $deviceDetails;
        $data['contact_address']= $contactDetails;
        return view('customer.smsbox.inbox', $data);
    }

    public function changeStatus(Request $request)
    {
        $message = auth('customer')->user()->receive_messages()->where('id', $request->id)->first();
        if (!$message) {
            return response()->json(['status' => 'fail', 'message' => 'Message not found']);
        }
        if ($request->status == 'read')
            $message->read = 'yes';
        elseif ($request->status == 'unread')
            $message->read = 'no';

        $message->save();
        return response()->json(['status' => 'success', 'message' => 'Message status changed successfully']);

    }

    public function move_trash(Request $request){
        $request->validate([
            'ids'=>'required'
        ]);
        $ids=explode(',', $request->ids);

        auth('customer')->user()->receive_messages()->whereIn('id',$ids)->delete();

        return back()->with('success', 'Message successfully moved to trash');

    }


}
