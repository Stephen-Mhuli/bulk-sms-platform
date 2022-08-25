<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function index()
    {
        $data['drafts']=auth('customer')->user()->drafts;
        return view('customer.smsbox.draft',$data);
    }

    public function store(Request $request)
    {

        $from = $request->from;
        $to = $request->to;
        $scheduleCheck = $request->checked;
        $schedule = $request->schedule;
        $draft_id = $request->draft_id;
        $request['numbers'] = json_encode([
            'from' => $from,
            'to' => $to
        ]);

        if ($scheduleCheck == 'true') {
            $request['schedule_datetime'] =Carbon::createFromTimeString($schedule);
        }

        if ($draft_id) {
            $preDraft=Draft::find($draft_id);
            if(!$preDraft)
                return response()->json(['status'=>'fail','message'=>'Draft not found']);

            $validData=$request->only(['schedule_datetime','numbers','body']);
            auth('customer')->user()->drafts()->where('id', $draft_id)->update($validData);
            $draft = $preDraft;
        } else {
            $draft = auth('customer')->user()->drafts()->create($request->all());

        }

        return response()->json(['status' => 'success', 'message' => 'Message successfully drafted', 'data' => ['id' => $draft->id]]);

    }

    public function delete(Request $request){
        $draft = auth('customer')->user()->drafts()->where('id', $request->id)->first();
        if(!$draft)
            return redirect()->back()->with('fail','Draft not found');

        auth('customer')->user()->drafts()->where('id', $request->id)->delete();
        return redirect()->back()->with('success','Draft successfully removed');

    }
    public function move_draft(Request $request){
        $request->validate([
            'ids'=>'required'
        ]);
        $ids=explode(',', $request->ids);

        auth('customer')->user()->drafts()->whereIn('id',$ids)->delete();

        return back()->with('success', 'Message successfully moved to trash');

    }
}
