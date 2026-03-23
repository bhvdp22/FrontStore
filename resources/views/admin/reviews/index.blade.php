@extends('admin.layout')
@section('title', 'Reviews')
@section('header', 'Reviews')

@section('content')
    <h1 class="page-title">Reviews</h1>
    <p class="page-subtitle">Moderate customer reviews across all products</p>

    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Total Reviews</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending</div>
            <div class="value">{{ $stats['pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Approved</div>
            <div class="value">{{ $stats['approved'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Rejected</div>
            <div class="value">{{ $stats['rejected'] }}</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-row">
        <a href="{{ route('admin.reviews') }}" class="filter-pill {{ !$status ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.reviews', ['status' => 'pending']) }}" class="filter-pill {{ $status === 'pending' ? 'active' : '' }}">Pending</a>
        <a href="{{ route('admin.reviews', ['status' => 'approved']) }}" class="filter-pill {{ $status === 'approved' ? 'active' : '' }}">Approved</a>
        <a href="{{ route('admin.reviews', ['status' => 'rejected']) }}" class="filter-pill {{ $status === 'rejected' ? 'active' : '' }}">Rejected</a>
    </div>

    <div class="table-wrap">
        <div class="table-header">
            <h2>All Reviews</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->customer_name }}</td>
                        <td>{{ $review->product->name ?? '—' }}</td>
                        <td>
                            <span class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                @endfor
                            </span>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($review->title ?? $review->review_text, 40) }}</td>
                        <td><span class="status-text {{ $review->status }}">{{ $review->status }}</span></td>
                        <td>{{ $review->created_at->format('d M Y') }}</td>
                        <td class="text-right">
                            <div class="flex gap-6 items-center justify-end">
                                @if($review->status !== 'approved')
                                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                @endif
                                @if($review->status !== 'rejected')
                                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm">Reject</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty-state">No reviews found</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reviews->hasPages())
            <div class="pagination-wrap">{{ $reviews->appends(request()->query())->links() }}</div>
        @endif
    </div>
@endsection
