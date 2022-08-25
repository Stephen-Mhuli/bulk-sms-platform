<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function applicationSetting()
    {
        $app_name = get_settings('app_name');
        $recaptcha_site_key = get_settings('recaptcha_site_key');
        $registration_status = get_settings('registration_status');
        $landing_page_status = get_settings('landing_page_status');
        $contact_info = get_settings('contact_info');

        $data = [
            'app_name' => $app_name,
            'recaptcha_site_key' => $recaptcha_site_key,
            'registration_status' => $registration_status,
            'landing_page_status' => $landing_page_status,
            'contact_info' => $contact_info,
        ];

        return response()->json(['data' => $data]);
    }

    public function sendingSetting()
    {
        $sendingSetting = auth()->user()->sending_settings()->first();
        if (!$sendingSetting) {
            return response()->json(['message' => 'Sending settings not configured'], 400);
        }
        return response()->json(['data' => json_decode($sendingSetting->value)]);
    }
}
