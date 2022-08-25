<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::redirect('/', '/login');
//#region admin route
Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {

    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', ['uses' => 'Auth\AdminLoginController@index', 'as' => 'login']);
        Route::post('/login', ['uses' => 'Auth\AdminLoginController@authenticate', 'as' => 'authenticate']);

        Route::get('/password/reset', ['uses' => 'Auth\ForgotPasswordController@showLinkRequestFormAdmin', 'as' => 'password.request']);
        Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmailAdmin')->name('password.email');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/logout', ['uses' => 'Auth\AdminLoginController@logout', 'as' => 'logout']);

        Route::group(['namespace' => 'Admin'], function () {
            Route::get('/dashboard', ['uses' => 'DashboardController@index', 'as' => 'dashboard']);

            Route::resource('/customers', 'CustomerController');
            Route::resource('/numbers', 'NumberController');
            Route::resource('/plans', 'PlanController');
            Route::resource('/page', 'PageController');

            Route::group(['as' => 'customer.', 'prefix' => 'customer'], function () {
                Route::get('/all', 'CustomerController@getAll')->name('get.all');
                Route::post('/assign-number', 'CustomerController@assignNumber')->name('number.assign');
                Route::post('/remove-number', 'CustomerController@removeNumber')->name('number.remove');
                Route::post('/change-plan', 'CustomerController@changePlan')->name('plan.change');
                Route::post('/login-as', 'CustomerController@loginAs')->name('login.ass');
            });

            Route::group(['as' => 'number.', 'prefix' => 'number'], function () {
                Route::get('/all', 'NumberController@getAll')->name('get.all');
                Route::get('/requests', 'NumberController@requests')->name('requests');
                Route::get('/requests/get', 'NumberController@get_requests')->name('get.requests');
                Route::post('/requests/response', 'NumberController@request_response')->name('requests.response');
            });

            Route::group(['as' => 'plan.', 'prefix' => 'plan'], function () {
                Route::get('/all', 'PlanController@getAll')->name('get.all');
                Route::get('/requests', 'PlanController@requests')->name('requests');
                Route::get('/requests/get', 'PlanController@get_requests')->name('get.requests');
            });

            Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
                Route::get('/', 'SettingsController@index')->name('index');
                Route::post('/update/profile', 'SettingsController@profile_update')->name('profile_update');
                Route::post('/update/application', 'SettingsController@app_update')->name('app_update');
                Route::post('/update/smtp', 'SettingsController@smtp_update')->name('smtp_update');
                Route::post('/email-template/store', 'SettingsController@templateStore')->name('email.template.store');
                Route::post('/update/local/setting', 'SettingsController@local_settings')->name('local.setting');
                Route::post('/sending-setting', 'SettingsController@sending_setting')->name('sending.setting');

            });

            Route::group(['as' => 'ticket.', 'prefix' => 'ticket'], function () {
                Route::get('/', 'TicketController@index')->name('index');
                Route::get('/get-all', 'TicketController@show')->name('get.all');
                Route::post('/store', 'TicketController@store')->name('store');
                Route::get('/reply', 'TicketController@reply')->name('reply');
                Route::post('/status', 'TicketController@status')->name('status');
                Route::get('/download', 'TicketController@documentDownload')->name('download');
            });


            Route::group(['as' => 'addon.', 'prefix' => 'addon'], function () {
                Route::get('/', 'AddonController@index')->name('index');
                Route::get('/import', 'AddonController@import')->name('import');
                Route::post('/import', 'AddonController@importPost')->name('import');
                Route::get('/get/all', 'AddonController@getAll')->name('get.all');
                Route::delete('/uninstall', 'AddonController@uninstall')->name('uninstall');
                Route::post('/change-status', 'AddonController@changeStatus')->name('change-status');

            });

        });
    });

});
//#endregion


//#region customer routes

//Guest customer route
Route::group(['middleware' => 'guest:customer'], function () {
    Route::get('/login', ['uses' => 'Auth\CustomerLoginController@index', 'as' => 'login']);
    Route::post('/login', ['uses' => 'Auth\CustomerLoginController@authenticate', 'as' => 'authenticate']);

    Route::get('/sign-up', ['uses' => 'Auth\CustomerLoginController@sign_up', 'as' => 'signup']);
    Route::post('/sign-up', ['uses' => 'Auth\CustomerLoginController@sign_up_create', 'as' => 'signup']);

    Route::get('password/reset', 'Auth\ForgotPasswordController@show_form')->name('password.request');
    Route::post('password/reset', 'Auth\ForgotPasswordController@sent_email')->name('password.sent');
    Route::get('password/reset/confirm', 'Auth\ForgotPasswordController@reset_form')->name('password.reset.confirm');
    Route::post('password/reset/confirm', 'Auth\ForgotPasswordController@reset_confirm')->name('password.reset.confirm');

    Route::post('/inbound/{type}', ['uses' => 'InboundController@process', 'as' => 'inbound.process']);
    Route::any('/webhook/deliver', ['uses' => 'InboundController@webhookDeliver', 'as' => 'webhook.deliver']);
    Route::get('/verify/', ['uses' => 'Auth\CustomerLoginController@verifyView', 'as' => 'customer.verify.view']);
    Route::get('/verify/customer', ['uses' => 'Auth\CustomerLoginController@verify', 'as' => 'customer.verify']);
    Route::get('/gateway/notification', ['uses' => 'InboundController@gatewayStatus', 'as' => 'sms.gateway.status']);

});

