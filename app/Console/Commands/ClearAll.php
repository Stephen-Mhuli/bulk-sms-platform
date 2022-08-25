<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClearAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all {--withlog}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $this->info("All cache cleared");
        $withLog=$this->option('withlog');
        if($withLog){
            $files = Arr::where(Storage::disk('log')->files(), function($filename) {
                return Str::endsWith($filename,'.log');
            });
            $count = count($files);
            if(Storage::disk('log')->delete($files)) {
                $this->info(sprintf('Deleted %s log %s!', $count, Str::plural('file', $count)));
            } else {
                $this->error('Error in deleting log files!');
            }
        }
        return 0;
    }
}
