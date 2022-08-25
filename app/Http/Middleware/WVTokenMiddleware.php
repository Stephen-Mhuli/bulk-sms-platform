<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class WVTokenMiddleware
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
        $token = $_COOKIE['wv_token'] ?? '';
        if ($token) {
            $personalAccessToken = PersonalAccessToken::findToken($token);
            if (auth('customer')->check() && $personalAccessToken) {
                $user = $personalAccessToken->tokenable;
                if ($user->id != auth('customer')->id()) {
                    auth('customer')->login($user);
                }
            } else if (!auth('customer')->check() && $personalAccessToken) {
                $user = $personalAccessToken->tokenable;
                auth('customer')->login($user);
                return redirect()->intended(route('customer.dashboard'));
            }
        }


        return $next($request);
    }
}
