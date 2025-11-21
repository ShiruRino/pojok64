<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    public function logout(){
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Logout success');
    }
}
