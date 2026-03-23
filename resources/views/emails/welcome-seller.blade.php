<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; color: #333333; }
        .info-box { background-color: #fff3e0; border-left: 4px solid #ff9800; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .steps { margin: 20px 0; }
        .steps li { margin-bottom: 12px; line-height: 1.6; }
        .highlight { color: #1565c0; font-weight: bold; }
        .btn { display: block; width: 220px; margin: 25px auto; padding: 14px; background-color: #1565c0; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; font-size: 12px; color: #777777; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="header">Welcome to FrontStore, {{ $user->business_name ?? $user->name }}! 🚀</h2>

        <p>Hi {{ $user->name }},</p>
        <p>Congratulations on registering as a seller on <strong>FrontStore Marketplace</strong>! We're excited to have you on board.</p>

        <div class="info-box">
            <strong>📋 Your Account Status:</strong> Under Review<br>
            Our admin team will verify your details and approve your account shortly. You'll receive a notification once you're approved to start selling.
        </div>

        <p><strong>Here's how to get started once approved:</strong></p>
        <ol class="steps">
            <li>📦 <strong>Add Your Products</strong> — Upload photos, set prices, and manage your inventory.</li>
            <li>🏪 <strong>Set Up Your Storefront</strong> — Customize your seller profile with your brand story and logo.</li>
            <li>📊 <strong>Track Orders & Payments</strong> — Manage orders, process returns, and request payouts from your dashboard.</li>
            <li>⭐ <strong>Build Your Reputation</strong> — Deliver quality products on time to earn great reviews and a high seller score.</li>
        </ol>

        <p>Your registered business details:</p>
        <ul>
            <li><strong>Business Name:</strong> {{ $user->business_name }}</li>
            <li><strong>Location:</strong> {{ $user->city }}, {{ $user->state }}</li>
            @if($user->gstin)<li><strong>GSTIN:</strong> {{ $user->gstin }}</li>@endif
        </ul>

        <a href="{{ url('/login') }}" class="btn">Go to Seller Dashboard</a>

        <p>If you have any questions about selling on FrontStore, just reply to this email. We're here to help you succeed!</p>

        <div class="footer">
            &copy; {{ date('Y') }} FrontStore Marketplace. All rights reserved.<br>
            This email was sent to {{ $user->email }} because you registered as a seller on FrontStore.
        </div>
    </div>
</body>
</html>
