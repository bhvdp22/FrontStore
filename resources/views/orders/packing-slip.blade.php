<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - {{ $order->order_id }}</title>
    <style>
        @page {
            size: 4in 6in;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            background: #f5f5f5;
        }
        
        .label-container {
            width: 4in;
            height: 6in;
            background: white;
            margin: 10px auto;
            border: 2px solid #000;
            padding: 8px;
            display: flex;
            flex-direction: column;
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 6px;
            border-bottom: 2px solid #000;
            margin-bottom: 8px;
        }
        
        .awb-barcode {
            font-family: 'Courier New', monospace;
            font-size: 12pt;
            font-weight: bold;
        }
        
        .weight-box {
            text-align: right;
            font-size: 9pt;
        }
        
        .weight-value {
            font-size: 14pt;
            font-weight: bold;
        }
        
        .ship-to-section {
            border: 2px solid #000;
            padding: 8px;
            margin-bottom: 8px;
            min-height: 90px;
        }
        
        .section-title {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #333;
        }
        
        .customer-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .address-text {
            font-size: 10pt;
            line-height: 1.4;
        }
        
        .middle-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 8px;
        }
        
        .info-box {
            border: 1px solid #000;
            padding: 6px;
        }
        
        .info-box .label {
            font-size: 8pt;
            color: #555;
            text-transform: uppercase;
        }
        
        .info-box .value {
            font-size: 10pt;
            font-weight: bold;
        }
        
        .codes-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 8px;
            flex-grow: 1;
        }
        
        .qr-box {
            border: 2px solid #000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px;
            text-align: center;
        }
        
        .qr-placeholder {
            width: 80px;
            height: 80px;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #666;
            margin-bottom: 4px;
            background: 
                repeating-linear-gradient(0deg, #000 0px, #000 3px, #fff 3px, #fff 6px),
                repeating-linear-gradient(90deg, #000 0px, #000 3px, #fff 3px, #fff 6px);
            background-size: 6px 100%, 100% 6px;
        }
        
        .order-id-box {
            border: 2px solid #000;
            padding: 8px;
            text-align: center;
        }
        
        .order-id-large {
            font-family: 'Courier New', monospace;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 1px;
            word-break: break-all;
        }
        
        .barcode-visual {
            height: 40px;
            margin: 6px 0;
            background: repeating-linear-gradient(
                90deg,
                #000 0px, #000 2px,
                #fff 2px, #fff 4px,
                #000 4px, #000 5px,
                #fff 5px, #fff 8px,
                #000 8px, #000 10px,
                #fff 10px, #fff 11px,
                #000 11px, #000 14px,
                #fff 14px, #fff 16px
            );
        }
        
        .product-section {
            border: 1px solid #000;
            padding: 6px;
            margin-bottom: 8px;
            font-size: 9pt;
        }
        
        .product-row {
            display: flex;
            justify-content: space-between;
        }
        
        .sku-box {
            background: #000;
            color: #fff;
            padding: 2px 6px;
            font-weight: bold;
            font-size: 9pt;
        }
        
        .ship-from-section {
            border-top: 1px dashed #000;
            padding-top: 6px;
            font-size: 8pt;
            color: #333;
        }
        
        .ship-from-title {
            font-weight: bold;
            font-size: 8pt;
        }
        
        .footer-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 6px;
            border-top: 2px solid #000;
            font-size: 8pt;
        }
        
        .prepaid-badge {
            background: #000;
            color: #fff;
            padding: 4px 12px;
            font-weight: bold;
            font-size: 10pt;
        }

        .print-controls {
            text-align: center;
            padding: 15px;
            background: #fff3cd;
            margin: 10px auto;
            width: 4in;
            border: 1px solid #ffc107;
        }
        
        .print-btn {
            padding: 10px 25px;
            background: #232f3e;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            margin: 0 5px;
        }
        
        .print-btn:hover {
            background: #37475a;
        }
        
        .print-btn.secondary {
            background: #666;
        }

        @media print {
            body {
                background: white;
            }
            
            .print-controls {
                display: none;
            }
            
            .label-container {
                margin: 0;
                border: none;
                width: 100%;
                height: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <p style="margin-bottom: 10px; font-size: 13px;"><strong>Shipping Label</strong> - Ready to print</p>
        <button onclick="window.print()" class="print-btn">🖨️ Print Label</button>
        <button onclick="window.close()" class="print-btn secondary">✕ Close</button>
    </div>

    <div class="label-container">
        <!-- Top Bar with AWB and Weight -->
        <div class="top-bar">
            <div class="awb-barcode">SCS {{ $order->order_id }}</div>
            <div class="weight-box">
                <div class="weight-value">0.5 kgs</div>
            </div>
        </div>

        <!-- Ship To Section -->
        <div class="ship-to-section">
            <div class="section-title">Ship To:</div>
            <div class="customer-name">{{ $order->customer_name }}</div>
            <div class="address-text">
                {{ $order->shipping_address ?? 'Address not provided' }}
                @if($order->customer_phone)
                <br>Phone: {{ $order->customer_phone }}
                @endif
            </div>
        </div>

        <!-- Middle Info Section -->
        <div class="middle-section">
            <div class="info-box">
                <div class="label">Order Date</div>
                <div class="value">{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-box">
                <div class="label">Ship By</div>
                <div class="value">{{ $order->created_at ? $order->created_at->addDays(2)->format('d/m/Y') : 'N/A' }}</div>
            </div>
        </div>

        <!-- QR Code and Order ID Section -->
        <div class="codes-section">
            <div class="qr-box">
                <div class="qr-placeholder"></div>
                <div style="font-size: 8pt; font-weight: bold;">SCAN TO TRACK</div>
            </div>
            <div class="order-id-box">
                <div class="section-title">Order ID</div>
                <div class="barcode-visual"></div>
                <div class="order-id-large">{{ $order->order_id }}</div>
            </div>
        </div>

        <!-- Product Section -->
        <div class="product-section">
            <div class="product-row">
                <div>
                    <strong>{{ Str::limit($order->product_name, 35) }}</strong>
                </div>
                <div>
                    <span class="sku-box">{{ $order->sku }}</span>
                </div>
            </div>
            <div class="product-row" style="margin-top: 4px;">
                <div>Qty: <strong>{{ $order->quantity }}</strong></div>
                <div>Condition: <strong>New</strong></div>
            </div>
        </div>

        <!-- Ship From Section -->
        <div class="ship-from-section">
            <div class="ship-from-title">Ship From: {{ $seller->business_name ?? $seller->name ?? 'SELLER CENTRAL' }}</div>
            <div>
                @if($seller)
                    {{ $seller->business_address ?? '' }}
                    @if($seller->city), {{ $seller->city }}@endif
                    @if($seller->state), {{ $seller->state }}@endif
                    @if($seller->pincode) - {{ $seller->pincode }}@endif
                    @if($seller->country), {{ $seller->country }}@endif
                @else
                    Business Address, City, State - 123456
                @endif
            </div>
            @if($seller && $seller->email)
            <div>Email: {{ $seller->email }}</div>
            @endif
            @if($seller && $seller->phone)
            <div>Phone: {{ $seller->phone }}</div>
            @endif
        </div>

        <!-- Footer Bar -->
        <div class="footer-bar">
            <div class="prepaid-badge">PREPAID</div>
            <div style="text-align: right;">
                <div style="font-weight: bold;">Standard Shipping</div>
                <div>{{ now()->format('d M Y') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
