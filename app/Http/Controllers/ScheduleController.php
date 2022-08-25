<?php

namespace App\Http\Controllers;

use App\Events\SendMail;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Number;
use App\Models\SmsQueue;
use App\SmsProvider\SendSMS;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function processEmail(){
        Artisan::call("queue:work",['--stop-when-empty'=>true]);
    }
}
