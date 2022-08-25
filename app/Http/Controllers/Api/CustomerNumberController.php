<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerNumber;
use App\Models\CustomerWhatsAppNumber;
use Illuminate\Http\Request;

class CustomerNumberController extends Controller
{
    public function index(Request $request)
    {
        $customerNumber='';
        $user = auth()->user();
        if ($request->type == 'number') {
            $customerNumber = CustomerNumber::select('id', 'number', 'forward_to_dial_code', 'forward_to', 'cost', 'cost', 'created_at', 'expire_date', 'webhook_url', 'webhook_method')->where('customer_id', $user->id)->get();
        }
        if ($request->type == 'whatsapp') {
            $customerNumber = CustomerWhatsAppNumber::select('id', 'number', 'forward_to_dial_code', 'forward_to', 'cost', 'cost', 'created_at', 'expire_date')->where('customer_id', $user->id)->get();
        }

        return response()->json(['status'=>'success', 'data'=>$customerNumber]);
    }
}
