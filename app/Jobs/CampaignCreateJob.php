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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class CampaignCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $from_number;
    private $to_numbers;
    private $campaign;
    private $message;
    private $total_to_count;
    /**
     * @var float|int
     */
    private $daily_sent_limit;
    /**
     * @var float|int
     */
    private $date_difference;
    private $start_date;
    private $start_time;
    private $send_speed;
    private $user;
    private $take_count=5000;
    /**
     * @var array
     */
    private $campaign_running_date;
    private $last_key;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($from,$to,$campaign,$message,$totalTo,$totalFrom,$dateDiff,$startDate,$startTime,$sendSpeed,$user,$last_key)
    {
        $this->from_number=$from;
        $this->to_numbers=$to;
        $this->campaign=$campaign;
        $this->message=$message;
        $this->total_to_count=$totalTo;
        $this->date_difference=$dateDiff;
        $this->daily_sent_limit = ceil(($this->total_to_count / $this->date_difference)/$totalFrom);
        $this->start_date=$startDate;
        $this->start_time=$startTime;
        $this->send_speed=$sendSpeed;
        $this->user=$user;
        $this->last_key=$last_key;
        $this->setCampaignFutureDates();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jobs=new Collection();

        foreach ($this->getCampaignFutureDates() as $key=> $date){
            $jobs->push(new CampaignCreateChunk(
                $this->message,
                $this->daily_sent_limit,
                $date,
                $this->send_speed,
                $this->user,
                $key*$this->daily_sent_limit
            ));
        }

        if($this->last_key==$this->from_number){
            $jobs->push(
                new CampaignCreateSuccessJob($this->campaign)
            );
        }


        Bus::chain($jobs->toArray())->dispatch();

    }

    public function getCampaignFutureDates():array{
        return $this->campaign_running_date;
    }

    private function setCampaignFutureDates():array{
        $campaign_running_date = [];

        for ($i = 1; $i <= $this->date_difference; $i++) {
            $dayLimit = 0;
            $startDateTime = Carbon::parse($this->start_date->addDay()->toDateString() . ' ' . $this->start_time->toTimeString());
            $day = $startDateTime->format('l');
            if ($day == 'Saturday') {
                $dayLimit = 2;
            }else if($day=='Sunday'){
                $dayLimit = 1;
            }
            $campaign_running_date[] = Carbon::parse($this->start_date->addDays($dayLimit)->toDateString() . ' ' . $this->start_time->toTimeString());
            $this->start_date->subDays($dayLimit);
        }

        $this->campaign_running_date=$campaign_running_date;
        return  $campaign_running_date;
    }

    public function failed(\Exception $exception)
    {
        Campaign::where('id',$this->message->campaign_id)->update(['status'=>'failed','import_fail_message'=>substr($exception->getMessage(), 0, 191)]);
    }
}
