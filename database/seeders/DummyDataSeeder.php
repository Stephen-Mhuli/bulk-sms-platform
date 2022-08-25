<?php

namespace Database\Seeders;

use App\Models\AuthorizationToken;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\CustomerSettings;
use App\Models\Group;
use App\Models\Label;
use App\Models\Plan;
use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            'first_name' => 'Customer',
            'last_name' => 'Demo',
            'email' => 'customer@demo.com',
            'password' => bcrypt('123456'),
            'status' => 'active',
            'email_verified_at' => now(),
            'admin_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];

        $customer=\App\Models\Customer::create($users);
        $access_token= $customer->createToken($customer->email)->plainTextToken;
        $preToken = AuthorizationToken::where('customer_id', $customer->id)->first();
        $authorization = isset($preToken) ? $preToken : new AuthorizationToken();
        $authorization->access_token = $access_token;
        $authorization->customer_id=$customer->id;
        $authorization->refresh_token = $access_token;
        $authorization->save();

        $setting= new CustomerSettings();
        $setting->customer_id = $customer->id;
        $setting->name = 'email_notification';
        $setting->value = 'false';
        $setting->save();

        $label = new Label();
        $label->title='new';
        $label->customer_id=$customer->id;
        $label->color='red';
        $label->status='active';
        $label->save();

        //Assigning plan to customer
        $plan = Plan::first();
        $customer->plans()->create(['plan_id' => $plan->id,
            'contact_limit' => 99999,
            'daily_send_limit' => 99999,
            'daily_receive_limit' => 99999,
            'device_limit' =>9999,
            'is_current' => 'yes',
            'status' => 'accepted',
            'price' => $plan->price]);

        $customer->devices()->create([
            'device_unique_id'=>'a0c515dde41ae01b',
            'name'=>'samsung',
            'model'=>'SM-A207F',
            'android_version'=>'30',
            'app_version'=>'1.0',
        ]);

        Contact::factory()->count(50)->create();
        Group::factory()->count(1)->create();
        ContactGroup::factory()->count(50)->create();
        SmsTemplate::factory()->count(1)->create();
    }
}
