<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eligible for Return - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        
        .page-header { margin-bottom: 24px; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: #007185; text-decoration: none; font-size: 14px; margin-bottom: 16px; }
        .back-link:hover { text-decoration: underline; }
        .page-title { font-size: 28px; font-weight: 700; color: #232f3e; }
        .page-subtitle { color: #6b7280; margin-top: 8px; }
        
        .orders-list { display: flex; flex-direction: column; gap: 20px; }
        
        .order-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e5e7eb;
        }
        .order-header span { font-weight: 600; color: #374151; }
        .order-header small { color: #6b7280; }
        
        .order-items { padding: 20px; }
        
        .order-item {
            display: flex;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
            align-items: center;
        }
        .order-item:last-child { border-bottom: none; }
        
        .order-item img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }
        
        .item-info { flex: 1; }
        .item-info h4 { font-size: 15px; font-weight: 600; color: #111827; margin-bottom: 4px; }
        .item-info p { font-size: 13px; color: #6b7280; margin-bottom: 2px; }
        .item-info .price { font-size: 16px; font-weight: 700; color: #059669; }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            color: #232f3e;
        }
        .btn-primary:hover { box-shadow: 0 4px 12px rgba(247,202,0,0.3); }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .empty-state i { font-size: 48px; color: #d1d5db; margin-bottom: 16px; }
        .empty-state h3 { font-size: 20px; color: #374151; margin-bottom: 8px; }
        .empty-state p { color: #6b7280; }
        
        .info-box {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .info-box i { color: #f59e0b; font-size: 20px; }
        .info-box p { color: #92400e; font-size: 14px; }
    </style>
</head>
<body>
    @include('shop.partials.navbar')
    
    <div class="container">
        <div class="page-header">
            <a href="{{ route('returns.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to My Returns
            </a>
            <h1 class="page-title">Items Eligible for Return</h1>
            <p class="page-subtitle">Select an item from your recent orders to request a return</p>
        </div>
        
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Items can be returned within 30 days of delivery. Make sure the item is unused and in original packaging.</p>
        </div>
        
        @if($eligibleItems->count() > 0)
            <div class="orders-list">
                @foreach($eligibleItems as $item)
                    <div class="order-card">
                        <div class="order-header">
                            <span>Order #{{ $item->order_id }}</span>
                            <small>Ordered on {{ $item->created_at->format('M d, Y') }}</small>
                        </div>
                        <div class="order-items">
                            <div class="order-item">
                                @php
                                    $imgSrc = 'https://placehold.co/80?text=No+Image';
                                    if ($item->img_path) {
                                        if (preg_match('/^https?:\/\//', $item->img_path)) {
                                            $imgSrc = $item->img_path;
                                        } elseif (preg_match('/^\/storage\/|^storage\//', $item->img_path)) {
                                            $imgSrc = asset(ltrim($item->img_path, '/'));
                                        } else {
                                            $imgSrc = str_starts_with($item->img_path, 'http') ? $item->img_path : asset('storage/' . ltrim($item->img_path, '/'));
                                        }
                                    }
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $item->product_name }}"
                                     onerror="this.onerror=null;this.src='https://placehold.co/80?text=No+Image'">
                                <div class="item-info">
                                    <h4>{{ $item->product_name }}</h4>
                                    <p>SKU: {{ $item->sku }} • Qty: {{ $item->quantity }}</p>
                                    <div class="price">₹{{ number_format($item->total_price, 2) }}</div>
                                </div>
                                <a href="{{ route('returns.create', ['order_item_id' => $item->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-undo"></i> Return Item
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Eligible Items</h3>
                <p>You don't have any items eligible for return at this time.</p>
            </div>
        @endif
    </div>
    
    @include('shop.partials.footer')
</body>
</html>
