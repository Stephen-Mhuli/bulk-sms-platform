<?php

namespace App\Listeners;

use App\Events\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendMailListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SendMail $event
     * @return void
     */
    public function handle(SendMail $event)
    {
        Mail::send('sendMail', ['htmlData' => $event->message], function ($message) use ($event) {
            $message->to($event->to)->subject
            ($event->subject);
        });
    }
    public $tries = 2;

}
