@extends('admin.layout')
@section('title', 'Refunds')
@section('header', 'Refunds')

@section('content')
    <h1 class="page-title">Refunds</h1>
    <p class="page-subtitle">Track all refund transactions</p>

    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Total Refunds</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending</div>
            <div class="value">{{ $stats['pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Completed</div>
            <div class="value">{{ $stats['completed'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Total Refunded</div>
            <div class="value"><span class="rupee">₹</span>{{ number_format($stats['total_refunded'], 2) }}</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-row">
        <a href="{{ route('admin.refunds') }}" class="filter-pill {{ !$status ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.refunds', ['status' => 'pending']) }}" class="filter-pill {{ $status === 'pending' ? 'active' : '' }}">Pending</a>
        <a href="{{ route('admin.refunds', ['status' => 'processing']) }}" class="filter-pill {{ $status === 'processing' ? 'active' : '' }}">Processing</a>
        <a href="{{ route('admin.refunds', ['status' => 'completed']) }}" class="filter-pill {{ $status === 'completed' ? 'active' : '' }}">Completed</a>
        <a href="{{ route('admin.refunds', ['status' => 'failed']) }}" class="filter-pill {{ $status === 'failed' ? 'active' : '' }}">Failed</a>
    </div>

    <div class="table-wrap">
        <div class="table-header">
            <h2>All Refunds</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Refund #</th>
                    <th>Return #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Razorpay Ref ID</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($refunds as $refund)
                    <tr>
                        <td style="font-weight: 500;">{{ $refund->refund_number }}</td>
                        <td>
                            @if($refund->productReturn)
                                <a href="{{ route('admin.returns.show', $refund->return_id) }}" class="btn-link">{{ $refund->productReturn->return_number }}</a>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $refund->customer->name ?? '—' }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($refund->amount, 2) }}</td>
                        <td>{{ $refund->refund_method_label }}</td>
                        <td style="font-family: monospace; font-size: 12px;">{{ $refund->razorpay_refund_id ?? '—' }}</td>
                        <td><span class="status-text {{ $refund->status }}">{{ $refund->status_label }}</span></td>
                        <td>{{ $refund->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty-state">No refunds found</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($refunds->hasPages())
            <div class="pagination-wrap">{{ $refunds->appends(request()->query())->links() }}</div>
        @endif
    </div>
@endsection
