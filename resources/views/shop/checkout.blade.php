<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - Front Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #eaeded; margin: 0; }

        .container { max-width: 1000px; margin: 20px auto; padding: 0 20px; }
        
        .checkout-container { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        
        .checkout-form { background: white; padding: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #007185; box-shadow: 0 0 3px rgba(0, 113, 133, 0.5); }
        .required { color: red; }

        .order-summary { background: white; padding: 20px; height: fit-content; }
        .summary-title { font-size: 18px; font-weight: 700; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 10px; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .summary-total { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 2px solid #ddd; font-size: 18px; font-weight: 700; }

        .place-order-btn { background-color: #ffd814; border: 1px solid #fcd200; padding: 12px; border-radius: 8px; width: 100%; cursor: pointer; font-size: 16px; font-weight: 500; margin-top: 20px; }
        .place-order-btn:hover { background-color: #f7ca00; }

        .section-title { font-size: 20px; font-weight: 700; margin-bottom: 15px; }
        .note { font-size: 12px; color: #565959; margin-top: 5px; }
    </style>
</head>
<body>

    @include('shop.partials.navbar')

    <div class="container">
        <h2>Complete Your Order</h2>

        @if(session('error'))
            <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="checkout-container">
            <div class="checkout-form">
                <div class="section-title">1. Shipping Information</div>
                
                @if(isset($savedAddresses) && $savedAddresses->count() > 0)
                <!-- Saved Addresses Section -->
                <div style="margin-bottom: 25px; padding: 20px; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-radius: 12px; border: 1px solid #7dd3fc;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <i class="fas fa-bookmark" style="color: #0284c7;"></i>
                        <label style="font-weight: 600; color: #0369a1; margin: 0;">Select Saved Address</label>
                    </div>
                    <div style="display: grid; gap: 12px;" id="savedAddressesGrid">
                        @foreach($savedAddresses as $address)
                        <div class="saved-address-card" onclick="selectAddress(this, {{ json_encode($address) }})" 
                             style="background: white; padding: 15px; border-radius: 8px; border: 2px solid #e5e7eb; cursor: pointer; transition: all 0.2s; position: relative;">
                            @if($address->is_default)
                                <span style="position: absolute; top: -8px; right: 12px; background: #059669; color: white; padding: 2px 10px; border-radius: 10px; font-size: 10px; font-weight: 600;">DEFAULT</span>
                            @endif
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <div style="width: 20px; height: 20px; border: 2px solid #d1d5db; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;" class="radio-circle">
                                    <div style="width: 10px; height: 10px; background: #0284c7; border-radius: 50%; display: none;" class="radio-dot"></div>
                                </div>
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                                        <strong style="color: #0f172a;">{{ $address->full_name }}</strong>
                                        <span style="background: {{ $address->address_type == 'home' ? '#dbeafe' : ($address->address_type == 'work' ? '#fef3c7' : '#e0e7ff') }}; color: {{ $address->address_type == 'home' ? '#1e40af' : ($address->address_type == 'work' ? '#92400e' : '#3730a3') }}; padding: 2px 8px; border-radius: 10px; font-size: 11px; text-transform: uppercase;">
                                            {{ ucfirst($address->address_type) }}
                                        </span>
                                    </div>
                                    <div style="color: #64748b; font-size: 13px; line-height: 1.5;">
                                        {{ $address->address_line1 }}{{ $address->address_line2 ? ', ' . $address->address_line2 : '' }}<br>
                                        {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}<br>
                                        <span style="color: #0f172a;"><i class="fas fa-phone" style="font-size: 11px;"></i> {{ $address->phone }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="margin-top: 15px; display: flex; align-items: center; gap: 15px;">
                        <button type="button" onclick="clearAddressSelection()" style="background: none; border: none; color: #6b7280; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            Enter address manually
                        </button>
                        <a href="{{ route('profile.addresses') }}" style="color: #0284c7; font-size: 13px; text-decoration: none; display: flex; align-items: center; gap: 5px;">
                            <i class="fas fa-plus"></i> Add new address
                        </a>
                    </div>
                </div>
                <style>
                    .saved-address-card:hover { border-color: #0284c7 !important; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.15); }
                    .saved-address-card.selected { border-color: #0284c7 !important; background: #f0f9ff !important; }
                    .saved-address-card.selected .radio-circle { border-color: #0284c7 !important; }
                    .saved-address-card.selected .radio-dot { display: block !important; }
                </style>
                @elseif(session()->has('customer_id'))
                <!-- Logged in but no saved addresses -->
                <div style="margin-bottom: 20px; padding: 15px; background: #fef3c7; border-radius: 8px; border: 1px solid #fcd34d;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-lightbulb" style="color: #d97706;"></i>
                        <span style="color: #92400e; font-size: 14px;">
                            <strong>Tip:</strong> Save your addresses for faster checkout! 
                            <a href="{{ route('profile.addresses') }}" style="color: #0284c7; font-weight: 600;">Add address now</a>
                        </span>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('shop.placeOrder') }}" id="checkout_form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="customer_name">Full Name <span class="required">*</span></label>
                        <input type="text" id="customer_name" name="customer_name" required value="{{ old('customer_name') }}">
                        @error('customer_name')
                            <div class="note" style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="customer_email">Email Address <span class="required">*</span></label>
                        <input type="email" id="customer_email" name="customer_email" required value="{{ old('customer_email', $customer->email ?? '') }}">
                        <div class="note">We'll send order confirmation to this email</div>
                        @error('customer_email')
                            <div class="note" style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="customer_phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="customer_phone" name="customer_phone" required value="{{ old('customer_phone') }}" placeholder="+91-9876543210">
                        <div class="note">For delivery updates and support</div>
                        @error('customer_phone')
                            <div class="note" style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="shipping_address">Shipping Address <span class="required">*</span></label>
                        <textarea id="shipping_address" name="shipping_address" rows="4" required placeholder="House No., Street, Landmark, City, State, PIN Code">{{ old('shipping_address') }}</textarea>
                        <div class="note">Enter complete address with PIN code</div>
                        @error('shipping_address')
                            <div class="note" style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-title" style="margin-top: 30px;">2. Payment Information</div>

                    <div class="form-group">
                        <label for="payment_method">Payment Method <span class="required">*</span></label>
                        <select id="payment_method" name="payment_method" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">-- Select Payment Method --</option>
                            <option value="Cash on Delivery" {{ old('payment_method') == 'Cash on Delivery' ? 'selected' : '' }}>💵 Cash on Delivery (COD)</option>
                            <option value="razorpay" {{ old('payment_method') == 'razorpay' ? 'selected' : '' }}>💳 Pay Online (Card/UPI/Wallet) - Razorpay</option>
                        </select>
                        @error('payment_method')
                            <div class="note" style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="razorpay_note" style="display: none; background-color: #e7f4f5; padding: 15px; border-radius: 4px; margin: 15px 0; border-left: 4px solid #007185;">
                        <strong><i class="fas fa-info-circle"></i> Online Payment</strong><br>
                        <small>You will be redirected to Razorpay's secure payment gateway. We accept all major cards, UPI, wallets, and net banking.</small>
                    </div>

                    <div id="cod_note" style="display: none; background-color: #fff3cd; padding: 15px; border-radius: 4px; margin: 15px 0; border-left: 4px solid #856404;">
                        <strong><i class="fas fa-exclamation-triangle"></i> Cash on Delivery</strong><br>
                        <small>Please keep exact cash ready. Extra charges may apply for COD orders.</small>
                    </div>

                    <div style="background-color: #f7f7f7; padding: 15px; border-radius: 4px; margin: 20px 0;">
                        <strong>Note:</strong> All orders will be processed within 1-2 business days. You will receive tracking information via email once shipped.
                    </div>

                    <div style="font-size:12px;color:#565959;line-height:1.7;margin:10px 0 16px;">
                        By placing this order, you agree to FrontStore's
                        <a href="{{ route('page.return-policy') }}" style="color:#007185;font-weight:600;text-decoration:none;">Return Policy</a>,
                        <a href="{{ route('page.refund-policy') }}" style="color:#007185;font-weight:600;text-decoration:none;">Refund Policy</a>,
                        <a href="{{ route('page.privacy-policy') }}" style="color:#007185;font-weight:600;text-decoration:none;">Privacy Policy</a>, and
                        <a href="{{ route('page.disclaimer') }}" style="color:#007185;font-weight:600;text-decoration:none;">Disclaimer</a>.
                    </div>

                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                    <button type="submit" class="place-order-btn" id="place_order_btn">
                        <i class="fas fa-lock"></i> <span id="btn_text">Place Order</span>
                    </button>
                </form>
<script>
    // ========== ADDRESS SELECTION FUNCTIONS ==========
    function selectAddress(card, address) {
        // Remove selection from all cards
        document.querySelectorAll('.saved-address-card').forEach(c => c.classList.remove('selected'));
        
        // Add selection to clicked card
        card.classList.add('selected');
        
        // Fill in the form fields
        document.getElementById('customer_name').value = address.full_name;
        document.getElementById('customer_phone').value = address.phone;
        
        // Build full address string
        let fullAddress = address.address_line1;
        if (address.address_line2) {
            fullAddress += ', ' + address.address_line2;
        }
        fullAddress += ', ' + address.city + ', ' + address.state + ' - ' + address.pincode;
        if (address.country && address.country !== 'India') {
            fullAddress += ', ' + address.country;
        }
        
        document.getElementById('shipping_address').value = fullAddress;
        
        // Scroll to payment section smoothly
        setTimeout(() => {
            document.getElementById('payment_method').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }
    
    function clearAddressSelection() {
        // Remove selection from all cards
        document.querySelectorAll('.saved-address-card').forEach(c => c.classList.remove('selected'));
        
        // Clear form fields
        document.getElementById('customer_name').value = '';
        document.getElementById('customer_phone').value = '';
        document.getElementById('shipping_address').value = '';
        
        // Focus on name field
        document.getElementById('customer_name').focus();
    }
    
    // Auto-select default address on page load
    document.addEventListener('DOMContentLoaded', function() {
        const defaultCard = document.querySelector('.saved-address-card span[style*="DEFAULT"]');
        if (defaultCard) {
            const card = defaultCard.closest('.saved-address-card');
            if (card) {
                card.click();
            }
        }
    });
</script>

<script>
    console.log('Checkout script loaded');
    console.log('Razorpay available:', typeof Razorpay !== 'undefined');
    
    // 1. Handle Payment Method Toggle
    document.getElementById('payment_method').addEventListener('change', function() {
        const razorpayNote = document.getElementById('razorpay_note');
        const codNote = document.getElementById('cod_note');
        const btnText = document.getElementById('btn_text');
        
        razorpayNote.style.display = 'none';
        codNote.style.display = 'none';
        
        if (this.value === 'razorpay') {
            razorpayNote.style.display = 'block';
            btnText.textContent = 'Proceed to Payment';
        } else if (this.value === 'Cash on Delivery') {
            codNote.style.display = 'block';
            btnText.textContent = 'Place Order';
        } else {
            btnText.textContent = 'Place Order';
        }
    });

    // 2. Handle Form Submission
    document.getElementById('checkout_form').addEventListener('submit', function(e) {
        const paymentMethod = document.getElementById('payment_method').value;
        console.log('Form submit intercepted, payment method:', paymentMethod);
        
        if (paymentMethod === 'razorpay') {
            e.preventDefault(); // STOP the form from submitting normally
            e.stopPropagation(); // Prevent any other handlers
            console.log('Razorpay selected, calling initiateRazorpayPayment()');
            initiateRazorpayPayment(); // Start the secure flow
            return false;
        }
        // If COD, do nothing and let the form submit
    });

    function initiateRazorpayPayment() {
        console.log('initiateRazorpayPayment() called');
        
        // Check if Razorpay is loaded
        if (typeof Razorpay === 'undefined') {
            alert('Payment gateway not loaded. Please refresh the page and try again.');
            console.error('Razorpay script not loaded');
            return;
        }
        
        const form = document.getElementById('checkout_form');
        const formData = new FormData(form);
        const btn = document.getElementById('place_order_btn');
        
        // Show loading state
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        console.log('Sending request to:', '{{ route("payment.create") }}');
        
        // A. Call Server to Create Secure Order ID
        fetch('{{ route("payment.create") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                customer_name: formData.get('customer_name'),
                customer_email: formData.get('customer_email'),
                customer_phone: formData.get('customer_phone'),
                shipping_address: formData.get('shipping_address'),
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Server response:', data);
            if (data.success) {
                // B. SUCCESS: Open Popup with REAL Server Data
                console.log("Server Response:", data); // Check console to see the Order ID
                openRazorpayCheckout(data.order, formData);
            } else {
                alert('Error creating order: ' + data.message);
                resetButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong. Please check console.');
            resetButton();
        });
    }

    function openRazorpayCheckout(order, formData) {
        console.log('Razorpay public key:', "{{ config('services.razorpay.key') }}");
        console.log('Order object received:', order);
        console.log('Order keys:', Object.keys(order || {}));
        
        // Robust order ID extraction with fallbacks
        let orderId = null;
        if (order) {
            orderId = order.id || order.order_id || order['id'] || null;
        }
        console.log('Extracted orderId:', orderId);
        
        // Prefill hidden order id so backend can fallback even if handler misses it
        if (orderId) {
            document.getElementById('razorpay_order_id').value = orderId;
        }

        // Configure Razorpay to rely on server-created order only
        const options = {
            "key": "{{ config('services.razorpay.key') }}",
            "name": "FrontStore",
            "description": "Order Payment",
            "order_id": orderId,
            "handler": function (response) {
                console.log('Razorpay handler response:', response);
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                if (response.razorpay_order_id) {
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                }
                if (response.razorpay_signature) {
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                }
                document.getElementById('checkout_form').submit();
            },
            "prefill": {
                "name": formData.get('customer_name'),
                "email": formData.get('customer_email'),
                "contact": formData.get('customer_phone')
            },
            "theme": { "color": "#131921" },
            "modal": {
                "ondismiss": function() { resetButton(); }
            }
        };

        console.log('Opening Razorpay with options:', options);
        const rzp = new Razorpay(options);
        rzp.open();
    }

    function resetButton() {
        const btn = document.getElementById('place_order_btn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock"></i> <span id="btn_text">Proceed to Payment</span>';
    }

    // Initialize state on page load
    if (document.getElementById('payment_method').value) {
        document.getElementById('payment_method').dispatchEvent(new Event('change'));
    }
</script>
            </div>

            <div class="order-summary">
                <div class="summary-title">Order Summary</div>
                
                @foreach($cart as $item)
                    <div class="summary-item">
                        <div>
                            <div style="font-weight: 500;">{{ $item['name'] }}</div>
                            <div style="font-size: 12px; color: #565959;">Qty: {{ $item['quantity'] }} × ₹{{ number_format($item['price'], 2) }}</div>
                        </div>
                        <div style="font-weight: 700;">
                            ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </div>
                    </div>
                @endforeach

                <!-- Fee Breakdown -->
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f0;">
                    @foreach($breakdown as $line)
                        @if($line['show'] && !isset($line['isTotal']))
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                <span style="color: #565959;">{{ $line['label'] }}</span>
                                <span>₹{{ number_format($line['amount'], 2) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="summary-total">
                    <span>Order Total:</span>
                    <span style="color: #b12704;">₹{{ number_format($fees['total'], 2) }}</span>
                </div>

                <!-- Fee Info Tooltip -->
                <div style="margin-top: 10px; padding: 10px; background: #f7f7f7; border-radius: 4px; font-size: 11px; color: #666;">
                    <i class="fas fa-info-circle"></i> 
                    Price includes {{ $fees['tax_label'] ?? 'GST' }} ({{ $fees['tax_rate'] }}%) 
                    @if($fees['platform_fee'] > 0)
                        and platform fee
                    @endif
                </div>

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="fas fa-truck" style="color: #007600;"></i>
                        <span style="font-size: 12px; color: #007600;">Free Delivery</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-shield-alt" style="color: #007185;"></i>
                        <span style="font-size: 12px; color: #565959;">Secure Payment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="order_amount" value="{{ $fees['total'] }}">

    @include('shop.partials.footer')

</body>
</html>
