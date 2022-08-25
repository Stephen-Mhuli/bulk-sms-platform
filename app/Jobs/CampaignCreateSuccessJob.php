<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CampaignCreateSuccessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign)
    {
        $this->campaign=$campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Campaign::where('id',$this->campaign->id)->update(['status'=>'running','start_date'=>$this->campaign->start_date]);
    }

    public function failed(\Exception $exception)
    {
        Campaign::where('id',$this->campaign->id)->update(['status'=>'failed','import_fail_message'=>substr($exception->getMessage(), 0, 191)]);
    }
}
