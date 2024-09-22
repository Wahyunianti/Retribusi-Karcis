<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response{
        if(Auth::check()){
            if(Auth::user()->role_id == 2) {
                return $next($request);
            }else{
                return redirect('/')->with('message', 'access denied');
            }
        }else{
            return redirect('/login')->with('message', 'Login untuk melakukan akses pengguna');
        }
    }

}
