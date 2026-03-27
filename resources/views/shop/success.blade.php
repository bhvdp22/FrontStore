<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #eaeded; margin: 0; }
        
        .navbar { background-color: #131921; color: white; padding: 10px 20px; }
        .logo { font-size: 24px; font-weight: bold; }

        .container { max-width: 600px; margin: 50px auto; padding: 0 20px; }
        
        .success-card { background: white; padding: 50px; text-align: center; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .success-icon { font-size: 80px; color: #007600; margin-bottom: 20px; }
        .success-title { font-size: 28px; font-weight: 700; color: #0F1111; margin-bottom: 15px; }
        .success-message { font-size: 16px; color: #565959; margin-bottom: 30px; line-height: 1.5; }
        
        .info-box { background-color: #f7f7f7; padding: 20px; border-radius: 4px; margin: 20px 0; text-align: left; }
        .info-box h4 { margin-top: 0; color: #0F1111; }
        .info-box p { margin: 10px 0; color: #565959; }
        
        .btn { display: inline-block; padding: 12px 30px; margin: 10px; text-decoration: none; border-radius: 8px; font-weight: 500; }
        .btn-primary { background-color: #ffd814; color: #0F1111; border: 1px solid #fcd200; }
        .btn-primary:hover { background-color: #f7ca00; }
        .btn-secondary { background-color: #fff; color: #0F1111; border: 1px solid #ddd; }
        .btn-secondary:hover { background-color: #f0f0f0; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">Front<span style="font-size: 14px; font-weight: normal;">Store</span></div>
    </nav>

    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1 class="success-title">Order Placed Successfully!</h1>
            
            <p class="success-message">
                Thank you for your order. We've received your order and will begin processing it shortly.
            </p>

            @if(session('order_id'))
                <div style="background-color: #e7f4f5; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #007185;">
                    <p style="margin: 0; color: #0F1111;"><strong>Order ID:</strong> <span style="font-size: 18px; font-weight: bold; color: #007185;">{{ session('order_id') }}</span></p>
                    @if(session('payment_status'))
                        <p style="margin: 10px 0 0 0; color: #0F1111;">
                            <strong>Payment Status:</strong> 
                            <span style="padding: 4px 10px; border-radius: 3px; font-weight: bold; {{ session('payment_status') == 'Completed' ? 'background-color: #d4edda; color: #155724;' : 'background-color: #fff3cd; color: #856404;' }}">
                                {{ session('payment_status') == 'Completed' ? '✓ Paid' : '⚠ Cash on Delivery' }}
                            </span>
                        </p>
                    @endif
                </div>
            @endif

            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> What's Next?</h4>
                <p><i class="fas fa-envelope"></i> You'll receive an order confirmation email shortly</p>
                <p><i class="fas fa-box"></i> We'll start processing your order within 1-2 business days</p>
                <p><i class="fas fa-truck"></i> You'll get shipping updates via email and SMS</p>
            </div>

            @if(session('success'))
                <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 20px 0;">
                    <i class="fas fa-check"></i> {{ session('success') }}
                </div>
            @endif

            <div style="margin-top: 30px;">
                @if(session('order_id'))
                    <a href="{{ route('profile.invoice', session('order_id')) }}" class="btn btn-primary" style="background-color: #232f3e; color: white; border-color: #232f3e;">
                        <i class="fas fa-file-pdf"></i> Download Invoice
                    </a>
                @endif
                <a href="{{ route('profile.orders') }}" class="btn btn-primary">
                    <i class="fas fa-box"></i> View My Orders
                </a>
                <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
                <!-- <a href="/" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Seller Dashboard
                </a> -->
            </div>

            @if(session('product_id'))
                <div style="margin-top: 20px;">
                    <a href="{{ route('review.form', ['product' => session('product_id'), 'order' => session('order_id')]) }}" class="btn btn-secondary" style="background-color: #ff9900; color: white; border-color: #ff9900;">
                        <i class="fas fa-star"></i> Write a Product Review
                    </a>
                </div>
            @endif

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #565959;">
                <p>Need help? Contact our support team</p>
                <p><i class="fas fa-phone"></i> +91-1800-123-4567 | <i class="fas fa-envelope"></i> frontstore.team@outlook.com</p>
            </div>
        </div>
    </div>

    @include('shop.partials.footer')

</body>
</html>
