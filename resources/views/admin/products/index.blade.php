@extends('admin.layout')
@section('title', 'Products')
@section('header', 'Products')

@section('content')
    <h1 class="page-title">Products</h1>
    <p class="page-subtitle">All products listed across sellers</p>

    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Total Products</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Active</div>
            <div class="value">{{ $stats['active'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Out of Stock</div>
            <div class="value">{{ $stats['out_of_stock'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Sponsored</div>
            <div class="value">{{ $stats['sponsored'] }}</div>
        </div>
    </div>

    <div class="table-wrap">
        <div class="table-header">
            <h2>All Products</h2>
            <form method="GET" action="{{ route('admin.products') }}" class="flex gap-8 items-center">
                <input type="text" name="search" class="form-control" style="width: 220px;" placeholder="Search name or SKU..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-sm">Search</button>
                @if($search ?? false)
                    <a href="{{ route('admin.products') }}" class="btn-link">Clear</a>
                @endif
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Seller</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td style="font-weight: 500;">{{ $product->name }}</td>
                        <td class="text-muted">{{ $product->sku }}</td>
                        <td>{{ $product->seller->business_name ?? $product->seller->name ?? '—' }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td><span class="status-text {{ $product->status }}">{{ $product->status }}</span></td>
                        <td class="text-right">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn-link">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty-state">No products found</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($products->hasPages())
            <div class="pagination-wrap">{{ $products->appends(request()->query())->links() }}</div>
        @endif
    </div>
@endsection
