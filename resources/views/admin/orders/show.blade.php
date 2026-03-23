@extends('admin.layout')
@section('title', 'Order Details')
@section('header', 'Order Details')

@section('content')
    <a href="{{ route('admin.orders') }}" class="back-link">Back to orders</a>

    <h1 class="page-title">Order {{ $order->order_id }}</h1>
    <p class="page-subtitle">Placed {{ $order->created_at->format('d M Y, h:i A') }} — <span class="status-text {{ $order->status }}">{{ $order->status }}</span></p>

    {{-- ── Customer & Seller ────────────────────────── --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="card">
            <div class="card-title">Customer</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Name</div>
                    <div class="dd">{{ $order->customer->name ?? $order->customer_name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Email</div>
                    <div class="dd">{{ $order->customer->email ?? $order->customer_email ?? '—' }}</div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="dt">Shipping Address</div>
                    <div class="dd">{{ $order->shipping_address ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Seller</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Name</div>
                    <div class="dd">{{ $order->seller->name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Business</div>
                    <div class="dd">{{ $order->seller->business_name ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Order Items ──────────────────────────────── --}}
    <div class="table-wrap">
        <div class="table-header">
            <h2>Items</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if($order->items && $order->items->count())
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? $item->product_name ?? '—' }}</td>
                            <td>{{ $item->sku ?? '—' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><span class="rupee">₹</span>{{ number_format($item->price, 2) }}</td>
                            <td><span class="rupee">₹</span>{{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    {{-- Fallback: single-product order --}}
                    <tr>
                        <td>{{ $order->product_name }}</td>
                        <td>{{ $order->sku }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($order->total_price / max($order->quantity, 1), 2) }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($order->total_price, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- ── Financial Summary ────────────────────────── --}}
    <div class="card">
        <div class="card-title">Financial Summary</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="dt">Subtotal</div>
                <div class="dd"><span class="rupee">₹</span>{{ number_format($order->subtotal ?? $order->total_price, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Tax ({{ $order->tax_rate ?? 0 }}%)</div>
                <div class="dd"><span class="rupee">₹</span>{{ number_format($order->tax_amount ?? 0, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Platform Fee</div>
                <div class="dd"><span class="rupee">₹</span>{{ number_format($order->platform_fee ?? 0, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Grand Total</div>
                <div class="dd" style="font-size: 16px; font-weight: 700;"><span class="rupee">₹</span>{{ number_format($order->grand_total ?? $order->total_price, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Commission ({{ $order->commission_rate ?? 0 }}%)</div>
                <div class="dd"><span class="rupee">₹</span>{{ number_format($order->commission_amount ?? 0, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Seller Earnings</div>
                <div class="dd"><span class="rupee">₹</span>{{ number_format($order->seller_earnings ?? 0, 2) }}</div>
            </div>
        </div>
    </div>
@endsection
