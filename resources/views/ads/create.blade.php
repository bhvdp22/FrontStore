@extends('welcome')

@section('content')
<div class="main-content" style="display: flex; justify-content: center;">
    
    <div style="background: white; border: 1px solid #d5d9d9; border-radius: 8px; padding: 30px; width: 600px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        
        <h2 style="margin-top: 0; color: #111;">Launch New Campaign</h2>
        <p style="color: #555; font-size: 13px; margin-bottom: 20px;">Set a budget and duration to reach more customers.</p>

        {{-- Balance display --}}
        <div style="background: {{ $balance > 0 ? '#f0faf0' : '#fff4f4' }}; border: 1px solid {{ $balance > 0 ? '#067d62' : '#c40000' }}; border-radius: 8px; padding: 16px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 12px; color: #565959; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Available Balance</div>
                <div style="font-size: 24px; font-weight: 700; color: {{ $balance > 0 ? '#067d62' : '#c40000' }}; margin-top: 2px;">₹{{ number_format($balance, 2) }}</div>
            </div>
            <div style="font-size: 12px; color: #888; text-align: right;">
                From delivered<br>order earnings
            </div>
        </div>

        @if($balance <= 0)
        {{-- Zero balance — block campaign creation --}}
        <div style="background: #fff4f4; border: 1px solid #c40000; border-radius: 8px; padding: 30px; text-align: center;">
            <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #c40000; margin-bottom: 15px;"></i>
            <h3 style="color: #c40000; margin: 0 0 10px;">Add Funds to Continue</h3>
            <p style="color: #555; font-size: 14px; margin: 0 0 20px;">
                Your available balance is ₹0.00. You need delivered order earnings to fund ad campaigns.<br>
                <strong>Get more orders delivered to increase your balance.</strong>
            </p>
            <a href="{{ route('ads.index') }}" style="display: inline-block; background: #f0f2f2; color: #0f1111; text-decoration: none; padding: 10px 25px; border-radius: 4px; font-weight: 600; border: 1px solid #d5d9d9;">
                ← Back to Campaigns
            </a>
        </div>
        @else

        @if ($errors->any())
            <div style="background-color: #fff4f4; border: 1px solid #c40000; color: #c40000; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fff4f4; border: 1px solid #c40000; color: #c40000; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('ads.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #333;">Campaign Name</label>
                <input type="text" name="campaign_name" placeholder="e.g. Summer Clearance Sale" required value="{{ old('campaign_name') }}"
                       style="width: 100%; padding: 10px; border: 1px solid #888; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #333;">Select Product to Advertise</label>
                <select name="sku" id="productSelect" required style="width: 100%; padding: 10px; border: 1px solid #888; border-radius: 4px; box-sizing: border-box; background: #fff;">
                    <option value="">-- Choose a Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->sku }}" data-product='{{ json_encode($product) }}' {{ old('sku') == $product->sku ? 'selected' : '' }}>
                            {{ $product->name }} (SKU: {{ $product->sku }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Product Details Display Section -->
            <div id="productDetails" style="display: none; margin-bottom: 20px; padding: 0; border: 1px solid #d5d9d9; border-radius: 4px; background-color: #fff; overflow: hidden;">
                <div style="display: flex; gap: 20px; padding: 15px;">
                    <div style="flex: 0 0 160px; position: relative;">
                        <img id="productImage" src="" alt="Product Image" style="width: 160px; height: auto; object-fit: cover; border: 1px solid #d5d9d9; border-radius: 4px; display: block;">
                        <div style="position: absolute; bottom: 10px; right: 10px; background-color: rgba(255, 255, 255, 0.95); border: 1px solid #999; border-radius: 3px; padding: 4px 8px; font-size: 11px; color: #666; font-weight: 600; text-align: center;">
                            Sponsored
                        </div>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                        <p id="productName" style="margin: 0 0 12px; color: #007185; font-size: 14px; font-weight: 700;"></p>
                        <div style="margin-bottom: 12px;">
                            <p id="productPrice" style="margin: 0; color: #b12704; font-size: 24px; font-weight: bold;">₹</p>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <span style="color: #565959; font-size: 12px;"><strong id="productQuantity"></strong> units available</span>
                        </div>
                        <div style="display: flex; gap: 15px;">
                            <div><span style="color: #565959; font-size: 11px; font-weight: bold;">SKU:</span><p id="productSKU" style="margin: 2px 0; color: #111; font-size: 12px;"></p></div>
                            <div><span style="color: #565959; font-size: 11px; font-weight: bold;">ASIN:</span><p id="productASIN" style="margin: 2px 0; color: #111; font-size: 12px;"></p></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #333;">Daily Budget (₹)</label>
                <input type="number" name="daily_budget" id="dailyBudgetInput" placeholder="Min: 50" min="50" max="{{ floor($balance) }}" required value="{{ old('daily_budget') }}"
                       style="width: 100%; padding: 10px; border: 1px solid #888; border-radius: 4px; box-sizing: border-box;">
                <p style="margin: 6px 0 0; font-size: 12px; color: #888;">
                    Max budget per day: <strong style="color: #067d62;">₹{{ number_format(floor($balance), 0) }}</strong> (your available balance)
                </p>
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div style="flex: 1;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #333;">Start Date</label>
                    <input type="date" name="start_date" required min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}"
                           style="width: 100%; padding: 10px; border: 1px solid #888; border-radius: 4px; box-sizing: border-box;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #333;">End Date</label>
                    <input type="date" name="end_date" required min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}"
                           style="width: 100%; padding: 10px; border: 1px solid #888; border-radius: 4px; box-sizing: border-box;">
                </div>
            </div>

            {{-- Estimated cost preview --}}
            <div id="costEstimate" style="display:none; background: #f7f9fa; border: 1px solid #e3e6e6; border-radius: 6px; padding: 14px; margin-bottom: 20px; font-size: 13px; color: #555;">
                <strong>Estimated Total Cost:</strong> <span id="estimatedCost" style="color: #c7511f; font-weight: 700;"></span>
                <span id="costWarning" style="color: #c40000; font-size: 12px; display: none; margin-left: 8px;"></span>
            </div>

            <div style="display: flex; align-items: center;">
                <button type="submit" style="background-color: #f0c14b; border: 1px solid #a88734; padding: 10px 20px; cursor: pointer; border-radius: 3px; font-weight: bold; color: #111;">
                    Launch Campaign
                </button>
                <a href="{{ route('ads.index') }}" style="margin-left: 15px; text-decoration: none; color: #007185; font-size: 14px;">Cancel</a>
            </div>

        </form>
        @endif
    </div>