//Auth customer route
Route::group(['as' => 'customer.', 'middleware' => ['auth:customer', 'email.verify:customer']], function () {
    Route::get('/logout', ['uses' => 'Auth\CustomerLoginController@logout', 'as' => 'logout']);


    Route::group(['namespace' => 'Customer'], function () {
        Route::get('/dashboard', ['uses' => 'DashboardController@index', 'as' => 'dashboard']);

        Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
            Route::get('/index', ['uses' => 'SettingsController@index', 'as' => 'index']);
            Route::post('/profile-update', ['uses' => 'SettingsController@profile_update', 'as' => 'profile_update']);
            Route::post('/password-update', ['uses' => 'SettingsController@password_update', 'as' => 'password_update']);
            Route::post('/notification-update', ['uses' => 'SettingsController@notification_update', 'as' => 'notification_update']);
            Route::post('/webhook/update', ['uses' => 'SettingsController@webhookUpdate', 'as' => 'webhook_update']);
            Route::post('/data/posting', ['uses' => 'SettingsController@dataPosting', 'as' => 'data_posting']);
            Route::post('/sending-setting', 'SettingsController@sending_setting')->name('sending.update');

        });
        Route::group(['as' => 'smsbox.', 'prefix' => 'smsbox'], function () {
            //inbox
            Route::get('/inbox', 'InboxController@index')->name('inbox');
            Route::post('/inbox/trash', 'InboxController@move_trash')->name('inbox.trash');
            Route::post('/inbox/change-status', 'InboxController@changeStatus')->name('inbox.change-status');

            //sent
            Route::get('/sent', 'SentController@index')->name('sent');
            Route::post('/sent/trash', 'SentController@move_trash')->name('sent.trash');

            //draft
            Route::get('/draft', 'DraftController@index')->name('draft');
            Route::post('/draft/store', 'DraftController@store')->name('draft.store');
            Route::post('/draft/delete', 'DraftController@delete')->name('draft.delete');
            Route::post('/move/draft', 'DraftController@move_draft')->name('move.draft');

            //trash
            Route::get('/trash', 'TrashController@index')->name('trash');
            Route::post('/remove/trash', 'TrashController@remove_trash')->name('remove.trash');

            //compose
            Route::get('/compose', 'ComposeController@index')->name('compose');
            Route::post('/compose/sent', 'ComposeController@sentCompose')->name('compose.sent');

            //Queue
            Route::get('/queue', 'ComposeController@queueList')->name('queue');
            //overview
            Route::get('/overview', 'ComposeController@overview')->name('overview');
            Route::get('/overview/get/data', 'ComposeController@overview_get_data')->name('overview.get.data');
            Route::delete('/overview/data/delete', 'ComposeController@overview_data_delete')->name('overview.data.delete');


        });

 //            API Token
        Route::get('/authorization/token/create', 'AuthorizationController@index')->name('authorization.token.create');
        Route::post('/authorization/token/store', 'AuthorizationController@store')->name('authorization.token.store');

        Route::group(['as' => 'billing.', 'prefix' => 'billing'], function () {
            Route::get('/', 'BillingController@index')->name('index');
            Route::get('/phone-numbers', 'BillingController@phone_numbers')->name('phone_numbers');
            Route::get('/get-numbers', 'BillingController@get_numbers')->name('get.numbers');
            Route::post('/update', 'BillingController@update')->name('update');
            Route::get('/change', 'BillingController@change_billing')->name('change.billing');
            Route::post('/pending/plan/submit/form', 'BillingController@pending_plan_submit_form')->name('pending.plan.submit.form');
            Route::get('/pending/plan', 'BillingController@pending_plan')->name('pending.plan');
        });

        //Contacts
        Route::resource('/contacts', 'ContactController');
        Route::group(['as' => 'contact.', 'prefix' => 'contact'], function () {
            Route::get('/get', 'ContactController@getAll')->name('get.all');
            Route::get('/search', 'ContactController@search')->name('get.search');
            Route::get('/import/contacts/create', 'ContactController@import_contacts')->name('import.contacts');
            Route::post('/import/contacts/show', 'ContactController@import_contacts_show')->name('import.contacts.show');
            Route::post('/import/contacts/store', 'ContactController@import_contacts_store')->name('import.contacts.store');
        });
//Device Group
        Route::resource('/device-group', 'DeviceGroupController');
        Route::get('/get/all/device-group', 'DeviceGroupController@show')->name('get.all.device.group');

//        Chat Response
        Route::get('/chat/response/', 'ChatResponseController@index')->name('chat.response');
        Route::get('/get/all/chat/response/', 'ChatResponseController@getAll')->name('get.all.chat.response');
        Route::post('/chat/response/store', 'ChatResponseController@store')->name('chat.response.store');
        Route::post('/chat/response/update', 'ChatResponseController@update')->name('chat.response.update');
        Route::get('/chat/response/delete', 'ChatResponseController@delete')->name('chat.response.delete');


        //Groups
        Route::resource('/groups', 'GroupController');
        Route::group(['as' => 'group.', 'prefix' => 'group'], function () {
            Route::get('/get', 'GroupController@getAll')->name('get.all');
            Route::get('/get/numbers', 'GroupController@getAllNumbers')->name('get.numbers');

        });
        Route::get('/export/group/contact/', 'GroupController@exportContact')->name('export.group.contact');
        Route::get('/group-records', 'GroupController@groupRecords')->name('group.records');
        Route::get('/filtered-records', 'GroupController@filteredRecord')->name('group.filter.records');
        Route::post('/new-group', 'GroupController@newGroup')->name('create.new.group');

//Campaign Reports
        Route::get('/campaign/report/', 'CampaignController@report')->name('campaign.report');
//        Campaign Statistic
        Route::get('/campaign/statistic/{id}', 'CampaignController@statistic')->name('campaign.statistic');

        Route::resource('/campaign', 'CampaignController');
        Route::get('/get/sms-template', 'CampaignController@getTemplate')->name('get.sms.template');
        Route::get('/get/campaigns', 'CampaignController@getAll')->name('get.campaings');
        Route::post('/campaigns/status', 'CampaignController@status')->name('campaign.status');

//        sms template
        Route::post('sms/template', 'SmsTemplateController@store')->name('sms.template');
        Route::delete('sms/template/delete', 'SmsTemplateController@delete')->name('sms.template.delete');

        //Chat
        Route::group(['as' => 'chat.', 'prefix' => 'chat'], function () {
            Route::get('/index', 'ChatController@index')->name('index');
            Route::get('/get/data', 'ChatController@get_data')->name('get.data');
            Route::post('/label/update', 'ChatController@label_update')->name('label.update');
            Route::get('/get/numbers', 'ChatController@get_numbers')->name('get.numbers');
            Route::get('/get/chats', 'ChatController@get_chats')->name('get.chats');
            Route::get('/get/chat/response', 'ChatController@get_chat_response')->name('get.chat.response');
        });

        Route::post('/exception/', 'ChatController@exception')->name('exception');
        Route::post('/add/new/contact', 'ChatController@addNewContact')->name('add.new.contact');
        Route::post('/send/contact/data', 'ChatController@sendContactInfo')->name('send.contact.data');

        Route::resource('/label', 'LabelController');
        Route::get('/get/all/labels', 'LabelController@getAll')->name('get.all.labels');
        Route::group(['as' => 'ticket.', 'prefix' => 'ticket'], function () {
            Route::get('/', 'TicketController@index')->name('index');
            Route::post('/store', 'TicketController@store')->name('store');
            Route::get('/get-all', 'TicketController@show')->name('get.all');
            Route::get('/details', 'TicketController@details')->name('details');
            Route::post('/reply', 'TicketController@reply')->name('reply');
            Route::get('/download', 'TicketController@documentDownload')->name('download');
        });

        Route::resource('/campaign', 'CampaignController');
        Route::get('/get/sms-template', 'CampaignController@getTemplate')->name('get.sms.template');
        Route::get('/get/campaigns', 'CampaignController@getAll')->name('get.campaings');
        Route::post('/campaigns/status', 'CampaignController@status')->name('campaign.status');

//        sms template
        Route::post('sms/template', 'SmsTemplateController@store')->name('sms.template');
        Route::delete('sms/template/delete', 'SmsTemplateController@delete')->name('sms.template.delete');

        //download sample
        Route::get('/download/sample/{type}','SettingsController@downloadSample')->name('download.sample');


            Route::get('/get/all/device', 'DeviceController@getAll')->name('get.all.device');
            Route::post('/device/status', 'DeviceController@status')->name('device.status');
            Route::resource('/device', 'DeviceController');

    });
});

//#endregion

Route::get('/process/email', ['uses' => 'ScheduleController@processEmail', 'as' => 'email.process']);
Route::get('/demo/login', ['uses' => 'FrontController@demoLogin', 'as' => 'login.demo']);

Route::get('/process/upgrade', ['uses' => 'UpgradeController@process', 'as' => 'process.upgrade']);

//Route::redirect('/', route('login'));
Route::redirect('/admin', route('admin.login'));

Route::get('/test',['uses'=>'UpgradeController@test','as'=>'test.gen']);

//Route::get('{url}',['uses' => 'RouteController@index']);
Route::get('locale/{type}', [\App\Http\Controllers\Admin\DashboardController::class, 'setLocale'])->name('set.locale');

Route::post('/verify/user','FrontController@verifyCode')->name('verify');
