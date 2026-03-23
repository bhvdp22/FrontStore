<?php

namespace App\Http\Controllers;

use App\Models\ProductReturn;
use App\Models\Refund;
use App\Models\ReturnMessage;
use Illuminate\Http\Request;

class SellerReturnController extends Controller
{
    /**
     * Display a listing of returns for the seller.
     */
    public function index(Request $request)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            session(['intended_url' => $request->fullUrl()]);
            return redirect()->route('login')->with('error', 'Please login to continue');
        }

        $query = ProductReturn::forSeller($sellerId)
            ->with(['order', 'customer', 'product', 'refund']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $returns = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get counts for dashboard
        $counts = [
            'pending' => ProductReturn::forSeller($sellerId)->where('status', 'pending')->count(),
            'approved' => ProductReturn::forSeller($sellerId)->where('status', 'approved')->count(),
            'in_transit' => ProductReturn::forSeller($sellerId)->whereIn('status', ['pickup_scheduled', 'picked_up'])->count(),
            'completed' => ProductReturn::forSeller($sellerId)->whereIn('status', ['refund_completed', 'closed'])->count(),
        ];

        return view('seller.returns.index', compact('returns', 'counts'));
    }

    /**
     * Display the specified return.
     */
    public function show(Request $request, $id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            session(['intended_url' => $request->fullUrl()]);
            return redirect()->route('login')->with('error', 'Please login to continue');
        }

        $return = ProductReturn::with(['order', 'orderItem', 'customer', 'product', 'refund', 'messages'])
            ->forSeller($sellerId)
            ->findOrFail($id);

        // Mark customer messages as read
        $return->messages()
            ->where('sender_type', 'customer')
            ->update(['is_read' => true]);

        return view('seller.returns.show', compact('return'));
    }

    /**
     * Approve a return request.
     */
    public function approve(Request $request, $id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $return = ProductReturn::forSeller($sellerId)
            ->where('status', 'pending')
            ->findOrFail($id);

        $request->validate([
            'seller_notes' => 'nullable|string|max:500',
        ]);

        $return->update([
            'status' => 'approved',
            'approved_at' => now(),
            'seller_notes' => $request->seller_notes,
        ]);

        // Send message to customer
        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'message' => 'Your return request has been approved. ' . ($request->seller_notes ?? ''),
        ]);

        return back()->with('success', 'Return request approved successfully.');
    }

    /**
     * Reject a return request.
     */
    public function reject(Request $request, $id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $return = ProductReturn::forSeller($sellerId)
            ->where('status', 'pending')
            ->findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $return->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'seller_notes' => $request->rejection_reason,
        ]);

        // Send message to customer
        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'message' => 'Your return request has been rejected. Reason: ' . $request->rejection_reason,
        ]);

        return back()->with('success', 'Return request rejected.');
    }

    /**
     * Schedule pickup for return.
     */
    public function schedulePickup(Request $request, $id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $return = ProductReturn::forSeller($sellerId)
            ->where('status', 'approved')
            ->findOrFail($id);

        $request->validate([
            'courier_name' => 'required|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'pickup_date' => 'required|date|after_or_equal:today',
        ]);

        // Auto-generate tracking number if not provided
        $trackingNumber = $request->tracking_number ?: ProductReturn::generateTrackingNumber();

        $return->update([
            'status' => 'pickup_scheduled',
            'pickup_scheduled_at' => $request->pickup_date,
            'courier_name' => $request->courier_name,
            'tracking_number' => $trackingNumber,
        ]);

        // Send message to customer
        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'message' => "Pickup has been scheduled for {$request->pickup_date}. Courier: {$request->courier_name}. " . 
                ($request->tracking_number ? "Tracking: {$request->tracking_number}" : ''),
        ]);

        return back()->with('success', 'Pickup scheduled successfully.');
    }

    /**
     * Mark item as picked up.
     */
    public function markPickedUp($id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $return = ProductReturn::forSeller($sellerId)
            ->where('status', 'pickup_scheduled')
            ->findOrFail($id);

        $return->update([
            'status' => 'picked_up',
            'picked_up_at' => now(),
        ]);

        // Send message to customer
        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'message' => 'Your return item has been picked up by the courier. It is on its way to us.',
        ]);

        return back()->with('success', 'Item marked as picked up.');
    }

    /**
     * Mark item as received.
     */
    public function markReceived($id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $return = ProductReturn::forSeller($sellerId)
            ->where('status', 'picked_up')
            ->findOrFail($id);

        $return->update([
            'status' => 'received',
            'received_at' => now(),
        ]);

        // Send message to customer
        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'message' => 'We have received your returned item. Our team will inspect it shortly.',
        ]);

        return back()->with('success', 'Item marked as received.');
    }

    /**
     * Complete inspection and decide on refund.
     */
    public function completeInspection(Request $request, $id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $return = ProductReturn::forSeller($sellerId)
            ->where('status', 'received')
            ->findOrFail($id);

        $request->validate([
            'inspection_result' => 'required|in:approve_refund,partial_refund,reject_refund',
            'refund_amount' => 'required_if:inspection_result,partial_refund|nullable|numeric|min:0',
            'inspection_notes' => 'nullable|string|max:500',
        ]);

        $return->update([
            'status' => 'inspected',
            'inspected_at' => now(),
            'seller_notes' => $return->seller_notes . "\n\nInspection: " . $request->inspection_notes,
        ]);

        if ($request->inspection_result === 'reject_refund') {
            $return->update(['status' => 'closed']);
            ReturnMessage::create([
                'return_id' => $return->id,
                'sender_type' => 'seller',
                'sender_id' => $sellerId,
                'message' => 'After inspection, the return has been closed without refund. Reason: ' . $request->inspection_notes,
            ]);
            return back()->with('info', 'Return closed without refund.');
        }

        // Calculate refund amount
        $refundAmount = $request->inspection_result === 'partial_refund' 
            ? $request->refund_amount 
            : $return->refund_amount;

        $return->update(['refund_amount' => $refundAmount]);

        // Send message to customer
        $inspectionMsg = $request->inspection_result === 'partial_refund' 
            ? "Inspection completed. A partial refund of ₹{$refundAmount} has been approved." 
            : "Inspection completed successfully. Full refund of ₹{$refundAmount} has been approved.";
        
        if ($request->inspection_notes) {
            $inspectionMsg .= " Note: {$request->inspection_notes}";
        }

        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'message' => $inspectionMsg,
        ]);

        return back()->with('success', 'Inspection completed. Proceed to initiate refund.');
    }

    /**
     * Initiate refund — creates a pending request for Admin to process.
     */
    public function initiateRefund(Request $request, $id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $return = ProductReturn::forSeller($sellerId)
            ->whereIn('status', ['received', 'inspected'])
            ->with(['order.payment'])
            ->findOrFail($id);

        $request->validate([
            'refund_method' => 'required|in:original_payment,bank_transfer,store_credit,upi',
        ]);

        // Check if a refund already exists
        if ($return->refund) {
            return back()->with('error', 'A refund request already exists for this return.');
        }

        // Create a PENDING refund record — Admin will process it via Razorpay
        $refund = Refund::create([
            'refund_number' => Refund::generateRefundNumber(),
            'return_id' => $return->id,
            'order_id' => $return->order_id,
            'customer_id' => $return->customer_id,
            'payment_id' => $return->order->payment->id ?? null,
            'amount' => $return->refund_amount,
            'refund_method' => $request->refund_method,
            'status' => 'pending',
            'initiated_at' => now(),
        ]);

        $return->update([
            'status' => 'refund_initiated',
            'refund_initiated_at' => now(),
        ]);

        // Notify via return message
        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'message' => "Refund of ₹{$return->refund_amount} has been requested. Method: {$refund->refund_method_label}. Waiting for admin approval and processing.",
        ]);

        return back()->with('success', 'Refund request sent to admin for processing.');
    }

    /**
     * Check refund status (replaces completeRefund — admin handles actual processing now).
     */
    public function completeRefund(Request $request, $id)
    {
        // This route is kept for backward compatibility but sellers can no longer process refunds.
        // Admin processes refunds via Razorpay. Seller can only view status.
        return back()->with('info', 'Refund is being processed by admin. You will be notified once completed.');
    }

    /**
     * Send a message in return conversation.
     */
    public function sendMessage(Request $request, $id)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $return = ProductReturn::forSeller($sellerId)->findOrFail($id);

        $request->validate([
            'message' => 'required|string|max:1000',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('return-messages', 'public');
                $attachments[] = $path;
            }
        }

        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'message' => $request->message,
            'attachments' => $attachments,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    /**
     * Export returns to CSV.
     */
    public function export(Request $request)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        $returns = ProductReturn::forSeller($sellerId)
            ->with(['order', 'customer', 'product', 'refund'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'returns_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($returns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Return Number',
                'Order ID',
                'Customer',
                'Product',
                'Quantity',
                'Refund Amount',
                'Reason',
                'Status',
                'Created At',
            ]);

            foreach ($returns as $return) {
                fputcsv($file, [
                    $return->return_number,
                    $return->order->id ?? 'N/A',
                    $return->customer->name ?? 'N/A',
                    $return->product->name ?? 'N/A',
                    $return->quantity,
                    $return->refund_amount,
                    $return->reason_label,
                    $return->status_label,
                    $return->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
