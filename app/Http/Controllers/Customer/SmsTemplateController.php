<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $user = auth()->user('customer');

        $template = isset($request->id) ? SmsTemplate::find($request->id) : new SmsTemplate();
        $template->customer_id = $user->id;
        $template->title = $request->title;
        $template->status = $request->status;
        $template->body = $request->body;
        $template->save();

        return redirect()->back()->with('success', trans('customer.messages.template_added'));
    }

    public function delete(Request $request){

        $customer = auth()->user('customer');
        $template=SmsTemplate::where('customer_id',$customer->id)->where('id',$request->id)->firstOrFail();
        $template->delete();
        return redirect()->back()->with('success', trans('customer.messages.template_delete'));
    }
}
