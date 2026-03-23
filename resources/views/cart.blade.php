<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Loading Spinner */
        #loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff; display: flex; justify-content: center; align-items: center; z-index: 9999; transition: opacity 0.5s ease-out; }
        #loader.hide { opacity: 0; pointer-events: none; }
        .spinner { width: 60px; height: 60px; border: 6px solid #f3f3f3; border-top: 6px solid #002e36; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        body { font-family: 'Poppins', sans-serif; background-color: #eaeded; margin: 0; padding: 0; }
        
        /* Navbar */
        .navbar { background-color: #131921; color: white; padding: 10px 20px; display: flex; align-items: center; gap: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: white; text-decoration: none; }
        .search-bar { flex: 1; display: flex; }
        .search-bar input { width: 100%; padding: 10px; border: none; border-radius: 4px 0 0 4px; }
        .search-bar button { background-color: #febd69; border: none; padding: 10px 20px; border-radius: 0 4px 4px 0; cursor: pointer; }
        .cart-icon { font-size: 24px; color: white; text-decoration: none; display: flex; align-items: center; gap: 5px; }

        /* Main Container */
        .cart-container { display: flex; gap: 20px; max-width: 1400px; margin: 20px auto; padding: 0 20px; align-items: flex-start; }
        
        /* Left Column: Cart Items */
        .cart-left { flex: 3; background: white; padding: 20px; border-radius: 4px; }
        .cart-header { font-size: 28px; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end;}
        .cart-header small { font-size: 14px; color: #565959; font-weight: normal; }
        
        /* Single Item Row */
        .item-row { display: flex; border-bottom: 1px solid #ddd; padding-bottom: 20px; margin-bottom: 20px; }
        .item-image { width: 180px; height: 180px; object-fit: contain; margin-right: 20px; background: #fff; padding: 10px; }
        
        .item-details { flex: 1; }
        .item-title { font-size: 18px; font-weight: bold; color: #007185; text-decoration: none; display: block; margin-bottom: 5px; }
        .item-price { font-size: 20px; font-weight: bold; float: right; }
        .stock-status { color: #007600; font-size: 12px; margin-bottom: 10px; }

        /* Quantity Controls */
        .qty-control { display: inline-flex; align-items: center; background: #F0F2F2; border: 1px solid #D5D9D9; border-radius: 8px; margin-top: 10px; box-shadow: 0 2px 5px rgba(15,17,17,.15); }
        .qty-btn { padding: 5px 12px; cursor: pointer; text-decoration: none; color: #111; font-weight: bold; background: transparent; border: none; }
        .qty-btn:hover { background-color: #e7e9ec; }
        .qty-display { padding: 5px 10px; border-left: 1px solid #D5D9D9; border-right: 1px solid #D5D9D9; background: white; min-width: 20px; text-align: center;}

        .delete-link { color: #007185; text-decoration: none; font-size: 12px; margin-left: 15px; border-left: 1px solid #ddd; padding-left: 15px; }
        .delete-link:hover { text-decoration: underline; color: #C7511F; }

        /* Right Column: Checkout Sidebar */
        .cart-right { flex: 1; background: white; padding: 20px; border-radius: 4px; position: sticky; top: 20px; }
        .subtotal { font-size: 18px; margin-bottom: 20px; }
        .checkout-btn { background-color: #ffd814; border: 1px solid #fcd200; width: 100%; padding: 10px; border-radius: 20px; cursor: pointer; box-shadow: 0 2px 5px rgba(213,217,217,.5); }
        .checkout-btn:hover { background-color: #f7ca00; }
        
        /* Back Button Style */
        .back-link { display: inline-block; margin-bottom: 15px; color: #007185; text-decoration: none; }
        .back-link:hover { text-decoration: underline; color: #C7511F; }

    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <nav class="navbar">
        <a href="/shop" class="logo">Front<span style="font-size: 14px; font-weight: normal;">Store</span></a>
        <div class="search-bar">
            <input type="text" placeholder="Search Front Store">
            <button><i class="fas fa-search"></i></button>
        </div>
        <a href="/cart" class="cart-icon">
            <i class="fas fa-shopping-cart"></i> Cart
            <span style="font-size: 14px; color: #f90; font-weight: bold;">{{ $cartItems->sum('quantity') }}</span>
        </a>
    </nav>

    <div class="cart-container">
        
        <div class="cart-left">
            <a href="/shop" class="back-link">‹ Back to Shopping</a>
            
            <div class="cart-header">
                Shopping Cart
                <small>Price</small>
            </div>

            @if($cartItems->count() > 0)
                @foreach($cartItems as $item)
                <div class="item-row">
                    @php
                        $cImg = $item->product->image ?? '';
                        $cImgUrl = (preg_match('/^https?:\/\//', $cImg)) ? $cImg : asset($cImg);
                    @endphp
                    <img src="{{ $cImgUrl }}" class="item-image" onerror="this.src='https://placehold.co/150'">

                    <div class="item-details">
                        <div class="item-price">₹{{ number_format($item->product->price, 2) }}</div>
                        
                        <a href="#" class="item-title">{{ $item->product->name }}</a>
                        <div class="stock-status">In Stock</div>
                        <div style="font-size: 12px; color: #565959;">Eligible for FREE Shipping</div>
                        
                        <div style="display: flex; align-items: center; margin-top: 10px;">
                            <div class="qty-control">
                                <a href="{{ route('cart.decrease', $item->id) }}" class="qty-btn">-</a>
                                <span class="qty-display">{{ $item->quantity }}</span>
                                <a href="{{ route('cart.increase', $item->id) }}" class="qty-btn">+</a>
                            </div>

                            <a href="{{ route('cart.destroy', $item->id) }}" class="delete-link">Delete</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align:center; padding: 40px;">
                    <h2>Your Amazon Cart is empty.</h2>
                    <a href="/shop" style="color: #007185;">Shop today's deals</a>
                </div>
            @endif
        </div>

        @if($cartItems->count() > 0)
        <div class="cart-right">
            <div class="subtotal">
                Subtotal ({{ $cartItems->sum('quantity') }} items):<br>
                <strong style="font-size: 20px;">₹{{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 2) }}</strong>
            </div>
            
            <form action="#" >
                @csrf
                <button type="submit" class="checkout-btn">Proceed to Buy</button>
            </form>
        </div>
        @endif
    </div>

    <script>
        // Hide loader after 1.5 seconds
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('loader');
                loader.classList.add('hide');
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500);
            }, 1500);
        });
    </script>

</body>
</html>