<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => ['auth:sanctum'],'namespace'=>'Api'], function () {
//    Add Device
    Route::get('/get/devices', ['uses' => 'DeviceController@getDevices', 'as' => 'get.devices']);
    Route::post('/add/device', ['uses' => 'DeviceController@store', 'as' => 'add.device']);
    Route::get('/queues', ['uses' => 'ScheduleController@getQueues', 'as' => 'queue.list']);
    Route::post('/queue/update/status', ['uses' => 'ScheduleController@updateQueueStatus', 'as' => 'queue.update.status']);
    Route::post('/inbound', ['uses' => 'ScheduleController@inbound', 'as' => 'inbound']);

    Route::get('/inbox/list', ['uses' => 'MessageController@inbox', 'as' => 'inbox.list']);
    Route::get('/sent/list', ['uses' => 'MessageController@sent', 'as' => 'sent.list']);
//    group list
    Route::get('/group/list', ['uses' => 'GroupController@index', 'as' => 'group.list']);
    Route::get('/group/contact/list', ['uses' => 'GroupController@groupContact', 'as' => 'group.contact.list']);
//    Customer Number
    Route::get('/customer/number', ['uses' => 'CustomerNumberController@index', 'as' => 'customer.number']);
//    SMS Template
    Route::get('/sms/template', ['uses' => 'SMSTemplateController@index', 'as' => 'sms.template']);
//Campaign
    Route::get('/campaign/list', ['uses' => 'CampaignController@index', 'as' => 'campaign.list']);
    Route::post('/campaign/store', ['uses' => 'CampaignController@store', 'as' => 'campaign.store']);
    Route::get('/campaign/statistic/{id}', ['uses' => 'CampaignController@statistic', 'as' => 'campaign.statistic']);
//    Get SMS Template
    Route::get('/sms/template', ['uses' => 'CampaignController@getTemplate', 'as' => 'sms.template']);
//    Compose
    Route::post('/sent/compose', ['uses' => 'ComposeController@sentCompose', 'as' => 'sent.compose']);
//    Sender Ids
    Route::get('/sender-id', ['uses' => 'ComposeController@getSenderIds', 'as' => 'sender.id']);
//    SMS Queue
   // Route::get('/sms-queue', ['uses' => 'MessageController@smsQueue', 'as' => 'sms.queue']);

//    Settings
    Route::get('/application/setting', ['uses' => 'SettingsController@applicationSetting', 'as' => 'application.setting']);
    Route::get('/sending/setting', ['uses' => 'SettingsController@sendingSetting', 'as' => 'sending.setting']);

    // Contact
Route::get('/contacts', ['uses' => 'ContactController@contacts', 'as' => 'contacts']);
Route::get('/contact/details', ['uses' => 'ContactController@numberDetails', 'as' => 'contact.details']);

});
//    Authentication
Route::post('/authentication', ['uses' => 'Api\AuthController@authentication', 'as' => 'authentication']);
