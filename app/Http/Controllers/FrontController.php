<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Subscribe;
use App\Models\Template;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FrontController extends Controller
{
    public function verifyCode(Request $request){
       $code=$request->purchase_code;
       if(!$code){
           abort(404);
       }
        $client = new Client();
        $res = $client->request('GET', 'http://verify.picotech.app/verify.php?purchase_code='.$code);
        $response= json_decode($res->getBody());

        if(isset($response->id) && $response->id){
            $data=[
                'code'=>$code,
                'id'=>$response->id,
                'checked_at'=>now()
            ];
            File::put(storage_path().'/framework/build',base64_encode(json_encode($data)));
            if($request->verify){
                return back();
            }
            return back()->with('success','Purchase code verified successfully');

        }else{
            File::delete(storage_path().'/framework/build');
            return back()->withErrors(['msg'=>'Invalid purchase code']);
        }

    }

    public function demoLogin(){
        return view('front.login_demo');
    }
}
