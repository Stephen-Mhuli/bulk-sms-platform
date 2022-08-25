<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TrustBuilder
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->path() == 'process/email' || $request->path() == 'check/schedule' || str_contains($request->path(),'inbound')){
            return $next($request);
        }
        return $next($request);
    }

    public function getTrustBuilder()
    {
        return file_exists(storage_path() . '/framework/build');
    }

    public function isExpired($data): bool
    {
        if (isset($data->checked_at)) {
            $dateDiff = Carbon::parse($data->checked_at)->diffInDays(now());
            if ($dateDiff > 7) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

}
