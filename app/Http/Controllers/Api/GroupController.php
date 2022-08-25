<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $groups = $user->groups()->select(['id','name', 'status', 'created_at', 'import_status', 'import_fail_message'])->get();

        return response()->json(['status' => 'success', ' data' => $groups]);
    }

    public function groupContact(Request $request){
        $user = auth()->user();
        $groupId= $user->groups()->where('id', $request->id)->firstOrFail();
        $contactGroup = ContactGroup::where('group_id', $groupId)->where('customer_id', $user->id)->pluck('contact_id');
        $contacts= Contact::select('first_name','last_name','number', 'email', 'city', 'state','zip_code', 'note', 'address', 'company','created_at', 'updated_at')->whereIn('id', $contactGroup)->get();

        return response()->json(['status' => 'success', ' data' => $contacts]);

    }
}
