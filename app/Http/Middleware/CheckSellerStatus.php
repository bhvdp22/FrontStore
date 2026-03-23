<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckSellerStatus
{
    /**
     * Handle an incoming request.
     * 
     * Check if the seller's account status allows them to perform the action.
     * - 'active': Full access
     * - 'pending': View-only access (cannot add/edit products, create orders, etc.)
     * - 'banned/blocked': No access at all - redirect to login
     */
    public function handle(Request $request, Closure $next, string $action = 'view'): Response
    {
        $username = session('loginusername');
        
        if (!$username) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $seller = User::where('name', $username)->first();

        if (!$seller) {
            session()->forget(['loginusername', 'loginId']);
            return redirect()->route('login')->with('error', 'Account not found.');
        }

        $status = $seller->status ?? 'pending';

        // Banned/Blocked sellers cannot access anything
        if ($status === 'banned' || $status === 'blocked') {
            session()->forget(['loginusername', 'loginId']);
            return redirect()->route('login')
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        // Pending sellers can only VIEW, not create/edit/delete
        if ($status === 'pending' && $action !== 'view') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is pending approval. You cannot perform this action until your account is verified.'
                ], 403);
            }
            
            return redirect()->back()
                ->with('error', 'Your account is pending approval. You cannot perform this action until your account is verified by admin.');
        }

        // Active sellers have full access
        return $next($request);
    }
}
