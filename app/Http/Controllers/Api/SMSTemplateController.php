<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class SMSTemplateController extends Controller
{
    public function index(Request $request){

        $user= auth()->user();
        $template =SmsTemplate::select('title', 'status', 'body', 'created_at')->where('customer_id', $user->id)->where('id', $request->id)->firstOrFail();

        return response()->json(['status'=>'success', 'data'=>$template]);
    }
}
