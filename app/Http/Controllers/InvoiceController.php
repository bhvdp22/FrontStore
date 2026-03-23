<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Generate and download invoice PDF for an order (Seller side)
     */
    public function generateInvoice($id)
    {
        $order = $this->findOrder($id);
        $seller = $this->getSellerFromOrder($order);
        
        // Get order items if available
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        
        // If no order items, create a single item from order data
        if ($orderItems->isEmpty()) {
            $orderItems = collect([
                (object) [
                    'product_name' => $order->product_name,
                    'quantity' => $order->quantity,
                    'price' => $order->subtotal > 0 ? $order->subtotal / $order->quantity : $order->total_price / $order->quantity,
                ]
            ]);
        }

        // Get seller details for invoice
        $company = $this->getSellerCompanyDetails($seller);
        
        // Get business settings
        $settings = BusinessSetting::current();

        $invoiceData = [
            'order' => $order,
            'orderItems' => $orderItems,
            'company' => $company,
            'seller' => $seller,
            'settings' => $settings,
            'invoiceNumber' => 'INV-' . strtoupper(substr(md5($order->order_id), 0, 8)),
            'invoiceDate' => $order->created_at->format('d M, Y'),
        ];

        $pdf = Pdf::loadView('invoices.invoice', $invoiceData);
        
        return $pdf->download('Invoice-' . $order->order_id . '.pdf');
    }

    /**
     * Preview invoice in browser (Seller side)
     */
    public function previewInvoice($id)
    {
        $order = $this->findOrder($id);
        $seller = $this->getSellerFromOrder($order);
        
        // Get order items if available
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        
        // If no order items, create a single item from order data
        if ($orderItems->isEmpty()) {
            $orderItems = collect([
                (object) [
                    'product_name' => $order->product_name,
                    'quantity' => $order->quantity,
                    'price' => $order->subtotal > 0 ? $order->subtotal / $order->quantity : $order->total_price / $order->quantity,
                ]
            ]);
        }

        // Get seller details for invoice
        $company = $this->getSellerCompanyDetails($seller);
        
        // Get business settings
        $settings = BusinessSetting::current();

        $invoiceData = [
            'order' => $order,
            'orderItems' => $orderItems,
            'company' => $company,
            'seller' => $seller,
            'settings' => $settings,
            'invoiceNumber' => 'INV-' . strtoupper(substr(md5($order->order_id), 0, 8)),
            'invoiceDate' => $order->created_at->format('d M, Y'),
        ];

        $pdf = Pdf::loadView('invoices.invoice', $invoiceData);
        
        return $pdf->stream('Invoice-' . $order->order_id . '.pdf');
    }

    /**
     * Generate invoice for customer (by order_id string)
     * Groups items by seller — each seller gets a separate invoice page (like Amazon/Flipkart)
     */
    public function customerInvoice($orderId)
    {
        $email = session('customer_email');
        
        if (!$email) {
            return redirect()->route('customer.login')->with('error', 'Please login to download invoice.');
        }

        // Get all orders for this order_id (including items with suffix like ORD-xxx-2)
        $allOrders = Order::where('customer_email', $email)
            ->where(function ($query) use ($orderId) {
                $query->where('order_id', $orderId)
                      ->orWhere('order_id', 'LIKE', $orderId . '-%');
            })
            ->get();

        if ($allOrders->isEmpty()) {
            abort(404, 'Order not found or access denied.');
        }

        // Get business settings
        $settings = BusinessSetting::current();

        // Group orders by seller
        $sellerGroups = [];
        
        foreach ($allOrders as $order) {
            $product = Product::where('sku', $order->sku)->first();
            $sellerId = $product && $product->seller_id ? $product->seller_id : 0;
            
            if (!isset($sellerGroups[$sellerId])) {
                $seller = $sellerId ? User::find($sellerId) : null;
                $sellerGroups[$sellerId] = [
                    'seller' => $seller,
                    'company' => $this->getSellerCompanyDetails($seller),
                    'orders' => collect(),
                    'items' => collect(),
                ];
            }
            
            $sellerGroups[$sellerId]['orders']->push($order);
            $sellerGroups[$sellerId]['items']->push((object) [
                'product_name' => $order->product_name,
                'quantity' => $order->quantity,
                'price' => $order->subtotal > 0 ? $order->subtotal / $order->quantity : $order->total_price / $order->quantity,
                'sku' => $order->sku,
            ]);
        }

        // If only one seller, generate single invoice (original behavior)
        if (count($sellerGroups) === 1) {
            $group = reset($sellerGroups);
            $firstOrder = $group['orders']->first();
            
            // Calculate totals
            $firstOrder->subtotal = $group['orders']->sum('subtotal') ?: $group['orders']->sum('total_price');
            $firstOrder->tax_amount = $group['orders']->sum('tax_amount');
            $firstOrder->platform_fee = $group['orders']->first()->platform_fee ?? 0;
            $firstOrder->total_price = $group['orders']->sum('total_price');

            $invoiceData = [
                'order' => $firstOrder,
                'orderItems' => $group['items'],
                'company' => $group['company'],
                'seller' => $group['seller'],
                'settings' => $settings,
                'invoiceNumber' => 'INV-' . strtoupper(substr(md5($orderId), 0, 8)),
                'invoiceDate' => $firstOrder->created_at->format('d M, Y'),
            ];

            $pdf = Pdf::loadView('invoices.invoice', $invoiceData);
            return $pdf->download('Invoice-' . $orderId . '.pdf');
        }

        // Multiple sellers → generate combined PDF with per-seller invoice pages
        $invoicePages = [];
        $pageIndex = 1;

        foreach ($sellerGroups as $sellerId => $group) {
            $firstOrder = $group['orders']->first();
            
            // Calculate per-seller totals
            $firstOrder->subtotal = $group['orders']->sum('subtotal') ?: $group['orders']->sum('total_price');
            $firstOrder->tax_amount = $group['orders']->sum('tax_amount');
            $firstOrder->platform_fee = 0; // Platform fee only on first invoice
            $firstOrder->total_price = $group['orders']->sum('total_price');

            $invoicePages[] = [
                'order' => $firstOrder,
                'orderItems' => $group['items'],
                'company' => $group['company'],
                'seller' => $group['seller'],
                'settings' => $settings,
                'invoiceNumber' => 'INV-' . strtoupper(substr(md5($orderId . '-' . $sellerId), 0, 8)),
                'invoiceDate' => $firstOrder->created_at->format('d M, Y'),
            ];
            $pageIndex++;
        }

        // Render multi-seller invoice view
        $pdf = Pdf::loadView('invoices.invoice-multi', [
            'invoicePages' => $invoicePages,
            'orderId' => $orderId,
        ]);
        
        return $pdf->download('Invoice-' . $orderId . '.pdf');
    }

    /**
     * Get seller from order's product SKU
     */
    private function getSellerFromOrder(Order $order): ?User
    {
        $product = Product::where('sku', $order->sku)->first();
        
        if ($product && $product->seller_id) {
            return User::find($product->seller_id);
        }
        
        return null;
    }

    /**
     * Build company details array from seller data
     */
    private function getSellerCompanyDetails(?User $seller): array
    {
        if (!$seller) {
            // Fallback to default company details
            return [
                'name' => 'FrontStore',
                'tagline' => 'Your Trusted Online Store',
                'address' => '123 Business Park',
                'city' => 'Mumbai, Maharashtra 400001',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'country' => 'India',
                'phone' => '+91 98765 43210',
                'email' => 'support@frontstore.com',
                'website' => 'www.frontstore.com',
                'gstin' => '',
                'pan' => '',
                'cin' => '',
            ];
        }

        return [
            'name' => $seller->business_name ?: $seller->name,
            'tagline' => 'Trusted Seller on FrontStore',
            'address' => $seller->business_address ?: '',
            'city' => $seller->city ?: '',
            'state' => $seller->state ?: '',
            'pincode' => $seller->pincode ?: '',
            'country' => $seller->country ?: 'India',
            'phone' => $seller->phone ? '+91 ' . $seller->phone : '',
            'email' => $seller->email ?: '',
            'website' => '',
            'gstin' => $seller->gstin ?: '',
            'pan' => $seller->pan ?: '',
            'cin' => $seller->cin ?: '',
        ];
    }

    /**
     * Find order with ownership check
     */
    private function findOrder($id): Order
    {
        $seller = $this->currentSeller();
        $sellerSkus = $seller ? Product::where('seller_id', $seller->id)->pluck('sku') : Product::pluck('sku');

        $order = Order::where('id', $id)
            ->when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->first();

        if (!$order) {
            abort(404, 'Order not found or access denied.');
        }

        return $order;
    }

    private function currentSeller(): ?User
    {
        $username = session('loginusername');
        return $username ? User::where('name', $username)->first() : null;
    }
}
