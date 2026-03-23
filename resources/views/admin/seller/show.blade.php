@extends('admin.layout')
@section('title', 'Seller Details')
@section('header', 'Seller Details')

@section('content')
    <a href="{{ route('admin.sellers') }}" class="back-link">Back to sellers</a>

    <h1 class="page-title">{{ $seller->business_name ?? $seller->name }}</h1>
    <p class="page-subtitle">Seller #{{ $seller->id }} — <span class="status-text {{ $seller->status }}">{{ $seller->status }}</span></p>

    {{-- ── Quick Actions ────────────────────────────── --}}
    <div class="flex gap-8 mt-8" style="margin-bottom: 24px;">
        @if($seller->status !== 'active')
            <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-primary">Approve</button>
            </form>
        @endif
        @if($seller->status !== 'banned')
            <form action="{{ route('admin.sellers.ban', $seller->id) }}" method="POST" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-danger">Ban</button>
            </form>
        @endif
        @if($seller->status === 'active' || $seller->status === 'banned')
            <form action="{{ route('admin.sellers.pending', $seller->id) }}" method="POST" class="inline-form">
                @csrf
                <button type="submit" class="btn">Set Pending</button>
            </form>
        @endif
    </div>

    {{-- ── Account Information ──────────────────────── --}}
    <div class="card">
        <div class="card-title">Account Information</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="dt">Name</div>
                <div class="dd">{{ $seller->name }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Email</div>
                <div class="dd">{{ $seller->email }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Phone</div>
                <div class="dd">{{ $seller->phone ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Registered</div>
                <div class="dd">{{ $seller->created_at ? $seller->created_at->format('d M Y, h:i A') : '—' }}</div>
            </div>
        </div>
    </div>

    {{-- ── Business Details ─────────────────────────── --}}
    <div class="card">
        <div class="card-title">Business Details</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="dt">Business Name</div>
                <div class="dd">{{ $seller->business_name ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Address</div>
                <div class="dd">{{ $seller->getFullBusinessAddress() ?: '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">GSTIN</div>
                <div class="dd">{{ $seller->gstin ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">PAN</div>
                <div class="dd">{{ $seller->pan ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">CIN</div>
                <div class="dd">{{ $seller->cin ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- ── Bank Details ─────────────────────────────── --}}
    <div class="card">
        <div class="card-title">Bank Details</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="dt">Bank Name</div>
                <div class="dd">{{ $seller->bank_name ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Account Number</div>
                <div class="dd">{{ $seller->bank_account ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">IFSC Code</div>
                <div class="dd">{{ $seller->ifsc_code ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- ── Products by this Seller ──────────────────── --}}
    <div class="table-wrap">
        <div class="table-header">
            <h2>Products ({{ $seller->products->count() }})</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($seller->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td><span class="status-text {{ $product->status }}">{{ $product->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">No products listed</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
