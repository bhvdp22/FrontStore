<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class AdminReviewController extends Controller
{
    // 1. List all reviews
    public function index(Request $request)
    {
        $query = Review::with(['product', 'order']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats', 'status'));
    }

    // 2. Approve review
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Review approved.');
    }

    // 3. Reject review
    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Review rejected.');
    }

    // 4. Delete review
    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Review deleted.');
    }
}
