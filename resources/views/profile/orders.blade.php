<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }

        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 50%, #485769 100%);
            border-radius: 16px;
            padding: 35px 40px;
            margin-bottom: 25px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,189,105,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            margin-bottom: 15px;
            position: relative;
        }
        .breadcrumb a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .breadcrumb a:hover { color: #febd69; }
        .breadcrumb span { color: rgba(255,255,255,0.5); }
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        .page-header h1 i { color: #febd69; }
        .page-header .user-email {
            font-size: 14px;
            opacity: 0.85;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Stats Bar */
        .stats-bar {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px 25px;
            flex: 1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .stat-icon.orange { background: linear-gradient(135deg, #ff9900, #febd69); color: white; }
        .stat-icon.green { background: linear-gradient(135deg, #007600, #00a32a); color: white; }
        .stat-icon.blue { background: linear-gradient(135deg, #232f3e, #37475a); color: white; }
        .stat-icon.purple { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .stat-content h3 { font-size: 24px; font-weight: 700; color: #0f1111; }
        .stat-content p { font-size: 13px; color: #565959; }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        .alert-error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border: 1px solid #86efac;
        }

        /* Order Card */
        .order-card {
            background: white;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border: 1px solid #d5d9d9;
            overflow: hidden;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            background: #f0f2f2;
            border-bottom: 1px solid #d5d9d9;
        }
        .order-id {
            font-size: 13px;
            color: #565959;
        }
        .order-id span { color: #007185; font-weight: 500; }
        .order-date {
            font-size: 13px;
            color: #565959;
        }
        .order-date span { color: #0f1111; }

        /* Status Badges - Hidden in minimal design */
        .status-badge { display: none; }

        /* Order Items */
        .order-items { padding: 20px; }

        .order-item {
            display: flex;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child { border-bottom: none; }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 4px;
            border: 1px solid #eee;
            background: #fff;
        }
        .item-image-placeholder {
            width: 80px;
            height: 80px;
            background: #f7f7f7;
            border-radius: 4px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .item-image-placeholder i { color: #ccc; font-size: 24px; }

        .item-details { flex: 1; }
        .item-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f1111;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        .item-name:hover { color: #c7511f; cursor: pointer; text-decoration: underline; }
        .item-delivery {
            color: #007600;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .item-delivery.pending { color: #b12704; }
        .item-delivery.shipped { color: #007185; }
        .view-details {
            color: #007185;
            font-size: 13px;
            text-decoration: none;
        }
        .view-details:hover { text-decoration: underline; color: #c7511f; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-icon {
            width: 80px;
            height: 80px;
            background: #f0f2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .empty-icon i { font-size: 32px; color: #8d9096; }
        .empty-state h3 {
            color: #0f1111;
            font-size: 18px;
            margin-bottom: 8px;
        }
        .empty-state p {
            color: #565959;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .empty-state a {
            color: #007185;
            text-decoration: none;
        }
        .empty-state a:hover { text-decoration: underline; color: #c7511f; }

        /* Review Box */
        .review-box {
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #e7e7e7;
            border-radius: 8px;
            background: #fafafa;
        }
        .review-box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .review-box-header strong {
            color: #0f1111;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .review-box-header strong i { color: #ff9900; }
        .review-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }
        .review-star-btn {
            border: none;
            background: none;
            padding: 0;
            font-size: 20px;
            line-height: 1;
            color: #ff9900;
            cursor: pointer;
        }
        .review-rating-text {
            font-size: 12px;
            color: #565959;
            margin-left: 4px;
            font-weight: 600;
        }
        .review-box textarea {
            width: 100%;
            border: 1px solid #d5d9d9;
            border-radius: 4px;
            padding: 10px;
            min-height: 80px;
            font-family: inherit;
            font-size: 13px;
            resize: vertical;
        }
        .review-box textarea:focus {
            outline: none;
            border-color: #007185;
            box-shadow: 0 0 0 2px rgba(0,113,133,0.1);
        }
        .review-box-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .btn-review {
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            color: #0f1111;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-review:hover {
            background: linear-gradient(180deg, #f7ca00 0%, #e6b800 100%);
        }
        .review-msg {
            margin-top: 10px;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
            display: none;
        }
        .review-msg.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }
        .review-msg.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        @media (max-width: 768px) {
            .stats-bar { flex-direction: column; }
            .page-header { padding: 25px; }
            .page-header h1 { font-size: 22px; }
            .order-header { flex-direction: column; gap: 8px; align-items: flex-start; }
            .order-item { flex-direction: row; }
            .item-image, .item-image-placeholder { width: 60px; height: 60px; }
        }
    </style>
</head>
<body>

    @include('shop.partials.navbar')

    <div class="container">

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb">
                <a href="{{ route('shop.index') }}"><i class="fas fa-home"></i> Home</a>
                <span>/</span>
                <a href="{{ route('profile.index') }}">My Account</a>
                <span>/</span>
                <span style="color: #febd69;">My Orders</span>
            </div>
            <h1><i class="fas fa-box"></i> My Orders</h1>
            <p class="user-email"><i class="fas fa-envelope"></i> {{ $email }}</p>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-content">
                    <h3>{{ count($orders) }}</h3>
                    <p>Total Orders</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-clock"></i></div>
                <div class="stat-content">
                    <h3>{{ $orders->flatten()->where('status', 'Unshipped')->count() }}</h3>
                    <p>Pending</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-truck"></i></div>
                <div class="stat-content">
                    <h3>{{ $orders->flatten()->where('status', 'Shipped')->count() }}</h3>
                    <p>Shipped</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-content">
                    <h3>{{ $orders->flatten()->where('status', 'Delivered')->count() }}</h3>
                    <p>Delivered</p>
                </div>
            </div>
        </div>

        @if($orders->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3>No Orders Yet</h3>
                    <p>You haven't placed any orders yet.</p>
                    <a href="{{ route('shop.index') }}">Start Shopping</a>
                </div>
        @else
            @foreach($orders as $orderId => $items)
                @php
                    $firstItem = $items->first();
                    $totalAmount = $items->sum('total_price');
                    $allStatuses = $items->pluck('status')->unique();
                    $hasMixedStatus = $allStatuses->count() > 1;
                @endphp

                <div class="order-card">
                    <div class="order-header">
                        <div class="order-id">Order ID:<span>{{ $orderId }}</span></div>
                        <div class="order-date">Order Date:<span>{{ $firstItem->created_at->timezone(config('app.timezone'))->format('j F Y') }}</span></div>
                    </div>

                    <div class="order-items">
                        @foreach($items as $item)
                            <div class="order-item">
                                @if($item->img_path)
                                    <img src="{{ $item->img_path }}" alt="{{ $item->product_name }}" class="item-image">
                                @else
                                    <div class="item-image-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif

                                <div class="item-details">
                                    <div class="item-name">{{ $item->product_name }}</div>
                                    @php $itemStatus = $item->status; @endphp
                                    @if($itemStatus == 'Delivered')
                                        <div class="item-delivery">Delivered on {{ $item->updated_at->timezone(config('app.timezone'))->format('j F Y') }}</div>
                                    @elseif($itemStatus == 'Shipped')
                                        <div class="item-delivery shipped">Shipped - On the way</div>
                                    @else
                                        <div class="item-delivery pending">Processing your order</div>
                                    @endif
                                    <a href="{{ route('profile.track', $orderId) }}" class="view-details">View Details</a>
                                </div>
                            </div>

                            @php
                                $product = $productsBySku[$item->sku] ?? null;
                            @endphp

                            @if($item->status === 'Delivered' && $product)
                                <div class="review-box">
                                    <div class="review-box-header">
                                        <strong><i class="fas fa-star"></i> Write a Review</strong>
                                    </div>
                                    <form class="review-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="order_id" value="{{ $orderId }}">
                                        <input type="hidden" name="rating" value="5" class="review-rating-input">
                                        <div class="review-rating">
                                            <button type="button" class="review-star-btn" data-rating="1" aria-label="1 star">☆</button>
                                            <button type="button" class="review-star-btn" data-rating="2" aria-label="2 stars">☆</button>
                                            <button type="button" class="review-star-btn" data-rating="3" aria-label="3 stars">☆</button>
                                            <button type="button" class="review-star-btn" data-rating="4" aria-label="4 stars">☆</button>
                                            <button type="button" class="review-star-btn" data-rating="5" aria-label="5 stars">☆</button>
                                            <span class="review-rating-text">5/5</span>
                                        </div>
                                        <textarea name="review_text" placeholder="Share your experience with this product..." required></textarea>
                                        <div class="review-box-actions">
                                            <button type="submit" class="btn-review">
                                                <i class="fas fa-paper-plane"></i> Submit Review
                                            </button>
                                        </div>
                                    </form>
                                    <div class="review-msg success"><i class="fas fa-check-circle"></i> Review submitted!</div>
                                    <div class="review-msg error"></div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

    </div>

<script>
function paintReviewStarsInBox(box, selectedRating) {
    box.querySelectorAll('.review-star-btn').forEach((starButton) => {
        const starValue = parseInt(starButton.dataset.rating || '0', 10);
        starButton.textContent = starValue <= selectedRating ? '★' : '☆';
    });

    const ratingText = box.querySelector('.review-rating-text');
    if (ratingText) {
        ratingText.textContent = `${selectedRating}/5`;
    }
}

document.querySelectorAll('.review-box').forEach((box) => {
    const ratingInput = box.querySelector('.review-rating-input');
    const defaultRating = parseInt(ratingInput?.value || '5', 10);
    paintReviewStarsInBox(box, defaultRating);

    box.querySelectorAll('.review-star-btn').forEach((starButton) => {
        starButton.addEventListener('click', function() {
            const selectedRating = parseInt(this.dataset.rating || '5', 10);
            if (ratingInput) {
                ratingInput.value = selectedRating;
            }
            paintReviewStarsInBox(box, selectedRating);
        });
    });
});

document.querySelectorAll('.review-form').forEach(form => {
    form.addEventListener('submit', function(e){
        e.preventDefault();
        const box = this.closest('.review-box');
        const successMsg = box.querySelector('.review-msg.success');
        const errorMsg = box.querySelector('.review-msg.error');
        successMsg.style.display = 'none';
        errorMsg.style.display = 'none';

        const formData = new FormData(this);
        fetch('{{ route('reviews.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                successMsg.style.display = 'block';
                this.reset();
                const ratingInput = box.querySelector('.review-rating-input');
                if (ratingInput) {
                    ratingInput.value = 5;
                }
                paintReviewStarsInBox(box, 5);
            } else {
                errorMsg.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Error submitting review.');
                errorMsg.style.display = 'block';
            }
        }).catch(() => {
            errorMsg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error submitting review.';
            errorMsg.style.display = 'block';
        });
    });
});
</script>
</body>
</html>
