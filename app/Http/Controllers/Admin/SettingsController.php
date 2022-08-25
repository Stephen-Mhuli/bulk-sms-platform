<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    public function index()
    {
        $data['admin'] = auth()->user();
        return view('admin.settings.index', $data);
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'u_name' => 'required',
            'email' => 'required|unique:users,email,' . auth()->id(),
            'profile' => 'image',
        ]);
        if(env('APP_DEMO')){
            return redirect()->back()->with('fail','Update is not available on demo mode');
        }
        $pre_email = auth()->user()->email;
        $new_email = $request->email;
        $user = auth()->user();
        if ($pre_email != $new_email) {
            $user->email_verified_at = null;

            //TODO::send email here to verify email address
        }
        $user->name = $request->u_name;
        $user->email = $new_email;
        if ($request->password)
            $user->password = bcrypt($request->password);

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $user->profile_picture = $imageName;
        }

        $user->save();
        cache()->flush();
        return redirect()->back()->with('success', 'Profile successfully updated');
    }

    public function app_update(Request $request)
    {
        $request->validate([
            'app_name' => 'required',
            'logo'=>'image',
            'favicon'=>'image',
        ]);
        if(env('APP_DEMO')){
            return redirect()->back()->with('fail','Update is not available on demo mode');
        }
        //TODO:: in future update the settings dynamically

        //update application name
        $data = ['name' => 'app_name'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->app_name;
        $setting->save();

        $data = ['name' => 'crisp_token'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->crisp_token;

        $data = ['name' => 'recaptcha_site_key'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->recaptcha_site_key;
        $setting->save();

        $data = ['name' => 'link_apk'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->link_apk;
        $setting->save();

        $data = ['name' => 'registration_status'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->registration_status;
        $setting->save();

        $data = ['name' => 'landing_page_status'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->landing_page_status;
        $setting->save();

        $data=['name'=>'contact_info'];
        $requestData=$request->only('phone_number','email_address','address');
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($requestData);
        $setting->save();

        //update favicon
        if ($request->hasFile('favicon')) {

            $file = $request->file('favicon');
            $favicon_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $favicon_name);

            $data = ['name' => 'app_favicon'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = $favicon_name;
            $setting->save();
        }

        //update logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logo_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $logo_name);

            $data = ['name' => 'app_logo'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = $logo_name;
            $setting->save();
        }
        cache()->flush();
        return redirect()->back()->with('success', 'Application successfully updated');
    }

    public function smtp_update(Request $request)
    {
        $request->validate([
           'from'=>'required|email',
           'host'=>'required',
           'name'=>'required',
           'username'=>'required',
           'password'=>'required',
           'port'=>'required|numeric',
           'encryption'=>'required|in:ssl,tls',
        ]);
        unset($request['_token']);


        $from = "Picotech Support <demo@picotech.app>";
        $to = "Picotech Support <demo@picotech.app>";
        $subject = "Hi!";
        $body = "Hi,\n\nHow are you?";

        $host = $request->host;
        $port = $request->port;
        $username = $request->username;
        $password = $request->password;
        $config = array(
            'driver' => 'smtp',
            'host' => $host,
            'port' => $port,
            'from' => array('address' => $request->from, 'name' => $request->name),
            'encryption' => $request->encryption,
            'username' => $username,
            'password' => $password,
        );
        Config::set('mail', $config);

        try {
            Mail::send('sendMail', ['htmlData' => $body], function ($message) {
                $message->to("tuhin.picotech@gmail.com")->subject
                ("Setting check");
            });
        } catch (\Exception $ex) {
            dd($ex->getMessage());
            return redirect()->back()->withErrors(['msg' => trans('Invalid email credentials')]);
        }


        foreach ($request->all() as $key => $req) {
            $data = ['name' => 'mail_' . $key];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = $request->$key;
            $setting->save();
        }
        //we need to flush the cache as settings are from cache
        cache()->flush();

        return back()->with('success', 'SMTP configuration successfully updated');
    }


    public function templateStore(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required'
        ]);
        $user = auth()->user();
        $emailTemplate = isset($request->emailTemplateID) ? EmailTemplate::find($request->emailTemplateID) : new EmailTemplate();
        $emailTemplate->user_id = $user->id;
        $emailTemplate->type = $request->type;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->body = $request->body;
        $emailTemplate->status = 'active';

        $emailTemplate->save();
        cache()->flush();
        return redirect()->back()->with('success', trans('admin.message.message.setting_update'));
    }
    public function local_settings(Request $request)
    {

        $request->validate([
            'language' => 'required',
            'date_time_format' => 'required',
            'date_time_separator' => 'required',
            'timezone' => 'required',
            'decimal_format' => 'required',
            'currency_symbol' => 'required',
            'currency_symbol_position' => 'required',
            'thousand_separator' => 'required',
            'decimals' => 'required',
            'direction' => 'in:rtl,ltr'

        ]);
        $availableLang = get_available_languages();
        $type = $request->language;

        if (!in_array($type, $availableLang)){
            abort('404');
        }

        session()->put('locale', $type);
        app()->setLocale($type);


        $localSetting = $request->only('thousand_separator', 'decimals', 'language', 'date_time_format', 'date_time_separator', 'timezone', 'decimal_format', 'currency_symbol', 'currency_code', 'currency_symbol_position', 'direction');
        $data = ['name' => 'local_setting'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($localSetting);
        $setting->save();
        cache()->flush();

        return redirect()->back()->with('success', trans('admin.message.local_setting_updated'));
    }

}
