<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!Session::has('locale'))
        {
            $locale=isset(json_decode(get_settings('local_setting'))->language)?json_decode(get_settings('local_setting'))->language:'en';
            Session::put('locale',$locale);
        }
        App::setLocale(session('locale'));
        return $next($request);
    }
}
