<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order_id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            background: #fff;
            line-height: 1.4;
        }

        .invoice-container { max-width: 800px; margin: 0 auto; padding: 20px; }

        /* Header */
        .header {
            background: linear-gradient(90deg, #232f3e, #37475a);
            padding: 20px;
            color: #fff;
            border-radius: 6px 6px 0 0;
        }
        .header-table { width: 100%; }
        .header .logo-text { font-size: 24px; font-weight: bold; color: #fff; }
        .header .logo-accent { font-size: 14px; color: #febd69; }
        .header .invoice-title { font-size: 22px; font-weight: bold; color: #febd69; text-align: right; }
        .header .invoice-number { font-size: 11px; color: rgba(255,255,255,0.7); text-align: right; }

        /* Invoice Meta */
        .meta-section {
            background: #f8f9fa;
            padding: 15px 20px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }
        .meta-table { width: 100%; }
        .meta-table td { padding: 3px 0; font-size: 11px; vertical-align: top; }
        .meta-label { color: #888; width: 120px; }
        .meta-value { color: #232f3e; font-weight: bold; }

        /* Bill To / Ship To */
        .addresses {
            padding: 15px 20px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }
        .addr-table { width: 100%; }
        .addr-table td { vertical-align: top; padding: 5px 10px; }
        .addr-title {
            font-weight: bold;
            font-size: 11px;
            color: #232f3e;
            border-bottom: 2px solid #febd69;
            padding-bottom: 4px;
            margin-bottom: 6px;
            display: inline-block;
        }
        .addr-content { font-size: 10px; line-height: 1.6; color: #555; margin-top: 6px; }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .items-table th {
            background: #232f3e;
            color: #febd69;
            padding: 8px 10px;
            font-size: 10px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table th:last-child { text-align: right; }
        .items-table td {
            padding: 8px 10px;
            font-size: 10px;
            color: #333;
            border-bottom: 1px solid #eee;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }
        .items-table td:last-child { text-align: right; }
        .items-table tr:nth-child(even) td { background: #fafafa; }

        /* Totals */
        .totals-section {
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 0;
        }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 6px 10px; font-size: 10px; }
        .totals-table .label { text-align: right; width: 80%; color: #555; }
        .totals-table .value { text-align: right; width: 20%; color: #333; font-weight: bold; }
        .totals-table .grand-total td {
            border-top: 2px solid #232f3e;
            font-size: 13px;
            font-weight: bold;
            color: #232f3e;
            padding: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding: 15px 20px;
            background: #f8f9fa;
            border-radius: 0 0 6px 6px;
            border: 1px solid #ddd;
        }
        .footer-note { font-size: 9px; color: #888; text-align: center; line-height: 1.5; }
        .footer-brand { font-size: 10px; color: #232f3e; font-weight: bold; text-align: center; margin-top: 8px; }

        /* Payment Badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-paid { background: #d4edda; color: #155724; }
        .badge-pending { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="invoice-container">

        {{-- Header --}}
        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="vertical-align:middle;">
                        <span class="logo-text">Front</span><span class="logo-accent">Store</span>
                    </td>
                    <td>
                        <div class="invoice-title">TAX INVOICE</div>
                        <div class="invoice-number">#{{ $invoice_number }}</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Invoice Meta --}}
        <div class="meta-section">
            <table class="meta-table">
                <tr>
                    <td class="meta-label">Invoice Number:</td>
                    <td class="meta-value">{{ $invoice_number }}</td>
                    <td class="meta-label" style="text-align:right;">Invoice Date:</td>
                    <td class="meta-value" style="text-align:right;">{{ $order_date }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Order ID:</td>
                    <td class="meta-value">{{ $order_id }}</td>
                    <td class="meta-label" style="text-align:right;">Payment:</td>
                    <td style="text-align:right;">
                        <span class="badge {{ $payment_status === 'Completed' ? 'badge-paid' : 'badge-pending' }}">
                            {{ $payment_status === 'Completed' ? 'PAID' : 'COD' }}
                        </span>
                        {{ $payment_method }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Addresses --}}
        <div class="addresses">
            <table class="addr-table">
                <tr>
                    <td style="width:50%;">
                        <div class="addr-title">BILLED TO</div>
                        <div class="addr-content">
                            <strong>{{ $customer_name }}</strong><br>
                            {{ $customer_email }}<br>
                            {{ $customer_phone }}
                        </div>
                    </td>
                    <td style="width:50%;">
                        <div class="addr-title">SHIPPED TO</div>
                        <div class="addr-content">
                            <strong>{{ $customer_name }}</strong><br>
                            {{ $shipping_address }}<br>
                            📞 {{ $customer_phone }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Items --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:45%;">Product</th>
                    <th style="width:10%;">Qty</th>
                    <th style="width:20%;">Unit Price</th>
                    <th style="width:20%;text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item['name'] }}</strong><br>
                        <span style="color:#888;font-size:9px;">SKU: {{ $item['sku'] }}</span>
                    </td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>₹{{ number_format($item['price'], 2) }}</td>
                    <td style="text-align:right;">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="value">₹{{ number_format($fees['subtotal'], 2) }}</td>
                </tr>
                @if(($fees['tax_amount'] ?? 0) > 0)
                <tr>
                    <td class="label">{{ $fees['tax_label'] ?? 'GST' }} ({{ $fees['tax_rate'] }}%):</td>
                    <td class="value">₹{{ number_format($fees['tax_amount'], 2) }}</td>
                </tr>
                @endif
                @if(($fees['platform_fee'] ?? 0) > 0)
                <tr>
                    <td class="label">{{ $fees['platform_fee_label'] ?? 'Platform Fee' }}:</td>
                    <td class="value">₹{{ number_format($fees['platform_fee'], 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="label">Grand Total:</td>
                    <td class="value">₹{{ number_format($fees['total'], 2) }}</td>
                </tr>
            </table>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="footer-note">
                This is a computer-generated invoice and does not require a physical signature.<br>
                For queries regarding this invoice, contact us at support@frontstore.com
            </div>
            <div class="footer-brand">
                FrontStore Marketplace &mdash; Thank you for your purchase!
            </div>
        </div>

    </div>
</body>
</html>
