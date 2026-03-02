<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
class AuthController extends Controller
{
    public function index(){
        return view('admin.login');
    }
    public function login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
        $user = User::where('name', $request->username)->first();
        if ($user && \Hash::check($request->password, $user->password)) {
            Auth::login($user);
            
            return redirect()->intended('/')
                             ->with('success', 'Login successful!');
        }
        return redirect()->back()->withErrors(['username' => 'Username Invaild']);
    }
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login')->with('success', 'You have been logged out.');
    }
}
