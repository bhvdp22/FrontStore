<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function login(Request $req)
    {
        $username = $req->input('uname');
        $password = $req->input('psw');

        $user = User::where('name', $username)->first();

        if ($user && $user->password === $password) {
            
            // Check if user is banned/blocked
            if ($user->status === 'banned' || $user->status === 'blocked') {
                return back()->withErrors([
                    'login' => 'Your account has been suspended. Please contact support for assistance.'
                ])->withInput();
            }
            
            // Login the user using Laravel Auth as well
            Auth::login($user);
            
            session(['loginusername' => $user->name, 'loginId' => $user->id]);
            
            // Check if there's an intended URL and redirect there
            if (session()->has('intended_url')) {
                $intended = session('intended_url');
                session()->forget('intended_url');
                return redirect($intended)->with('success', 'Login successful');
            }
            
            return redirect('/')->with('success', 'Login successful');
        }

        return back()->withErrors(['login' => 'Invalid username or password'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        session()->forget(['loginusername', 'loginId']);
        return redirect('/login')->with('success', 'Logged out successfully');
    }
}