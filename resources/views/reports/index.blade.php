@extends('layouts.seller')

@section('title', 'Reports & Analytics - Seller Central')

@section('extra_styles')
<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f1f3f3; }
    
    .container { padding: 20px; max-width: 1400px; margin: 0 auto; }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    
    .page-title { font-size: 28px; font-weight: 700; color: #232f3e; }
    
    .date-filter {
        display: flex;
        gap: 10px;
        align-items: center;
        background: white;
        padding: 12px 16px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .date-filter input {
        padding: 8px 12px;
        border: 1px solid #888;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .btn-primary {
        background: #ff9900;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .btn-primary:hover { background: #e88b00; }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    /* stat-card styling handled by global component */
    
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .report-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .report-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
        color: white;
        font-size: 16px;
        font-weight: 600;
    }
    
    .report-body {
        padding: 20px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .report-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .report-table th {
        text-align: left;
        padding: 10px;
        background: #f0f2f2;
        font-size: 12px;
        font-weight: 600;
        color: #0f1111;
        border-bottom: 2px solid #e7e7e7;
    }
    
    .report-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #eaeded;
        font-size: 13px;
        color: #0f1111;
    }
    
    .report-table tr:hover {
        background: #f7fafa;
    }
    
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #565959;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #d5d9d9;
        margin-bottom: 16px;
    }

    /* Low Stock Alerts */
    .alert-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .alert-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, #c7511f 0%, #e47340 100%);
        color: white;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .alert-header.warning {
        background: linear-gradient(135deg, #ff9900 0%, #ffb84d 100%);
    }
    
    .stock-grid {
        padding: 20px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .stock-item {
        display: grid;
        grid-template-columns: 50px 1fr 110px 160px;
        gap: 12px;
        align-items: center;
        padding: 10px 12px;
        border-bottom: 1px solid #eaeded;
        transition: all 0.2s;
    }

    .stock-item:last-child {
        border-bottom: none;
    }
    
    .stock-item:hover {
        background: #f7fafa;
    }
    
    .stock-img {
        width: 45px;
        height: 45px;
        object-fit: contain;
        border-radius: 3px;
        background: white;
        padding: 2px;
        border: 1px solid #ddd;
    }
    
    .stock-info h4 {
        margin: 0 0 3px 0;
        font-size: 13px;
        font-weight: 600;
        color: #0f1111;
        line-height: 1.3;
    }
    
    .stock-info p {
        margin: 0;
        font-size: 11px;
        color: #565959;
    }
    
    .stock-level {
        text-align: center;
    }
    
    .stock-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .stock-badge.critical {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .stock-badge.low {
        background: #fef3c7;
        color: #92400e;
    }
    
    .stock-actions {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: flex-end;
    }
    
    .quick-update {
        display: flex;
        gap: 4px;
        align-items: center;
    }
    
    .stock-input {
        width: 55px;
        padding: 6px 4px;
        border: 1px solid #888;
        border-radius: 3px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }
    
    .stock-input:focus {
        border-color: #ff9900;
        outline: none;
        box-shadow: 0 0 0 2px rgba(255,153,0,0.1);
    }
    
    .btn-update {
        background: #067d62;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    
    .btn-update:hover {
        background: #055d4a;
    }
    
    .btn-edit {
        background: #232f3e;
        color: white;
        padding: 6px 10px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
        white-space: nowrap;
    }
    
    .btn-edit:hover {
        background: #37475a;
        text-decoration: none;
        color: white;
    }

    .success-msg {
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
<div class="container">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-chart-bar"></i> Reports & Analytics</h1>
        
        <form method="GET" action="{{ route('report') }}" class="date-filter">
            <label>From:</label>
            <input type="date" name="start_date" value="{{ $startDate }}" required>
            <label>To:</label>
            <input type="date" name="end_date" value="{{ $endDate }}" required>
            <button type="submit" class="btn-primary">
                <i class="fas fa-sync-alt"></i> Update
            </button>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <x-stat-card 
            title="Total Sales"
            value="₹{{ number_format($salesData->total_revenue ?? 0, 0) }}"
            subtitle="{{ $salesData->total_orders ?? 0 }} orders • {{ $salesData->total_units ?? 0 }} units"
        />
        
        <x-stat-card 
            title="Total Returns"
            value="{{ $returnsData->total_returns ?? 0 }}"
            subtitle="{{ $returnsData->total_units_returned ?? 0 }} units returned • ₹{{ number_format($returnsData->total_refund_amount ?? 0, 0) }} refunded"
            borderColor="#c7511f"
        />
        
        <x-stat-card 
            title="Completed Refunds"
            value="{{ $returnsData->completed_refunds ?? 0 }}"
            subtitle="Out of {{ $returnsData->total_returns ?? 0 }} total returns"
            borderColor="#c7511f"
        />
        
        <x-stat-card 
            title="Payment Collection"
            value="₹{{ number_format($paymentData->completed_amount ?? 0, 0) }}"
            subtitle="{{ $paymentData->online_payments ?? 0 }} online • {{ $paymentData->cod_payments ?? 0 }} COD"
            borderColor="#067d62"
        />
    </div>

    <!-- Reports Grid -->
    <div class="reports-grid">
        <!-- Top Products by Sales -->
        <div class="report-card">
            <div class="report-header">
                <i class="fas fa-trophy"></i> Top Products by Sales
            </div>
            <div class="report-body">
                @if($topProducts->count() > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Units</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                        <tr>
                            <td><strong>{{ Str::limit($product->product_name, 30) }}</strong></td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->units_sold }}</td>
                            <td><strong>₹{{ number_format($product->revenue, 0) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No sales data for selected period</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Top Returned Products -->
        <div class="report-card">
            <div class="report-header">
                <i class="fas fa-undo-alt"></i> Top Returned Products
            </div>
            <div class="report-body">
                @if($topReturnedProducts->count() > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Returns</th>
                            <th>Refunded</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topReturnedProducts as $product)
                        <tr>
                            <td><strong>{{ Str::limit($product->name, 25) }}</strong></td>
                            <td>{{ $product->sku }}</td>
                            <td><span class="badge badge-danger">{{ $product->return_count }}</span></td>
                            <td><strong>₹{{ number_format($product->total_refunded, 0) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p>No returns for selected period</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Returns by Reason -->
        <div class="report-card">
            <div class="report-header">
                <i class="fas fa-comments"></i> Return Reasons Analysis
            </div>
            <div class="report-body">
                @if($returnsByReason->count() > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Reason</th>
                            <th>Count</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalReturns = $returnsByReason->sum('count'); @endphp
                        @foreach($returnsByReason as $reason)
                        <tr>
                            <td><strong>{{ ucfirst(str_replace('_', ' ', $reason->return_reason)) }}</strong></td>
                            <td>{{ $reason->count }}</td>
                            <td>{{ $totalReturns > 0 ? round(($reason->count / $totalReturns) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fas fa-smile"></i>
                    <p>No returns to analyze</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Returns by Status -->
        <div class="report-card">
            <div class="report-header">
                <i class="fas fa-tasks"></i> Returns by Status
            </div>
            <div class="report-body">
                @if($returnsByStatus->count() > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalReturns = $returnsByStatus->sum('count'); @endphp
                        @foreach($returnsByStatus as $status)
                        <tr>
                            <td>
                                @php
                                    $badgeClass = 'badge-info';
                                    if ($status->status == 'refund_completed') $badgeClass = 'badge-success';
                                    elseif ($status->status == 'rejected') $badgeClass = 'badge-danger';
                                    elseif (in_array($status->status, ['pending', 'approved'])) $badgeClass = 'badge-warning';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $status->status)) }}</span>
                            </td>
                            <td><strong>{{ $status->count }}</strong></td>
                            <td>{{ $totalReturns > 0 ? round(($status->count / $totalReturns) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p>No returns to display</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($lowStockProducts->count() > 0)
    <div class="alert-card">
        <div class="alert-header warning">
            <span><i class="fas fa-exclamation-triangle"></i> Low Stock Alert</span>
            <span class="badge" style="background: white; color: #d97706; font-size: 13px;">{{ $lowStockProducts->count() }} products</span>
        </div>
        <div class="stock-grid">
            @foreach($lowStockProducts as $product)
            <div class="stock-item">
                <div>
                    @php
                        $imgSrc = 'https://placehold.co/45?text=No+Image';
                        if ($product->img_path) {
                            if (preg_match('/^https?:\/\//', $product->img_path)) {
                                $imgSrc = $product->img_path;
                            } else {
                                $imgSrc = asset('storage/' . ltrim($product->img_path, '/'));
                            }
                        }
                    @endphp
                    <img src="{{ $imgSrc }}" 
                         alt="{{ $product->name }}" 
                         class="stock-img"
                         onerror="this.src='https://placehold.co/45?text=No+Image'">
                </div>
                <div class="stock-info">
                    <h4>{{ Str::limit($product->name, 45) }}</h4>
                    <p>SKU: {{ $product->sku }} @if($product->asin)• ASIN: {{ $product->asin }}@endif</p>
                </div>
                <div class="stock-level">
                    <span class="stock-badge {{ $product->quantity <= 3 ? 'critical' : 'low' }}">
                        {{ $product->quantity }} {{ $product->quantity == 1 ? 'unit' : 'units' }} left
                    </span>
                </div>
                <div class="stock-actions">
                    <form class="quick-update" onsubmit="updateStock(event, {{ $product->id }})">
                        @csrf
                        <input type="number" 
                               name="quantity" 
                               class="stock-input" 
                               value="{{ $product->quantity }}" 
                               min="0" 
                               required
                               id="stock-{{ $product->id }}">
                        <button type="submit" class="btn-update">
                            <i class="fas fa-check"></i> Update
                        </button>
                    </form>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Out of Stock -->
    @if($outOfStockProducts->count() > 0)
    <div class="alert-card">
        <div class="alert-header">
            <span><i class="fas fa-box-open"></i> Out of Stock</span>
            <span class="badge" style="background: white; color: #c7511f; font-size: 13px;">{{ $outOfStockProducts->count() }} products</span>
        </div>
        <div class="stock-grid">
            @foreach($outOfStockProducts as $product)
            <div class="stock-item">
                <div>
                    @php
                        $imgSrc = 'https://placehold.co/45?text=No+Image';
                        if ($product->img_path) {
                            if (preg_match('/^https?:\/\//', $product->img_path)) {
                                $imgSrc = $product->img_path;
                            } else {
                                $imgSrc = asset('storage/' . ltrim($product->img_path, '/'));
                            }
                        }
                    @endphp
                    <img src="{{ $imgSrc }}" 
                         alt="{{ $product->name }}" 
                         class="stock-img"
                         onerror="this.src='https://placehold.co/45?text=No+Image'">
                </div>
                <div class="stock-info">
                    <h4>{{ Str::limit($product->name, 45) }}</h4>
                    <p>SKU: {{ $product->sku }} @if($product->asin)• ASIN: {{ $product->asin }}@endif</p>
                </div>
                <div class="stock-level">
                    <span class="stock-badge critical">
                        Out of Stock
                    </span>
                </div>
                <div class="stock-actions">
                    <form class="quick-update" onsubmit="updateStock(event, {{ $product->id }})">
                        @csrf
                        <input type="number" 
                               name="quantity" 
                               class="stock-input" 
                               value="0" 
                               min="0" 
                               required
                               id="stock-{{ $product->id }}"
                               placeholder="Enter qty">
                        <button type="submit" class="btn-update">
                            <i class="fas fa-plus"></i> Restock
                        </button>
                    </form>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function updateStock(event, productId) {
    event.preventDefault();
    
    const form = event.target;
    const input = document.getElementById('stock-' + productId);
    const newQuantity = input.value;
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalContent = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    submitBtn.disabled = true;
    
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
            // Show success message
            showSuccessMessage('Stock updated successfully!');
            
            // Update the input value
            input.value = data.new_quantity;
            
            // Reload page after 1 second to reflect changes
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('Failed to update stock. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update stock. Please try again.');
    })
    .finally(() => {
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
    });
}

function showSuccessMessage(message) {
    const msgDiv = document.createElement('div');
    msgDiv.className = 'success-msg';
    msgDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
    document.body.appendChild(msgDiv);
    
    setTimeout(() => {
        msgDiv.style.animation = 'slideIn 0.3s ease-out reverse';
        setTimeout(() => msgDiv.remove(), 300);
    }, 2000);
}
</script>
@endsection
