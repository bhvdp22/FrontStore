<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('loginusername')) {
            return redirect('/login')->with('error', 'Please login or register to continue.');
        }
        return $next($request);
    }
}
