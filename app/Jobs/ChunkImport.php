<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Group;
use App\Models\Message;
use App\Models\MessageLog;
use App\Models\SmsQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ChunkImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $request;
    private $user;
    private $group;
    private $offset;
    private $result_count;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($rq,$group,$user,$resultCount)
    {
        $this->request=$rq;
        $this->user=$user;
        $this->group=$group;
        $this->result_count=$resultCount;
        $this->offset = ($rq->page - 1) * $this->result_count;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customer = $this->user;
        $request=(object)$this->request;
        if (!$request->group_ids){
            return;
        }

        $contacts=ContactGroup::where('contact_groups.customer_id',$customer->id)
            ->select('contact_groups.contact_id','contact_groups.customer_id','contacts.first_name','contacts.label','contacts.last_name','contacts.city','contacts.address','contacts.number','contacts.state','contacts.zip_code','contacts.email')
            ->whereIn('contact_groups.group_id',$request->group_ids)
            ->skip($this->offset)
            ->take($this->result_count)
            ->groupBy('contacts.number')
            ->join('contacts', 'contacts.id', '=', 'contact_groups.contact_id');



        if (isset($request->first_name) && $request->first_name && $request->first_name_type == '=') {
            $contacts->where('first_name', $request->first_name);
        }elseif(isset($request->first_name) && $request->first_name && $request->first_name_type == '!='){
            $contacts->where('first_name','!=', $request->first_name);
        }

        if (isset($request->last_name) && $request->last_name && $request->last_name_type == '=') {
            $contacts->where('last_name', $request->last_name);
        }elseif(isset($request->last_name) && $request->last_name && $request->last_name_type == '!='){
            $contacts->where('last_name','!=', $request->last_name);
        }

        if (isset($request->phone_number) && $request->phone_number && $request->phone_number_type == '=') {
            $contacts->where('number', $request->phone_number);
        }elseif(isset($request->phone_number) && $request->phone_number && $request->phone_number_type == '!='){
            $contacts->where('number','!=', $request->phone_number);
        }
        if (isset($request->address) && $request->address && $request->address_type == '=') {
            $contacts->where('address', $request->address);
        }elseif(isset($request->address) && $request->address && $request->address_type == '!='){
            $contacts->where('address','!=', $request->address);
        }
        if (isset($request->city) && $request->city && $request->city_type == '=') {
            $contacts->where('city', $request->city);
        }elseif(isset($request->city) && $request->city && $request->city_type == '!='){
            $contacts->where('city','!=', $request->city);
        }
        if (isset($request->state) && $request->state && $request->state_type == '=') {
            $contacts->where('state', $request->state);
        }elseif(isset($request->state) && $request->state && $request->state_type == '!='){
            $contacts->where('state','!=', $request->state);
        }
        if (isset($request->zip_code) && $request->zip_code && $request->zip_code_type == '=') {
            $contacts->where('zip_code', $request->state);
        }elseif(isset($request->zip_code) && $request->zip_code && $request->zip_code_type == '!='){
            $contacts->where('zip_code','!=', $request->state);
        }
        if (isset($request->email) && $request->email && $request->email_type == '=') {
            $contacts->where('email', $request->email);
        }elseif(isset($request->email) && $request->email && $request->email_type == '!='){
            $contacts->where('email','!=', $request->email);
        }
        if ($request->label){
            $contacts->where('label', $request->label);
        }

        $contactTo = $contacts->pluck('number');
        $sent_messages = MessageLog::where('type', 'sent')->where('status','!=','pending')->whereIn('to', $contactTo);
        if ($request->sent_type == 'older_than') {
            $sent_messages->whereDate('created_at', '<=', Carbon::now()->subDays($request->sms_sent_days));
        }
        if ($request->sent_type == 'within') {
            $sent_messages->whereBetween('created_at', [Carbon::now()->subDays($request->sms_sent_days)->toDateString(), Carbon::now()->addDay()->toDateString()]);
        }
        if ($request->sent_type == 'between') {
            $sent_messages->whereBetween('created_at', [Carbon::parse($request->between_from)->toDateString(), Carbon::parse($request->between_to)->toDateString()]);
        }
        if ($request->sent_type == 'empty'){
            $sent_messages=$sent_messages->get();
            $already_sent_to=$sent_messages->pluck('to');
            $toContactResult=$contactTo->filter(function ($value, $key) use ($already_sent_to) {
                return ! ($already_sent_to->contains($value));
            });
        }else{
            $sent_messages=$sent_messages->get();
            $toContactResult=$sent_messages->pluck('to');
        }
        $inbox_messages = MessageLog::where('type', 'inbox')->whereIn('from', $toContactResult);

        if ($request->sms_received_type == 'older_than') {
            $inbox_messages->whereDate('created_at', '<=', Carbon::now()->subDays($request->sms_received_days));
        }

        if ($request->sms_received_type == 'within') {
            $inbox_messages->whereBetween('created_at', [Carbon::now()->subDays($request->sms_received_days)->toDateString(), Carbon::now()->addDay()->toDateString()]);
        }

        if ($request->sms_received_type == 'between') {
            $inbox_messages->whereBetween('created_at', [Carbon::parse($request->sms_received_between_from)->toDateString(), Carbon::parse($request->sms_received_between_to)->toDateString()]);
        }

        if ($request->sms_received_type == 'empty') {
            $inbox_messages=$inbox_messages->get();
            $already_received_from=$inbox_messages->pluck('from');
            $toContactResult=$toContactResult->filter(function ($value, $key) use ($already_received_from) {
                return ! ($already_received_from->contains($value));
            });
        }else{
            $inbox_messages=$inbox_messages->get();
            $toContactResult=$inbox_messages->pluck('from');
        }
        $all_contacts = $contacts->whereIn('number',$toContactResult)->get();

        $contactsList = new Collection();
        foreach ($all_contacts as $contact) {
            $sentMessage=null;
            $inMessage=null;

            if ($request->sent_type != 'empty') {
                $sentMessage=$sent_messages->filter(function ($item) use ($contact) {
                    return strpos(" ".$item->to,$contact->number) !== false;
                })->first();
            }
            if($request->sms_received_type != 'empty'){
                $inMessage=$inbox_messages->filter(function ($item) use ($contact) {
                    return strpos(" ".$item->from,$contact->number) !== false;
                })->first();
            }
            if($sentMessage && $inMessage){
                $contact['received_sms_date'] = $inMessage->created_at;
                $contact['delivered_at'] = $sentMessage->created_at;
            }elseif ($sentMessage){
                $contact['delivered_at'] = $sentMessage->created_at;
            }elseif ($inMessage){
                $contact['received_sms_date'] = $inMessage->created_at;
            }
            if(!$contactsList->contains($contact)){
                $contactsList->push($contact);
            }
        }
        $contact_ids = $contactsList->pluck('contact_id');
        $contactArray=[];
        foreach ($contact_ids as $contact_id) {
            $contactArray[] = [
                'contact_id' => $contact_id,
                'customer_id' => $this->group->customer_id,
                'group_id' => $this->group->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if($contactArray){
            ContactGroup::insert($contactArray);
        }
    }

    public function failed(\Exception $exception)
    {
        Group::where('id',$this->group->id)->update(['import_status'=>'failed','import_fail_message'=>substr($exception->getMessage(), 0, 191)]);
    }
}
