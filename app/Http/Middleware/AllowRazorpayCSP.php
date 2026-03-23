<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowRazorpayCSP
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Remove all CSP headers that might block Razorpay
        $response->headers->remove('Content-Security-Policy');
        $response->headers->remove('Content-Security-Policy-Report-Only');
        $response->headers->remove('X-Content-Security-Policy');
        
        // Set permissive CSP for Razorpay
        $csp = implode('; ', [
            "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:",
            "script-src * 'unsafe-inline' 'unsafe-eval'",
            "connect-src *",
            "img-src * data: blob: 'unsafe-inline'",
            "frame-src *",
            "style-src * 'unsafe-inline'"
        ]);
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
