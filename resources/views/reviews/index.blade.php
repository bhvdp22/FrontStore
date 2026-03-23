@extends('layouts.seller')

@section('title', 'Manage Reviews - Seller Central')

@section('extra_styles')
<style>
    .page-header { padding: 15px 20px 0 20px; border-bottom: 1px solid #ddd; }
    .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .page-title { font-size: 24px; font-weight: 700; color: #0f1111; }

    .table-wrapper { padding: 20px; }
    .reviews-table { width: 100%; border-collapse: collapse; border: 1px solid #eaeded; background: #fff; }
    .reviews-table thead { background: #fafafa; border-bottom: 1px solid #eaeded; }
    .reviews-table th {
        text-align: left; padding: 12px; color: #555; font-size: 12px; font-weight: 700;
        text-transform: uppercase; border-right: 1px solid #eaeded;
    }
    .reviews-table tr { border-bottom: 1px solid #eaeded; }
    .reviews-table td { padding: 12px; vertical-align: top; color: #0f1111; }

    .review-product { font-weight: 700; color: #007185; margin-bottom: 4px; }
    .review-customer { color: #565959; font-size: 12px; }
    .review-text { margin-bottom: 4px; }
    .stars { color: #ff9900; }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-approved { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }

    .col-actions { width: 100px; text-align: center; position: relative; }
    .kebab-btn {
        background: #fff; border: 1px solid #d5d9d9; border-radius: 3px; padding: 4px 8px;
        cursor: pointer; box-shadow: 0 2px 5px rgba(213,217,217,.5);
    }
    .row-actions {
        position: absolute; right: 10px; top: 36px; background: #fff; border: 1px solid #d5d9d9; border-radius: 4px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12); display: none; min-width: 160px; z-index: 10;
    }
    .row-actions a, .row-actions button {
        display: block; width: 100%; text-align: left; padding: 8px 12px; background: #fff; border: none; cursor: pointer; font-size: 13px;
        color: #111; text-decoration: none;
    }
    .row-actions a:hover, .row-actions button:hover { background: #f7fafa; text-decoration: none; }
    .row-actions .approve-btn { color: #007600; }
    .row-actions .reject-btn { color: #c45500; }
    .row-actions .delete-btn { color: #c40000; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="top-row">
        <div style="display:flex; align-items:baseline; gap:10px;">
            <div class="page-title">Manage Product Reviews</div>
        </div>
    </div>
</div>

<div class="table-wrapper">
    <div style="margin: 10px 0 20px 0; font-size:12px; color:#555; display:flex; gap:20px;">
        <div>
            <label style="font-weight: 600;">Filter by Status:</label>
            <select id="filterStatus" onchange="filterReviews()" style="padding: 6px; margin-left: 8px; border: 1px solid #888C8C; border-radius: 3px;">
                <option value="">All Reviews</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div>
            <label style="font-weight: 600;">Filter by Rating:</label>
            <select id="filterRating" onchange="filterReviews()" style="padding: 6px; margin-left: 8px; border: 1px solid #888C8C; border-radius: 3px;">
                <option value="">All Ratings</option>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
        </div>
    </div>

    @if($reviews->count() > 0)
        <div style="margin-bottom: 10px; font-size:12px; color:#555;"><b>{{ $reviews->total() }}</b> reviews</div>

        <table class="reviews-table">
            <thead>
                <tr>
                    <th>Product & Customer</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr data-status="{{ $review->status }}" data-rating="{{ $review->rating }}">
                    <td>
                        <div class="review-product">{{ $review->product->name ?? 'N/A' }}</div>
                        <div class="review-customer">{{ $review->customer_name }}</div>
                        <div class="review-customer">{{ $review->customer_email }}</div>
                        @if($review->verified_purchase)
                            <div class="review-customer" style="color: #155724; font-weight: 600;">✓ Verified Purchase</div>
                        @endif
                    </td>
                    <td>
                        <div class="review-text" style="font-weight: 600;">{{ $review->title }}</div>
                        <div class="review-customer">{{ \Illuminate\Support\Str::limit($review->review_text, 80) }}</div>
                    </td>
                    <td>
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </div>
                        <div class="review-customer">{{ $review->rating }}/5</div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ strtolower($review->status) }}">
                            {{ ucfirst($review->status) }}
                        </span>
                    </td>
                    <td class="review-customer">
                        {{ $review->created_at->format('M d, Y') }}
                    </td>
                    <td class="col-actions">
                        <button class="kebab-btn" onclick="toggleRowActions(event, '{{ $review->id }}')">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="row-actions" id="row-actions-{{ $review->id }}" onclick="event.stopPropagation()">
                            @if($review->status !== 'approved')
                                <a href="#" class="approve-btn" onclick="approveReview(event, {{ $review->id }})">
                                    <i class="fas fa-check"></i> Approve
                                </a>
                            @endif
                            @if($review->status !== 'rejected')
                                <a href="#" class="reject-btn" onclick="rejectReview(event, {{ $review->id }})">
                                    <i class="fas fa-times"></i> Reject
                                </a>
                            @endif
                            <a href="#" class="delete-btn" onclick="deleteReview(event, {{ $review->id }})">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(method_exists($reviews, 'links'))
            <div style="margin-top: 20px;">
                {{ $reviews->links() }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 40px; color: #565959;">
            <i class="fas fa-comments" style="font-size: 48px; color: #ccc; display: block; margin-bottom: 15px;"></i>
            <p>No reviews yet. Customer reviews will appear here once you receive them.</p>
        </div>
    @endif
</div>
@endsection

@section('extra_scripts')
    const csrfToken = '{{ csrf_token() }}';

    function toggleRowActions(e, rid) {
        e.stopPropagation();
        const id = 'row-actions-' + rid;
        const menu = document.getElementById(id);

        document.querySelectorAll('.row-actions').forEach(function(m){ if(m.id !== id) m.style.display = 'none'; });
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }

    document.addEventListener('click', function(){
        document.querySelectorAll('.row-actions').forEach(function(m){ m.style.display = 'none'; });
    });

    function filterReviews() {
        const status = document.getElementById('filterStatus').value;
        const rating = document.getElementById('filterRating').value;

        document.querySelectorAll('.reviews-table tbody tr').forEach(row => {
            let show = true;
            if (status && row.dataset.status !== status) show = false;
            if (rating && row.dataset.rating !== rating) show = false;
            row.style.display = show ? 'table-row' : 'none';
        });
    }

    function approveReview(e, id) {
        e.preventDefault();
        fetch(`/reviews/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                alert('Review approved!');
                location.reload();
            }
        }).catch(err => alert('Error: ' + err.message));
    }

    function rejectReview(e, id) {
        e.preventDefault();
        fetch(`/reviews/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                alert('Review rejected!');
                location.reload();
            }
        }).catch(err => alert('Error: ' + err.message));
    }

    function deleteReview(e, id) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this review?')) {
            fetch(`/reviews/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    alert('Review deleted!');
                    location.reload();
                }
            }).catch(err => alert('Error: ' + err.message));
        }
    }
@endsection
