@extends('layouts.seller')

@section('title', 'Orders - Seller Central')

@section('extra_styles')
<style>
    body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f1f3f3; }
    
    .container { padding: 20px; max-width: 1400px; margin: 0 auto; }
    
    /* Page Header */
    .page-header {
        background: white;
        padding: 20px 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #0f1111;
        margin: 0 0 8px 0;
    }
    
    .page-subtitle {
        font-size: 14px;
        color: #565959;
        margin: 0;
    }
    
    /* Filters Section */
    .filters-section {
        background: white;
        padding: 20px 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }
    
    .search-form {
        display: flex;
        gap: 10px;
        flex: 1;
        max-width: 500px;
    }
    
    .search-input {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid #888;
        border-radius: 4px;
        font-size: 14px;
        font-family: inherit;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #e77600;
        box-shadow: 0 0 0 3px rgba(228,121,17,0.1);
    }
    
    /* Buttons */
    .btn {
        background: #ff9900;
        border: 1px solid #ff9900;
        color: white;
        padding: 10px 18px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn:hover {
        background: #e88b00;
        border-color: #e88b00;
    }
    
    .btn-secondary {
        background: white;
        color: #0f1111;
        border-color: #888;
    }
    
    .btn-secondary:hover {
        background: #f0f2f2;
    }
    
    /* Table Card */
    .table-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }
    
    thead {
        background: #f7f8fa;
        border-bottom: 2px solid #e7e7e7;
    }
    
    th {
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        color: #0f1111;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }
    
    td {
        padding: 14px 16px;
        font-size: 13px;
        text-align: left;
        color: #0f1111;
        border-bottom: 1px solid #f0f2f2;
    }
    
    tbody tr:hover {
        background: #f9fafb;
    }
    
    tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Status Badges */
    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .badge-unshipped {
        background: #fff3cd;
        color: #856404;
    }
    
    .badge-shipped {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .badge-delivered {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    
    /* Row Actions */
    .row-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    
    .row-actions a {
        color: #007185;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .row-actions a:hover {
        color: #c7511f;
        text-decoration: underline;
    }
    
    /* Success Message */
    .success-message {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #565959;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #d5d9d9;
        margin-bottom: 16px;
    }
    
    .empty-state h3 {
        font-size: 18px;
        margin: 0 0 8px 0;
        color: #0f1111;
    }
    
    .empty-state p {
        margin: 0;
        font-size: 14px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Manage Orders</h1>
        <p class="page-subtitle">View and manage all your customer orders</p>
    </div>
    
    <!-- Filters Section -->
    <div class="filters-section">
        <form method="GET" action="{{ route('orders.index') }}" class="search-form">
            <input type="text" name="search" class="search-input" 
                   placeholder="Search by Order ID, Customer Name, or SKU" 
                   value="{{ $search ?? '' }}">
            <button class="btn btn-secondary" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
        <a class="btn" href="{{ route('orders.create') }}">
            <i class="fas fa-plus"></i> Create Order
        </a>
    </div>

    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Orders Table -->
    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Qty</th>
                    <th>Order Total</th>
                    <th>Your Earnings</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td>{{ $o->id }}</td>
                    <td><strong>{{ $o->order_id }}</strong></td>
                    <td>{{ $o->customer_name }}</td>
                    <td>{{ $o->product_name }}</td>
                    <td><code style="background: #f0f2f2; padding: 2px 6px; border-radius: 3px; font-size: 12px;">{{ $o->sku }}</code></td>
                    <td>{{ $o->quantity }}</td>
                    <td>₹{{ number_format((float)$o->total_price, 2) }}</td>
                    <td>
                        @if($o->seller_earnings > 0)
                            <span style="color: #28a745; font-weight: 600;">₹{{ number_format((float)$o->seller_earnings, 2) }}</span>
                            @if($o->commission_rate > 0)
                                <br><small style="color: #888; font-size: 10px;">-{{ $o->commission_rate }}% commission</small>
                            @endif
                        @else
                            <span style="color: #666;">₹{{ number_format((float)$o->total_price, 2) }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $status = strtolower($o->status);
                            $badgeClass = 'badge ';
                            if ($status === 'unshipped') $badgeClass .= 'badge-unshipped';
                            elseif ($status === 'shipped') $badgeClass .= 'badge-shipped';
                            elseif ($status === 'delivered') $badgeClass .= 'badge-delivered';
                            elseif ($status === 'cancelled') $badgeClass .= 'badge-cancelled';
                        @endphp
                        <span class="{{ $badgeClass }}">{{ $o->status }}</span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('orders.show', $o->id) }}" title="View Details">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('orders.invoice', $o->id) }}" title="Download Invoice">
                                <i class="fas fa-file-pdf"></i> Invoice
                            </a>
                            <a href="{{ route('orders.edit', $o->id) }}" title="Edit Order">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="border: none; padding: 0;">
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h3>No Orders Found</h3>
                            <p>You don't have any orders yet. Create your first order to get started.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
