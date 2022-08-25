<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerPlan;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'model' => 'required',
            'android_version' => 'required',
            'app_version' => 'required',
            'device_unique_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->messages()], 400);
        }
        $user = auth()->user();

        $existingDevice = Device::where('device_unique_id', $request->device_unique_id)->where('customer_id', $user->id)->first();
        if ($existingDevice) {
            return response()->json(['message' => 'Device already added'], 200);
        }
        $customer = auth()->user();
        $currentPlan = $customer->currentPlan();
        if (isset($currentPlan->renew_date) && $currentPlan->renew_date < Carbon::now()){
            return response()->json(['message' => 'Your Plan has expired'], 400);
        }

        $devices = Device::where('customer_id',$user->id)->count();
        $plan = $user->currentPlan();
        if (($devices) >= $plan->device_limit) {
            return response()->json(['message' => 'Your have extended your Device limit'], 400);
        }

        $device = new Device();
        $device->name = $request->name;
        $device->model = $request->model;
        $device->android_version = $request->android_version;
        $device->app_version = $request->app_version;
        $device->customer_id = $user->id;
        $device->status = 'active';
        $device->device_unique_id = $request->device_unique_id;
        $device->save();

        return response()->json(['message' => 'Device successfully added'], 201);
    }

    public function getDevices(){
        $devices = auth()->user()->devices()->get();
        return response()->json(['status'=>'success', 'data'=>$devices]);
    }
}
