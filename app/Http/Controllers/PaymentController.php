<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment; 
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payout;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $razorpayKey;
    private $razorpaySecret;

    public function __construct()
    {
        $this->razorpayKey = config('services.razorpay.key');
        $this->razorpaySecret = config('services.razorpay.secret');
    }

    public function index()
    {
        $seller = $this->currentSeller();
        $sellerSkus = $seller ? Product::where('seller_id', $seller->id)->pluck('sku') : Product::pluck('sku');
        $sellerOrderIds = $seller ? Order::whereIn('sku', $sellerSkus)->pluck('order_id') : collect();

        // Get all payments with their related order information, newest first
        $payments = Payment::with('order')
            ->when($seller, function ($q) use ($sellerOrderIds) {
                $q->whereIn('order_id', $sellerOrderIds);
            })
            ->orderByDesc('created_at')
            ->get();
        
        // Calculate totals for the "Wallet" cards
        $totalRevenue = Payment::when($seller, function ($q) use ($sellerOrderIds) {
                $q->whereIn('order_id', $sellerOrderIds);
            })
            ->where('status', 'Completed')
            ->sum('amount');

        $pendingAmount = Payment::when($seller, function ($q) use ($sellerOrderIds) {
                $q->whereIn('order_id', $sellerOrderIds);
            })
            ->where('status', 'Pending')
            ->sum('amount');

        // Payout data for seller
        $payouts = $seller ? Payout::where('seller_id', $seller->id)
            ->orderByDesc('created_at')
            ->get() : collect();

        $nextPayoutDate = $seller ? (Payout::getNextPayoutDate($seller->id) ?? 'N/A') : 'N/A';
        $payableBalance = $seller ? Payout::getPayableBalance($seller->id) : 0;
        $totalRefunds = $seller ? Payout::getTotalRefundDeductions($seller->id) : 0;
        $totalPaidOut = $seller ? Payout::where('seller_id', $seller->id)
            ->where('status', 'completed')
            ->sum('net_amount') : 0;

        return view('payments.index', compact(
            'payments', 'totalRevenue', 'pendingAmount',
            'payouts', 'nextPayoutDate', 'payableBalance', 'totalPaidOut', 'totalRefunds'
        ));
    }

    // Function to Mark as Paid
    public function updateStatus($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'Completed';
        $payment->save();

        return redirect()->back()->with('success', 'Payment marked as Completed!');
    }

    // Create Razorpay Order
    public function createRazorpayOrder(Request $request)
    {
        try {
            \Log::info('createRazorpayOrder called', [
                'request_data' => $request->all(),
                'has_cart' => !empty(session()->get('cart', []))
            ]);
            
            // 1. Validate
            $validator = \Validator::make($request->all(), [
                'customer_name' => 'required|string',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'shipping_address' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                \Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first()
                ], 422);
            }
            
            $validated = $validator->validated();
            \Log::info('Validation passed', $validated);

            // 2. Check Keys
            if (empty($this->razorpayKey) || empty($this->razorpaySecret)) {
                return response()->json(['success' => false, 'message' => 'Payment keys not found.'], 500);
            }

            \Log::info('Razorpay config check', [
                'key_set' => !empty($this->razorpayKey),
                'secret_set' => !empty($this->razorpaySecret),
                // Log only a safe prefix of the public key (never the secret)
                'key_prefix' => substr($this->razorpayKey, 0, 8),
            ]);

            $api = new Api($this->razorpayKey, $this->razorpaySecret);
            
            // 3. Get Cart
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return response()->json(['success' => false, 'message' => 'Cart is empty.'], 400);
            }

            // 4. Calculate Total SECURELY (Server Side) using FeeCalculator (includes GST & Platform Fee)
            $feeCalculator = new \App\Services\FeeCalculator();
            $fees = $feeCalculator->calculateForCart($cart);
            $amountRupees = $fees['total'];
            
            \Log::debug('Cart fee calculation', [
                'subtotal' => $fees['subtotal'],
                'tax_amount' => $fees['tax_amount'],
                'platform_fee' => $fees['platform_fee'],
                'grand_total' => $fees['total']
            ]);

            // 5. Convert to Paise (₹1 = 100 paise)
            $amountInPaise = (int) round($amountRupees * 100);
            
            \Log::info('Razorpay amount calculation', [
                'amount_rupees' => $amountRupees,
                'amount_paise' => $amountInPaise,
                'cart_count' => count($cart)
            ]);
            
            // Create Razorpay Order
            $orderData = [
                'receipt'         => 'order_' . time(),
                'amount'          => $amountInPaise, // <--- This ensures the Popup shows Real Price
                'currency'        => 'INR',
                'payment_capture' => 1
            ];

            \Log::debug('Razorpay orderData prepared', $orderData);

            $razorpayOrder = $api->order->create($orderData);

            \Log::info('Razorpay Order Created', [
                'order_id' => $razorpayOrder['id'],
                'real_price_rupees' => $amountRupees
            ]);

            // Log full order details for debugging (safe: contains no secret)
            \Log::debug('Razorpay Order Details', [
                'order' => $razorpayOrder
            ]);

            // 6. Store the REAL Calculated Amount in Session (Not the Request amount)
            session([
                'razorpay_order_id' => $razorpayOrder['id'],
                'customer_data' => [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                    'address' => $request->shipping_address,
                ],
                'amount' => $amountRupees, // <--- Save the REAL amount here
            ]);

            // Return clean, flat object for frontend (avoid prototype chain issues)
            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $razorpayOrder['id'],
                    'amount' => $razorpayOrder['amount'],
                    'currency' => $razorpayOrder['currency']
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // Verify Razorpay Payment
    public function verifyPayment(Request $request)
    {
        try {
            $api = new Api($this->razorpayKey, $this->razorpaySecret);
            
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            // Verify signature
            $api->utility->verifyPaymentSignature($attributes);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ], 400);
        }
    }

    private function currentSeller(): ?User
    {
        $username = session('loginusername');
        return $username ? User::where('name', $username)->first() : null;
    }
}