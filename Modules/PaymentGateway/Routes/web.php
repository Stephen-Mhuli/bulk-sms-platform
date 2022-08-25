<?php

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

Route::name('paymentgateway::')->prefix('payment')->group(function() {
    Route::get('/', 'PaymentGatewayController@index');
    Route::post('/process', 'PaymentGatewayController@process')->name('process');
    Route::get('/email/payment/process', 'PaymentGatewayController@email_payment_process')->name('email.payment.process');
    Route::post('/process/paynow', 'PaymentGatewayController@payNow')->name('process.paynow');
    Route::post('/setting/gateway/store', 'SettingController@store')->name('payment.settings.store');
//    Coin Payment
    Route::post('/process/coin/payment', 'PaymentGatewayController@coinPayment')->name('process.coin.payment');
//    Check payment validity
    Route::get('/check-payment/validity', 'PaymentGatewayController@checkValidPayment')->name('check.payment.validity');

    Route::post('/payment/paytm/success',' PaymentGatewayController@processPaytmRedirect')->name('payment.paytm.redirect');
//Mollie
    Route::get('/payment/process/mollie',' PaymentGatewayController@processMollieSuccess')->name('payment.mollie.success');
    Route::post('/payment/plan-change/mollie/{id}',' PaymentGatewayController@processMollieWebhook')->name('payment.changeplan.mollie.webhook');
//    Paystack
    Route::get('/payment/paystack/process',' PaymentGatewayController@processPaystackPayment')->name('payment.paystack.process');

    Route::get('/process/success', 'PaymentGatewayController@paymentSuccess')->name('payment.process.success');
    Route::get('/process/cancel', 'PaymentGatewayController@paymentCancel')->name('payment.process.cancel');
});
Route::post('/coin/payment/webhook', ['uses' => 'PaymentGatewayController@webhook', 'as' => 'coin.payment']);

