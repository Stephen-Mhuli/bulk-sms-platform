<?php

namespace App\Http\Controllers\Customer;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\CustomerPlan;
use App\Models\Device;
use App\Models\Number;
use App\SmsProvider\SendSMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComposeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('customer')->user();
        $data['draft'] = $user->drafts()->where('id', $request->draft)->first();
        $data['devices'] = $user->devices()->where('status', 'active')->get();

        $usersToGroups = [];
        $usersToContacts = [];
        foreach ($user->active_groups as $group) {
            $usersToGroups[] = ['value' => $group->name, 'id' => $group->id, 'type' => 'group'];
        }
        foreach ($user->contacts()->limit(10000)->get() as $contact) {
            $usersToContacts[] = ['value' => isset($contact->first_name) ? $contact->number . ' (' . $contact->first_name . ' ' . $contact->last_name . ')' : $contact->number, 'id' => $contact->id, 'type' => 'contact'];
        }


        $data['users_to_contacts'] = $usersToContacts;
        $data['users_to_groups'] = $usersToGroups;

        return view('customer.smsbox.compose', $data);
    }

    public function sentCompose(Request $request)
    {

        $request->validate([
            'device_id' => 'required',
            'to_numbers' => 'required|array',
            'body' => 'required',
        ]);
        $customer = auth('customer')->user();
        $currentPlan = $customer->currentPlan();
        if (isset($currentPlan->renew_date) && $currentPlan->renew_date < Carbon::now()){
            return back()->with('fail', 'Your Plan has expired');
        }
        $user = auth()->guard('customer')->user();
        $device = $user->activeDevices($request->device_id)->first();
        if (!$device) {
            return back()->with('fail', 'Device has been removed or inactive');
        }

        $sent = auth('customer')->user()->message_logs()->where('type','sent')->whereDate('created_at', Carbon::today())->count();
        $plan = $user->currentPlan();
        if (($sent + count($request->to_numbers)) > $plan->daily_send_limit) {
            return back()->withErrors(['message' => 'Your have extended your daily send limit']);
        }

        $messageFiles = [];
        if ($request->mms_files) {
            foreach ($request->mms_files as $key => $file) {
                $messageFiles[] = $fileName = time() . $key . '.' . $file->extension();
                $file->move(public_path('uploads/'), $fileName);
            }
            $request['message_files'] = json_encode($messageFiles);
        }

        if (isset($request->isSchedule)) {
            $sd = Carbon::createFromTimeString($request->schedule);
            $request['schedule_datetime'] = $sd;
        } else {
            $request['schedule_datetime'] = now();
        }
        $allToNumbers = [];
        $allGroupIds = [];
        $allContactIds = [];

        foreach ($request->to_numbers as $item) {
            $number = (array)json_decode($item);
            if (isset($number['type']) && isset($number['id'])) {
                if ($number['type'] == 'contact') {
                    $allContactIds[] = $number['id'];
                } elseif ($number['type'] == 'group') {
                    $allGroupIds[] = $number['id'];
                }
            } else {
                $allToNumbers[] = $item;
            }
        }

        $contactNumbers = Contact::select('id', 'number')->whereIn('id', $allContactIds)->get();
        $groupNumbers = ContactGroup::with('contact')->whereIn('group_id', $allGroupIds)->get();

        foreach ($contactNumbers as $cn) {
            $allToNumbers[] = trim($cn->number);
        }
        foreach ($groupNumbers as $gn) {
            $allToNumbers[] = trim($gn->contact->number);
        }

        $allToNumbers = array_unique($allToNumbers);

        $request['to_numbers'] = $allToNumbers;
        $request['numbers'] = json_encode(['from' => $device->id, 'to' => $allToNumbers]);
        $request['type'] = 'sent';

        $current_plan = auth('customer')->user()->currentPlan();
        if (!$current_plan)
            return back()->with('fail', 'Customer doesn\'t have any plan right now');

        //subtracting one sms TODO:: will need to count text and sub that also calculate today send
        $pre_available_sms = $current_plan->daily_send_limit;
        $new_available_sms = $pre_available_sms - count($allToNumbers);

        //if not enough sms then return
        if ($new_available_sms < 0)
            return redirect()->back()->with('fail', 'Doesn\'t have enough sms');


        DB::beginTransaction();
        try {
            $newMessage = auth('customer')->user()->messages()->create($request->all());

            $sms_queue = [];
            foreach ($request->to_numbers as $to) {
                $newMessageFiles = null;
                if ($messageFiles) {
                    $newMessageFiles = $messageFiles;

                    array_walk($newMessageFiles, function (&$value, $index) {
                        $value = asset('uploads/' . $value);
                    });
                }
                $sms_queue[] = [
                    'message_id' => $newMessage->id,
                    'from' => $device->id,
                    'device_unique_id' => $device->device_unique_id,
                    'to' => $to,
                    'schedule_datetime' => $request->schedule_datetime,
                    'body' => $request->body,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'type' => 'sent',
                ];

            }

            auth('customer')->user()->sms_queues()->createMany($sms_queue);
            auth('customer')->user()->message_logs()->createMany($sms_queue);

            DB::commit();
            if (!$request->ajax()) {
                return back()->with('success', 'Message queued successfully');
            } else {
                return response()->json(['status' => 'success', 'message' => 'Message queued successfully']);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return back()->with('fail', $ex->getMessage());
        }
    }

    public function queueList(Request $request)
    {
        $data['queuesList'] = auth('customer')->user()->sms_queues()->whereNotNull('schedule_datetime')->whereNull('delivered_at')->orderBy('created_at', 'desc')->paginate(10);
        return view('customer.smsbox.queue', $data);
    }
    public function overview()
    {
        return view('customer.smsbox.overview');
    }
    public function overview_get_data(Request $request)
    {
        if ($request->from_date || $request->to_date || $request->status || $request->type){
            if ($request->status && $request->type == 'sent'){
                $overview = auth('customer')->user()->message_logs()->where('status',$request->status)->where('type',$request->type)->select(['id', 'body', 'status', 'updated_at','from','to','type']);
            }elseif ($request->type == 'sent'){
                $overview = auth('customer')->user()->message_logs()->where('type',$request->type)->select(['id', 'body', 'status', 'updated_at','from','to','type']);
            }elseif ($request->type == 'trash' && $request->from_date && $request->to_date ){
                $overview = auth('customer')->user()->message_logs()->whereBetween('created_at',[$request->from_date ,$request->to_date])->onlyTrashed()->select(['id', 'body', 'status', 'updated_at','from','to','type']);
            }elseif ($request->type == 'trash'){
                $overview = auth('customer')->user()->message_logs()->onlyTrashed()->select(['id', 'body', 'status', 'updated_at','from','to','type']);
            }elseif ($request->type == 'inbox' && $request->from_date && $request->to_date){
                $overview = auth('customer')->user()->message_logs()->where('type',$request->type)->whereBetween('created_at',[$request->from_date ,$request->to_date])->select(['id', 'body', 'status', 'updated_at','from','to','type']);
            }elseif ($request->type == 'inbox'){
                $overview = auth('customer')->user()->message_logs()->where('type',$request->type)->select(['id', 'body', 'status', 'updated_at','from','to','type']);
            }
            elseif ($request->type == 'draft' && $request->from_date && $request->to_date ){
                $overview = auth('customer')->user()->drafts()->whereBetween('created_at',[$request->from_date ,$request->to_date])->select(['id', 'body','updated_at','numbers']);
            }elseif ($request->type == 'draft'){
                $overview = auth('customer')->user()->drafts()->select(['id', 'body','updated_at','numbers']);
            }
            elseif ($request->from_date && $request->to_date){
                $overview = auth('customer')->user()->message_logs()->whereBetween('created_at',[$request->from_date ,$request->to_date])->where('status',$request->status)->where('type',$request->type)->select(['id', 'body', 'status', 'updated_at','from','to','type']);
            }

        }else{
            $overview = auth('customer')->user()->message_logs()->orderBy('created_at', 'desc')->select(['id', 'body', 'status', 'updated_at','from','to','type']);
        }

        if ($request->type == 'draft'){
            return datatables()->of($overview)
                ->addColumn('updated_at', function ($q) {
                    return "<a href='" . route('customer.smsbox.compose', ['draft'=>$q->id]) . "'>".formatDate($q->updated_at)."</a>";
                })
                ->addColumn('to', function ($q) {
                    $draftNumbers = json_decode($q->numbers)->to;
                    if ($draftNumbers){
                        $draftNumbers = json_decode($q->numbers)->to;
                        $count=count($draftNumbers);
                        $text=$count>=100?' and more '.($q->contacts()->count()-$count):'';
                        $draftTONumbers = "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" . implode(", ", $draftNumbers).$text. " </div>";
                    }else{
                        $draftTONumbers = '';
                    }

                    return $draftTONumbers;
                })
                ->addColumn('from', function ($q) {
                    $draftFromNumbers = json_decode($q->numbers)->from;
                    return $draftFromNumbers;
                })
                ->addColumn('type', function ($q) {
                    $draftType = null;
                    return $draftType;
                })
                ->addColumn('status', function ($q) {
                    $draftStatus = null;
                    return $draftStatus;
                })
                ->addColumn('action', function ($q) {
                    return '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this draft?"
                                        data-action=' . route('customer.smsbox.draft.delete', ['id'=>$q]) . '
                                        data-input={"_method":"post"}
                                        data-toggle="modal" data-target="#modal-confirm">Delete</button>';
                })
                ->rawColumns(['action','updated_at','to'])
                ->toJson();
        }else{
            return datatables()->of($overview)
                ->addColumn('updated_at', function ($q) {
                    return formatDate($q->updated_at);
                })
                ->addColumn('body', function ($q) {
                   return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" .$q->body . " </div>";
                })
                ->addColumn('action', function ($q) {
                    return '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this message?"
                                        data-action=' . route('customer.smsbox.overview.data.delete', ['id'=>$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm">Delete</button>';
                })
                ->rawColumns(['action','body'])
                ->toJson();
        }

    }

    public function overview_data_delete(Request $request)
    {
        $request->validate([
            'id'=>'required'
        ]);
        $ids=explode(',', $request->id);
        auth('customer')->user()->message_logs()->whereIn('id',$ids)->delete();
        return back()->with('success', 'Message successfully moved to trash');
    }
}
