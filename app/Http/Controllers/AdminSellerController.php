<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminSellerController extends Controller
{
    // 1. Show List with counts
    public function index()
    {
        // Get all users, ordered by newest first
        $sellers = User::orderBy('created_at', 'desc')->get();
        
        // Get counts for status cards
        $counts = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'pending' => User::where('status', 'pending')->count(),
            'banned' => User::where('status', 'banned')->count(),
        ];
        
        return view('admin.seller.index', compact('sellers', 'counts'));
    }

    // 2. Approve/Activate a seller
    public function approve($id)
    {
        $seller = User::findOrFail($id);
        $previousStatus = $seller->status;
        $seller->status = 'active'; // This activates the account
        $seller->save();

        $message = $previousStatus === 'banned' 
            ? 'Seller ' . ($seller->business_name ?? $seller->name) . ' has been unblocked and activated!'
            : 'Seller ' . ($seller->business_name ?? $seller->name) . ' has been approved!';

        return back()->with('success', $message);
    }

    // 3. Ban/Block a seller
    public function ban($id)
    {
        $seller = User::findOrFail($id);
        $seller->status = 'banned'; // This blocks the account
        $seller->save();

        return back()->with('danger', 'Seller ' . ($seller->business_name ?? $seller->name) . ' has been blocked.');
    }
    
    // 4. Set seller to pending status
    public function pending($id)
    {
        $seller = User::findOrFail($id);
        $seller->status = 'pending'; // Set to pending for review
        $seller->save();

        return back()->with('warning', 'Seller ' . ($seller->business_name ?? $seller->name) . ' has been set to pending review.');
    }
    
    // 5. View seller details
    public function show($id)
    {
        $seller = User::findOrFail($id);
        return view('admin.seller.show', compact('seller'));
    }
}