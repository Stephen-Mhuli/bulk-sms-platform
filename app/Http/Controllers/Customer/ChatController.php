<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Exception;
use App\Models\Label;
use App\Models\MessageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $no_of_data = 20;
        $from_numbers = auth()->user()->message_logs()->select('from AS to', DB::raw('MAX(updated_at) as created_at'))->where('type', 'inbox')->orderByDesc('created_at')->groupBy('from')->limit($no_of_data)->get();

        $data['to_numbers'] = $from_numbers->sortByDesc('created_at')->pluck('to')->unique();
        $createdAt=[];
        foreach ($from_numbers as $number){
            $createdAt[$number->to]=$number->created_at;
        }
        $data['numbers'] = auth()->user()->numbers()->get();
        $data['chat_responses'] = auth('customer')->user()->chat_responses()->where('status', 'active')->get();
        $data['labels']=auth('customer')->user()->labels()->where('status','active')->get();
        $data['createdAt']=$createdAt;
        return view('customer.chat.index', $data);
    }
    public function get_numbers(Request $request)
    {
        $page_no = $request->page;
        if(!$page_no) abort(404);

        $no_of_data = 20;
        $offset = ($page_no * $no_of_data) - $no_of_data;

        $search = $request->search;
        $allNumbers = auth()->user()->message_logs()->select('from AS to','body', DB::raw('MAX(updated_at) as created_at'))->where('type', 'inbox')->groupBy('from');
        if ($request->type=='old'){
            $allNumbers = $allNumbers->orderBy('created_at','asc');
        }else{
            $allNumbers = $allNumbers->orderByDesc('created_at');
        }
        if ($search){
            $contacts = Contact::where('number', 'like', '%' . $search . '%')->orWhere('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%')->pluck('number');
            $allNumbers = $allNumbers->whereIn('from', $contacts)->where('type','inbox');
        }

        if($request->date){
            $dates = explode('-',$request->date);
                $fromDate = isset($dates) && isset($dates["0"]) ? str_replace(' ', '', $dates["0"]) : now();
                $toDate = isset($dates) && isset($dates["1"]) ? str_replace(' ', '', $dates["1"]) : now();
                $fromDate = new \DateTime($fromDate);
                $toDate = new \DateTime($toDate);
            if($fromDate != $toDate) {
                $allNumbers = $allNumbers->whereBetween('updated_at', [$fromDate, $toDate]);
            }
        }
        if($request->label_id){

            $label = Label::where('id', $request->label_id)->first();
            if (!$label){
                return response()->json(['status'=>'failed','message'=>'Invalid Label']);
            }
            $contacts = auth('customer')->user()->contacts()->where('label_id', $label->id)->pluck('number')->unique();
            $allNumbers = $allNumbers->whereIn('from', $contacts);
        }
        $allNumbers = $allNumbers->limit($no_of_data)->offset($offset)->get();

        if ($request->type=='old'){
            $from_numbers = $allNumbers->sortBy('created_at')->pluck('to')->unique();
        }else{
            $from_numbers = $allNumbers->sortByDesc('created_at')->pluck('to')->unique();
        }


        $createdAt=[];
        foreach ($allNumbers as $number){
            $createdAt[$number->to]=$number->created_at;
        }
        $allChats = auth('customer')->user()->message_logs()->whereIn('to', $from_numbers)
            ->orWhereIn('from',$from_numbers)
            ->orderBy('updated_at')
            ->get(['body', 'to', 'from', 'created_at', 'updated_at']);

        $find_chat=[];
        foreach ($allChats as $key=>$chat){
            $find_chat[$chat->to]=$chat->body;
        }
        foreach ($allChats as $key=>$chat){
            $find_chat[$chat->from]=$chat->body;
        }

        $numbersArray=[];
        foreach ($from_numbers as $number){
            $numbersArray[]= '+'.str_replace('+','',$number);
        }
        $findContacts = auth('customer')->user()->contacts()->whereIn('number', $from_numbers)->orWhereIn('number', $numbersArray)->orderBy('created_at')->get();
        $findContact=[];
        foreach ($findContacts as $contact) {
            $findContact[$contact->number] = [
                'label' => isset($contact->label) ? ucfirst(mb_strimwidth($contact->label->title, 0, 7, '..')) : '',
                'color' => isset($contact->label) ? $contact->label->color : '',
                'full_name' => isset($contact->full_name)?$contact->full_name:''
            ];
        }

        $data=[];
        foreach($from_numbers as $key=>$from_number){
            $data[$key]['full_name']= isset($findContact[$from_number]) && isset($findContact[$from_number]['full_name'])?$findContact[$from_number]['full_name']:'';
            $data[$key]['number']=$from_number;
            $data[$key]['created_at']=isset($createdAt) && isset($createdAt[$from_number])?formatDate($createdAt[$from_number]):'';
            $data[$key]['label']= isset($findContact[$from_number]) && isset($findContact[$from_number]['label'])?$findContact[$from_number]['label']:'';
            $data[$key]['color']=  isset($findContact[$from_number]) && isset($findContact[$from_number]['color'])?$findContact[$from_number]['color']:'';
            $data[$key]['body']=isset($find_chat[$from_number])?$find_chat[$from_number]:'';
        }

        $labels=auth('customer')->user()->labels()->where('status','active')->get();

        if($from_numbers->isNotEmpty()){
            return response()->json(['status'=>'success','data'=>['numbers'=>$data,'labels'=>$labels,'page'=>$page_no+1]]);
        }else{
            return response()->json(['status'=>'success','data'=>['numbers'=>[],'page'=>'end']]);
        }
    }

    public function label_update(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'label' => 'required'
        ]);

        $contact = Contact::where('number', $request->number)->orWhere('number', str_replace('+', '', $request->number))->first();

        if(!$contact){
            return response()->json(['status' => 'failed']);
        }
        $label = auth('customer')->user()->labels()->where('id', $request->label)->where('status','active')->first();
        if(!$label){
            return response()->json(['status' => 'failed','message'=>'This is not a valid label']);
        }
        $contact->label_id = $label->id;
        $contact->update();
        return response()->json(['status' => 'success', 'message' => 'Label successfully updated']);
    }


    public function get_data(Request $request)
    {
        $no_of_data = 20;
        $chats = auth('customer')->user()->message_logs()->where('to', $request->number)->orWhere('from',$request->number)->orderByDesc('updated_at')->limit($no_of_data)->get(['body', 'to', 'from','type', 'created_at', 'updated_at'])->toArray();

        $contact_id = auth('customer')->user()->contacts()->where('number', $request->number)->first();

        if($contact_id){
            $address = isset($contact_id->address)?$contact_id->address:'';
            $zillowUrl='https://www.zillow.com/homes/recently_sold/'.str_replace(' ','-', $contact_id->address);
            $exception = Exception::where('number', $contact_id->number)->orWhere('number', str_replace('+', '', $contact_id->number))->first();
            $label = auth('customer')->user()->labels()->where('id', $contact_id->label_id)->where('status','active')->first();
            return response()->json(['status' => 'success', 'data' => ['zillow_url'=>$zillowUrl,'address'=>$address,'number'=>$exception,'id' => $contact_id->id,'color'=>isset($label)?$label->color:'' ,'label' => isset($label)?$label->id:'','name'=>$contact_id->full_name,'messages' => $chats,'page'=>count($chats)<$no_of_data?'end':2]]);
        }
        return response()->json(['status' => 'success', 'data' => ['id' => null, 'label' => null, 'messages' => $chats,'page'=>count($chats)<$no_of_data?'end':2]]);
    }

    public function get_chats(Request $request){
        $chats_no = $request->chats;
        if(!$chats_no) abort(404);

        $no_of_data = 20;
        $offset = ($chats_no * $no_of_data) - $no_of_data;

        $chats = auth('customer')->user()->message_logs()->where('to', $request->number)->orWhere('from',$request->number)->orderByDesc('updated_at')->offset($offset)->limit($no_of_data)->get(['body', 'to', 'from','type', 'created_at', 'updated_at'])->toArray();

        if($chats){
                return response()->json(['status' => 'success', 'data' => ['messages' => $chats,'page'=>count($chats)<$no_of_data?'end':$chats_no+1]]);
        }else{
            return response()->json(['status' => 'success', 'data' => ['messages' => [],'page'=>'end']]);
        }
    }

    public function exception(Request $request){
        $label = auth('customer')->user()->labels()->where('title', 'new')->first();
        if (!$label) {
            $label = new Label();
            $label->title = 'new';
            $label->status = 'active';
            $label->customer_id = auth('customer')->user()->id;
            $label->color = 'red';
            $label->save();
        }

        if ($request->check_add_contact){
            $contact = new Contact();
            $contact->customer_id = auth('customer')->user()->id;
            $contact->number = $request->number;
            $contact->label_id = $label->id;
            $contact->save();
        }
        if ($request->type=='add') {
            $exception = new Exception();
            $exception->number = $request->number;
            $exception->customer_id = auth('customer')->user()->id;
            $exception->save();
            return response()->json(['status' => 'success','type'=>$request->type]);
        }elseif ($request->type=='delete'){
            $exception = Exception::where('number',$request->number)->orWhere('number', str_replace('+', '', $request->number))->where('customer_id', auth('customer')->user()->id)->first();
            if($exception){
                $exception->delete();
            }
            return response()->json(['status' => 'success','type'=>$request->type]);
        }

    }

    public function addNewContact(Request $request){
        $label = auth('customer')->user()->labels()->where('id', $request->label)->where('status','active')->first();
        if(!$label){
            return response()->json(['status' => 'failed','message'=>'This is not a valid label']);
        }
        $preContact = Contact::where('number', $request->number)->orWhere('number', str_replace('+', '', $request->number))->first();
        if ($preContact) {
            $preContact->label_id = $label->id;
            $preContact->save();
            return response()->json(['status' => 'success', 'message' => 'Contact Successfully added']);
        }
            $contact = new Contact();
            $contact->customer_id = auth('customer')->user()->id;
            $contact->number = $request->number;
            $contact->label_id = $label->id;
            $contact->save();
        return response()->json(['status'=>'success','message'=>'Contact Successfully added']);
    }

    public function sendContactInfo(Request $request){
        if (!$request->number || !$request->url){
            return response()->json(['status'=>'failed', 'message'=>'Invalid Request']);
        }
        $requestNumber= '+'.str_replace('+','',trim($request->number));

        $contact = Contact::select('first_name', 'last_name', 'number', 'email', 'label_id', 'city', 'state', 'zip_code', 'note', 'address', 'company')
            ->where('number', $requestNumber)->first();
        if (!$contact) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid number']);
        }
        $message_logs = MessageLog::select('to','from','body','type','status', 'created_at', 'updated_at')->where('to', $contact->number)->orWhere('from', $contact->number)->orderByDesc('updated_at')->get();

        $contactData=[
            'first_name'=>$contact->first_name,
            'last_name'=>$contact->last_name,
            'number'=>$contact->number,
            'email'=>$contact->email,
            'city'=>$contact->city,
            'state'=>$contact->state,
            'zip_code'=>$contact->zip_code,
            'address'=>$contact->address,
            'note'=>$contact->note,
            'company'=>$contact->company,
            'label'=>null
        ];
        if (isset($contact->label)){
            $contactData['label']=[
                'title'=>$contact->label->title,
                'color'=>$contact->label->color,
            ];
        }
        $messageData=[];
        foreach($message_logs as $key=>$message_log){
            $messageData[$key]['from']=$message_log->from;
            $messageData[$key]['to']=$message_log->to;
            $messageData[$key]['body']=$message_log->body;
            $messageData[$key]['type']=$message_log->type;
            $messageData[$key]['status']=$message_log->status;
            $messageData[$key]['created_at']=$message_log->created_at->toDateTimeString();
            $messageData[$key]['updated_at']=$message_log->updated_at->toDateTimeString();
        }

        $data=[
            'contact'  =>$contactData,
            'messages'  =>$messageData,
        ];

        $client=new \GuzzleHttp\Client(['verify' => false ]);
        if ($request->url_method=='post'){
            $client->post($request->url,[
                'form_params'=>$data
            ]);
        }else {
            $client->get($request->url, [
                'query' => $data
            ]);
        }
        return response()->json(['status'=>'success']);
    }
}
