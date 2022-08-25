<?php

namespace App\Jobs;

use App\Models\ContactGroup;
use App\Models\Group;
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

class ListBuilderQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;

    private $user;
    private $group;
    /**
     * @var int
     */
    private $page;
    private $result_count = 10000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request,$group,$user)
    {
        $this->request=(object)$request;
        $this->user=$user;
        $this->group=$group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->generatePageNumber($this->request);
        $jobs = new Collection();
        for ($i=1;$i<=$this->get_page_number();$i++){
            $this->request->page=$i;
            $jobs->push(new ChunkImport(
                $this->request,
                $this->group,
                $this->user,
                $this->result_count
            ));
        }
        $jobs->push(new AfterGenerate($this->group));

        Bus::chain($jobs->toArray())->dispatch();

    }

    private function generatePageNumber($request){
        if(gettype($request->group_ids)=='string'){
            $request->group_ids=json_decode($request->group_ids);
        }

        $page = $request->page??1;
        $offset = ($page - 1) * $this->result_count;
        $customer = $this->user;
        if (!$request->group_ids){
            return;
        }

        $count=ContactGroup::where('customer_id',$customer->id)
            ->whereIn('group_id',$request->group_ids)
            ->count();

        $endCount = $offset + $this->result_count;
        $morePages = $count > $endCount;
        $this->page=$page;
        if($morePages){
            $request->page=$page+1;
            $this->generatePageNumber($request);
        }
    }

    public function get_page_number():int{
        return $this->page;
    }
}
