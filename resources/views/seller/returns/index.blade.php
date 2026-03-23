@extends('layouts.seller')

@section('title', 'Return Management')

@section('extra_styles')
<style>
    body { font-family: 'Poppins', sans-serif; }
    
    .page-header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-title { font-size: 28px; font-weight: 700; color: #1e293b; }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    /* stat-card styles removed as using global component */
    .filters-bar {
        background: #fff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .filters-bar input, .filters-bar select {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
    }
    .filters-bar input:focus, .filters-bar select:focus {
        border-color: #febd69;
        outline: none;
    }
    .filters-bar .search-input { flex: 1; min-width: 200px; }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-primary { background: #febd69; color: #232f3e; }
    .btn-primary:hover { background: #f3a847; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
    
    .returns-table {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .returns-table table {
        width: 100%;
        border-collapse: collapse;
    }
    .returns-table th {
        text-align: left;
        padding: 14px 16px;
        background: #f8fafc;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
    }
    .returns-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
    }
    .returns-table tr:hover { background: #f8fafc; }
    
    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .product-cell img {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }
    .product-cell .name { font-weight: 600; color: #1e293b; }
    .product-cell .sku { font-size: 12px; color: #64748b; }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .action-btns {
        display: flex;
        gap: 6px;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.2s;
    }
    .action-btn:hover { background: #f1f5f9; color: #1e293b; }
    .action-btn.view:hover { color: #3b82f6; }
    .action-btn.approve:hover { color: #10b981; }
    .action-btn.reject:hover { color: #ef4444; }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 20px;
    }
    .pagination a, .pagination span {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        color: #64748b;
        background: #f1f5f9;
    }
    .pagination a:hover { background: #e2e8f0; }
    .pagination .active { background: #232f3e; color: #fff; }
    
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #64748b;
    }
    .empty-state i { font-size: 48px; color: #cbd5e1; margin-bottom: 16px; }
    .empty-state h3 { color: #334155; margin-bottom: 8px; }
    
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="page-header-bar">
    <h1 class="page-title">Return Management</h1>
    <a href="{{ route('seller.returns.export') }}" class="btn btn-secondary">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

@if(session('success'))
    <div style="background:#d1fae5;color:#065f46;padding:14px 20px;border-radius:8px;margin-bottom:20px;font-weight:500;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="stats-grid">
    <x-stat-card 
        title="Pending Review"
        value="{{ $counts['pending'] }}"
        valueColor="#f59e0b"
    />
    <x-stat-card 
        title="Approved"
        value="{{ $counts['approved'] }}"
        valueColor="#10b981"
    />
    <x-stat-card 
        title="In Transit"
        value="{{ $counts['in_transit'] }}"
        valueColor="#3b82f6"
    />
    <x-stat-card 
        title="Completed"
        value="{{ $counts['completed'] }}"
        valueColor="#22c55e"
    />
</div>

<form action="{{ route('seller.returns.index') }}" method="GET" class="filters-bar">
    <input type="text" name="search" class="search-input" placeholder="Search by return #, customer, product..." value="{{ request('search') }}">
    <select name="status">
        <option value="">All Status</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="pickup_scheduled" {{ request('status') == 'pickup_scheduled' ? 'selected' : '' }}>Pickup Scheduled</option>
        <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
        <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
        <option value="refund_initiated" {{ request('status') == 'refund_initiated' ? 'selected' : '' }}>Refund Initiated</option>
        <option value="refund_completed" {{ request('status') == 'refund_completed' ? 'selected' : '' }}>Refund Completed</option>
        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>
    <input type="date" name="from_date" value="{{ request('from_date') }}" placeholder="From Date">
    <input type="date" name="to_date" value="{{ request('to_date') }}" placeholder="To Date">
    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
</form>

<div class="returns-table">
    @if($returns->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Return ID</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Reason</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returns as $return)
                    <tr>
                        <td><strong>{{ $return->return_number }}</strong></td>
                        <td>
                            <div class="product-cell">
                                @php
                                    $placeholder = 'https://placehold.co/50x50?text=No+Image';
                                    $img = $return->product->img_path ?? null;
                                    if ($img && !preg_match('/^https?:\/\//', $img)) {
                                        $img = asset('storage/' . ltrim($img, '/'));
                                    }
                                    if (!$img) {
                                        $img = 'https://m.media-amazon.com/images/I/41-a+x5eB+L._SX342_SY445_.jpg';
                                    }
                                @endphp
                                <img src="{{ $img }}" alt="{{ $return->product->name ?? '' }}" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src='{{ $placeholder }}'">
                                <div>
                                    <div class="name">{{ Str::limit($return->product->name ?? 'Product', 25) }}</div>
                                    <div class="sku">Qty: {{ $return->quantity }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $return->customer->name ?? 'Customer' }}</td>
                        <td>{{ $return->reason_label }}</td>
                        <td><strong>₹{{ number_format($return->refund_amount, 0) }}</strong></td>
                        <td>
                            <span class="status-badge" style="background:{{ $return->status_color }}22;color:{{ $return->status_color }};">
                                {{ $return->status_label }}
                            </span>
                        </td>
                        <td>{{ $return->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('seller.returns.show', $return->id) }}" class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($return->status === 'pending')
                                    <form action="{{ route('seller.returns.approve', $return->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="action-btn approve" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination">
            {{ $returns->withQueryString()->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>No Returns Found</h3>
            <p>No return requests match your current filters.</p>
        </div>
    @endif
</div>
@endsection
