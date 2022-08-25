<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuthorizationToken;
use App\Models\MessageLog;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function inbox(Request $request)
    {
        $page_no = $request->page ?? 0;
        $no_of_data = 20;
        $offset = ($page_no * $no_of_data) - $no_of_data;

        $customer = auth()->user();

        $messages = MessageLog::select('to', 'from', 'body', 'type', 'status', 'created_at', 'updated_at')->where('customer_id', $customer->id)->where('type', 'inbox')->orderByDesc('updated_at')->offset($offset)->limit($no_of_data)->get();

        $data = ['inbox' => $messages];
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function sent(Request $request)
    {
        $page_no = $request->page ?? 0;
        $no_of_data = 20;
        $offset = ($page_no * $no_of_data) - $no_of_data;

        $customer = auth()->user();
        $messages = MessageLog::select('to', 'from', 'body', 'type', 'status', 'created_at', 'updated_at')->where('customer_id', $customer->id)->where('type', 'sent')->orderByDesc('updated_at')->offset($offset)->limit($no_of_data)->get();

        $data = ['sent' => $messages];

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function smsQueue(Request $request)
    {
        $page_no = $request->page ?? 0;
        $no_of_data = 20;
        $offset = ($page_no * $no_of_data) - $no_of_data;

        $customer = auth()->user();

        $messages = MessageLog::select('to', 'from', 'body', 'status', 'schedule_completed', 'delivered_at', 'created_at', 'updated_at')->where('customer_id', $customer->id)->orderByDesc('updated_at')->offset($offset)->limit($no_of_data)->get();

        $data = ['messages' => $messages];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
