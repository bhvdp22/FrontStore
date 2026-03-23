<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Front Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f1f3f6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 16px; }

        /* ── Product Page Grid ──────────────────────── */
        .product-page {
            display: grid;
            grid-template-columns: 480px 1fr;
            gap: 24px;
            background: transparent;
        }

        /* ── LEFT: Gallery Section (Sticky) ─────────── */
        .gallery-section {
            position: sticky;
            top: 80px;
            align-self: start;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 16px;
        }

        .gallery-layout {
            display: grid;
            grid-template-columns: 64px 1fr;
            gap: 12px;
        }

        /* Vertical Thumbnails */
        .thumb-strip {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 420px;
            overflow-y: auto;
        }

        .thumb-strip::-webkit-scrollbar { width: 3px; }
        .thumb-strip::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }

        .thumb {
            width: 58px;
            height: 58px;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            cursor: pointer;
            overflow: hidden;
            flex-shrink: 0;
            transition: border-color 0.2s;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumb:hover { border-color: #2874f0; }
        .thumb.active { border-color: #2874f0; box-shadow: 0 0 0 1px #2874f0; }
        .thumb img { max-width: 52px; max-height: 52px; object-fit: contain; }

        /* Main Image Display */
        .main-image-wrapper {
            position: relative;
            background: #fff;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            overflow: hidden;
            cursor: crosshair;
        }

        .main-image-wrapper img {
            max-width: 100%;
            max-height: 420px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .main-image-wrapper:hover img { transform: scale(1.05); }

        .sponsored-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.95);
            border: 1px solid #bbb;
            border-radius: 3px;
            padding: 3px 8px;
            font-size: 11px;
            color: #666;
            font-weight: 600;
        }

        .share-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #666;
            font-size: 14px;
            transition: all 0.2s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .share-btn:hover { background: #f5f5f5; color: #2874f0; }

        /* Image Counter */
        .img-counter {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(0,0,0,0.6);
            color: #fff;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 10px;
        }

        /* ── RIGHT: Product Details Section ─────────── */
        .details-section {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 24px;
        }

        .breadcrumb {
            font-size: 12px;
            color: #878787;
            margin-bottom: 10px;
        }
        .breadcrumb a { color: #2874f0; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        .product-title {
            font-size: 18px;
            font-weight: 600;
            color: #212121;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .meta-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }

        .meta-info { font-size: 13px; color: #878787; }
        .meta-divider { color: #e0e0e0; }

        .seller-info {
            font-size: 13px;
            color: #878787;
            margin-bottom: 12px;
        }
        .seller-name { color: #2874f0; font-weight: 600; }
        .seller-reputation {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
        .seller-reputation .score { font-weight: 800; }
        .rep-trusted { background: #e8f5e9; color: #1b5e20; }
        .rep-reliable { background: #e3f2fd; color: #0d47a1; }
        .rep-growing { background: #fff8e1; color: #8d6e63; }
        .rep-new { background: #f3e5f5; color: #6a1b9a; }
        .rep-attention { background: #ffebee; color: #b71c1c; }

        /* Rating Badge */
        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #388e3c;
            color: #fff;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        .rating-badge i { font-size: 10px; }
        .rating-count { font-size: 13px; color: #878787; margin-left: 8px; }

        /* Price Section */
        .price-section { margin: 16px 0; padding: 16px 0; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; }
        .price-main {
            font-size: 28px;
            font-weight: 700;
            color: #212121;
        }
        .price-main .rupee { font-family: 'Poppins', system-ui; }

        .stock-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 6px;
            flex-wrap: wrap;
        }
        .stock-in { color: #388e3c; }
        .stock-out { color: #ff6161; }
        .stock-low { color: #c45500; }
        .stock-urgency {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            font-weight: 700;
            margin-left: 6px;
            color: #c45500;
        }

        /* Action Buttons */
        .action-buttons { display: flex; gap: 12px; margin: 20px 0; }
        .btn-cart {
            flex: 1;
            padding: 14px 20px;
            background: #ff9f00;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-cart:hover { background: #e89200; }
        .btn-cart:disabled { background: #ccc; cursor: not-allowed; }
        .btn-cart i { font-size: 18px; }

        .btn-buy {
            flex: 1;
            padding: 14px 20px;
            background: #fb641b;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-buy:hover { background: #e85d19; }

        /* Features Card */
        .features-card {
            margin-top: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .features-header {
            padding: 14px 18px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 700;
            font-size: 15px;
            color: #212121;
            background: #fafafa;
        }
        .features-list { padding: 14px 18px 14px 36px; }
        .features-list li {
            margin: 8px 0;
            color: #212121;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Highlights */
        .highlights { margin-top: 20px; }
        .highlights-title { font-size: 15px; font-weight: 700; color: #212121; margin-bottom: 10px; }
        .highlight-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin: 6px 0;
            font-size: 14px;
            color: #212121;
        }
        .highlight-item i { color: #878787; font-size: 6px; margin-top: 7px; flex-shrink: 0; }

        /* Back Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #2874f0;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-top: 16px;
        }
        .back-link:hover { text-decoration: underline; }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #212121;
            color: #fff;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            z-index: 9999;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .toast.show { transform: translateX(-50%) translateY(0); }

        /* Responsive */
        @media (max-width: 900px) {
            .product-page { grid-template-columns: 1fr; }
            .gallery-section { position: static; }
            .gallery-layout { grid-template-columns: 50px 1fr; }
            .thumb { width: 46px; height: 46px; }
            .thumb img { max-width: 40px; max-height: 40px; }
            .main-image-wrapper { min-height: 300px; }
            .main-image-wrapper img { max-height: 300px; }
            .action-buttons { flex-direction: column; }
        }

        @media (max-width: 500px) {
            .gallery-layout { grid-template-columns: 1fr; }
            .thumb-strip { flex-direction: row; overflow-x: auto; max-height: none; }
        }
    </style>
</head>
<body>
    @include('shop.partials.navbar')

    <div class="container">
        @php
            $allImages = $product->all_images;
            if (empty($allImages)) {
                $allImages = [$product->img_path ?: 'https://placehold.co/400x400?text=No+Image'];
            }
        @endphp

        <div class="product-page">
            {{-- ── LEFT: Gallery ──────────────────────── --}}
            <div class="gallery-section">
                <div class="gallery-layout">
                    {{-- Vertical Thumbnails --}}
                    <div class="thumb-strip">
                        @foreach($allImages as $index => $img)
                            <div class="thumb {{ $index === 0 ? 'active' : '' }}" 
                                 onmouseenter="switchImage({{ $index }})" 
                                 onclick="switchImage({{ $index }})"
                                 id="thumb-{{ $index }}">
                                <img src="{{ asset($img) }}" alt="{{ $product->name }}" 
                                     onerror="this.src='https://placehold.co/60x60?text=No+Img'">
                            </div>
                        @endforeach
                    </div>

                    {{-- Main Image --}}
                    <div class="main-image-wrapper" id="mainImageWrapper">
                        @if(!empty($product->is_sponsored))
                            <div class="sponsored-badge">Sponsored</div>
                        @endif
                        <button class="share-btn" onclick="shareProduct()" title="Share">
                            <i class="fas fa-share-alt"></i>
                        </button>
                        <img src="{{ asset($allImages[0]) }}" 
                             alt="{{ $product->name }}" 
                             id="mainImage"
                             onerror="this.src='https://placehold.co/400x400?text=No+Image'">
                        @if(count($allImages) > 1)
                            <div class="img-counter">
                                <span id="currentImgNum">1</span>/{{ count($allImages) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Product Details ─────────────── --}}
            <div class="details-section">
                {{-- Breadcrumb --}}
                <div class="breadcrumb">
                    <a href="{{ route('shop.index') }}">Home</a> &rsaquo;
                    @if($product->category)
                        <a href="{{ route('shop.index', ['category' => $product->category->slug ?? '']) }}">{{ $product->category->name }}</a> &rsaquo;
                    @endif
                    <span>{{ $product->name }}</span>
                </div>

                {{-- Title --}}
                <h1 class="product-title">{{ $product->name }}</h1>

                {{-- Meta --}}
                <div class="meta-row">
                    <span class="meta-info">SKU: {{ $product->sku }}</span>
                    <span class="meta-divider">|</span>
                    <span class="meta-info">ASIN: {{ $product->asin ?? 'N/A' }}</span>
                </div>

                {{-- Seller --}}
                <div class="seller-info">
                    Sold by:
                    @if($product->seller->storefront_enabled && $product->seller->slug)
                        <a href="{{ route('seller.storefront', $product->seller->slug) }}" class="seller-name" style="text-decoration:none;">{{ $product->seller->business_name ?? $product->seller->name ?? 'Marketplace Seller' }}</a>
                    @else
                        <span class="seller-name">{{ $product->seller->business_name ?? $product->seller->name ?? 'Marketplace Seller' }}</span>
                    @endif
                </div>

                @php
                    $sellerBadge = $product->seller->seller_reputation_badge ?? 'New Seller';
                    $sellerScore = $product->seller->seller_reputation_score;
                    $sellerBadgeClass = match ($sellerBadge) {
                        'Trusted Seller' => 'rep-trusted',
                        'Reliable' => 'rep-reliable',
                        'Growing Seller' => 'rep-growing',
                        'Needs Attention' => 'rep-attention',
                        default => 'rep-new',
                    };
                @endphp
                <div class="seller-reputation {{ $sellerBadgeClass }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>{{ $sellerBadge }}</span>
                    @if($sellerScore !== null)
                        <span class="score">{{ $sellerScore }}/100</span>
                    @endif
                </div>

                {{-- Rating --}}
                @php
                    $avgRating = $product->getAverageRating() ?? 0;
                    $reviewCount = $product->getReviewCount();
                @endphp
                @if($reviewCount > 0)
                    <div class="meta-row" style="margin-bottom: 12px;">
                        <span class="rating-badge">
                            {{ number_format($avgRating, 1) }} <i class="fas fa-star"></i>
                        </span>
                        <span class="rating-count">{{ $reviewCount }} {{ $reviewCount === 1 ? 'Rating' : 'Ratings' }}</span>
                    </div>
                @endif

                {{-- Price --}}
                <div class="price-section">
                    <div class="price-main">
                        <span class="rupee">₹</span>{{ number_format($product->price, 0) }}
                    </div>
                    <div class="stock-status {{ $product->quantity <= 0 ? 'stock-out' : ($product->quantity <= 5 ? 'stock-low' : 'stock-in') }}">
                        <i class="fas {{ $product->quantity > 0 ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $product->quantity > 0 ? 'In stock' : 'Currently unavailable' }}
                        @if($product->quantity > 0 && $product->quantity <= 5)
                            <span class="stock-urgency"><i class="fas fa-bolt"></i> Only {{ $product->quantity }} left!</span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="action-buttons">
                    <form class="add-to-cart-form" method="POST" action="{{ route('shop.addToCart') }}" style="flex:1; display:flex;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn-cart" {{ $product->quantity > 0 ? '' : 'disabled' }}>
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                </div>

                {{-- Key Features --}}
                <div class="features-card">
                    <div class="features-header"><i class="fas fa-list-ul" style="margin-right: 8px; color: #878787;"></i> Key Features</div>
                    <ul class="features-list">
                        @php
                            $desc = $product->description ?? '';
                            $parts = preg_split('/[\r\n;•]+/', $desc);
                            $features = array_filter(array_map('trim', $parts));
                            if(empty($features)) { $features = ['No description available.']; }
                        @endphp
                        @foreach($features as $f)
                            @if(!empty($f))
                                <li>{{ $f }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <a href="{{ route('shop.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Results
                </a>
            </div>
        </div>

        <!-- Reviews Section -->
        @php
            $averageRating = $product->getAverageRating() ?? 0;
            $reviewCountTotal = $product->getReviewCount();
            
            $reviews = App\Models\Review::where('product_id', $product->id)
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            $ratingDistribution = [];
            for ($i = 5; $i >= 1; $i--) {
                $ratingDistribution[$i] = App\Models\Review::where('product_id', $product->id)
                    ->where('rating', $i)
                    ->where('status', 'approved')
                    ->count();
            }
        @endphp

        @include('reviews-display', [
            'product' => $product,
            'averageRating' => $averageRating,
            'reviewCount' => $reviewCountTotal,
            'reviews' => $reviews,
            'ratingDistribution' => $ratingDistribution,
            'orderId' => session('last_order_id') ?? null
        ])
    </div>

    @include('shop.partials.footer')

    {{-- Toast notification --}}
    <div class="toast" id="toast"></div>

    <script>
        // ── Image Gallery Controls ──────────────────
        const allImages = @json($allImages);
        let currentIndex = 0;

        function switchImage(index) {
            currentIndex = index;
            const mainImg = document.getElementById('mainImage');
            mainImg.src = '{{ asset("") }}' + allImages[index];
            mainImg.onerror = function() { this.src = 'https://placehold.co/400x400?text=No+Image'; };

            // Update active thumb
            document.querySelectorAll('.thumb').forEach((t, i) => {
                t.classList.toggle('active', i === index);
            });

            // Update counter
            const counter = document.getElementById('currentImgNum');
            if (counter) counter.textContent = index + 1;
        }

        // Keyboard navigation for images
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                e.preventDefault();
                switchImage(Math.max(0, currentIndex - 1));
            } else if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                e.preventDefault();
                switchImage(Math.min(allImages.length - 1, currentIndex + 1));
            }
        });

        // Share product
        function shareProduct() {
            if (navigator.share) {
                navigator.share({ title: '{{ $product->name }}', url: window.location.href });
            } else {
                navigator.clipboard.writeText(window.location.href);
                showToast('Link copied to clipboard!');
            }
        }

        // Toast notification
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 2500);
        }

        // Cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof cartCount !== 'undefined') {
                var badge = document.getElementById('cart-count');
                if (badge) badge.textContent = cartCount;
            }
        });

        // AJAX add-to-cart
        document.querySelector('.add-to-cart-form')?.addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);
            const btn = this.querySelector('button');
            const origHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            btn.disabled = true;

            fetch('{{ route('shop.addToCart') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData
            }).then(r => r.json()).then(data => {
                btn.innerHTML = origHTML;
                btn.disabled = false;
                if(data.success && typeof data.cartCount !== 'undefined'){
                    var badge = document.getElementById('cart-count');
                    if (badge) badge.textContent = data.cartCount;
                    showToast('✓ Added to cart successfully!');
                }
            }).catch(() => {
                btn.innerHTML = origHTML;
                btn.disabled = false;
            });
        });
    </script>
</body>
</html>
