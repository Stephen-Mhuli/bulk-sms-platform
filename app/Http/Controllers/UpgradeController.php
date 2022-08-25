<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\Keypair;

class UpgradeController extends Controller
{
    public function process()
    {
        if (env('APP_DEBUG')) {
            echo "<h1>Upgrading...</h1>";
            Artisan::call('clear:all');
            Artisan::call('migrate');
            /*
            $sqlFile = Storage::disk('local')->get('upgrade/v2.2-v2.3.sql');

            if ($sqlFile) {
                DB::unprepared($sqlFile);
            }*/
            echo "Database upgrade has been finished <br/>";
            echo "<a href='" . route('admin.login') . "'>Back to login page</a>";
        }else{
            return redirect()->route('login')->withErrors(['msg'=>'Please enable APP_DEBUG']);
        }
    }
}
