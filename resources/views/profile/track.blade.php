<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - {{ $orderId }} - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }

        /* Container */
        .container { max-width: 1000px; margin: 0 auto; padding: 30px 20px; }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 50%, #485769 100%);
            border-radius: 16px;
            padding: 35px 40px;
            margin-bottom: 25px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,189,105,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            margin-bottom: 15px;
            position: relative;
        }
        .breadcrumb a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .breadcrumb a:hover { color: #febd69; }
        .breadcrumb span { color: rgba(255,255,255,0.5); }
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        .page-header h1 i { color: #febd69; }
        .page-header p {
            font-size: 14px;
            opacity: 0.85;
            position: relative;
        }

        /* Status Overview Card */
        .status-overview {
            background: white;
            border-radius: 16px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .status-left h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0f1111;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-left h2 i { color: #ff9900; }
        .status-left p { color: #565959; font-size: 14px; }
        
        .status-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-badge-large i { font-size: 18px; }
        .status-pending-large {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }
        .status-shipped-large {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }
        .status-delivered-large {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }
        .status-mixed-large {
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            color: #3730a3;
        }

        /* Tracking Card */
        .tracking-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f1111;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i { color: #ff9900; }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 60px;
            margin: 30px 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 24px;
            top: 10px;
            bottom: 10px;
            width: 4px;
            background: linear-gradient(180deg, #e0e6eb 0%, #f0f2f4 100%);
            border-radius: 4px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 35px;
        }
        .timeline-item:last-child { margin-bottom: 0; }

        .timeline-icon {
            position: absolute;
            left: -60px;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: linear-gradient(135deg, #f3f5f7, #e8ebee);
            color: #7a8794;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .timeline-icon.active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        .timeline-icon.current {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
            animation: pulse 2s infinite;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4); }
            50% { transform: scale(1.08); box-shadow: 0 8px 25px rgba(245, 158, 11, 0.5); }
        }

        .timeline-content {
            background: linear-gradient(135deg, #fafafa, #fff);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #f0f0f0;
            transition: all 0.3s;
        }
        .timeline-item:hover .timeline-content {
            border-color: #e0e0e0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .timeline-content h3 {
            margin: 0 0 6px 0;
            font-size: 16px;
            font-weight: 600;
            color: #0f1111;
        }
        .timeline-content p {
            margin: 0;
            color: #565959;
            font-size: 14px;
        }
        .timeline-date {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #007185;
            margin-top: 10px;
            background: #e7f4f7;
            padding: 5px 12px;
            border-radius: 20px;
        }

        /* Products Section */
        .products-card {
            background: white;
            border-radius: 16px;
            padding: 25px 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .product-item {
            display: flex;
            gap: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #fafafa, #fff);
            border-radius: 12px;
            margin-bottom: 15px;
            border: 1px solid #f0f0f0;
            transition: all 0.3s;
        }
        .product-item:last-child { margin-bottom: 0; }
        .product-item:hover {
            border-color: #e0e0e0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }
        .product-image-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-image-placeholder i { color: #bbb; font-size: 24px; }

        .product-details { flex: 1; }
        .product-name {
            font-size: 15px;
            font-weight: 600;
            color: #0f1111;
            margin-bottom: 6px;
        }
        .product-meta {
            font-size: 13px;
            color: #565959;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .product-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-unshipped {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }
        .status-shipped {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }
        .status-delivered {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #0f1111;
            text-align: right;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            color: #0f1111;
        }
        .btn-primary:hover {
            background: linear-gradient(180deg, #f7ca00 0%, #e6b800 100%);
            box-shadow: 0 4px 12px rgba(247, 202, 0, 0.4);
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: white;
            color: #0f1111;
            border: 1px solid #d5d9d9;
        }
        .btn-secondary:hover {
            background: #f7fafa;
            border-color: #007185;
            color: #007185;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Delivery Info */
        .delivery-info {
            background: linear-gradient(135deg, #e7f4f7, #d1e9ef);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .delivery-info i {
            font-size: 24px;
            color: #007185;
        }
        .delivery-info-text h4 {
            font-size: 14px;
            font-weight: 600;
            color: #0f1111;
            margin-bottom: 3px;
        }
        .delivery-info-text p {
            font-size: 13px;
            color: #565959;
        }

        @media (max-width: 768px) {
            .page-header { padding: 25px; }
            .page-header h1 { font-size: 22px; }
            .status-overview { flex-direction: column; align-items: flex-start; }
            .timeline { padding-left: 50px; }
            .timeline-icon { left: -50px; width: 44px; height: 44px; font-size: 16px; }
            .product-item { flex-direction: column; }
            .product-image, .product-image-placeholder { width: 100%; height: 120px; }
            .product-price { text-align: left; margin-top: 10px; }
            .action-buttons { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    @include('shop.partials.navbar')

    <div class="container">

        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb">
                <a href="{{ route('shop.index') }}"><i class="fas fa-home"></i> Home</a>
                <span>/</span>
                <a href="{{ route('profile.index') }}">My Account</a>
                <span>/</span>
                <a href="{{ route('profile.orders') }}">My Orders</a>
                <span>/</span>
                <span style="color: #febd69;">Track Order</span>
            </div>
            <h1><i class="fas fa-map-marker-alt"></i> Track Your Order</h1>
            <p>Real-time tracking for Order #{{ $orderId }}</p>
        </div>

        <!-- Status Overview -->
        <div class="status-overview">
            <div class="status-left">
                <h2><i class="fas fa-receipt"></i> Order #{{ $orderId }}</h2>
                <p>Placed on {{ $orderItems->first()->created_at->timezone(config('app.timezone'))->format('F d, Y') }}</p>
            </div>
            @php
                $allStatuses = $orderItems->pluck('status')->unique();
                $hasMixedStatus = $allStatuses->count() > 1;
            @endphp
            @if($hasMixedStatus)
                <span class="status-badge-large status-mixed-large">
                    <i class="fas fa-info-circle"></i> Multiple Status
                </span>
            @else
                @php $status = $orderItems->first()->status; @endphp
                @if($status == 'Unshipped')
                    <span class="status-badge-large status-pending-large">
                        <i class="fas fa-clock"></i> Pending Shipment
                    </span>
                @elseif($status == 'Shipped')
                    <span class="status-badge-large status-shipped-large">
                        <i class="fas fa-truck"></i> In Transit
                    </span>
                @elseif($status == 'Delivered')
                    <span class="status-badge-large status-delivered-large">
                        <i class="fas fa-check-circle"></i> Delivered
                    </span>
                @else
                    <span class="status-badge-large">{{ $status }}</span>
                @endif
            @endif
        </div>

        @if(!$hasMixedStatus)
        @php $status = $orderItems->first()->status; @endphp
        <!-- Timeline Card -->
        <div class="tracking-card">
            <div class="section-title"><i class="fas fa-route"></i> Shipment Progress</div>

            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-icon active">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Order Placed</h3>
                        <p>We have received your order and payment confirmation</p>
                        <div class="timeline-date">
                            <i class="far fa-clock"></i>
                            {{ $orderItems->first()->created_at->timezone(config('app.timezone'))->format('M d, Y - h:i A') }}
                        </div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon {{ $status != 'Unshipped' ? 'active' : 'current' }}">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Processing</h3>
                        <p>Your order is being prepared and packed for shipment</p>
                        @if($status != 'Unshipped')
                            <div class="timeline-date">
                                <i class="far fa-clock"></i>
                                {{ $orderItems->first()->updated_at->timezone(config('app.timezone'))->format('M d, Y - h:i A') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon {{ $status == 'Shipped' || $status == 'Delivered' ? 'active' : '' }} {{ $status == 'Shipped' ? 'current' : '' }}">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Shipped</h3>
                        <p>Your package is on the way to your delivery address</p>
                        @if($status == 'Shipped' || $status == 'Delivered')
                            <div class="timeline-date">
                                <i class="far fa-clock"></i>
                                {{ $orderItems->first()->updated_at->timezone(config('app.timezone'))->format('M d, Y - h:i A') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon {{ $status == 'Delivered' ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Delivered</h3>
                        <p>Package has been successfully delivered to your address</p>
                        @if($status == 'Delivered')
                            <div class="timeline-date">
                                <i class="far fa-clock"></i>
                                {{ $orderItems->first()->updated_at->timezone(config('app.timezone'))->format('M d, Y - h:i A') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($status == 'Shipped')
            <div class="delivery-info">
                <i class="fas fa-truck-fast"></i>
                <div class="delivery-info-text">
                    <h4>Your package is on the way!</h4>
                    <p>Expected delivery within 2-3 business days. You will receive a notification once delivered.</p>
                </div>
            </div>
            @elseif($status == 'Delivered')
            <div class="delivery-info" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                <i class="fas fa-check-circle" style="color: #16a34a;"></i>
                <div class="delivery-info-text">
                    <h4>Package Delivered!</h4>
                    <p>Your order has been successfully delivered. We hope you enjoy your purchase!</p>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Products Card -->
        <div class="products-card">
            <div class="section-title">
                <i class="fas fa-box-open"></i> Order Items 
                <span style="background: #232f3e; color: white; padding: 4px 12px; border-radius: 20px; font-size: 13px; margin-left: 10px;">
                    {{ $orderItems->count() }} {{ $orderItems->count() === 1 ? 'Item' : 'Items' }}
                </span>
            </div>

            @foreach($orderItems as $item)
                <div class="product-item">
                    @if($item->img_path)
                        <img src="{{ $item->img_path }}" alt="{{ $item->product_name }}" class="product-image">
                    @else
                        <div class="product-image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif

                    <div class="product-details">
                        <div class="product-name">{{ $item->product_name }}</div>
                        <div class="product-meta">
                            <span><i class="fas fa-barcode"></i> {{ $item->sku }}</span>
                            <span><i class="fas fa-layer-group"></i> Qty: {{ $item->quantity }}</span>
                        </div>
                        @php $itemStatus = $item->status; @endphp
                        <span class="status-badge status-{{ strtolower($itemStatus) }}">
                            @if($itemStatus == 'Unshipped')
                                <i class="fas fa-clock"></i> Pending
                            @elseif($itemStatus == 'Shipped')
                                <i class="fas fa-truck"></i> Shipped
                            @elseif($itemStatus == 'Delivered')
                                <i class="fas fa-check-circle"></i> Delivered
                            @else
                                {{ $itemStatus }}
                            @endif
                        </span>
                    </div>

                    <div class="product-price">
                        ₹{{ number_format($item->total_price, 2) }}
                    </div>

                    @php
                        $itemDaysSince = $item->updated_at ? $item->updated_at->diffInDays(now()) : 0;
                        $itemReturnEligible = ($item->status === 'Delivered') && $itemDaysSince <= 30;
                        $itemActiveReturn = $itemReturnEligible
                            ? \App\Models\ProductReturn::where('order_item_id', $item->id)
                                ->whereNotIn('status', ['cancelled', 'rejected', 'closed'])
                                ->first()
                            : null;
                    @endphp
                    @if($itemActiveReturn)
                        <a href="{{ route('returns.show', $itemActiveReturn->id) }}"
                           style="margin-top:8px;display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;background:#fef3c7;color:#92400e;border:1px solid #f59e0b;text-decoration:none;">
                            <i class="fas fa-search"></i> View Return
                        </a>
                    @elseif($itemReturnEligible)
                        <a href="{{ route('returns.create', ['order_item_id' => $item->id]) }}"
                           style="margin-top:8px;display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;background:#fff;color:#b12704;border:1px solid #b12704;text-decoration:none;">
                            <i class="fas fa-undo-alt"></i> Return This Item
                        </a>
                    @elseif($item->status === 'Delivered' && !$itemReturnEligible)
                        <span style="margin-top:8px;display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:12px;color:#9ca3af;border:1px solid #e5e7eb;">
                            <i class="fas fa-times-circle"></i> Return Expired
                        </span>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Payment Information -->
        @php
            $payment = $orderItems->first()->payment ?? null;
        @endphp
        @if($payment)
        <div class="products-card">
            <div class="section-title"><i class="fas fa-credit-card"></i> Payment Information</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="padding:16px;background:linear-gradient(135deg,#fafafa,#fff);border-radius:12px;border:1px solid #f0f0f0;">
                    <p style="font-size:12px;color:#9ca3af;margin:0 0 4px;">Payment Method</p>
                    <p style="font-size:15px;font-weight:600;color:#0f1111;margin:0;display:flex;align-items:center;gap:8px;">
                        @if(strtolower($payment->payment_method) === 'cod')
                            <i class="fas fa-money-bill-wave" style="color:#059669;"></i> Cash on Delivery
                        @elseif(strtolower($payment->payment_method) === 'razorpay')
                            <i class="fas fa-bolt" style="color:#3b82f6;"></i> Razorpay
                        @else
                            <i class="fas fa-credit-card" style="color:#6366f1;"></i> {{ ucfirst($payment->payment_method) }}
                        @endif
                    </p>
                </div>
                <div style="padding:16px;background:linear-gradient(135deg,#fafafa,#fff);border-radius:12px;border:1px solid #f0f0f0;">
                    <p style="font-size:12px;color:#9ca3af;margin:0 0 4px;">Amount Paid</p>
                    <p style="font-size:18px;font-weight:700;color:#0f1111;margin:0;">₹{{ number_format((float)$payment->amount, 2) }}</p>
                </div>
                <div style="padding:16px;background:linear-gradient(135deg,#fafafa,#fff);border-radius:12px;border:1px solid #f0f0f0;">
                    <p style="font-size:12px;color:#9ca3af;margin:0 0 4px;">Payment Status</p>
                    <p style="font-size:14px;font-weight:600;margin:0;">
                        @if($payment->status === 'Completed' || $payment->status === 'completed')
                            <span style="color:#059669;"><i class="fas fa-check-circle"></i> Completed</span>
                        @elseif($payment->status === 'Pending' || $payment->status === 'pending')
                            <span style="color:#f59e0b;"><i class="fas fa-clock"></i> Pending</span>
                        @elseif($payment->status === 'Failed' || $payment->status === 'failed')
                            <span style="color:#ef4444;"><i class="fas fa-times-circle"></i> Failed</span>
                        @else
                            {{ $payment->status }}
                        @endif
                    </p>
                </div>
                <div style="padding:16px;background:linear-gradient(135deg,#fafafa,#fff);border-radius:12px;border:1px solid #f0f0f0;">
                    <p style="font-size:12px;color:#9ca3af;margin:0 0 4px;">Payment ID</p>
                    <p style="font-size:13px;font-weight:600;color:#0f1111;margin:0;font-family:'Courier New',monospace;letter-spacing:0.3px;">{{ $payment->payment_id }}</p>
                </div>
            </div>

            {{-- Razorpay specific details --}}
            @if($payment->razorpay_payment_id || $payment->razorpay_order_id)
            <div style="margin-top:16px;padding:20px;background:linear-gradient(135deg,#eef2ff,#e0e7ff);border-radius:12px;border:1px solid #c7d2fe;">
                <p style="font-size:13px;font-weight:700;color:#3730a3;margin:0 0 12px;display:flex;align-items:center;gap:8px;"><i class="fas fa-bolt"></i> Razorpay Details</p>
                @if($payment->razorpay_payment_id)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #ddd6fe;">
                    <span style="font-size:13px;color:#6366f1;">Razorpay Payment ID</span>
                    <span style="font-size:13px;font-weight:600;color:#312e81;font-family:'Courier New',monospace;">{{ $payment->razorpay_payment_id }}</span>
                </div>
                @endif
                @if($payment->razorpay_order_id)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
                    <span style="font-size:13px;color:#6366f1;">Razorpay Order ID</span>
                    <span style="font-size:13px;font-weight:600;color:#312e81;font-family:'Courier New',monospace;">{{ $payment->razorpay_order_id }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- COD specific details --}}
            @if(strtolower($payment->payment_method) === 'cod')
            <div style="margin-top:16px;padding:20px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-radius:12px;border:1px solid #a7f3d0;">
                <p style="font-size:13px;font-weight:700;color:#065f46;margin:0 0 8px;display:flex;align-items:center;gap:8px;"><i class="fas fa-money-bill-wave"></i> Cash on Delivery</p>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
                    <span style="font-size:13px;color:#059669;">COD Transaction ID</span>
                    <span style="font-size:13px;font-weight:600;color:#065f46;font-family:'Courier New',monospace;">{{ $payment->payment_id }}</span>
                </div>
                <p style="font-size:12px;color:#065f46;margin:8px 0 0;">Payment will be collected at the time of delivery.</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('profile.orders') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            <a href="{{ route('profile.invoice', $orderId) }}" class="btn btn-secondary">
                <i class="fas fa-file-pdf"></i> Download Invoice
            </a>
            @php
                $firstItem = $orderItems->first();
                $isDelivered = $firstItem && $firstItem->status === 'Delivered';
                $deliveryDate = $firstItem ? $firstItem->updated_at : null;
                $daysSinceDelivery = $deliveryDate ? $deliveryDate->diffInDays(now()) : 0;
                $isReturnEligible = $isDelivered && $daysSinceDelivery <= 30;

                // Check if any item has active return
                $hasActiveReturn = false;
                $activeReturn = null;
                if ($isDelivered) {
                    foreach ($orderItems as $item) {
                        $existingReturn = \App\Models\ProductReturn::where('order_item_id', $item->id)
                            ->whereNotIn('status', ['cancelled', 'rejected', 'closed'])
                            ->first();
                        if ($existingReturn) {
                            $hasActiveReturn = true;
                            $activeReturn = $existingReturn;
                            break;
                        }
                    }
                }
            @endphp
            @if($hasActiveReturn)
                <a href="{{ route('returns.show', $activeReturn->id) }}" class="btn btn-secondary" style="background: linear-gradient(135deg, #fef3c7, #fde68a); border-color: #f59e0b; color: #92400e;">
                    <i class="fas fa-undo-alt"></i> View Return Status
                </a>
            @elseif($isReturnEligible)
                <a href="{{ route('returns.eligible') }}" class="btn btn-secondary" style="border-color: #b12704; color: #b12704;">
                    <i class="fas fa-undo-alt"></i> Return an Item
                </a>
            @elseif($isDelivered && !$isReturnEligible)
                <span class="btn btn-secondary" style="opacity: 0.6; cursor: not-allowed; background: #f5f5f5;">
                    <i class="fas fa-times-circle"></i> Return Period Expired
                </span>
            @endif
            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Continue Shopping
            </a>
        </div>

    </div>

</body>
</html>
