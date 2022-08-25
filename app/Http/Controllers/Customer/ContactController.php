<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Imports\ContactsImport;
use App\Models\Contact;
use App\Models\CustomerPlan;
use App\Models\Group;
use App\Models\Label;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    public function create()
    {
        return view('customer.contacts.create');
    }

    public function index()
    {
        return view('customer.contacts.index');
    }

    public function import_contacts()
    {
        return view('customer.contacts.import_create');
    }

    public function getAll()
    {
        $contacts = auth('customer')->user()->contacts()->select(['id', 'number', 'first_name', 'last_name', 'email', 'company', 'address', 'zip_code', 'city', 'state', 'note']);
        return datatables()->of($contacts)
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' data-toggle='tooltip' data-placement='top' title='Edit' href='" . route('customer.contacts.edit', [$q->id]) . "'>"."<i class='fas fa-edit'></i>"."</a> &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this number?"
                                        data-action=' . route('customer.contacts.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>';
            })
            ->addColumn('number', function ($q) {
                return $q->number;
            })
            ->addColumn('name', function ($q) {
                return $q->first_name . ' ' . $q->last_name;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:contacts|regex:/^[0-9\-\+]{9,15}$/',
            'contact_dial_code' => 'required'
        ]);
        $customer = auth('customer')->user();
        $contact = auth('customer')->user()->contacts()->count();
        $plan = auth('customer')->user()->currentPlan();
        $currentPlan = $customer->currentPlan();
        if (isset($currentPlan->renew_date) && $currentPlan->renew_date < Carbon::now()){
            return back()->with('fail', 'Your Plan has expired');
        }
        if($contact >= $plan->contact_limit){
            return back()->withErrors(['message'=>'You have extended your contact limit']);
        }
        $notification = auth('customer')->user()->settings()->where('name', 'email_notification')->first();
        $request['email_notification'] = $notification->value;
        $label = auth('customer')->user()->labels()->where('title', 'new')->first();
        if (!$label) {
            $label = new Label();
            $label->title = 'new';
            $label->status = 'active';
            $label->customer_id = auth('customer')->user()->id;
            $label->color = 'red';
            $label->save();
        }
        $request['label_id'] = $label->id;
        auth('customer')->user()->contacts()->create($request->all());

        return back()->with('success', 'Contact successfully added');
    }

    public function edit(Contact $contact)
    {
        $data['contact'] = $contact;
        return view('customer.contacts.edit', $data);
    }

    public function update(Contact $contact, Request $request)
    {
        $request->validate([
            'first_name' => 'required',
        ]);
        $contact = auth('customer')->user()->contacts()->count();
        $plan = auth('customer')->user()->plan()->latest('id')->first();
        $contact_limit = Plan::where('id', $plan->id)->first();
        $customer = auth('customer')->user();
        $renewDate = CustomerPlan::where('customer_id',$customer->id)->where('is_current','yes')->where('renew_date','<',Carbon::now())->first();
        if ($renewDate){
            return back()->withErrors(['message'=>'Your Plan has expired']);
        }
        if($contact >= $contact_limit->contact_limit){
            return back()->withErrors(['message'=>'Your contact added limit is over']);
        }
        $notification = auth('customer')->user()->settings()->where('name', 'email_notification')->first();

        $valid_data = $request->only('first_name', 'last_name', 'email', 'company', 'forward_to', 'forward_to_dial_code', 'address', 'zip_code', 'city', 'state', 'note');
        $valid_data['email_notification'] = $notification->value;

        //update the model
        $contact->update($valid_data);

        return back()->with('success', 'Contact successfully updated');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'Contact successfully deleted');
    }

    public function import_contacts_show(Request $request)
    {

        $request->validate([
            'import_contact_csv' => 'required|mimes:csv,txt'
        ]);

        $import_contact_data = Excel::toArray(new class implements ToCollection,WithCustomCsvSettings {
            public function collection(\Illuminate\Support\Collection $rows)
            {
                return $rows;
            }
            public function getCsvSettings(): array
            {
                return [
                    'input_encoding' => 'ISO-8859-1'
                ];
            }
        }, $request->file('import_contact_csv')
        );
        $import_contact_contact_array = [];
        unset($import_contact_data[0][0]);
        foreach (array_slice($import_contact_data[0],0,10) as $data) {
            $import_contact_contact_array[] = [
                'number' => "+".str_replace('+','',$data[0]),
                'first_name' => $data[1],
                'last_name' => $data[2],
                'email' => $data[3],
                'address' => $data[4],
                'city' => $data[5],
                'state' => $data[6],
                'zip_code' => $data[7],
                'company' => $data[8],
                'note' => $data[9],
                'full_name' => $data[1] . '&nbsp;' . $data[2],
            ];
        }

        return response()->json(['status' => 'success', 'data' => $import_contact_contact_array]);

    }

    public function import_contacts_store(Request $request)
    {
        $request->validate([
            'import_name' => 'required',
            'import_contact_csv' => 'required|mimes:csv,txt'
        ]);
        $customer = auth('customer')->user();
        $currentPlan = $customer->currentPlan();
        if (isset($currentPlan->renew_date) && $currentPlan->renew_date < Carbon::now()){
            return back()->with('fail', 'Your Plan has expired');
        }
        DB::beginTransaction();
        try {
            $preGroup = auth('customer')->user()->groups()->where('name', $request->import_name)->first();
            if ($preGroup) return back()->withErrors(['msg' => "Import name already exists"]);

            $importContact = new Group();
            $importContact->customer_id = auth('customer')->id();
            $importContact->name = $request->import_name;
            $importContact->save();

            if ($request->hasFile('import_contact_csv')) {
                $data = $request->file('import_contact_csv');
                $fileName = $importContact->id . '.' . $data->getClientOriginalExtension();
                $data->move(public_path() . '/uploads', $fileName);
                $file_url = public_path() . '/uploads/' . $fileName;
                $fp = file($file_url);
                $contacts = auth('customer')->user()->contacts()->count();
                $importedContact = (count($fp) - 1) + $contacts;
                $current_plan = auth('customer')->user()->currentPlan();

                if (!$current_plan)
                    throw new \ErrorException('Doesn\'t have any plan right now');
                $planContactLimit = $current_plan->contact_limit;
                if ($importedContact >= $planContactLimit) {
                    return redirect()->route('customer.contacts.index')->with('fail', 'You have extended your contact limit');
                }
                try {
                    Excel::import(new ContactsImport($importContact->id, auth('customer')->user()), $file_url);
                    DB::commit();
                } catch (\Exception $ex) {
                    if (isset($ex->validator)) {
                        return redirect()->back()->withErrors($ex->validator->errors());
                    } else {
                        return redirect()->back()->withErrors(['msg' => $ex->getMessage()]);
                    }

                }
            }

        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return back()->with('fail', $ex->getMessage());
        }

        return back()->with('success', 'Import Contact Successfully Created');
    }

    public function search(Request $request)
    {
        $contacts = auth('customer')->user()->contacts();
        $contactsForCount=auth('customer')->user()->contacts();
        if ($request->ajax())
        {
            $page = $request->page;
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            if ($request->search) {
                $contacts->where('number', 'like', "%" . $request->search . "%")
                    ->orWhere('first_name', 'like', "%" . $request->search . "%")
                    ->orWhere('zip_code', 'like', "%" . $request->search . "%")
                    ->orWhere('address', 'like', "%" . $request->search . "%")
                    ->orWhere('last_name', 'like', "%" . $request->search . "%");

                $contactsForCount->where('number', 'like', "%" . $request->search . "%")
                    ->orWhere('first_name', 'like', "%" . $request->search . "%")
                    ->orWhere('zip_code', 'like', "%" . $request->search . "%")
                    ->orWhere('address', 'like', "%" . $request->search . "%")
                    ->orWhere('last_name', 'like', "%" . $request->search . "%");
            }

            $results=$contacts->skip($offset)->take($resultCount)->get();
            $count = $contactsForCount->count();
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;
            $finalResults = [];
            foreach ($results as $contact) {
                $finalResults[] = [
                    'id' => $contact->id,
                    'text' => $contact->number.' '.($contact->first_name?'('.$contact->first_name.' '.$contact->last_name.')':'')
                ];
            }
            $results = array(
                "results" => $finalResults,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }
}
