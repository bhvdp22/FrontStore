<?php

namespace App\Services;

use App\Models\Refund;
use App\Models\ProductReturn;
use App\Models\Payment;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class RefundService
{
    protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    /**
     * Process refund through Razorpay
     */
    public function processRazorpayRefund(Refund $refund): array
    {
        try {
            $payment = $refund->payment;
            
            if (!$payment || !$payment->razorpay_payment_id) {
                throw new \Exception('No valid Razorpay payment found for refund.');
            }

            // Check if already refunded
            if ($refund->razorpay_refund_id) {
                return [
                    'success' => true,
                    'message' => 'Refund already processed',
                    'refund_id' => $refund->razorpay_refund_id
                ];
            }

            // Process refund through Razorpay
            $razorpayRefund = $this->razorpay->payment
                ->fetch($payment->razorpay_payment_id)
                ->refund([
                    'amount' => (int)($refund->amount * 100), // Amount in paise
                    'speed' => 'normal', // 'normal' or 'optimum'
                    'notes' => [
                        'return_number' => $refund->productReturn->return_number ?? '',
                        'refund_number' => $refund->refund_number,
                        'reason' => $refund->productReturn->return_reason ?? 'Customer requested refund',
                    ],
                    'receipt' => $refund->refund_number,
                ]);

            // Update refund record
            $refund->update([
                'razorpay_refund_id' => $razorpayRefund->id,
                'transaction_id' => $razorpayRefund->id,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Update return status
            if ($refund->productReturn) {
                $refund->productReturn->update([
                    'status' => 'refund_completed',
                    'refund_completed_at' => now(),
                ]);
            }

            Log::info('Razorpay refund processed successfully', [
                'refund_id' => $refund->id,
                'razorpay_refund_id' => $razorpayRefund->id,
                'amount' => $refund->amount,
            ]);

            return [
                'success' => true,
                'message' => 'Refund processed successfully',
                'refund_id' => $razorpayRefund->id,
            ];

        } catch (\Exception $e) {
            Log::error('Razorpay refund failed', [
                'refund_id' => $refund->id,
                'error' => $e->getMessage(),
            ]);

            $refund->update([
                'status' => 'failed',
                'failure_reason' => $e->getMessage(),
                'failed_at' => now(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check refund status from Razorpay
     */
    public function checkRefundStatus(Refund $refund): array
    {
        try {
            if (!$refund->razorpay_refund_id) {
                return [
                    'status' => $refund->status,
                    'message' => 'Refund not yet processed through Razorpay',
                ];
            }

            $razorpayRefund = $this->razorpay->refund->fetch($refund->razorpay_refund_id);

            return [
                'status' => $razorpayRefund->status,
                'amount' => $razorpayRefund->amount / 100,
                'speed_processed' => $razorpayRefund->speed_processed ?? 'normal',
                'created_at' => date('Y-m-d H:i:s', $razorpayRefund->created_at),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to check refund status', [
                'refund_id' => $refund->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process manual refund (bank transfer, UPI, etc.)
     */
    public function processManualRefund(Refund $refund, string $transactionId, string $notes = null): array
    {
        try {
            $refund->update([
                'transaction_id' => $transactionId,
                'notes' => $notes,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Update return status
            if ($refund->productReturn) {
                $refund->productReturn->update([
                    'status' => 'refund_completed',
                    'refund_completed_at' => now(),
                ]);
            }

            Log::info('Manual refund completed', [
                'refund_id' => $refund->id,
                'transaction_id' => $transactionId,
            ]);

            return [
                'success' => true,
                'message' => 'Refund marked as completed',
            ];

        } catch (\Exception $e) {
            Log::error('Manual refund failed', [
                'refund_id' => $refund->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create refund for a return
     */
    public function createRefund(ProductReturn $return, string $method = 'original_payment'): Refund
    {
        $payment = Payment::where('order_id', $return->order_id)->first();

        return Refund::create([
            'refund_number' => Refund::generateRefundNumber(),
            'return_id' => $return->id,
            'order_id' => $return->order_id,
            'customer_id' => $return->customer_id,
            'payment_id' => $payment?->id,
            'amount' => $return->refund_amount,
            'refund_method' => $method,
            'status' => 'pending',
            'initiated_at' => now(),
        ]);
    }

    /**
     * Get refund summary for dashboard
     */
    public function getRefundSummary(int $sellerId = null): array
    {
        $query = Refund::query();

        if ($sellerId) {
            $query->whereHas('productReturn', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            });
        }

        return [
            'total_refunds' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'processing' => (clone $query)->where('status', 'processing')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'failed' => (clone $query)->where('status', 'failed')->count(),
            'total_amount' => (clone $query)->where('status', 'completed')->sum('amount'),
        ];
    }
}
