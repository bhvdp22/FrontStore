<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Returns - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #232f3e;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-title i { color: #febd69; }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            color: #232f3e;
        }
        .btn-primary:hover {
            box-shadow: 0 4px 12px rgba(247,202,0,0.3);
        }
        
        .returns-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .return-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .return-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .return-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e5e7eb;
            flex-wrap: wrap;
            gap: 12px;
        }
        .return-number {
            font-weight: 600;
            color: #007185;
        }
        .return-date {
            font-size: 13px;
            color: #6b7280;
        }
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .return-body {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            padding: 20px;
            align-items: center;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }
        
        .product-info h4 {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }
        .product-info p {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .refund-amount {
            font-size: 18px;
            font-weight: 700;
            color: #059669;
        }
        
        .return-actions {
            display: flex;
            gap: 12px;
        }
        .return-actions .btn {
            padding: 8px 16px;
            font-size: 13px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .empty-state i {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 16px;
        }
        .empty-state h3 {
            font-size: 20px;
            color: #374151;
            margin-bottom: 8px;
        }
        .empty-state p {
            color: #6b7280;
            margin-bottom: 20px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
        }
        .pagination a, .pagination span {
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: #374151;
            background: #fff;
            border: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .pagination a:hover { background: #f3f4f6; }
        .pagination .active { background: #232f3e; color: #fff; border-color: #232f3e; }
        
        @media (max-width: 768px) {
            .return-body {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .product-image { margin: 0 auto; }
            .return-actions { justify-content: center; }
        }
    </style>
</head>
<body>
    @include('shop.partials.navbar')
    
    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-undo-alt"></i> My Returns</h1>
            <a href="{{ route('returns.eligible') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Request New Return
            </a>
        </div>
        
        @if(session('success'))
            <div style="background:#d1fae5;color:#065f46;padding:14px 20px;border-radius:8px;margin-bottom:20px;font-weight:500;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div style="background:#fee2e2;color:#991b1b;padding:14px 20px;border-radius:8px;margin-bottom:20px;font-weight:500;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
        @if($returns->count() > 0)
            <div class="returns-list">
                @foreach($returns as $return)
                    <div class="return-card">
                        <div class="return-header">
                            <div>
                                <span class="return-number">{{ $return->return_number }}</span>
                                <span class="return-date">• {{ $return->created_at->format('M d, Y') }}</span>
                            </div>
                            <span class="status-badge" style="background:{{ $return->status_color }}22;color:{{ $return->status_color }};">
                                {{ $return->status_label }}
                            </span>
                        </div>
                        <div class="return-body">
                            @php
                                $placeholder = 'https://placehold.co/80x80?text=No+Image';
                                $img = $return->product->img_path ?? null;
                                if ($img && !preg_match('/^https?:\/\//', $img)) {
                                    $img = asset('storage/' . ltrim($img, '/'));
                                }
                                if (!$img) {
                                    $img = 'https://m.media-amazon.com/images/I/41-a+x5eB+L._SX342_SY445_.jpg';
                                }
                            @endphp
                            <img src="{{ $img }}" alt="{{ $return->product->name ?? '' }}" class="product-image" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src='{{ $placeholder }}'">
                            <div class="product-info">
                                <h4>{{ $return->product->name ?? 'Product' }}</h4>
                                <p>Order #{{ $return->order_id }} • Qty: {{ $return->quantity }}</p>
                                <p><strong>Reason:</strong> {{ $return->reason_label }}</p>
                            </div>
                            <div>
                                <div class="refund-amount">₹{{ number_format($return->refund_amount, 0) }}</div>
                                <div class="return-actions" style="margin-top:12px;">
                                    <a href="{{ route('returns.show', $return->id) }}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="pagination">
                {{ $returns->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Returns Yet</h3>
                <p>You haven't requested any returns. If you need to return an item, click the button below.</p>
                <a href="{{ route('returns.eligible') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Request a Return
                </a>
            </div>
        @endif
    </div>
    
    @include('shop.partials.footer')
</body>
</html>
