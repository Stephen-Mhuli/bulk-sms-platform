<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuthorizationToken;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authentication(Request $request){

        $validator = Validator::make($request->all(), [
            'email'=>'required',
            'password'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->messages()], 404);
        }

        $customer = Customer::where('email', $request->email)->first();

        if ($customer && \Hash::check($request->password, $customer->password)) {
            $authorization = AuthorizationToken::where('customer_id', $customer->id)->first();
            if(!$authorization) {
                $authorization = new AuthorizationToken();
                $access_token= $customer->createToken($customer->email)->plainTextToken;
                $authorization->access_token = $access_token;
                $authorization->customer_id = $customer->id;
                $authorization->refresh_token = $access_token;
                $authorization->save();
            }
            $data=[
                'token'=>$authorization->access_token
            ];

            return response()->json(['message' => 'Login successful', 'data'=>$data]);

        }else{
            return response()->json(['message' => 'Invalid email or password. Please try again.'], 401);
        }
    }
}
