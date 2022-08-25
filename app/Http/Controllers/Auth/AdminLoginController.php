<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index(){
        return view('auth.admin_login');
    }

    public function authenticate(Request $request){
        $credentials['email']=trim($request -> email);
        $credentials['password']=trim($request -> password);
        $remember_me = $request->has('remember_me') ? true : false;
        if(Auth::attempt($credentials,$remember_me)){
            return redirect()->route('admin.dashboard');
        }
        return back()->withInput()->withErrors(['message'=>'Invalid email or password. Please try again.']);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('admin.login');

    }
}
