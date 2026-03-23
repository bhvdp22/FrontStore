<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background-color: #ffffff; border-radius: 8px; max-width: 600px; margin: 0 auto; overflow: hidden; }

        /* Header - matches navbar gradient */
        .email-header {
            background: linear-gradient(90deg, #232f3e 0%, #37475a 100%);
            padding: 25px 30px;
            text-align: center;
        }
        .email-header .logo-text { font-size: 28px; font-weight: 700; color: #fff; letter-spacing: 1px; }
        .email-header .logo-accent { font-size: 16px; font-weight: 500; color: #febd69; }
        .email-header .tagline { font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 4px; }

        /* Success Banner */
        .success-banner {
            background: linear-gradient(135deg, #28a745, #218838);
            padding: 20px 30px;
            text-align: center;
            color: #ffffff;
        }
        .success-banner h2 { margin: 0; font-size: 22px; }
        .success-banner p { margin: 5px 0 0 0; font-size: 14px; opacity: 0.9; }

        /* Body */
        .email-body { padding: 25px 30px; }
        .email-body p { color: #333333; line-height: 1.6; margin-bottom: 12px; font-size: 14px; }

        /* Order Info Box */
        .order-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 18px;
            margin: 18px 0;
        }
        .order-info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
            color: #555;
        }
        .order-info-row strong { color: #232f3e; }

        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin: 18px 0; }
        .items-table th {
            background: #232f3e;
            color: #febd69;
            padding: 10px 12px;
            font-size: 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table td {
            padding: 10px 12px;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #eee;
        }
        .items-table tr:last-child td { border-bottom: none; }
        .items-table .item-img { width: 45px; height: 45px; border-radius: 6px; object-fit: cover; }
        .items-table .text-right { text-align: right; }

        /* Totals */
        .totals-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px 18px;
            margin: 12px 0;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
            color: #555;
        }
        .totals-row.grand-total {
            border-top: 2px solid #232f3e;
            margin-top: 8px;
            padding-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #232f3e;
        }

        /* Shipping Address Box */
        .address-box {
            background-color: #fff8e1;
            border-left: 4px solid #febd69;
            padding: 14px 18px;
            margin: 18px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
            line-height: 1.6;
        }
        .address-box strong { color: #232f3e; }

        /* Payment Badge */
        .payment-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-completed { background-color: #d4edda; color: #155724; }
        .badge-pending { background-color: #fff3cd; color: #856404; }

        /* CTA Button */
        .btn {
            display: block;
            width: 220px;
            margin: 25px auto;
            padding: 14px;
            background: linear-gradient(135deg, #febd69, #f3a847);
            color: #232f3e;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 15px;
        }

        /* Invoice Attached Note */
        .invoice-note {
            background-color: #e3f2fd;
            border: 1px dashed #1565c0;
            border-radius: 8px;
            padding: 14px;
            text-align: center;
            margin: 20px 0;
            font-size: 13px;
            color: #1565c0;
        }
        .invoice-note strong { font-size: 14px; }

        /* Footer - matches navbar */
        .email-footer {
            background: #232f3e;
            padding: 20px 30px;
            text-align: center;
            font-size: 11px;
            color: rgba(255,255,255,0.6);
            line-height: 1.7;
        }
        .email-footer a { color: #febd69; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">

        {{-- ─── Header ─── --}}
        <div class="email-header">
            <div>
                <span class="logo-text">Front</span><span class="logo-accent">Store</span>
            </div>
            <div class="tagline">Your Trusted Online Marketplace</div>
        </div>

        {{-- ─── Success Banner ─── --}}
        <div class="success-banner">
            <h2>✅ Order Confirmed!</h2>
            <p>Thank you for shopping with FrontStore</p>
        </div>

        {{-- ─── Body ─── --}}
        <div class="email-body">

            <p>Hi <strong>{{ $orderData['customer_name'] }}</strong>,</p>
            <p>Great news! Your order has been placed successfully. Here are your order details:</p>

            {{-- Order Info --}}
            <div class="order-info">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding:6px 0;font-size:13px;color:#555;">Order ID</td>
                        <td style="padding:6px 0;font-size:13px;color:#232f3e;font-weight:bold;text-align:right;">{{ $orderData['order_id'] }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;font-size:13px;color:#555;">Date</td>
                        <td style="padding:6px 0;font-size:13px;color:#232f3e;font-weight:bold;text-align:right;">{{ $orderData['order_date'] }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;font-size:13px;color:#555;">Payment</td>
                        <td style="padding:6px 0;text-align:right;">
                            <span class="payment-badge {{ $orderData['payment_status'] === 'Completed' ? 'badge-completed' : 'badge-pending' }}">
                                {{ $orderData['payment_method'] }} — {{ $orderData['payment_status'] }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Items Table --}}
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="border-radius:6px 0 0 0;">Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th style="text-align:right;border-radius:0 6px 0 0;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderData['items'] as $item)
                    <tr>
                        <td>
                            <strong>{{ $item['name'] }}</strong><br>
                            <span style="font-size:11px;color:#888;">SKU: {{ $item['sku'] }}</span>
                        </td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>₹{{ number_format($item['price'], 2) }}</td>
                        <td class="text-right">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="totals-box">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding:5px 0;font-size:13px;color:#555;">Subtotal</td>
                        <td style="padding:5px 0;font-size:13px;color:#555;text-align:right;">₹{{ number_format($orderData['fees']['subtotal'], 2) }}</td>
                    </tr>
                    @if(($orderData['fees']['tax_amount'] ?? 0) > 0)
                    <tr>
                        <td style="padding:5px 0;font-size:13px;color:#555;">{{ $orderData['fees']['tax_label'] ?? 'GST' }} ({{ $orderData['fees']['tax_rate'] }}%)</td>
                        <td style="padding:5px 0;font-size:13px;color:#555;text-align:right;">₹{{ number_format($orderData['fees']['tax_amount'], 2) }}</td>
                    </tr>
                    @endif
                    @if(($orderData['fees']['platform_fee'] ?? 0) > 0)
                    <tr>
                        <td style="padding:5px 0;font-size:13px;color:#555;">{{ $orderData['fees']['platform_fee_label'] ?? 'Platform Fee' }}</td>
                        <td style="padding:5px 0;font-size:13px;color:#555;text-align:right;">₹{{ number_format($orderData['fees']['platform_fee'], 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="border-top:2px solid #232f3e;margin-top:8px;padding-top:10px;font-size:16px;font-weight:bold;color:#232f3e;padding:10px 0 5px;">Grand Total</td>
                        <td style="border-top:2px solid #232f3e;margin-top:8px;padding-top:10px;font-size:16px;font-weight:bold;color:#232f3e;text-align:right;padding:10px 0 5px;">₹{{ number_format($orderData['fees']['total'], 2) }}</td>
                    </tr>
                </table>
            </div>

            {{-- Shipping Address --}}
            <div class="address-box">
                <strong>📦 Shipping To:</strong><br>
                {{ $orderData['customer_name'] }}<br>
                {{ $orderData['shipping_address'] }}<br>
                📞 {{ $orderData['customer_phone'] }}
            </div>

            {{-- Invoice PDF Note --}}
            <div class="invoice-note">
                <strong>📎 Invoice Attached</strong><br>
                Your tax invoice PDF is attached to this email for your records.
            </div>

            {{-- CTA Button --}}
            <a href="{{ url('/profile/orders') }}" class="btn">Track Your Order</a>

            <p style="font-size:13px;color:#777;">If you have any questions about your order, simply reply to this email or visit our <a href="{{ url('/shop/help') }}" style="color:#1565c0;">Help Center</a>.</p>
        </div>

        {{-- ─── Footer ─── --}}
        <div class="email-footer">
            &copy; {{ date('Y') }} FrontStore Marketplace. All rights reserved.<br>
            This email was sent to {{ $orderData['customer_email'] }} for order #{{ $orderData['order_id'] }}.<br>
            <a href="{{ url('/shop') }}">Continue Shopping</a> &nbsp;|&nbsp; <a href="{{ url('/profile/orders') }}">My Orders</a> &nbsp;|&nbsp; <a href="{{ url('/shop/help') }}">Help</a>
        </div>
    </div>
</body>
</html>
