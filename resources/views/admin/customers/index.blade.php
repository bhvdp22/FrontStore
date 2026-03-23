@extends('admin.layout')
@section('title', 'Customers')
@section('header', 'Customers')

@section('content')
    <h1 class="page-title">Customers</h1>
    <p class="page-subtitle">All registered customers on the platform</p>

    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Total Customers</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">With Orders</div>
            <div class="value">{{ $stats['with_orders'] }}</div>
        </div>
    </div>

    {{-- Search --}}
    <div class="table-wrap">
        <div class="table-header">
            <h2>All Customers</h2>
            <form method="GET" action="{{ route('admin.customers') }}" class="flex gap-8 items-center">
                <input type="text" name="search" class="form-control" style="width: 240px;" placeholder="Search name or email..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-sm">Search</button>
                @if($search ?? false)
                    <a href="{{ route('admin.customers') }}" class="btn-link">Clear</a>
                @endif
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td style="font-weight: 500;">{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->created_at ? $customer->created_at->format('d M Y') : '—' }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn-link">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty-state">No customers found</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($customers->hasPages())
            <div class="pagination-wrap">{{ $customers->appends(request()->query())->links() }}</div>
        @endif
    </div>
@endsection
