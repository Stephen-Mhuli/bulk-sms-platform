<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AuthorizationToken;
use Illuminate\Http\Request;

class AuthorizationController extends Controller
{
    public function index()
    {
        $user = auth('customer')->user();
        $data['authorization_token'] = AuthorizationToken::where('customer_id', $user->id)->first();

        return view('customer.api.index', $data);
    }

    public function store(Request $request)
    {
        $user = auth('customer')->user();

        $preToken = AuthorizationToken::where('customer_id', $user->id)->first();
        if ($preToken){
            $user->tokens()->delete();
        }
        $access_token= $user->createToken($user->email)->plainTextToken;

        $authorization = isset($preToken) ? $preToken : new AuthorizationToken();
        $authorization->access_token = $access_token;
        $authorization->customer_id=$user->id;
        $authorization->refresh_token = $access_token;
        $authorization->save();

        return redirect()->route('customer.authorization.token.create')->with('success', 'API Token successfully updated');
    }
}
