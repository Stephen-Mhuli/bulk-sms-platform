<?php

namespace App\Http\Middleware;

use Closure;

class EmailVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        if(config('picosms.email_verify') && !auth('customer')->user()->email_verified_at){
            auth($guard)->logout();
            return redirect()->route('login')->with('fail','Email has not verified yet. Please verify email before login');
        }
        return $next($request);
    }
}
