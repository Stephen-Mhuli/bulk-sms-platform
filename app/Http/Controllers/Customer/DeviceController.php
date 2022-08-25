<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Label;
use App\Models\MessageLog;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(){

        return view('customer.device.index');
    }

    public function getAll(){
        $devices = auth('customer')->user()->devices()->withCount(['sent_messages']);
        return datatables()->of($devices)
            ->addColumn('total_sent_message',function($q){
                return $q->sent_messages_count;
            })
            ->addColumn('status',function ($q){
                if ($q->status=='active'){
                    return '<button type="button" class="btn light btn-sm bg-success bg-act dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Active
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to inactive this device?" data-action=' . route('customer.device.status', ['id' => $q->id, 'status' => 'inactive']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Inactive
                                     </button>
                                </div>';
                }elseif($q->status=='inactive'){
                    return '<button type="button" class="btn light btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Inactive
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to active this device?" data-action=' . route('customer.device.status', ['id' => $q->id, 'status' => 'active']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Active
                                     </button>
                                </div>';
                } else{
                    return $q->status;
                }
            })
            ->addColumn('action',function(Device $q){
                return '<a class="btn btn-sm btn-info mr-2" data-toggle="tooltip" data-placement="top" title="Edit" href="'.route('customer.device.edit',[$q]).'"><i class="fas fa-edit"></i></a>'.'<button class="btn btn-sm btn-danger" data-message="Are you sure you want to remove this device?"
                                        data-action='.route('customer.device.destroy',[$q]).'
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>' ;
            })
            ->rawColumns(['status','action'])
            ->toJson();
    }

    public function create(){

        return view('customer.device.create');
    }


    public function edit(Device $device){
        $data['device']=$device;
        return view('customer.device.edit', $data);
    }

    public function update(Device $device,Request $request){

        $request->validate([
            'name'=>'required',
            'status'=>'required',
        ]);

        $device->update($request->only('name', 'status'));

        return redirect()->route('customer.device.index')->with('success', 'Device successfully updated');
    }

    public function status(Request $request){
        $id= $request->id;
        $status= $request->status;
        $request->validate([
            'status'=>'required|in:active,inactive'
        ]);

        $device = auth('customer')->user()->devices()->where('id', $id)->firstOrFail();
        $device->status= $status;
        $device->save();

        return redirect()->route('customer.device.index')->with('success', 'Device status successfully changes');
    }

    public function destroy(Device $device){
        $user= auth()->user();
        if ($user->id != $device->customer_id){
            return abort(404);
        }
        $device->delete();

        return redirect()->route('customer.device.index')->with('success', 'Device successfully deleted');
    }
}
