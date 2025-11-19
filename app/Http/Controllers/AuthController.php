<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('username', $request->username)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return back()->with('error', 'Invalid username or password');
        }
        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Login success');
    }
    public function logout(Request $request){
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Logout success');
    }
}
