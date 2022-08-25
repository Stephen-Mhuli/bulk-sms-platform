<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ChatResponse;
use Illuminate\Http\Request;

class ChatResponseController extends Controller
{
    public function index()
    {

        return view('customer.chat_response.index');
    }

    public function getAll()
    {
        $chat_response = auth('customer')->user()->chat_responses()->select(['id', 'title', 'content', 'status']);
        return datatables()->of($chat_response)
            ->addColumn('title', function($q){
                return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'> $q->title </div>";
            })
            ->addColumn('content', function($q){
                return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'> $q->content </div>";
            })
            ->addColumn('status', function ($q) {
                if ($q->status == 'inactive') {
                    return "<span class='pl-2 pr-2 pt-1 pb-1 bg-danger bg-radius'>Inactive</span>";
                } else {
                    return "<span class='pl-2 pr-2 pt-1 pb-1 bg-success bg-radius'>Active</span>";
                }
            })
            ->addColumn('action', function ($q) {

                return "<a class='btn btn-sm btn-info edit_response' data-toggle='tooltip' data-placement='top' title='Edit' data-content='".htmlspecialchars($q->content,ENT_QUOTES, 'UTF-8')."' data-status='$q->status' data-title='".htmlspecialchars($q->title, ENT_QUOTES, 'UTF-8')."' data-id='$q->id' href='#'>"."<i class='fas fa-edit'></i>"."</a> &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this Chat Response?"
                                        data-action=' . route('customer.chat.response.delete', ['id'=>$q->id]) . '
                                        data-input={"_method":"get"}
                                        data-toggle="modal" data-target="#modal-confirm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['action','title','content','status'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:chat_responses,title',
            'status' => 'required|in:active,inactive',
            'response_content'=>'required',
        ]);
        $customer = auth('customer')->user();
        $response = new ChatResponse();
        $response->customer_id = $customer->id;
        $response->title = $request->title;
        $response->content = $request->response_content;
        $response->status = $request->status;
        $response->save();

        return redirect()->back()->with('success', trans('Chat Response Successfully Created'));
    }

    public function update(Request $request)
    {
        $customer = auth('customer')->user();
        $response = ChatResponse::where('customer_id',$customer->id)->where('id', $request->id)->firstOrFail();
        $request->validate([
            'title' => 'required|unique:chat_responses,title,'.$response->id,
            'status' => 'required|in:active,inactive',
            'response_content'=>'required',
        ]);
        $response->customer_id = $customer->id;
        $response->title = $request->title;
        $response->content = $request->response_content;
        $response->status = $request->status;
        $response->save();

        return redirect()->back()->with('success', trans('Chat Response Successfully Update'));
    }

    public function delete(Request $request){
        $customer = auth('customer')->user();
        $response = ChatResponse::where('customer_id',$customer->id)->where('id', $request->id)->firstOrFail();
        $response->delete();
        return redirect()->back()->with('success', trans('Chat Response Successfully Deleted'));
    }

}
