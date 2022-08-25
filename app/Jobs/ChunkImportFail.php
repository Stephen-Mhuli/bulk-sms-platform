<?php

namespace App\Jobs;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChunkImportFail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $group;
    private $error;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group,$error)
    {
        $this->group=$group;
        $this->error=$error;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Group::where('id',$this->group->id)->update(['import_status'=>'failed','import_fail_message'=>substr($this->error, 0, 191)]);
    }
}
