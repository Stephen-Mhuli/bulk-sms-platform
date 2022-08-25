<?php

namespace App\Jobs;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AfterGenerate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $group;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group)
    {
        $this->group=$group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       Group::where('id',$this->group->id)->update(['import_status'=>'completed']);
    }
}