</div>

<script>
    const balance = {{ $balance }};

    // Product preview
    const productSelect = document.getElementById('productSelect');
    const productDetails = document.getElementById('productDetails');

    if (productSelect) {
        productSelect.addEventListener('change', function() {
            if (this.value === '') {
                productDetails.style.display = 'none';
            } else {
                const productData = JSON.parse(this.options[this.selectedIndex].dataset.product);
                const price = parseFloat(productData.price).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('productName').textContent = productData.name;
                document.getElementById('productPrice').textContent = '₹' + price;
                document.getElementById('productQuantity').textContent = productData.quantity;
                document.getElementById('productSKU').textContent = productData.sku;
                document.getElementById('productASIN').textContent = productData.asin;
                document.getElementById('productImage').src = productData.img_path || 'https://placehold.co/160?text=No+Image';
                document.getElementById('productImage').onerror = function() { this.src = 'https://placehold.co/160?text=No+Image'; };
                productDetails.style.display = 'block';
            }
        });
    }

    // Cost estimator
    const budgetInput = document.getElementById('dailyBudgetInput');
    const startInput = document.querySelector('input[name="start_date"]');
    const endInput = document.querySelector('input[name="end_date"]');

    function updateEstimate() {
        const budget = parseFloat(budgetInput?.value || 0);
        const start = startInput?.value ? new Date(startInput.value) : null;
        const end = endInput?.value ? new Date(endInput.value) : null;
        const box = document.getElementById('costEstimate');
        
        if (budget > 0 && start && end && end > start) {
            const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            const total = budget * days;
            document.getElementById('estimatedCost').textContent = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2}) + ' (' + days + ' days)';
            
            const warn = document.getElementById('costWarning');
            if (total > balance) {
                warn.textContent = '⚠ Exceeds balance — campaign will auto-pause when balance runs out';
                warn.style.display = 'inline';
            } else {
                warn.style.display = 'none';
            }
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }

    if (budgetInput) budgetInput.addEventListener('input', updateEstimate);
    if (startInput) startInput.addEventListener('change', updateEstimate);
    if (endInput) endInput.addEventListener('change', updateEstimate);
</script>
@endsection