<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; color: #333333; }
        .coupon-box { background-color: #e3f2fd; border: 2px dashed #1565c0; padding: 15px; text-align: center; margin: 20px 0; font-size: 20px; font-weight: bold; color: #1565c0; border-radius: 8px; }
        .btn { display: block; width: 220px; margin: 25px auto; padding: 14px; background-color: #28a745; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; }
        .perks { margin: 20px 0; }
        .perks li { margin-bottom: 10px; line-height: 1.6; }
        .footer { text-align: center; font-size: 12px; color: #777777; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="header">Welcome to FrontStore, {{ $customer->name }}! 🛍️</h2>
        
        <p>Hi {{ $customer->name }},</p>
        <p>Thank you for joining FrontStore! We connect you directly with top-quality sellers, ensuring you get the best products at the best prices.</p>
        
        <p>As a special thank you for signing up, here's a <strong>10% discount</strong> on your first purchase:</p>

        <div class="coupon-box">
            🎉 WELCOME10
        </div>

        <p>This code is valid for the next <strong>7 days</strong>, so don't miss out!</p>

        <p><strong>What you can do on FrontStore:</strong></p>
        <ul class="perks">
            <li>🛒 Browse thousands of products from verified sellers</li>
            <li>⭐ Read & write honest product reviews</li>
            <li>📦 Track your orders in real time</li>
            <li>🔄 Easy returns & refunds if something's not right</li>
            <li>📍 Save multiple delivery addresses</li>
        </ul>

        <a href="{{ url('/shop') }}" class="btn">Start Shopping Now</a>

        <p>If you have any questions, just reply to this email. We are here to help!</p>

        <div class="footer">
            &copy; {{ date('Y') }} FrontStore Marketplace. All rights reserved.<br>
            This email was sent to {{ $customer->email }} because you registered on FrontStore.
        </div>
    </div>
</body>
</html>