@extends('admin.layout')
@section('title', 'Returns')
@section('header', 'Returns')

@section('content')
    <h1 class="page-title">Return Requests</h1>
    <p class="page-subtitle">Manage product returns and authorize refunds</p>

    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Total Returns</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending Review</div>
            <div class="value">{{ $stats['pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Approved</div>
            <div class="value">{{ $stats['approved'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Refund Pending</div>
            <div class="value">{{ $stats['refund_pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Completed</div>
            <div class="value">{{ $stats['completed'] }}</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-row">
        <a href="{{ route('admin.returns') }}" class="filter-pill {{ !$status ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.returns', ['status' => 'pending']) }}" class="filter-pill {{ $status === 'pending' ? 'active' : '' }}">Pending</a>
        <a href="{{ route('admin.returns', ['status' => 'approved']) }}" class="filter-pill {{ $status === 'approved' ? 'active' : '' }}">Approved</a>
        <a href="{{ route('admin.returns', ['status' => 'received']) }}" class="filter-pill {{ $status === 'received' ? 'active' : '' }}">Received</a>
        <a href="{{ route('admin.returns', ['status' => 'refund_initiated']) }}" class="filter-pill {{ $status === 'refund_initiated' ? 'active' : '' }}">Refund Initiated</a>
        <a href="{{ route('admin.returns', ['status' => 'refund_completed']) }}" class="filter-pill {{ $status === 'refund_completed' ? 'active' : '' }}">Completed</a>
        <a href="{{ route('admin.returns', ['status' => 'rejected']) }}" class="filter-pill {{ $status === 'rejected' ? 'active' : '' }}">Rejected</a>
    </div>

    <div class="table-wrap">
        <div class="table-header">
            <h2>Returns</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Return #</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Reason</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                    <tr>
                        <td style="font-weight: 500;">{{ $return->return_number }}</td>
                        <td>{{ $return->customer->name ?? '—' }}</td>
                        <td>{{ $return->product->name ?? '—' }}</td>
                        <td>{{ $return->reason_label }}</td>
                        <td><span class="rupee">₹</span>{{ number_format($return->refund_amount, 2) }}</td>
                        <td><span class="status-text {{ $return->status }}">{{ $return->status_label }}</span></td>
                        <td>{{ $return->created_at->format('d M Y') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.returns.show', $return->id) }}" class="btn-link">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty-state">No return requests found</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($returns->hasPages())
            <div class="pagination-wrap">{{ $returns->appends(request()->query())->links() }}</div>
        @endif
    </div>
@endsection
