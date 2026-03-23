<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReturn;
use App\Models\Refund;
use App\Services\RefundService;

class AdminReturnController extends Controller
{
    // 1. List all returns
    public function index(Request $request)
    {
        $query = ProductReturn::with(['customer', 'seller', 'product', 'order', 'refund']);

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $returns = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => ProductReturn::count(),
            'pending' => ProductReturn::where('status', 'pending')->count(),
            'approved' => ProductReturn::where('status', 'approved')->count(),
            'refund_pending' => ProductReturn::whereIn('status', ['received', 'inspected', 'refund_initiated'])->count(),
            'completed' => ProductReturn::where('status', 'refund_completed')->count(),
        ];

        return view('admin.returns.index', compact('returns', 'stats', 'status'));
    }

    // 2. Return details
    public function show($id)
    {
        $return = ProductReturn::with(['customer', 'seller', 'product', 'order', 'refund', 'messages'])->findOrFail($id);
        return view('admin.returns.show', compact('return'));
    }

    // 3. Admin approves return
    public function approve($id, Request $request)
    {
        $return = ProductReturn::findOrFail($id);
        
        $return->update([
            'status' => 'approved',
            'admin_notes' => $request->get('admin_notes'),
            'approved_at' => now(),
        ]);

        return back()->with('success', "Return {$return->return_number} approved.");
    }

    // 4. Admin rejects return
    public function reject($id, Request $request)
    {
        $return = ProductReturn::findOrFail($id);
        
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $return->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'rejected_at' => now(),
        ]);

        return back()->with('success', "Return {$return->return_number} rejected.");
    }

    // 5. Initiate refund for a return (admin-side)
    public function initiateRefund($id, Request $request)
    {
        $return = ProductReturn::with('refund')->findOrFail($id);

        if ($return->refund) {
            return back()->with('danger', 'A refund already exists for this return.');
        }

        if (!in_array($return->status, ['approved', 'received', 'inspected'])) {
            return back()->with('danger', 'This return is not eligible for refund at this stage.');
        }

        $request->validate([
            'refund_method' => 'required|in:original_payment,bank_transfer,upi,store_credit',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $refundService = new RefundService();
        $refund = $refundService->createRefund($return, $request->refund_method);

        // Override amount if admin adjusted
        if ($request->amount != $return->refund_amount) {
            $refund->update(['amount' => $request->amount]);
        }

        $return->update([
            'status' => 'refund_initiated',
            'refund_initiated_at' => now(),
        ]);

        return back()->with('success', "Refund {$refund->refund_number} initiated — ₹{$refund->amount}");
    }

    // 6. Process / complete refund via Razorpay (admin only)
    public function processRefund($id)
    {
        $return = ProductReturn::with(['refund', 'refund.payment'])->findOrFail($id);

        if (!$return->refund) {
            return back()->with('danger', 'No refund record found. Initiate refund first.');
        }

        $refund = $return->refund;
        $refundService = new RefundService();

        if ($refund->refund_method === 'original_payment') {
            $result = $refundService->processRazorpayRefund($refund);
        } else {
            // For manual methods, mark as completed directly
            $result = $refundService->processManualRefund($refund, 'MANUAL-' . now()->timestamp, 'Processed by admin');
        }

        if ($result['success']) {
            // Create a message visible to seller and customer
            \App\Models\ReturnMessage::create([
                'return_id' => $return->id,
                'sender_type' => 'admin',
                'sender_id' => auth('admin')->id(),
                'message' => $refund->razorpay_refund_id
                    ? "Refund of ₹{$refund->amount} processed successfully via Razorpay. Reference ID: {$refund->razorpay_refund_id}"
                    : "Refund of ₹{$refund->amount} has been processed successfully. Transaction ID: {$refund->transaction_id}",
            ]);

            return back()->with('success', $result['message']);
        }

        return back()->with('danger', 'Refund failed: ' . $result['message']);
    }

    // 7. List all refunds
    public function refunds(Request $request)
    {
        $query = Refund::with(['productReturn', 'customer', 'order']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $refunds = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Refund::count(),
            'pending' => Refund::where('status', 'pending')->count(),
            'completed' => Refund::where('status', 'completed')->count(),
            'total_refunded' => Refund::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.refunds.index', compact('refunds', 'stats', 'status'));
    }
}
