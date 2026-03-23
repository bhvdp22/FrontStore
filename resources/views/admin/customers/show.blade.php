@extends('admin.layout')
@section('title', 'Customer Details')
@section('header', 'Customer Details')

@section('content')
    <a href="{{ route('admin.customers') }}" class="back-link">Back to customers</a>

    <h1 class="page-title">{{ $customer->name }}</h1>
    <p class="page-subtitle">Customer #{{ $customer->id }}</p>

    <div class="grid-2">
        <div class="card">
            <div class="card-title">Account</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Name</div>
                    <div class="dd">{{ $customer->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Email</div>
                    <div class="dd">{{ $customer->email }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Registered</div>
                    <div class="dd">{{ $customer->created_at ? $customer->created_at->format('d M Y') : '—' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Summary</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Total Orders</div>
                    <div class="dd">{{ $stats['total_orders'] }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Total Spent</div>
                    <div class="dd"><span class="rupee">₹</span>{{ number_format($stats['total_spent'], 2) }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Last Order</div>
                    <div class="dd">{{ $stats['last_order'] ? $stats['last_order']->format('d M Y') : '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Addresses --}}
    @if($customer->addresses->count())
    <div class="card">
        <div class="card-title">Addresses</div>
        <div class="detail-grid">
            @foreach($customer->addresses as $addr)
                <div class="detail-item">
                    <div class="dt">{{ $addr->address_type ?? 'Address' }} {{ $addr->is_default ? '(Default)' : '' }}</div>
                    <div class="dd">{{ $addr->full_address }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Orders --}}
    <div class="table-wrap">
        <div class="table-header">
            <h2>Orders ({{ $orders->count() }})</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="font-weight: 500;">{{ $order->order_id }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($order->grand_total ?? $order->total_price, 2) }}</td>
                        <td><span class="status-text {{ $order->status }}">{{ $order->status }}</span></td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-link">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">No orders yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
