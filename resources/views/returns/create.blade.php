<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Return - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        
        .page-header {
            margin-bottom: 24px;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #007185;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .back-link:hover { text-decoration: underline; }
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #232f3e;
        }
        
        .return-form-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .product-summary {
            display: flex;
            gap: 20px;
            padding: 24px;
            background: #f8f9fa;
            border-bottom: 1px solid #e5e7eb;
            align-items: center;
        }
        .product-summary img {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }
        .product-summary h3 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }
        .product-summary p {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .product-summary .price {
            font-size: 18px;
            font-weight: 700;
            color: #059669;
        }
        
        .form-section {
            padding: 24px;
        }
        .form-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-section h4 i { color: #febd69; }
        
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e4e8ec;
            border-radius: 10px;
            font-size: 15px;
            transition: border-color 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #febd69;
            outline: none;
        }
        .form-group textarea { resize: vertical; min-height: 100px; }
        
        .reason-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }
        .reason-option {
            position: relative;
        }
        .reason-option input {
            position: absolute;
            opacity: 0;
        }
        .reason-option label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .reason-option input:checked + label {
            border-color: #febd69;
            background: #fffbeb;
        }
        .reason-option label i {
            color: #6b7280;
        }
        .reason-option input:checked + label i {
            color: #f59e0b;
        }
        
        .image-upload {
            border: 2px dashed #e5e7eb;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .image-upload:hover { border-color: #febd69; }
        .image-upload i { font-size: 32px; color: #9ca3af; margin-bottom: 12px; }
        .image-upload p { color: #6b7280; font-size: 14px; }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .btn-primary {
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            color: #232f3e;
        }
        .btn-primary:hover {
            box-shadow: 0 4px 16px rgba(247,202,0,0.4);
        }
        
        .error { color: #dc2626; font-size: 13px; margin-top: 6px; }
        
        @media (max-width: 600px) {
            .product-summary { flex-direction: column; text-align: center; }
            .reason-options { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('shop.partials.navbar')
    
    <div class="container">
        <div class="page-header">
            <a href="{{ route('returns.eligible') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Eligible Items
            </a>
            <h1 class="page-title">Request Return</h1>
        </div>
        
        @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:14px 20px;border-radius:8px;margin-bottom:20px;">
                <ul style="margin:0;padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="return-form-card">
            <div class="product-summary">
                <img src="{{ $order->img_path ? asset($order->img_path) : ($product && $product->image ? asset($product->image) : asset('placeholder.png')) }}" alt="{{ $order->product_name }}">
                <div>
                    <h3>{{ $order->product_name }}</h3>
                    <p>Order #{{ $order->order_id }} • Purchased: {{ $order->created_at->format('M d, Y') }}</p>
                    <p>Quantity ordered: {{ $order->quantity }}</p>
                    <div class="price">₹{{ number_format($order->total_price, 0) }}</div>
                </div>
            </div>
            
            <form action="{{ route('returns.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_item_id" value="{{ $order->id }}">
                
                <div class="form-section">
                    <h4><i class="fas fa-question-circle"></i> Why are you returning this item?</h4>
                    <div class="reason-options">
                        @foreach($returnReasons as $value => $label)
                            <div class="reason-option">
                                <input type="radio" name="return_reason" id="reason_{{ $value }}" value="{{ $value }}" {{ old('return_reason') == $value ? 'checked' : '' }} required>
                                <label for="reason_{{ $value }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $label }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('return_reason')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-section" style="padding-top:0;">
                    <h4><i class="fas fa-edit"></i> Additional Details</h4>
                    <div class="form-group">
                        <label for="reason_details">Please describe the issue (optional)</label>
                        <textarea name="reason_details" id="reason_details" placeholder="Provide more details about why you're returning this item...">{{ old('reason_details') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity to Return</label>
                        <select name="quantity" id="quantity">
                            @for($i = 1; $i <= $order->quantity; $i++)
                                <option value="{{ $i }}" {{ old('quantity', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="form-section" style="padding-top:0;">
                    <h4><i class="fas fa-camera"></i> Upload Photos (Optional)</h4>
                    <p style="color:#6b7280;font-size:14px;margin-bottom:16px;">
                        Adding photos helps us process your return faster. You can upload up to 5 images.
                    </p>
                    <div class="form-group">
                        <label class="image-upload" for="images">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload images (Max 5MB each)</p>
                        </label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*" style="display:none;">
                    </div>
                </div>
                
                <div class="form-section" style="padding-top:0;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Return Request
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @include('shop.partials.footer')
    
    <script>
        // Show selected files
        document.getElementById('images').addEventListener('change', function(e) {
            const label = document.querySelector('.image-upload');
            const files = e.target.files;
            if (files.length > 0) {
                label.innerHTML = '<i class="fas fa-check-circle" style="color:#10b981;"></i><p>' + files.length + ' file(s) selected</p>';
            }
        });
    </script>
</body>
</html>
