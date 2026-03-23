<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoices - {{ $orderId }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            background-color: #fff;
            line-height: 1.4;
        }

        /* Watermark Background */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.03);
            white-space: nowrap;
            z-index: -1;
            letter-spacing: 10px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Page break between seller invoices */
        .page-break {
            page-break-after: always;
        }

        /* Seller indicator banner */
        .seller-indicator {
            background: #f0f4ff;
            border: 1px solid #c5d3f5;
            border-radius: 4px;
            padding: 6px 12px;
            margin-bottom: 12px;
            font-size: 10px;
            color: #4a5e8a;
            text-align: center;
        }

        .seller-indicator strong {
            color: #2d3e6d;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header .subtitle {
            font-size: 12px;
            color: #555;
        }

        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .col-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }

        .col-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 15px;
        }

        /* Section Titles */
        .section-title {
            font-weight: bold;
            font-size: 11px;
            color: #000;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }

        .section-content {
            font-size: 10px;
            line-height: 1.5;
            color: #333;
        }

        .section-content p {
            margin-bottom: 2px;
        }

        .section-content .label {
            font-weight: bold;
            color: #000;
        }

        /* Sold By Box */
        .sold-by-box {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .sold-by-box .business-name {
            font-weight: bold;
            font-size: 12px;
            color: #000;
            margin-bottom: 5px;
        }

        /* Order Info Box */
        .order-info-box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .order-info-box table {
            width: 100%;
        }

        .order-info-box td {
            padding: 3px 0;
            font-size: 10px;
        }

        .order-info-box td.label {
            font-weight: bold;
            width: 45%;
        }

        /* Address Box */
        .address-box {
            margin-bottom: 10px;
        }

        /* Products Table */
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .products-table th {
            background: #333;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        .products-table th:nth-child(3),
        .products-table th:nth-child(4),
        .products-table th:nth-child(5) {
            text-align: right;
        }

        .products-table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10px;
        }

        .products-table td:nth-child(3),
        .products-table td:nth-child(4),
        .products-table td:nth-child(5) {
            text-align: right;
        }

        .products-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Totals */
        .totals-section {
            display: table;
            width: 100%;
            margin-top: 15px;
        }

        .totals-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .totals-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }

        .totals-table {
            width: 100%;
            border: 1px solid #ddd;
        }

        .totals-table td {
            padding: 6px 10px;
            font-size: 10px;
            border-bottom: 1px solid #eee;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .totals-table .grand-total {
            background: #333;
            color: #fff;
            font-size: 12px;
        }

        .totals-table .grand-total td {
            padding: 10px;
            border: none;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: bottom;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            margin-top: 30px;
        }

        .signature-line {
            width: 150px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        .signature-text {
            font-size: 10px;
            font-weight: bold;
        }

        .company-stamp {
            font-size: 9px;
            color: #666;
        }

        /* Legal Footer */
        .legal-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .legal-footer .company-name-footer {
            font-weight: bold;
            font-size: 10px;
            color: #333;
            margin-bottom: 3px;
        }

        /* Terms Box */
        .terms-box {
            background: #f5f5f5;
            border-left: 3px solid #333;
            padding: 10px;
            font-size: 9px;
            margin-top: 15px;
        }

        .terms-box h4 {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .terms-box ul {
            padding-left: 15px;
            margin: 0;
        }

        .terms-box li {
            margin-bottom: 3px;
        }

        /* Original Badge */
        .original-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #333;
            color: #fff;
            padding: 5px 15px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">FrontStore</div>

    @foreach($invoicePages as $index => $page)
    <div class="invoice-container {{ !$loop->last ? 'page-break' : '' }}">
        <!-- Original Badge -->
        <div class="original-badge">TAX INVOICE</div>

        <!-- Header -->
        <div class="header">
            <h1>FrontStore</h1>
            <div class="subtitle">Your Trusted Online Marketplace</div>
        </div>

        <!-- Seller Indicator -->
        <div class="seller-indicator">
            Invoice {{ $index + 1 }} of {{ count($invoicePages) }} — Seller: <strong>{{ $page['company']['name'] }}</strong>
        </div>

        <!-- Seller & Customer Info -->
        <div class="two-column">
            <!-- Left Column: Sold By -->
            <div class="col-left">
                <div class="sold-by-box">
                    <div class="section-title">Sold By:</div>
                    <div class="section-content">
                        <div class="business-name">{{ $page['company']['name'] }}</div>
                        <p>{{ $page['company']['address'] }}</p>
                        @if($page['company']['city'] || $page['company']['state'])
                            <p>{{ $page['company']['city'] }}{{ $page['company']['city'] && $page['company']['state'] ? ', ' : '' }}{{ $page['company']['state'] }} {{ $page['company']['pincode'] }}</p>
                        @endif
                        <p>{{ $page['company']['country'] }}</p>
                    </div>
                </div>

                @if($page['company']['pan'] || $page['company']['gstin'])
                <div class="address-box">
                    @if($page['company']['pan'])
                        <p><span class="label">PAN No:</span> {{ $page['company']['pan'] }}</p>
                    @endif
                    @if($page['company']['gstin'])
                        <p><span class="label">GST Registration No:</span> {{ $page['company']['gstin'] }}</p>
                    @endif
                    @if($page['company']['cin'])
                        <p><span class="label">CIN:</span> {{ $page['company']['cin'] }}</p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Right Column: Billing & Shipping Address -->
            <div class="col-right">
                <div class="address-box">
                    <div class="section-title">Billing Address:</div>
                    <div class="section-content">
                        <p><strong>{{ $page['order']->customer_name }}</strong></p>
                        @if($page['order']->shipping_address)
                            <p>{{ $page['order']->shipping_address }}</p>
                        @endif
                        @if($page['order']->customer_phone)
                            <p>Phone: {{ $page['order']->customer_phone }}</p>
                        @endif
                        @if($page['order']->customer_email)
                            <p>Email: {{ $page['order']->customer_email }}</p>
                        @endif
                    </div>
                </div>

                <div class="address-box">
                    <div class="section-title">Shipping Address:</div>
                    <div class="section-content">
                        <p><strong>{{ $page['order']->customer_name }}</strong></p>
                        @if($page['order']->shipping_address)
                            <p>{{ $page['order']->shipping_address }}</p>
                        @endif
                        @if($page['order']->customer_phone)
                            <p>Phone: {{ $page['order']->customer_phone }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order & Invoice Details -->
        <div class="two-column">
            <div class="col-left">
                <div class="order-info-box">
                    <table>
                        <tr>
                            <td class="label">Order Number:</td>
                            <td>{{ $orderId }}</td>
                        </tr>
                        <tr>
                            <td class="label">Order Date:</td>
                            <td>{{ $page['order']->created_at->format('d.m.Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-right">
                <div class="order-info-box">
                    <table>
                        <tr>
                            <td class="label">Invoice Number:</td>
                            <td>{{ $page['invoiceNumber'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Invoice Date:</td>
                            <td>{{ $page['invoiceDate'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">Product Description</th>
                    <th style="width: 15%;">Qty</th>
                    <th style="width: 15%;">Unit Price</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach($page['orderItems'] as $itemIndex => $item)
                    @php 
                        $itemTotal = $item->price * $item->quantity;
                        $subtotal += $itemTotal;
                    @endphp
                    <tr>
                        <td>{{ $itemIndex + 1 }}</td>
                        <td><strong>{{ $item->product_name }}</strong></td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->price, 2) }}</td>
                        <td><strong>₹{{ number_format($itemTotal, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <div class="totals-left">
                <div class="terms-box">
                    <h4>Terms & Conditions:</h4>
                    <ul>
                        <li>All disputes are subject to {{ $page['company']['city'] ?: 'local' }} jurisdiction.</li>
                        <li>Goods once sold will not be taken back.</li>
                        <li>This is a computer-generated invoice and does not require a physical signature.</li>
                    </ul>
                </div>
            </div>
            <div class="totals-right">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal:</td>
                        <td>₹{{ number_format($page['order']->subtotal ?: $subtotal, 2) }}</td>
                    </tr>
                    @if(isset($page['settings']) && $page['settings']->show_tax_on_invoice && $page['order']->tax_amount > 0)
                    <tr>
                        <td>{{ $page['settings']->tax_label ?? 'GST' }} ({{ $page['order']->tax_rate ?? $page['settings']->gst_percentage }}%):</td>
                        <td>₹{{ number_format($page['order']->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Shipping:</td>
                        <td>FREE</td>
                    </tr>
                    <tr class="grand-total">
                        <td>GRAND TOTAL:</td>
                        <td>₹{{ number_format($page['order']->total_price, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-left">
                    <p style="font-size: 11px; font-weight: bold; margin-bottom: 5px;">Thank you for your business!</p>
                    <p style="font-size: 9px; color: #666;">
                        For any queries regarding this invoice, please contact:<br>
                        @if($page['company']['email'])
                            Email: {{ $page['company']['email'] }}<br>
                        @endif
                        @if($page['company']['phone'])
                            Phone: {{ $page['company']['phone'] }}
                        @endif
                    </p>
                </div>
                <div class="footer-right">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-text">Authorized Signatory</div>
                        <div class="company-stamp">For {{ $page['company']['name'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seller Store Info -->
        <div style="text-align: center; margin-top: 15px; padding: 8px 0; border-top: 1px solid #eee;">
            <p style="font-size: 9px; color: #888; margin: 0;">Sold & Fulfilled by: <strong style="color: #555;">{{ $page['company']['name'] }}</strong></p>
        </div>

        <!-- Legal Footer -->
        <div class="legal-footer">
            <div class="company-name-footer">FrontStore Marketplace</div>
            <p>
                Seller: {{ $page['company']['name'] }} | {{ $page['company']['address'] }}{{ $page['company']['city'] ? ', ' . $page['company']['city'] : '' }}{{ $page['company']['state'] ? ', ' . $page['company']['state'] : '' }} {{ $page['company']['pincode'] }}, {{ $page['company']['country'] }}
            </p>
            <p style="margin-top: 8px; font-style: italic;">
                This is an electronically generated document and is valid without signature and stamp.
            </p>
        </div>
    </div>
    @endforeach
</body>
</html>
