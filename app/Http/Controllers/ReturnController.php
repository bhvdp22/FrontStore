<?php

namespace App\Http\Controllers;

use App\Models\ProductReturn;
use App\Models\Refund;
use App\Models\ReturnMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReturnController extends Controller
{
    /**
     * Get the current customer ID from session or by email lookup.
     */
    private function getCustomerId()
    {
        // First try to get from session
        $customerId = session('customer_id');
        if ($customerId) {
            return $customerId;
        }
        
        // If not in session, lookup by email
        $email = session('customer_email');
        if ($email) {
            $customer = Customer::where('email', $email)->first();
            if ($customer) {
                // Store in session for future use
                session(['customer_id' => $customer->id]);
                return $customer->id;
            }
        }
        
        return null;
    }
    
    /**
     * Display a listing of the customer's returns.
     */
    public function index()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $returns = ProductReturn::forCustomer($customerId)
            ->with(['order', 'product', 'refund'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('returns.index', compact('returns'));
    }

    /**
     * Show form to create a return request.
     */
    public function create(Request $request)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return redirect()->route('customer.login');
        }
        
        $customerEmail = session('customer_email');

        // order_item_id is actually the Order table's id (each row is one item)
        $orderId = $request->query('order_item_id');
        $order = Order::where('customer_email', $customerEmail)
            ->where('id', $orderId)
            ->first();
            
        if (!$order) {
            return redirect()->route('profile.orders')->with('error', 'Order not found.');
        }

        // Check if return already exists for this order/item
        $existingReturn = ProductReturn::where('order_item_id', $orderId)
            ->whereNotIn('status', ['cancelled', 'rejected', 'closed'])
            ->first();

        if ($existingReturn) {
            return redirect()->route('returns.show', $existingReturn->id)
                ->with('info', 'A return request already exists for this item.');
        }

        // Check if order is eligible for return (within 30 days)
        $deliveredAt = $order->updated_at ?? $order->created_at;
        $returnDeadline = $deliveredAt->copy()->addDays(30);

        if (now()->gt($returnDeadline)) {
            return redirect()->route('profile.orders')->with('error', 'Return window has expired. Returns are only accepted within 30 days of delivery.');
        }

        $returnReasons = [
            'defective' => 'Product is defective',
            'wrong_item' => 'Wrong item received',
            'not_as_described' => 'Product not as described',
            'damaged_in_shipping' => 'Damaged during shipping',
            'size_fit_issue' => 'Size/Fit issue',
            'changed_mind' => 'Changed my mind',
            'quality_issue' => 'Quality not satisfactory',
            'late_delivery' => 'Late delivery',
            'other' => 'Other reason',
        ];
        
        // Get the product by SKU
        $product = \App\Models\Product::where('sku', $order->sku)->first();

        return view('returns.create', compact('order', 'product', 'returnReasons'));
    }

    /**
     * Store a newly created return request.
     */
    public function store(Request $request)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return redirect()->route('customer.login');
        }
        
        $customerEmail = session('customer_email');

        $request->validate([
            'order_item_id' => 'required|exists:orders,id',
            'return_reason' => 'required|in:defective,wrong_item,not_as_described,damaged_in_shipping,size_fit_issue,changed_mind,quality_issue,late_delivery,other',
            'reason_details' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $order = Order::where('customer_email', $customerEmail)
            ->where('id', $request->order_item_id)
            ->first();
            
        if (!$order) {
            return redirect()->route('profile.orders')->with('error', 'Order not found.');
        }

        // Validate quantity
        if ($request->quantity > $order->quantity) {
            return back()->withErrors(['quantity' => 'Return quantity cannot exceed ordered quantity.']);
        }

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $result = cloudinary()->uploadApi()->upload($image->getRealPath(), [
                    'folder' => 'FrontStore/returns'
                ]);
                $images[] = $result['secure_url'];
            }
        }

        // Calculate refund amount
        $refundAmount = ($order->total_price / $order->quantity) * $request->quantity;
        
        // Get product by SKU and extract seller_id
        $product = \App\Models\Product::where('sku', $order->sku)->first();
        
        // Try multiple ways to get seller_id
        $sellerId = null;
        $productId = null;
        
        if ($product) {
            $productId = $product->id;
            // Check seller_id field first (primary), then user_id as fallback
            $sellerId = $product->seller_id ?? $product->user_id ?? null;
        }
        
        // If still no seller_id, try to get from product name match
        if (!$sellerId) {
            $productByName = \App\Models\Product::where('name', 'like', '%' . $order->product_name . '%')->first();
            if ($productByName) {
                $sellerId = $productByName->seller_id ?? $productByName->user_id ?? null;
                $productId = $productId ?? $productByName->id;
            }
        }
        
        // Final fallback - get first seller (admin/default seller)
        if (!$sellerId) {
            $defaultSeller = \App\Models\User::first();
            $sellerId = $defaultSeller ? $defaultSeller->id : 1;
        }

        // Get pickup address from customer's default address or order shipping address
        $pickupAddress = $order->shipping_address;
        if (empty($pickupAddress)) {
            $customerAddress = \App\Models\CustomerAddress::where('customer_id', $customerId)
                ->where('is_default', true)
                ->first();
            
            if ($customerAddress) {
                $pickupAddress = $customerAddress->address_line1 . ', ' . 
                    ($customerAddress->address_line2 ? $customerAddress->address_line2 . ', ' : '') .
                    $customerAddress->city . ', ' . 
                    $customerAddress->state . ' - ' . 
                    $customerAddress->pincode;
            }
        }

        // Create return request
        $return = ProductReturn::create([
            'return_number' => ProductReturn::generateReturnNumber(),
            'order_id' => $order->id,
            'order_item_id' => $order->id, // Using order id as order_item_id
            'customer_id' => $customerId,
            'seller_id' => $sellerId,
            'product_id' => $productId,
            'quantity' => $request->quantity,
            'refund_amount' => $refundAmount,
            'return_reason' => $request->return_reason,
            'reason_details' => $request->reason_details,
            'images' => $images,
            'status' => 'pending',
            'pickup_address' => $pickupAddress ?? 'Address not available',
        ]);

        return redirect()->route('returns.show', $return->id)
            ->with('success', 'Return request submitted successfully. The seller will review your request shortly.');
    }

    /**
     * Display the specified return.
     */
    public function show($id)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $return = ProductReturn::with(['order', 'orderItem', 'product', 'seller', 'refund', 'messages'])
            ->forCustomer($customerId)
            ->findOrFail($id);

        // Mark messages as read
        $return->messages()
            ->where('sender_type', '!=', 'customer')
            ->update(['is_read' => true]);

        return view('returns.show', compact('return'));
    }

    /**
     * Cancel a return request.
     */
    public function cancel($id)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $return = ProductReturn::forCustomer($customerId)->findOrFail($id);

        if (!$return->canBeCancelled()) {
            return back()->with('error', 'This return request cannot be cancelled at this stage.');
        }

        $return->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('returns.index')
            ->with('success', 'Return request cancelled successfully.');
    }

    /**
     * Send a message in return conversation.
     */
    public function sendMessage(Request $request, $id)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $return = ProductReturn::forCustomer($customerId)->findOrFail($id);

        $request->validate([
            'message' => 'required|string|max:1000',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $result = cloudinary()->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'FrontStore/return-messages'
                ]);
                $attachments[] = $result['secure_url'];
            }
        }

        ReturnMessage::create([
            'return_id' => $return->id,
            'sender_type' => 'customer',
            'sender_id' => $customerId,
            'message' => $request->message,
            'attachments' => $attachments,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    /**
     * Get orders eligible for return.
     * Each Order row = one product item from the cart.
     */
    public function eligibleOrders()
    {
        $customerId = $this->getCustomerId();
        $customerEmail = session('customer_email');
        if (!$customerId || !$customerEmail) {
            return redirect()->route('customer.login');
        }

        // Each Order row in the orders table represents one purchased product.
        // Fetch all delivered orders within 30 days for this customer.
        $eligibleItems = Order::where('customer_email', $customerEmail)
            ->where('status', 'Delivered')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($order) {
                // Exclude items that already have an active return
                return !ProductReturn::where('order_item_id', $order->id)
                    ->whereNotIn('status', ['cancelled', 'rejected', 'closed'])
                    ->exists();
            });

        return view('returns.eligible-orders', compact('eligibleItems'));
    }

    /**
     * Track refund status.
     */
    public function trackRefund($id)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $return = ProductReturn::with(['refund'])
            ->forCustomer($customerId)
            ->findOrFail($id);

        if (!$return->refund) {
            return back()->with('info', 'Refund has not been initiated yet.');
        }

        return view('returns.track-refund', compact('return'));
    }
}
