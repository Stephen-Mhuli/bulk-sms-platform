<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\DeviceGroup;
use App\Models\DeviceGroupName;
use Illuminate\Http\Request;

class DeviceGroupController extends Controller
{
    public function index(){

        return view('customer.device_group.index');
    }
    public function show(){
        $customers = auth('customer')->user()->device_groups()->select(['id', 'name', 'status']);
        return datatables()->of($customers)
            ->addColumn('device_name', function ($q) {
                $c = [];
                $device_names = DeviceGroupName::where('group_id', $q->id)->get();
                foreach ($device_names as $device_name) {
                    $c[] = trim($device_name->device_name);
                }
                $count=count($c);
                $text=$count>=100?' and more '.($q->device_group_name()->count()-$count):'';
                return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" . implode(", ", $c).$text. " </div>";
            })
            ->addColumn('status',function ($q){
                if ($q->status == 'active'){
                    return '<span class="pl-2 pr-2 pt-1 pb-1 bg-success" style="border-radius:25px;">'.$q->status.'</span>';
                }else {
                    return '<span class="pl-2 pr-2 pt-1 pb-1 bg-danger" style="border-radius:25px;">'.$q->status.'</span>';
                }
            })
            ->addColumn('action', function ($q) {

                return "<a class='btn btn-sm btn-info' href='" . route('customer.device-group.edit', [$q]) . "'>"."<i class='fas fa-edit'></i>"."</a> &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this from-group? <br><span class=\'text-danger text-sm\'>This will delete all the from numbers assigned to this group</span></br>"
                                        data-action=' . route('customer.device-group.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['action', 'device_name','status'])
            ->toJson();
    }
    public function create(){
        $data['devices'] = auth('customer')->user()->devices()->get();
        return view('customer.device_group.create',$data);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|unique:device_groups,name',
            'status' => 'required|in:active,inactive',
        ]);


        if (!$request->device_name || count($request->device_name) < 1){
            return  redirect()->back()->withErrors(['failed'=> trans('customer.messages.al_last_one_device_need')]);
        }

        $device_group = auth('customer')->user()->device_groups()->create($request->all());
        if ($request->device_name){
            foreach ($request->device_name as $device_name){
                $group_device_name = new DeviceGroupName();
                $group_device_name->group_id = $device_group->id;
                $group_device_name->device_name = $device_name;
                $group_device_name->save();
            }
        }

        return redirect()->route('customer.device-group.index')->with('success', trans('customer.messages.device_group_created'));
    }

    public function edit(DeviceGroup $device_group){
        $data['group'] = $device_group;
        $device_group_name = $device_group->device_group_name()->pluck('device_name');
        $data['device_group_names'] = json_decode($device_group_name);
        $data['devices'] = auth('customer')->user()->devices()->get();
        return view('customer.device_group.edit', $data);
    }

    public function update(DeviceGroup $device_group, Request $request){
        $request->validate([
            'name' => 'required|unique:device_groups,name,'.$device_group->id,
            'status' => 'required|in:active,inactive',
        ]);
        $device_group->update($request->all());

        if (!$request->device_name || count($request->device_name) < 1){
            return  redirect()->back()->withErrors(['failed'=> trans('customer.messages.al_last_one_device_need')]);
        }

        if ($request->device_name){
            foreach ($request->device_name as $device_name){
                $group_device_name = DeviceGroupName::where('group_id',$device_group->id)->first();
                $group_device_name->group_id = $device_group->id;
                $group_device_name->device_name = $device_name;
                $group_device_name->save();
            }
        }
        return redirect()->route('customer.device-group.index')->with('success', trans('customer.messages.device_group_updated'));
    }

    public function destroy(DeviceGroup $device_group){
        $campaign = Campaign::where('device_ids', $device_group->id)->first();
        if ($campaign) {
            return redirect()->back()->withErrors(['failed' => trans('customer.messages.device_group_used')]);
        }
        if ($device_group->device_group_name()){
            $device_group->device_group_name()->delete();
        }
        $device_group->delete();
        return redirect()->route('customer.device-group.index')->with('success', trans('customer.messages.device_group_deleted'));
    }
}
