<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shopping Cart - Front Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }

        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }

        /* Page Header */
        .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .page-header i {
            font-size: 28px;
            color: #ff9900;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f1111;
        }

        /* Cart Layout */
        .cart-container { 
            display: grid; 
            grid-template-columns: 1fr 380px; 
            gap: 25px;
            align-items: start;
        }

        /* Cart Items Card */
        .cart-items { 
            background: #fff; 
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .cart-header {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            color: #fff;
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header h2 {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-header h2 i {
            color: #febd69;
        }

        .items-count {
            background: rgba(255,255,255,0.15);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
        }

        /* Cart Item */
        .cart-item { 
            display: flex; 
            gap: 20px; 
            padding: 25px;
            border-bottom: 1px solid #e8e8e8;
            transition: background 0.2s;
        }

        .cart-item:hover {
            background: #fafafa;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 140px;
            height: 140px;
            background: #f8f8f8;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-image img { 
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
        }

        .item-details { 
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .item-title { 
            font-size: 16px; 
            font-weight: 600; 
            color: #0f1111;
            line-height: 1.4;
        }

        .item-title:hover {
            color: #c45500;
        }

        .item-sku {
            font-size: 12px;
            color: #888;
        }

        .item-stock { 
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #007600; 
            font-size: 13px;
            font-weight: 500;
        }

        .item-stock i {
            font-size: 10px;
        }

        .item-price { 
            color: #b12704; 
            font-size: 20px; 
            font-weight: 700;
        }

        .item-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 8px;
        }

        /* Quantity Selector */
        .quantity-selector { 
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity-selector label {
            font-size: 13px;
            color: #565959;
            font-weight: 500;
        }

        .quantity-selector select { 
            padding: 8px 30px 8px 12px;
            border: 1px solid #d5d9d9;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .quantity-selector select:focus {
            border-color: #ff9900;
            box-shadow: 0 0 0 3px rgba(255,153,0,0.15);
            outline: none;
        }

        .divider-line {
            width: 1px;
            height: 20px;
            background: #d5d9d9;
        }

        .remove-btn { 
            color: #007185; 
            cursor: pointer; 
            text-decoration: none; 
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .remove-btn:hover { 
            color: #c45500;
        }

        .remove-btn i {
            font-size: 12px;
        }

        .item-subtotal {
            text-align: right;
            min-width: 100px;
        }

        .subtotal-label {
            font-size: 12px;
            color: #565959;
            margin-bottom: 4px;
        }

        .subtotal-amount {
            font-size: 18px;
            font-weight: 700;
            color: #0f1111;
        }

        /* Cart Summary Card */
        .cart-summary { 
            background: #fff; 
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            position: sticky;
            top: 100px;
        }

        .summary-header {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            color: #fff;
            padding: 18px 25px;
        }

        .summary-header h3 {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-header h3 i {
            color: #febd69;
        }

        .summary-body {
            padding: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 14px;
            color: #565959;
        }

        .summary-row.total {
            border-top: 2px solid #e8e8e8;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            color: #0f1111;
            font-weight: 700;
        }

        .summary-row.total .amount {
            color: #b12704;
            font-size: 22px;
        }

        .checkout-btn { 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #ff9900, #ffad33);
            border: none; 
            padding: 14px 20px; 
            border-radius: 10px; 
            width: 100%; 
            cursor: pointer; 
            font-size: 15px;
            font-weight: 600;
            color: #0f1111;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(255, 153, 0, 0.3);
            margin-top: 15px;
        }

        .checkout-btn:hover { 
            background: linear-gradient(135deg, #e88b00, #ff9900);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 153, 0, 0.4);
        }

        .secure-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            font-size: 12px;
            color: #007600;
        }

        .secure-note i {
            font-size: 14px;
        }

        .continue-shopping {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            padding: 12px;
            border: 1px solid #d5d9d9;
            border-radius: 10px;
            color: #0f1111;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .continue-shopping:hover {
            background: #f5f5f5;
            border-color: #c5c5c5;
        }

        /* Empty Cart */
        .empty-cart { 
            text-align: center; 
            padding: 60px 40px; 
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .empty-cart-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .empty-cart-icon i { 
            font-size: 50px; 
            color: #c5c5c5;
        }

        .empty-cart h3 {
            font-size: 22px;
            color: #0f1111;
            margin-bottom: 10px;
        }

        .empty-cart p {
            color: #565959;
            margin-bottom: 25px;
        }

        .shop-now-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 30px;
            background: linear-gradient(135deg, #ff9900, #ffad33);
            text-decoration: none;
            color: #0f1111;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(255, 153, 0, 0.3);
        }

        .shop-now-btn:hover {
            background: linear-gradient(135deg, #e88b00, #ff9900);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 153, 0, 0.4);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .cart-container {
                grid-template-columns: 1fr;
            }

            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 600px) {
            .cart-item {
                flex-direction: column;
                gap: 15px;
            }

            .item-image {
                width: 100%;
                height: 180px;
            }

            .item-subtotal {
                text-align: left;
            }

            .item-actions {
                flex-wrap: wrap;
            }

            .page-header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

    @include('shop.partials.navbar')

    <div class="container">
        <div class="page-header">
            <i class="fas fa-shopping-cart"></i>
            <h1>Shopping Cart</h1>
        </div>

        @php
            $cart = session()->get('cart', []);
            $total = 0;
            $itemCount = 0;
            foreach($cart as $item) {
                $itemCount += $item['quantity'];
            }
        @endphp

        @if(empty($cart))
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Your cart is empty</h3>
                <p>Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('shop.index') }}" class="shop-now-btn">
                    <i class="fas fa-store"></i> Start Shopping
                </a>
            </div>
        @else
            <div class="cart-container">
                <div class="cart-items">
                    <div class="cart-header">
                        <h2><i class="fas fa-box"></i> Cart Items</h2>
                        <span class="items-count">{{ $itemCount }} {{ $itemCount == 1 ? 'item' : 'items' }}</span>
                    </div>
                    
                    @foreach($cart as $id => $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <div class="cart-item" data-id="{{ $id }}">
                            <div class="item-image">
                                @php
                                    $cartImg = $item['image'] ?? null;
                                    if ($cartImg && preg_match('/^https?:\/\//', $cartImg)) {
                                        $cartImgUrl = $cartImg;
                                    } elseif ($cartImg) {
                                        $cartImgUrl = asset($cartImg);
                                    } else {
                                        $cartImgUrl = 'https://placehold.co/120?text=No+Image';
                                    }
                                @endphp
                                <img src="{{ $cartImgUrl }}" alt="{{ $item['name'] }}" onerror="this.onerror=null;this.src='https://placehold.co/120?text=No+Image';">
                            </div>
                            <div class="item-details">
                                <div class="item-title">{{ $item['name'] }}</div>
                                <div class="item-sku">SKU: {{ $item['sku'] }}</div>
                                <div class="item-stock"><i class="fas fa-circle"></i> In Stock</div>
                                <div class="item-price">₹{{ number_format($item['price'], 2) }}</div>
                                
                                <div class="item-actions">
                                    <div class="quantity-selector">
                                        <label>Qty:</label>
                                        <select class="quantity-select" data-id="{{ $id }}">
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $item['quantity'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="divider-line"></div>
                                    <a href="#" class="remove-btn" data-id="{{ $id }}">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </a>
                                </div>
                            </div>
                            <div class="item-subtotal">
                                <div class="subtotal-label">Subtotal</div>
                                <div class="subtotal-amount">₹{{ number_format($subtotal, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cart-summary">
                    <div class="summary-header">
                        <h3><i class="fas fa-receipt"></i> Order Summary</h3>
                    </div>
                    <div class="summary-body">
                        <div class="summary-row">
                            <span>Items ({{ $itemCount }})</span>
                            <span>₹{{ number_format($total, 2) }}</span>
                        </div>
                        
                        @if(isset($fees) && $fees['tax_amount'] > 0)
                        <div class="summary-row">
                            <span>{{ $fees['tax_label'] ?? 'GST' }} ({{ $fees['tax_rate'] }}%)</span>
                            <span>₹{{ number_format($fees['tax_amount'], 2) }}</span>
                        </div>
                        @endif
                        
                        @if(isset($fees) && $fees['show_platform_fee'] && $fees['platform_fee'] > 0)
                        <div class="summary-row">
                            <span>{{ $fees['platform_fee_label'] ?? 'Platform Fee' }}</span>
                            <span>₹{{ number_format($fees['platform_fee'], 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span style="color: #007600;">FREE</span>
                        </div>
                        <div class="summary-row total">
                            <span>Order Total</span>
                            <span class="amount">₹{{ number_format($fees['total'] ?? $total, 2) }}</span>
                        </div>
                        
                        <a href="{{ route('shop.checkout') }}" style="text-decoration: none;">
                            <button class="checkout-btn">
                                <i class="fas fa-lock"></i> Proceed to Checkout
                            </button>
                        </a>
                        
                        <div class="secure-note">
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure checkout powered by SSL</span>
                        </div>

                        <a href="{{ route('shop.index') }}" class="continue-shopping">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Update quantity
        document.querySelectorAll('.quantity-select').forEach(select => {
            select.addEventListener('change', function() {
                const id = this.dataset.id;
                const quantity = this.value;
                const selectEl = this;
                
                fetch('{{ route("shop.updateCart") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id: id, quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        // Show error message for stock limit
                        alert(data.message || 'Could not update quantity');
                        // Reset to max available quantity if provided
                        if (data.max_quantity) {
                            selectEl.value = data.max_quantity;
                        }
                    }
                });
            });
        });

        // Remove item
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Remove this item from cart?')) {
                    const id = this.dataset.id;
                    
                    fetch('{{ route("shop.removeFromCart") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>

    @include('shop.partials.footer')

</body>
</html>
