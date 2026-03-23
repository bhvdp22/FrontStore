<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireCustomerLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('customer_email')) {
            // Remember intended URL to return after login
            session(['intended_url' => $request->fullUrl()]);
            return redirect()->route('customer.login')->with('error', 'Please login to continue.');
        }
        return $next($request);
    }
}
