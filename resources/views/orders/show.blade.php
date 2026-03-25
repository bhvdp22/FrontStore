@extends('layouts.seller')

@section('title', 'Order Details - Seller Central')

@section('extra_styles')
<style>
    body { font-family: 'Poppins', sans-serif; background: #f1f3f3; margin: 0; }
    
    .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    
    .header-row {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .header-title {
        font-size: 24px;
        font-weight: 700;
        color: #0f1111;
        margin-bottom: 8px;
    }
    
    .header-subtitle {
        font-size: 14px;
        color: #565959;
    }
    
    .header-subtitle span {
        margin-right: 20px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }
    
    .btn-back {
        background: white;
        color: #0f1111;
        border-color: #888;
    }
    
    .btn-back:hover {
        background: #f0f2f2;
    }
    
    .btn-primary {
        background: #ff9900;
        color: white;
        border-color: #ff9900;
    }
    
    .btn-primary:hover {
        background: #e88b00;
    }
    
    .btn-secondary {
        background: #232f3e;
        color: white;
        border-color: #232f3e;
    }
    
    .btn-secondary:hover {
        background: #37475a;
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f1111;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e7e7e7;
    }
    
    .info-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #f0f2f2;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        width: 140px;
        color: #565959;
        font-size: 13px;
        font-weight: 600;
    }
    
    .info-value {
        flex: 1;
        color: #0f1111;
        font-size: 13px;
    }
    
    .product-item {
        display: grid;
        grid-template-columns: 80px 1fr auto;
        gap: 16px;
        padding: 16px;
        border: 1px solid #e7e7e7;
        border-radius: 6px;
        margin-bottom: 12px;
    }
    
    .product-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        border: 1px solid #e7e7e7;
        border-radius: 4px;
        padding: 4px;
        background: white;
    }
    
    .product-details h4 {
        margin: 0 0 6px 0;
        font-size: 14px;
        font-weight: 600;
        color: #0f1111;
    }
    
    .product-details p {
        margin: 0;
        font-size: 12px;
        color: #565959;
    }
    
    .product-price {
        text-align: right;
    }
    
    .product-price .price {
        font-size: 16px;
        font-weight: 700;
        color: #0f1111;
    }
    
    .product-price .qty {
        font-size: 12px;
        color: #565959;
        margin-top: 4px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        background: #d1fae5;
        color: #065f46;
    }
    
    .timeline {
        margin-top: 16px;
    }
    
    .timeline-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        position: relative;
    }
    
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 35px;
        bottom: -12px;
        width: 2px;
        background: #e7e7e7;
    }
    
    .timeline-dot {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #067d62;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #067d62;
        flex-shrink: 0;
    }
    
    .timeline-content h5 {
        margin: 0 0 4px 0;
        font-size: 13px;
        font-weight: 600;
        color: #0f1111;
    }
    
    .timeline-content p {
        margin: 0;
        font-size: 12px;
        color: #565959;
    }
    
    .total-section {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 2px solid #e7e7e7;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 14px;
    }
    
    .total-row.grand {
        font-size: 18px;
        font-weight: 700;
        color: #0f1111;
        padding-top: 12px;
        border-top: 1px solid #e7e7e7;
    }

    @media print {
        body { background: white; }
        .action-buttons, .btn { display: none; }
        .header-row, .card { box-shadow: none; border: 1px solid #ddd; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Header -->
    <div class="header-row">
        <div class="header-title">Order details</div>
        <div class="header-subtitle">
            <span>Order ID: <strong>#{{ $order->order_id }}</strong></span>
            <span>Your Seller Order ID: <strong>#{{ $order->id }}</strong></span>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('orders.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Go back to List Orders
            </a>
            <a href="{{ route('orders.packingSlip', $order->id) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-print"></i> Print packing slip
            </a>
            <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-secondary">
                <i class="fas fa-file-invoice"></i> Print tax invoice
            </a>
            @if($order->status != 'cancelled')
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-secondary">
                <i class="fas fa-edit"></i> Edit Order
            </a>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-grid">
        <!-- Left Column -->
        <div>
            <!-- Order Summary -->
            <div class="card">
                <div class="card-title">Order Summary</div>
                <div class="info-row">
                    <div class="info-label">Ship by:</div>
                    <div class="info-value">{{ $order->created_at ? $order->created_at->addDays(2)->format('D, d M, Y') : 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Deliver by:</div>
                    <div class="info-value">{{ $order->created_at ? $order->created_at->addDays(7)->format('D, d M, Y') : 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Purchase date:</div>
                    <div class="info-value">{{ $order->created_at ? $order->created_at->format('D, d M, Y, g:i a T') : 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Shipping service:</div>
                    <div class="info-value">Standard</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fulfillment:</div>
                    <div class="info-value">Seller</div>
                </div>
            </div>

            <!-- Product Package -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-title">Package 1</div>
                
                @php
                    $imgSrc = 'https://placehold.co/80?text=No+Image';
                    if ($product && $product->img_path) {
                        if (preg_match('/^https?:\/\//', $product->img_path)) {
                            $imgSrc = $product->img_path;
                        } elseif (preg_match('/^\/storage\/|^storage\//', $product->img_path)) {
                            $imgSrc = asset(ltrim($product->img_path, '/'));
                        } else {
                            $imgSrc = str_starts_with($product->img_path, 'http') ? $product->img_path : asset('storage/' . ltrim($product->img_path, '/'));
                        }
                    }
                @endphp
                
                <div class="product-item">
                    <img src="{{ $imgSrc }}" 
                         alt="{{ $order->product_name }}" 
                         class="product-img"
                         onerror="this.onerror=null;this.src='https://placehold.co/80?text=No+Image'">
                    
                    <div class="product-details">
                        <h4>{{ $order->product_name }}</h4>
                        <p>Condition: New</p>
                        <p>Order Item ID: {{ $order->order_id }}</p>
                        <p>ASIN: {{ $product->asin ?? 'N/A' }}</p>
                        <p>SKU: {{ $order->sku }}</p>
                    </div>
                    
                    <div class="product-price">
                        <div class="price">₹{{ number_format((float)$order->total_price, 2) }}</div>
                        <div class="qty">Quantity: {{ $order->quantity }}</div>
                    </div>
                </div>

                <div class="total-section">
                    <div class="total-row">
                        <span>Item subtotal:</span>
                        <span>₹{{ number_format((float)($order->subtotal ?: $order->total_price), 2) }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                    <div class="total-row">
                        <span>Tax ({{ $order->tax_rate }}%):</span>
                        <span>₹{{ number_format((float)$order->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($order->platform_fee > 0)
                    <div class="total-row">
                        <span>Platform Fee:</span>
                        <span>₹{{ number_format((float)$order->platform_fee, 2) }}</span>
                    </div>
                    @endif
                    <div class="total-row grand">
                        <span>Order Total:</span>
                        <span>₹{{ number_format((float)$order->total_price, 2) }}</span>
                    </div>
                    @if($order->commission_amount > 0)
                    <div class="total-row" style="margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ddd;">
                        <span style="color: #dc3545;">Commission ({{ $order->commission_rate }}%):</span>
                        <span style="color: #dc3545;">-₹{{ number_format((float)$order->commission_amount, 2) }}</span>
                    </div>
                    <div class="total-row grand" style="background: #e8f5e9;">
                        <span style="color: #28a745;"><i class="fas fa-wallet"></i> Your Earnings:</span>
                        <span style="color: #28a745; font-weight: 700;">₹{{ number_format((float)$order->seller_earnings, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-title">Shipping label purchase</div>
                <div class="info-row">
                    <div class="info-label">Package type:</div>
                    <div class="info-value">Package</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dimensions (LWH):</div>
                    <div class="info-value">12.0 x 12.0 x 12.0 CM</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Package weight:</div>
                    <div class="info-value">0.5 KG</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Shipping cost:</div>
                    <div class="info-value">₹0.00</div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <!-- Ship to -->
            <div class="card">
                <div class="card-title">Ship to</div>
                <div style="margin-bottom: 12px;">
                    <strong style="font-size: 14px;">{{ $order->customer_name }}</strong>
                </div>
                <div style="font-size: 13px; color: #565959; line-height: 1.6;">
                    {{ $order->shipping_address ?? 'Address not available' }}
                </div>
                @if($order->customer_phone)
                <div style="margin-top: 12px; font-size: 13px;">
                    <strong>Phone:</strong> {{ $order->customer_phone }}
                </div>
                @endif
                @if($order->customer_email)
                <div style="margin-top: 8px;">
                    <a href="mailto:{{ $order->customer_email }}" style="color: #007185; font-size: 13px;">
                        Contact Buyer
                    </a>
                </div>
                @endif
            </div>

            <!-- Order Status -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-title">Order Status</div>
                <div style="margin-bottom: 16px;">
                    <span class="status-badge">{{ ucfirst($order->status) }}</span>
                </div>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h5>Order Placed</h5>
                            <p>{{ $order->created_at ? $order->created_at->format('M d, Y g:i A') : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h5>Processing</h5>
                            <p>{{ $order->updated_at ? $order->updated_at->format('M d, Y g:i A') : 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->status == 'shipped' || $order->status == 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h5>Shipped</h5>
                            <p>Estimated delivery in 5-7 days</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->status == 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h5>Delivered</h5>
                            <p>Order successfully delivered</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment -->
            @if($order->payment)
            <div class="card" style="margin-top: 20px;">
                <div class="card-title">Payment Information</div>
                <div class="info-row">
                    <div class="info-label">Method:</div>
                    <div class="info-value">{{ $order->payment->payment_method }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Amount:</div>
                    <div class="info-value">₹{{ number_format((float)$order->payment->amount, 2) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge">{{ $order->payment->status }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
