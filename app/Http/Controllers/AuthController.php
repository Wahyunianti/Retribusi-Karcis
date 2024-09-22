<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role_id == 2) {
                return redirect('Admdashboard');
            } elseif ($user->role_id == 1) {
                return redirect('Admtrdashboard');
            } else {
                return redirect('Admtrdashboard');
            }
        }

        return back()->withErrors([
            'pesan' => 'Username atau password salah',
        ]);
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
