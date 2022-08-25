<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CampaignCreateChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $daily_sent_limit;
    private $send_speed;
    private $campaign_running_date;
    private $user;
    private $offset;
    private $take_count = 5000;
    private $message;
    private $to_numbers;
    private $from_number;
    private $sms_queue;
    /**
     * @var string
     */
    private $device_unique_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message, $dailySentLimit, $campaignRunningDate, $sendSpeed, $user, $offset)
    {
        $this->message = $message;
        $this->daily_sent_limit = $dailySentLimit;
        $this->campaign_running_date = $campaignRunningDate;
        $this->send_speed = $sendSpeed;
        $this->user = $user;
        $this->offset = $offset;
        $numberArray = json_decode($message->numbers);
        $this->to_numbers = $numberArray->to ?? [];
        $this->from_number = $numberArray->from ?? [];
        $this->device_unique_id = $numberArray->device_unique_id ?? '';
        $this->sms_queue = [];
        sort($this->to_numbers);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaignRunningDate=Carbon::parse($this->campaign_running_date);
        $toNumber = array_slice($this->to_numbers, $this->offset, ($this->daily_sent_limit));
        if ($toNumber) {
            $generatedToNumbers = [];
            $contacts = Contact::where('customer_id', $this->user->id)->whereIn('number', $toNumber)->orderBy('number')->get()->unique('number');
            foreach ($contacts as $contact) {

                $templates = json_decode($this->message->body);
                $templateBody = $templates[random_int(0,count($templates)-1)];
                if ($contact->first_name) {
                    $templateBody = str_replace('{first_name}', $contact->first_name, $templateBody);
                } else {
                    $templateBody = str_replace('{first_name}', ' ', $templateBody);
                }
                if ($contact->last_name) {
                    $templateBody = str_replace('{last_name}', $contact->last_name, $templateBody);
                } else {
                    $templateBody = str_replace('{last_name}', ' ', $templateBody);
                }
                if ($contact->address) {
                    $templateBody = str_replace('{address}', $contact->address, $templateBody);
                } else {
                    $templateBody = str_replace('{address}', ' ', $templateBody);
                }
                if ($contact->city) {
                    $templateBody = str_replace('{city}', $contact->city, $templateBody);
                } else {
                    $templateBody = str_replace('{city}', ' ', $templateBody);
                }
                if ($contact->state) {
                    $templateBody = str_replace('{state}', $contact->state, $templateBody);
                } else {
                    $templateBody = str_replace('{state}', ' ', $templateBody);
                }
                if ($contact->zip_code) {
                    $templateBody = str_replace('{zip_code}', $contact->zip_code, $templateBody);
                } else {
                    $templateBody = str_replace('{zip_code}', ' ', $templateBody);
                }
                if ($contact->email) {
                    $templateBody = str_replace('{email}', $contact->email, $templateBody);
                } else {
                    $templateBody = str_replace('{email}', ' ', $templateBody);
                }
                if (!in_array($contact->number, $generatedToNumbers)) {
                    $toNumber=$contact->number;

                    $this->sms_queue[] = [
                        'message_id' => $this->message->id,
                        'campaign_id' => $this->message->campaign_id,
                        'from' => $this->from_number,
                        'device_unique_id' =>  $this->device_unique_id,
                        'to' => $toNumber,
                        'schedule_datetime' => null,
                        'body' => $templateBody,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $generatedToNumbers[] = $contact->number;
                }
            }

            foreach (array_chunk($this->sms_queue, $this->take_count) as $key => $daily_sms_queues) {
                $final_sms_queue = [];

                foreach ($daily_sms_queues as $queue) {
                    $addSeconds=floor($this->send_speed) < 1 ? 1 : floor($this->send_speed);
                    $final_sms_queue[] = [
                        'message_id' => $queue['message_id'],
                        'campaign_id' => $queue['campaign_id'],
                        'from' => $queue['from'],
                        'device_unique_id' =>  $queue['device_unique_id'],
                        'to' => $queue['to'],
                        'schedule_datetime' => $campaignRunningDate->addSeconds($addSeconds)->toDateTimeString(),
                        'body' => $queue['body'],
                        'created_at' => now(),
                        'updated_at' => now(),
                        'type'=> 'sent',
                    ];
                }
                if ($final_sms_queue) {
                    $this->user->sms_queues()->createMany($final_sms_queue);
                    $this->user->message_logs()->createMany($final_sms_queue);
                }
            }
        }else{
            Log::info("there is no to Numbers");
        }

    }


    public function failed(\Exception $exception)
    {
        Campaign::where('id',$this->message->campaign_id)->update(['import_fail_message'=>substr($exception->getMessage(), 0, 191)]);
    }
}
