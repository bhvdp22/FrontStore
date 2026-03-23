@extends('admin.layout')
@section('title', 'Seller Management')
@section('header', 'Seller Management')

@section('content')
    <h1 class="page-title">Sellers</h1>
    <p class="page-subtitle">Manage seller accounts and approvals</p>

    {{-- ── Counts ───────────────────────────────────── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Total</div>
            <div class="value">{{ $counts['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Active</div>
            <div class="value">{{ $counts['active'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending</div>
            <div class="value">{{ $counts['pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Banned</div>
            <div class="value">{{ $counts['banned'] }}</div>
        </div>
    </div>

    {{-- ── Seller Table ─────────────────────────────── --}}
    <div class="table-wrap">
        <div class="table-header">
            <h2>All Sellers</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Business</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sellers as $seller)
                    <tr>
                        <td>
                            <a href="{{ route('admin.sellers.show', $seller->id) }}" class="btn-link">{{ $seller->name }}</a>
                        </td>
                        <td>{{ $seller->business_name ?? '—' }}</td>
                        <td>{{ $seller->email }}</td>
                        <td><span class="status-text {{ $seller->status }}">{{ $seller->status }}</span></td>
                        <td>{{ $seller->created_at ? $seller->created_at->format('d M Y') : '—' }}</td>
                        <td class="text-right">
                            <div class="flex items-center gap-8" style="justify-content:flex-end;">
                                @if($seller->status !== 'active')
                                    <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm">Approve</button>
                                    </form>
                                @endif
                                @if($seller->status !== 'banned')
                                    <form action="{{ route('admin.sellers.ban', $seller->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Ban</button>
                                    </form>
                                @endif
                                @if($seller->status === 'active' || $seller->status === 'banned')
                                    <form action="{{ route('admin.sellers.pending', $seller->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm">Set Pending</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-state">No sellers found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
