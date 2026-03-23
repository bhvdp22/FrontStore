@extends('admin.layout')
@section('title', 'Orders')
@section('header', 'Orders')

@section('content')
    <h1 class="page-title">Orders</h1>
    <p class="page-subtitle">All orders across the platform</p>

    {{-- ── Summary ──────────────────────────────────── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Total Orders</div>
            <div class="value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Order Value</div>
            <div class="value"><span class="rupee">₹</span>{{ number_format($stats['total_order_value'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Platform Revenue</div>
            <div class="value"><span class="rupee">₹</span>{{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending</div>
            <div class="value">{{ $stats['pending_orders'] }}</div>
        </div>
    </div>

    {{-- ── Orders Table ─────────────────────────────── --}}
    <div class="table-wrap">
        <div class="table-header">
            <h2>All Orders</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Seller</th>
                    <th>Total</th>
                    <th>Commission</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="font-weight: 500;">{{ $order->order_id }}</td>
                        <td>{{ $order->customer->name ?? $order->customer_name ?? '—' }}</td>
                        <td>{{ $order->seller->business_name ?? $order->seller->name ?? '—' }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($order->grand_total ?? $order->total_price, 2) }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($order->commission_amount, 2) }}</td>
                        <td><span class="status-text {{ $order->status }}">{{ $order->status }}</span></td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-link">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty-state">No orders found</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($orders->hasPages())
            <div class="pagination-wrap">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
