<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
// use Illuminate\Http\Auth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){

        if(!empty(Auth::check())){
            return redirect("admin/dashboard");
        }
        // dd(Hash::make(123456));
        return view("auth.login");
    }

    public function AuthLogin(Request $request)
    {
       $remember = !empty($request->remember) ? $request->remember : false;

       if(Auth::attempt(['email'=> $request->email,'password'=> $request->password], $remember)){
        return redirect('admin/dashboard')->with('success','200');
       }
       else{
        return redirect()->with('error','PLEASE ENTER CORRECT EMAIL AND PASSWORD');
       }
       
        // dd($request->all());
    }

    public function logout(){
        Auth::logout();
        return redirect(url('/'));
    }
}
