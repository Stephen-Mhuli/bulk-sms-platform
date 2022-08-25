<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\MessageLog;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function contacts(Request $request)
    {
        $page_no = $request->page??0;
        $no_of_data = 20;
        $offset = ($page_no * $no_of_data) - $no_of_data;

        $customer=auth()->user();
        $contact = Contact::select('first_name','last_name','number', 'email', 'city', 'state','zip_code', 'note', 'address', 'company','created_at', 'updated_at')->offset($offset)->limit($no_of_data)->get();

        $data=[
            'contacts'  =>$contact,
        ];
        return response()->json(['status'=>'success', 'data'=>$data]);

    }


    public function numberDetails(Request $request)
    {
        $page_no = $request->page??0;
        $no_of_data = 20;
        $offset = ($page_no * $no_of_data) - $no_of_data;

        $customer=auth()->user();
        $request['number']='+'.str_replace('+','',$request->number);
        $contact = Contact::select('first_name','last_name','number', 'email', 'city', 'state','zip_code', 'note', 'address', 'company','created_at', 'updated_at')->where('number', $request->number)->first();
        if (!$contact) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid number']);
        }
        $message_logs = MessageLog::select('to','from','body','type','status', 'created_at', 'updated_at')->where('customer_id', $customer->id)
        ->where('to', $contact->number)->orWhere('from', $contact->number)->orderByDesc('updated_at')->offset($offset)->limit($no_of_data)->get();

        $data=[
            'contact'  =>$contact,
            'messages'  =>$message_logs,
            'more'=>count($message_logs)>=$no_of_data
        ];
        return response()->json(['status'=>'success', 'data'=>$data]);

    }
}
