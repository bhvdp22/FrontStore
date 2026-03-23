@extends('layouts.seller')

@section('title', 'Manage All Inventory - Seller Central')

@section('extra_styles')
<style>
        /* Loading Spinner */
        #loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff; display: flex; justify-content: center; align-items: center; z-index: 9999; transition: opacity 0.5s ease-out; }
        #loader.hide { opacity: 0; pointer-events: none; }
        .spinner { width: 60px; height: 60px; border: 6px solid #f3f3f3; border-top: 6px solid #002e36; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* --- 1. GLOBAL & RESET --- */
        body { margin: 0; padding: 0; font-family: 'Poppins', sans-serif; background-color: #fff; color: #0f1111; font-size: 13px; overflow-x: hidden; }
        * { box-sizing: border-box; }
        a { text-decoration: none; color: #007185; }
        a:hover { color: #c7511f; text-decoration: underline; }

        /* --- PAGE HEADER & BUTTONS --- */
        .page-header { padding: 15px 20px 0 20px; border-bottom: 1px solid #ddd; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .page-title { font-size: 24px; font-weight: 700; color: #0f1111; }
        .links-row { font-size: 13px; color: #555; margin-left: 10px; }
        
        .btn-dark {
            background: #232f3e; color: #fff; border: 1px solid #232f3e; padding: 6px 15px; 
            border-radius: 3px; font-size: 13px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-dark:hover { background: #485769; text-decoration: none; color: #fff; }

        .badge { background: #e7e9ec; color: #0f1111; padding: 2px 6px; border-radius: 10px; font-size: 11px; margin-left: 4px; }

        .pill-btn {
            border: 1px solid #888C8C; border-radius: 16px; padding: 4px 12px; cursor: pointer; background: #fff;
        }
        .pill-btn.active { background: #007185; color: white; border-color: #007185; }

        .search-row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .main-search-box {
            display: flex; height: 32px; border: 1px solid #888C8C; border-radius: 4px; overflow: hidden; width: 350px; 
            box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;
        }
        .dropdown-select {
            background: #f0f2f2; border: none; border-right: 1px solid #888C8C; padding: 0 8px; font-size: 12px; color: #0f1111; cursor: pointer;
        }
        .search-input-field {
            border: none; padding: 5px 10px; flex-grow: 1; outline: none; font-size: 13px;
        }
        .search-icon-btn {
            background: #fff; border: none; border-left: 1px solid #888C8C; padding: 0 10px; cursor: pointer;
        }
        
        .filter-dropdown {
            height: 32px; padding: 0 10px; border: 1px solid #888C8C; border-radius: 4px; 
            background: #f0f2f2; box-shadow: 0 1px 0 rgba(255,255,255,.6) inset; cursor: pointer;
            display: flex; align-items: center; justify-content: space-between; min-width: 140px; font-size: 13px;
        }
        .filter-dropdown:hover { background: #e3e6e6; }

        /* --- 5. DATA TABLE --- */
        .table-wrapper { padding: 0 20px 20px 20px; }
        .inventory-table { width: 100%; border-collapse: collapse; margin-top: 10px; border: 1px solid #eaeded; }
        
        /* Table Header */
        .inventory-table thead { background: #fafafa; border-bottom: 1px solid #eaeded; }
        .inventory-table th { 
            text-align: left; padding: 8px 10px; color: #555; font-size: 11px; font-weight: 700; 
            text-transform: uppercase; border-right: 1px solid #eaeded; vertical-align: top; height: 40px;
        }

        /* Table Body */
        .inventory-table tr { border-bottom: 1px solid #eaeded; background: #fff; }
        .inventory-table td { padding: 12px 10px; vertical-align: top; color: #0f1111; font-size: 13px; }
        
        /* Specific Columns */
        .col-status { width: 120px; }
        .status-text { font-weight: 700; margin-bottom: 2px; }
        .status-date { color: #565959; font-size: 11px; }

        .col-image { width: 80px; text-align: center; }
        .product-img { width: 60px; height: 60px; object-fit: contain; }

        .col-details { max-width: 300px; }
        .product-title { color: #007185; font-weight: 700; font-size: 14px; line-height: 1.3; display: block; margin-bottom: 4px; }
        .meta-info { font-size: 12px; color: #565959; line-height: 1.5; }
        .meta-label { color: #565959; }
        .meta-val { color: #111; }

        .col-inv { width: 140px; }
        .inv-input-group { display: flex; align-items: center; gap: 5px; margin-top: 5px; }
        .table-input { 
            width: 70px; padding: 4px 8px; border: 1px solid #888C8C; border-radius: 3px; 
            font-size: 13px; text-align: right; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;
        }
        .table-input:focus { border-color: #007185; box-shadow: 0 0 0 3px #c8f3fa; outline: none; }

        .col-price { width: 180px; }
        .currency-box { 
            display: flex; border: 1px solid #888C8C; border-radius: 3px; overflow: hidden; 
            box-shadow: 0 1px 2px rgba(15,17,17,.15) inset; width: 120px;
        }
        .currency-label { 
            background: #f0f2f2; color: #555; padding: 5px 8px; font-size: 11px; border-right: 1px solid #888C8C; display: flex; align-items: center;
        }
        .price-input { border: none; padding: 5px; width: 100%; text-align: right; font-size: 13px; outline: none; }
        .ship-cost { font-size: 11px; color: #565959; margin-top: 4px; text-align: right; width: 120px; }

        .col-fee { width: 120px; font-size: 12px; }
        .fee-link { color: #007185; font-size: 11px; display: block; margin-top: 2px; }

        .col-actions { width: 80px; text-align: center; position: relative; }
        .kebab-btn { 
            background: #fff; border: 1px solid #d5d9d9; border-radius: 3px; padding: 4px 8px; 
            cursor: pointer; box-shadow: 0 2px 5px rgba(213,217,217,.5); 
        }
        .row-actions {
            position: absolute; right: 10px; top: 36px; background: #fff; border: 1px solid #d5d9d9; border-radius: 4px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12); display: none; min-width: 160px; z-index: 10;
        }
        .row-actions a, .row-actions button {
            display: block; width: 100%; text-align: left; padding: 8px 12px; background: #fff; border: none; cursor: pointer; font-size: 13px;
        }
        .row-actions a:hover, .row-actions button:hover { background: #f7fafa; }

        /* Status Colors */
        .status-green { color: #007600; }
        .status-red { color: #c40000; }

        /* Quick Edit Buttons */
        .quick-edit-btn, .quick-save-btn {
            background: #232f3e;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 11px;
            transition: all 0.2s;
        }
        .quick-edit-btn:hover {
            background: #37475a;
        }
        .quick-save-btn {
            background: #067d62;
        }
        .quick-save-btn:hover {
            background: #055d4a;
        }
        
        .success-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #d1fae5;
            color: #065f46;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="top-row">
            <div style="display:flex; align-items:baseline; gap:10px;">
                <div class="page-title">Manage All Inventory</div>
                <div class="links-row">
                    <a href="https://sell.amazon.com/learn/inventory-management">Learn more</a>
                </div>
            </div>
            <div style="display:flex; gap:10px;">
                @if(Auth::check() && Auth::user()->status == 'active')
                    <a href="/products/create" class="btn-dark">Add a product</a>
                @else
                    <span class="btn-dark" style="opacity: 0.5; cursor: not-allowed;" title="Account must be approved to add products">
                        <i class="fas fa-lock" style="margin-right: 5px;"></i>Add a product
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Dashboard Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; padding: 0 20px; margin-bottom: 20px;">
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
            <div style="font-size: 12px; color: #565959; margin-bottom: 8px;">TOTAL INVENTORY</div>
            <div style="font-size: 28px; font-weight: 700; color: #0f1111;">{{ $products->sum('quantity') }}</div>
            <div style="font-size: 11px; color: #888; margin-top: 4px;">{{ count($products) }} products</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
            <div style="font-size: 12px; color: #565959; margin-bottom: 8px;">ACTIVE LISTINGS</div>
            <div style="font-size: 28px; font-weight: 700; color: #067d62;">{{ $products->where('quantity', '>', 0)->count() }}</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
            <div style="font-size: 12px; color: #565959; margin-bottom: 8px;">OUT OF STOCK</div>
            <div style="font-size: 28px; font-weight: 700; color: #c40000;">{{ $products->where('quantity', '<=', 0)->count() }}</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
            <div style="font-size: 12px; color: #565959; margin-bottom: 8px;">LOW STOCK</div>
            <div style="font-size: 28px; font-weight: 700; color: #ff9900;">{{ $products->where('quantity', '>', 0)->where('quantity', '<', 10)->count() }}</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
            <div style="font-size: 12px; color: #565959; margin-bottom: 8px;">90+ DAY INVENTORY</div>
            <div style="font-size: 28px; font-weight: 700; color: #565959;">
                {{ $products->filter(function($p) { 
                    return $p->created_at && $p->created_at->diffInDays(now()) > 90; 
                })->sum('quantity') }}
            </div>
            <div style="font-size: 11px; color: #888; margin-top: 4px;">aged stock</div>
        </div>
    </div>

        <div class="search-row">
            <form action="{{ route('products.index') }}" method="GET" style="display:contents;">
                <div class="main-search-box">
                    <select class="dropdown-select"><option>All</option></select>
                    <input type="text" name="search" class="search-input-field" placeholder="Search SKU, Title, ASIN..." value="{{ $search ?? '' }}">
                    <button type="submit" class="search-icon-btn"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-wrapper">
        <div style="margin: 10px 0; font-size:12px; color:#555; display:flex; justify-content:space-between;">
            <div><b>{{ count($products) }}</b> listings</div>
        </div>

        <table class="inventory-table">
            <thead>
                <tr>
                    <th class="col-status">Listing status</th>
                    <th class="col-image">Image</th>
                    <th class="col-details">Product details</th>
                    <th width="100">Performance</th>
                    <th class="col-inv">Inventory</th>
                    <th class="col-price">Price</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    
                    <td>
                        @if($product->quantity > 0)
                            <div class="status-text status-green">Active</div>
                        @else
                            <div class="status-text status-red">Out of stock</div>
                        @endif
                        <div class="status-date">{{ $product->created_at ? $product->created_at->format('d M Y') : now()->format('d M Y') }}</div>
                    </td>

                    <td class="col-image">
    @php
        // Fallback placeholder
        $placeholder = 'https://placehold.co/60?text=No+Image';

        // Use the stored image path from the product
        $img = $product->img_path ?? null;

        // If it's a local path (stored on public disk), build a public URL using asset()
        if ($img && !preg_match('/^https?:\/\//', $img)) {
            $img = asset($img);
        }

        // If nothing set, use placeholder
        if (!$img) {
            $img = $placeholder;
        }
    @endphp

    <img src="{{ $img }}"
         class="product-img"
         alt="{{ $product->name }}"
         referrerpolicy="no-referrer"
         onerror="this.onerror=null;this.src='{{ $placeholder }}';">
</td>

                    <td class="col-details">
                        <a href="{{ route('products.edit', $product->id) }}" class="product-title">{{ $product->name }}</a>
                        <div class="meta-info">
                            <span class="meta-label">ASIN:</span> <span class="meta-val">{{ $product->asin ?? 'B0G6T7HY48' }}</span><br>
                            <span class="meta-label">SKU:</span> <span class="meta-val">{{ $product->sku }}</span><br>
                            <span class="meta-label">Condition:</span> <span class="meta-val">New</span>
                        </div>
                    </td>

                    <td>
                        <div class="meta-info">
                            Sales: <b>{{ $product->orders->count() }}</b><br>
                            Units: <b>{{ $product->orders->sum('quantity') }}</b><br>
                            Rank: 113,434
                        </div>
                    </td>

                    <td class="col-inv">
                        <div class="meta-info">Available (FBM)</div>
                        <div class="inv-input-group">
                            <input type="text" 
                                   class="table-input" 
                                   value="{{ $product->quantity }}" 
                                   id="qty-{{ $product->id }}"
                                   readonly 
                                   style="background: #f0f2f2; cursor: not-allowed;">
                            <button onclick="enableQuickEdit({{ $product->id }})" 
                                    class="quick-edit-btn" 
                                    id="edit-btn-{{ $product->id }}"
                                    title="Quick update stock">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="saveQuickEdit({{ $product->id }})" 
                                    class="quick-save-btn" 
                                    id="save-btn-{{ $product->id }}"
                                    style="display:none;"
                                    title="Save stock">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </td>

                    <td class="col-price">
                        <div class="meta-info">Price</div>
                        <div class="currency-box">
                            <div class="currency-label">INR</div>
                            <input type="text" class="price-input" value="{{ $product->price }}">
                        </div>
                    </td>

                    <td class="col-actions">
                        @if(Auth::check() && Auth::user()->status == 'active')
                            <button class="kebab-btn" onclick="toggleRowActions(event, '{{ $product->id }}')"><i class="fas fa-ellipsis-v"></i></button>
                            <div class="row-actions" id="row-actions-{{ $product->id }}" onclick="event.stopPropagation()">
                                <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                                
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color:#c40000;">Delete</button>
                                </form>
                            </div>
                        @else
                            <span style="color:#888; font-size:11px;" title="Account must be approved to edit products">
                                <i class="fas fa-lock"></i>
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:30px;">
                        @if(Auth::check() && Auth::user()->status == 'active')
                            No inventory found. <a href="/products/create">Add a product</a> to get started.
                        @else
                            No inventory found. Your account must be approved before you can add products.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function toggleMenu() {
            var menu = document.getElementById('sideMenu');
            var overlay = document.getElementById('menuOverlay');
            if(menu.style.left === '0px') {
                menu.style.left = '-320px'; overlay.style.display = 'none';
            } else {
                menu.style.left = '0px'; overlay.style.display = 'block';
            }
        }
        function toggleSubmenu(id) {
            var el = document.getElementById(id);
            el.style.display = (el.style.display === 'block') ? 'none' : 'block';
        }
        function toggleRowActions(e, pid) {
            e.stopPropagation();
            var id = 'row-actions-' + pid;
            var menu = document.getElementById(id);
            
            document.querySelectorAll('.row-actions').forEach(function(m){ if(m.id !== id) m.style.display = 'none'; });
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }
        document.addEventListener('click', function(){
            document.querySelectorAll('.row-actions').forEach(function(m){ m.style.display = 'none'; });
        });

        // Quick Edit Stock Functions
        function enableQuickEdit(productId) {
            const input = document.getElementById('qty-' + productId);
            const editBtn = document.getElementById('edit-btn-' + productId);
            const saveBtn = document.getElementById('save-btn-' + productId);
            
            input.readOnly = false;
            input.style.background = 'white';
            input.style.cursor = 'text';
            input.style.borderColor = '#ff9900';
            input.focus();
            input.select();
            
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        }

        function saveQuickEdit(productId) {
            const input = document.getElementById('qty-' + productId);
            const editBtn = document.getElementById('edit-btn-' + productId);
            const saveBtn = document.getElementById('save-btn-' + productId);
            const newQuantity = input.value;
            
            // Show loading
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            saveBtn.disabled = true;
            
            fetch(`/products/${productId}/quick-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: newQuantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Reset input state
                    input.readOnly = true;
                    input.style.background = '#f0f2f2';
                    input.style.cursor = 'not-allowed';
                    input.style.borderColor = '#888C8C';
                    input.value = data.new_quantity;
                    
                    editBtn.style.display = 'inline-block';
                    saveBtn.style.display = 'none';
                    saveBtn.innerHTML = '<i class="fas fa-check"></i>';
                    saveBtn.disabled = false;
                    
                    // Show success message
                    showSuccessToast('Stock updated successfully!');
                    
                    // Reload page after 1.5 seconds to update stats
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    alert('Failed to update stock. Please try again.');
                    saveBtn.innerHTML = '<i class="fas fa-check"></i>';
                    saveBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update stock. Please try again.');
                saveBtn.innerHTML = '<i class="fas fa-check"></i>';
                saveBtn.disabled = false;
            });
        }

        function showSuccessToast(message) {
            const toast = document.createElement('div');
            toast.className = 'success-toast';
            toast.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        }
    </script>
@endsection