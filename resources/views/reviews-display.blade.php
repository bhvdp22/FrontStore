<!-- Product Reviews Section -->
<div style="margin-top: 40px; padding: 20px; background-color: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h2 style="color: #002e36; font-size: 24px; margin-bottom: 20px; border-bottom: 2px solid #ff9900; padding-bottom: 10px;">Customer Reviews</h2>

    <!-- Review Statistics -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <!-- Average Rating -->
        <div style="text-align: center; padding: 20px; background-color: #f7fafa; border-radius: 4px;">
            <div style="font-size: 48px; font-weight: bold; color: #ff9900; margin-bottom: 10px;">
                {{ number_format($averageRating, 1) }}
            </div>
            <div style="margin-bottom: 10px;">
                <span style="color: #ff9900; font-size: 20px;">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($averageRating))
                            ★
                        @elseif($i - 0.5 <= round($averageRating, 1))
                            ⯨
                        @else
                            ☆
                        @endif
                    @endfor
                </span>
            </div>
            <p style="color: #555; font-size: 14px; margin: 0;">
                Based on {{ $reviewCount }} reviews
            </p>
        </div>

        <!-- Rating Distribution -->
        <div style="padding: 20px; background-color: #f7fafa; border-radius: 4px;">
            @for($rating = 5; $rating >= 1; $rating--)
                @php
                    $count = $ratingDistribution[$rating] ?? 0;
                    $percentage = $reviewCount > 0 ? ($count / $reviewCount * 100) : 0;
                @endphp
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <span style="color: #ff9900; font-size: 14px; width: 30px;">{{ $rating }}★</span>
                    <div style="flex: 1; height: 8px; background-color: #e0e0e0; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; background-color: #ff9900; width: {{ $percentage }}%;"></div>
                    </div>
                    <span style="color: #555; font-size: 12px; width: 30px; text-align: right;">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Inline Write Review Form (only for verified buyers) -->
    @php
        $customerEmail = session('customer_email');
        $hasPurchased = false;
        if ($customerEmail) {
            $hasPurchased = App\Models\Order::where('customer_email', $customerEmail)
                ->whereHas('items', function($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->exists();
        }
    @endphp
    @if($customerEmail)
        <div style="margin: 20px 0; padding: 20px; background-color: #f7fafa; border-radius: 4px;">
            <h3 style="color:#002e36; margin:0 0 10px;">Write a Review</h3>
            @if($hasPurchased)
                <div id="inlineReviewSuccess" style="display:none; padding:10px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:4px; margin-bottom:10px;">✓ Review submitted! It will appear after approval.</div>
                <div id="inlineReviewError" style="display:none; padding:10px; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:4px; margin-bottom:10px;"></div>
                <form id="inlineReviewForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @if(isset($orderId))
                        <input type="hidden" name="order_id" value="{{ $orderId }}">
                    @endif
                    <input type="hidden" name="rating" id="inlineRatingInput" value="5">
                    <div style="margin-bottom:12px;">
                        <div style="font-size:13px; color:#333; font-weight:600; margin-bottom:6px;">Your Rating *</div>
                        <div id="inlineRatingStars" style="display:flex; gap:6px; align-items:center;">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="inline-star-btn" data-rating="{{ $i }}" style="border:none; background:none; cursor:pointer; padding:0; color:#ff9900; font-size:22px; line-height:1;">{{ $i <= 5 ? '★' : '☆' }}</button>
                            @endfor
                            <span id="inlineRatingText" style="font-size:12px; color:#565959; margin-left:6px;">5/5</span>
                        </div>
                    </div>
                    <textarea name="review_text" placeholder="Share your experience with this product." required style="width:100%; padding:10px; border:1px solid #d5d9d9; border-radius:4px; min-height:100px;"></textarea>
                    <div style="margin-top:10px; text-align:right;">
                        <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
                    </div>
                </form>
            @else
                <p style="color:#565959; font-size:14px;">Only verified buyers can review this product. Complete a purchase to share your feedback.</p>
            @endif
        </div>
    @endif

    <!-- Reviews List -->
    <div id="reviewsList" style="margin-top: 30px;">
        @if($reviews->count() > 0)
            @foreach($reviews as $review)
                <div style="padding: 20px; border-bottom: 1px solid #e0e0e0; margin-bottom: 15px;">
                    <!-- Review Header -->
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <h4 style="margin: 0 0 5px 0; color: #333; font-size: 16px;">{{ $review->title }}</h4>
                            <p style="margin: 0; color: #999; font-size: 12px;">
                                by <strong>{{ $review->customer_name }}</strong> | {{ $review->created_at->format('M d, Y') }}
                                @if($review->verified_purchase)
                                    <span style="background-color: #d4edda; color: #155724; padding: 2px 8px; border-radius: 3px; font-size: 11px; margin-left: 10px;">✓ Verified Purchase</span>
                                @endif
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: #ff9900; font-size: 16px; margin-bottom: 5px;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <span style="color: #999; font-size: 12px;">{{ $review->rating }} out of 5 stars</span>
                        </div>
                    </div>

                    <!-- Review Text -->
                    <p style="color: #555; line-height: 1.6; margin: 15px 0;">{{ $review->review_text }}</p>

                    <!-- Helpful Section -->
                    <div style="display: flex; gap: 15px; padding-top: 10px; border-top: 1px solid #f0f0f0; color: #999; font-size: 13px;">
                        <button onclick="markHelpful({{ $review->id }})" class="btn btn-outline btn-sm" style="font-size:13px; padding:4px 12px;">Helpful</button>
                            👍 Helpful (<span id="helpful-{{ $review->id }}">{{ $review->helpful_count }}</span>)
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Load More Button -->
            @if(method_exists($reviews, 'hasMorePages') && $reviews->hasMorePages())
                <div style="text-align: center; padding: 20px;">
                    <button onclick="loadMoreReviews()" class="btn btn-secondary btn-sm">Load More</button>
                        Load More Reviews
                    </button>
                </div>
            @endif
        @else
            <div style="padding: 30px; text-align: center; background-color: #f7fafa; border-radius: 4px;">
                <p style="color: #999; margin: 0;">No reviews yet. Be the first to review this product!</p>
            </div>
        @endif
    </div>
</div>

<script>
function paintInlineStars(selectedRating) {
    document.querySelectorAll('.inline-star-btn').forEach((button) => {
        const starValue = parseInt(button.dataset.rating || '0', 10);
        button.textContent = starValue <= selectedRating ? '★' : '☆';
    });
    const text = document.getElementById('inlineRatingText');
    if (text) {
        text.textContent = `${selectedRating}/5`;
    }
}

document.querySelectorAll('.inline-star-btn').forEach((button) => {
    button.addEventListener('click', function() {
        const selectedRating = parseInt(this.dataset.rating || '5', 10);
        const ratingInput = document.getElementById('inlineRatingInput');
        if (ratingInput) {
            ratingInput.value = selectedRating;
        }
        paintInlineStars(selectedRating);
    });
});

paintInlineStars(parseInt(document.getElementById('inlineRatingInput')?.value || '5', 10));

document.getElementById('inlineReviewForm')?.addEventListener('submit', function(e){
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    fetch('{{ route('reviews.store') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(r => r.json())
    .then(data => {
        const ok = data && data.success;
        const successEl = document.getElementById('inlineReviewSuccess');
        const errEl = document.getElementById('inlineReviewError');
        if (ok) {
            errEl.style.display = 'none';
            successEl.style.display = 'block';
            form.reset();
            const ratingInput = document.getElementById('inlineRatingInput');
            if (ratingInput) {
                ratingInput.value = 5;
            }
            paintInlineStars(5);
        } else {
            successEl.style.display = 'none';
            errEl.textContent = (data && data.message) ? data.message : 'Error submitting review.';
            errEl.style.display = 'block';
        }
    })
    .catch(() => {
        const errEl = document.getElementById('inlineReviewError');
        errEl.textContent = 'Error submitting review. Please try again.';
        errEl.style.display = 'block';
    });
});

function markHelpful(reviewId) {
    fetch(`/reviews/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`helpful-${reviewId}`).textContent = data.helpful_count;
        }
    })
    .catch(error => console.error('Error:', error));
}

function loadMoreReviews() {
    // This would load next page of reviews via AJAX
    // Implementation depends on your pagination setup
}
</script>
