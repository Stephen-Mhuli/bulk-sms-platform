<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerSettings;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $data['customer'] = $customer = auth('customer')->user();
        $data['customer_plan'] = auth('customer')->user()->currentPlan();

        $settings = $customer->settings;
        $customer_settings = [];
        foreach ($settings as $setting) {
            $customer_settings[$setting->name] = $setting->value;
        }
        $data['sms_templates'] = SmsTemplate::where('customer_id', $customer->id)->get();

        $data['customer_settings'] = $customer_settings;

        $data['sending_settings'] = isset($customer_settings['sending_settings'])? (array)json_decode($customer_settings['sending_settings']) : [];
        return view('customer.settings.index', $data);
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|unique:customers,email,' . auth('customer')->id(),
            'profile' => 'image'
        ]);

        $user = auth('customer')->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $user->profile_picture = $imageName;
        }
        $user->save();
        return redirect()->back()->with('success', 'Profile successfully updated');
    }

    public function password_update(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        if(env('APP_DEMO')){
            return redirect()->back()->with('fail','Update is not available on demo mode');
        }

        $customer = auth('customer')->user();

        if (!Hash::check($request->old_password, $customer->password)) {
            return back()->with('fail', 'Invalid old password. Please try with valid password');
        }

        $customer->password = bcrypt($request->new_password); //remove the bcrypt
        $customer->save();

        return redirect()->back()->with('success', 'Password successfully changed');

    }

    public function notification_update(Request $request)
    {
        $request->validate([
            'isChecked' => 'required|in:true,false'
        ]);
        $data = [
            'name' => 'email_notification',
        ];

        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = $request->isChecked;
        $setting->save();

        return response()->json(['status' => 'success', 'message' => 'Email notification updated']);
    }

    public function webhookUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:get,post',
        ]);

        $data = [
            'name' => 'webhook',
        ];
        $customerNumbers = auth('customer')->user()->numbers;

        $setting = auth('customer')->user()->settings()->firstOrNew($data);

        $updatedId = [];
        foreach ($customerNumbers as $customerNumber) {
            if (!$customerNumber->webhook_url || isset(json_decode($setting->value)->url) && $customerNumber->webhook_url == json_decode($setting->value)->url) {
                $updatedId[] = $customerNumber->id;
            }
        }

        $setting->value = json_encode($request->only('url', 'type'));
        $setting->save();
        $customerNumberUpdate = $customerNumbers->whereIn('id', $updatedId);
        foreach ($customerNumberUpdate as $update) {
            $update->webhook_url = $request->url;
            $update->webhook_method = $request->type;
            $update->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook updated successfully']);
    }

    public function dataPosting(Request $request)
    {
        $request->validate([
            'type' => 'required|in:get,post',
        ]);

        $data = [
            'name' => 'data_posting',
        ];
        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('url', 'type'));
        $setting->save();
        cache()->flush();
        return response()->json(['status' => 'success', 'message' => 'Data Posting URL updated successfully']);
    }

    public function downloadSample($type, Request $request)
    {
        if ($type == 'group') {
            return response()->download(public_path('csv/sample-group.csv'));
        }
    }

    public function sending_setting(Request $request)
    {
        $request->validate([
            'minute_limit'=>'required|numeric|min:1'
        ]);

        $customer_plan= auth('customer')->user()->currentPlan();

        if($customer_plan->daily_send_limit<$request->daily_send_limit){
            return redirect()->back()->with('fail','Daily send limit can not extend your plan limit')->withInput();
        }
        $data = ['name' => 'sending_settings'];

        $days = [];
        if ($request->offday) {
            foreach ($request->offday as $key => $day) {
                $days[] = strtolower($day);
            }
        }

        $request['offdays'] = json_encode($days);
        $sendData = $request->only('daily_send_limit', 'message_limit', 'minute_limit', 'start_time', 'end_time', 'offdays');
        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($sendData);
        $setting->save();

        cache()->flush();
        return redirect()->back()->with('success', 'Sending setting successfully updated');
    }

}
