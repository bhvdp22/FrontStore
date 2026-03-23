<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #eaeded; min-height: 100vh; color: #0f1111; -webkit-font-smoothing: antialiased; }
        a { text-decoration: none; color: inherit; }

        /* ── Navbar ──────────────────────────────────────────── */
        .sf-nav {
            background: #131921;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .sf-nav-left { display: flex; align-items: center; gap: 16px; }
        .sf-nav-left .back-link { color: #ccc; font-size: 13px; transition: color .15s; }
        .sf-nav-left .back-link:hover { color: #fff; }
        .sf-nav-left .back-link i { margin-right: 5px; }
        .sf-nav-brand { color: #fff; font-size: 16px; font-weight: 600; letter-spacing: -0.3px; }
        .sf-nav-right { display: flex; align-items: center; gap: 20px; }
        .sf-nav-right a { color: #ddd; font-size: 13px; transition: color .15s; }
        .sf-nav-right a:hover { color: #febd69; }

        .wrap { max-width: 1140px; margin: 0 auto; padding: 0 16px; }

        /* ── Profile Header ──────────────────────────────────── */
        .profile-header {
            background: #fff;
            border-bottom: 1px solid #ddd;
            padding: 28px 0 0;
        }
        .profile-header .wrap {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .ph-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e8e8e8;
            flex-shrink: 0;
            background: #f4f4f4;
        }
        .ph-avatar-letter {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #232f3e;
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ph-details { flex: 1; min-width: 0; }
        .ph-name { font-size: 22px; font-weight: 700; color: #0f1111; line-height: 1.2; }
        .ph-meta { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 6px; font-size: 13px; color: #565959; }
        .ph-meta span i { margin-right: 4px; color: #888; }

        /* Tabs under header */
        .ph-tabs {
            display: flex;
            gap: 0;
            margin-top: 18px;
            border-bottom: none;
        }
        .ph-tab {
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: all .15s;
        }
        .ph-tab:hover { color: #0f1111; }
        .ph-tab.active { color: #c45500; border-bottom-color: #e47911; font-weight: 600; }

        /* ── Stats Row ───────────────────────────────────────── */
        .stats-row {
            background: #fff;
            border-bottom: 1px solid #ddd;
        }
        .stats-inner {
            display: flex;
            padding: 14px 0;
        }
        .stat-item {
            flex: 1;
            text-align: center;
            position: relative;
        }
        .stat-item + .stat-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 15%;
            height: 70%;
            width: 1px;
            background: #e0e0e0;
        }
        .stat-num { font-size: 20px; font-weight: 700; color: #0f1111; }
        .stat-num small { font-size: 12px; font-weight: 400; color: #888; }
        .stat-label { font-size: 11px; color: #888; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-badge {
            display: inline-block;
            margin-top: 4px;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
        }
        .sb-trusted { background: #f0faf6; color: #067d62; border: 1px solid #b7e4d3; }
        .sb-reliable { background: #eef5fc; color: #1a6fb5; border: 1px solid #b5d4f0; }
        .sb-growing { background: #fff8ec; color: #b36a00; border: 1px solid #fdd; }
        .sb-new { background: #f5f5f5; color: #666; border: 1px solid #ddd; }
        .sb-needs { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .stars-inline { color: #de7921; font-size: 12px; letter-spacing: -1px; }

        /* ── Content Area ────────────────────────────────────── */
        .content-area { padding: 20px 0 50px; }
        .content-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 20px;
            align-items: start;
        }

        /* Sidebar */
        .sidebar-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .sidebar-card + .sidebar-card { margin-top: 14px; }
        .sc-header {
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #0f1111;
            border-bottom: 1px solid #eee;
            background: #fafafa;
        }
        .sc-body { padding: 14px 16px; }
        .about-text { font-size: 13.5px; line-height: 1.65; color: #444; }
        .about-text p + p { margin-top: 10px; }
        .read-more-btn {
            display: inline-block;
            margin-top: 8px;
            font-size: 13px;
            color: #007185;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
            font-family: inherit;
        }
        .read-more-btn:hover { color: #c7511f; text-decoration: underline; }

        .social-row { display: flex; gap: 8px; margin-top: 10px; }
        .social-row a {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #555;
            background: #f0f0f0;
            transition: all .15s;
        }
        .social-row a:hover { background: #232f3e; color: #fff; }

        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; }
        .detail-row + .detail-row { border-top: 1px solid #f2f2f2; }
        .detail-row .dr-label { color: #888; }
        .detail-row .dr-value { font-weight: 500; color: #0f1111; }

        /* Products Main */
        .products-main {}
        .pm-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .pm-count { font-size: 14px; color: #555; }
        .pm-count strong { color: #0f1111; }
        .pm-sort select {
            padding: 7px 12px;
            font-size: 13px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fff;
            color: #333;
            font-family: inherit;
            cursor: pointer;
        }
        .pm-sort select:focus { outline: none; border-color: #e77600; box-shadow: 0 0 0 2px rgba(228,121,17,.15); }

        .p-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 14px;
        }
        .p-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            transition: box-shadow .2s;
            display: flex;
            flex-direction: column;
        }
        .p-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); }

        .p-card-img {
            width: 100%;
            height: 190px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f7f7;
            overflow: hidden;
            position: relative;
        }
        .p-card-img img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            mix-blend-mode: multiply;
        }
        .p-card-img .img-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #bbb;
        }
        .p-card-img .img-placeholder i { font-size: 36px; margin-bottom: 6px; }
        .p-card-img .img-placeholder span { font-size: 11px; }

        .p-card-body {
            padding: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .p-card-body .p-title {
            font-size: 13px;
            font-weight: 400;
            color: #0066c0;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 4px;
        }
        .p-card:hover .p-title { color: #c7511f; }
        .p-card-body .p-price {
            font-size: 18px;
            font-weight: 700;
            color: #0f1111;
            margin-top: auto;
            padding-top: 6px;
        }
        .p-card-body .p-price .p-rupee { font-size: 12px; vertical-align: top; line-height: 1.8; }
        .p-card-body .p-stock {
            font-size: 11px;
            margin-top: 4px;
        }
        .p-stock-ok { color: #067d62; }
        .p-stock-low { color: #b12704; font-weight: 500; }

        /* Pagination */
        .pg-wrap { display: flex; justify-content: center; margin-top: 24px; }
        .pg-wrap nav span, .pg-wrap nav a {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 2px;
            border-radius: 3px;
            font-size: 13px;
            border: 1px solid #ccc;
            color: #0f1111;
            background: #fff;
        }
        .pg-wrap nav span[aria-current="page"] span {
            background: #131921;
            color: #fff;
            border-color: #131921;
        }

        .empty-box { text-align: center; padding: 50px 20px; color: #888; }
        .empty-box i { font-size: 40px; margin-bottom: 12px; color: #ccc; display: block; }

        /* ── Footer ──────────────────────────────────────────── */
        .sf-footer {
            background: #131921;
            padding: 20px 0;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .sf-footer a { color: #ddd; }
        .sf-footer a:hover { color: #febd69; }

        /* ── Responsive ──────────────────────────────────────── */
        @media (max-width: 860px) {
            .content-grid { grid-template-columns: 1fr; }
            .stats-inner { flex-wrap: wrap; }
            .stat-item { flex: 0 0 33.33%; margin-bottom: 8px; }
            .stat-item::before { display: none !important; }
        }
        @media (max-width: 600px) {
            .profile-header .wrap { flex-direction: column; align-items: center; text-align: center; }
            .ph-meta { justify-content: center; }
            .ph-tabs { justify-content: center; }
            .stat-item { flex: 0 0 50%; }
            .p-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .p-card-img { height: 150px; }
        }
    </style>
</head>
<body>

    @php
        $sellerName = $seller->business_name ?: $seller->name;
        $initial = strtoupper(substr($sellerName, 0, 1));
        $badgeMap = [
            'Trusted Seller' => 'sb-trusted',
            'Reliable'       => 'sb-reliable',
            'Growing Seller' => 'sb-growing',
            'Needs Attention'=> 'sb-needs',
        ];
        $badgeClass = $badgeMap[$trustMetrics['reputation_badge']] ?? 'sb-new';

        // star rendering
        $avgR = $trustMetrics['avg_rating'];
        $fullStars = $avgR ? floor($avgR) : 0;
        $halfStar  = $avgR ? ($avgR - $fullStars >= 0.5) : false;
    @endphp

    <!-- Navbar -->
    <nav class="sf-nav">
        <div class="sf-nav-left">
            <a href="{{ route('shop.index') }}" class="back-link"><i class="fas fa-chevron-left"></i> Back to store</a>
            <span class="sf-nav-brand">{{ $sellerName }}</span>
        </div>
        <div class="sf-nav-right">
            <a href="{{ route('shop.index') }}"><i class="fas fa-shopping-bag"></i> Shop</a>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="wrap">
            @if($seller->logo)
                <img class="ph-avatar" src="{{ $seller->logo }}" alt="" onerror="this.outerHTML='<div class=\'ph-avatar-letter\'>{{ $initial }}</div>'">
            @else
                <div class="ph-avatar-letter">{{ $initial }}</div>
            @endif
            <div class="ph-details">
                <h1 class="ph-name">{{ $sellerName }}</h1>
                <div class="ph-meta">
                    @if($seller->city || $seller->state)
                        <span><i class="fas fa-map-marker-alt"></i> {{ implode(', ', array_filter([$seller->city, $seller->state])) }}</span>
                    @endif
                    <span><i class="far fa-calendar-alt"></i> Joined {{ $seller->created_at ? $seller->created_at->format('M Y') : '—' }}</span>
                    <span><i class="fas fa-box-open"></i> {{ number_format($trustMetrics['orders_fulfilled']) }} orders fulfilled</span>
                </div>
                <div class="ph-tabs">
                    <span class="ph-tab active">Products</span>
                    @if($seller->brand_story)
                        <span class="ph-tab" onclick="document.getElementById('aboutCard').scrollIntoView({behavior:'smooth',block:'start'})">About</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="wrap stats-inner">
            <div class="stat-item">
                <div class="stat-num">{{ $trustMetrics['reputation_score'] }}<small>/100</small></div>
                <div class="stat-label">Seller Score</div>
                <span class="stat-badge {{ $badgeClass }}">{{ $trustMetrics['reputation_badge'] }}</span>
            </div>
            <div class="stat-item">
                <div class="stat-num">
                    @if($avgR)
                        {{ $avgR }}
                        <small>
                            <span class="stars-inline">
                                @for($i = 0; $i < $fullStars; $i++) <i class="fas fa-star"></i> @endfor
                                @if($halfStar) <i class="fas fa-star-half-alt"></i> @endif
                            </span>
                        </small>
                    @else
                        —
                    @endif
                </div>
                <div class="stat-label">{{ $trustMetrics['review_count'] }} {{ $trustMetrics['review_count'] === 1 ? 'Review' : 'Reviews' }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ number_format($trustMetrics['orders_fulfilled']) }}</div>
                <div class="stat-label">Orders Delivered</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ $trustMetrics['return_rate'] !== null ? $trustMetrics['return_rate'] . '%' : '—' }}</div>
                <div class="stat-label">Return Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ $trustMetrics['member_since'] ? $trustMetrics['member_since']->diffForHumans(null, true, 1) : '—' }}</div>
                <div class="stat-label">Selling on Platform</div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="wrap content-area">
        <div class="content-grid">

            <!-- Sidebar -->
            <aside>
                @if($seller->brand_story)
                <div class="sidebar-card" id="aboutCard">
                    <div class="sc-header">About this seller</div>
                    <div class="sc-body">
                        <div class="about-text" id="aboutText" style="max-height: 140px; overflow: hidden;">
                            {!! nl2br(e($seller->brand_story)) !!}
                        </div>
                        <button class="read-more-btn" id="readMoreBtn" onclick="
                            var t=document.getElementById('aboutText');
                            var b=document.getElementById('readMoreBtn');
                            if(t.style.maxHeight!=='none'){ t.style.maxHeight='none'; b.textContent='Show less'; }
                            else{ t.style.maxHeight='140px'; b.textContent='Read more'; }
                        ">Read more</button>

                        @if($seller->social_links && count(array_filter($seller->social_links)))
                            <div class="social-row">
                                @if(!empty($seller->social_links['website']))
                                    <a href="{{ $seller->social_links['website'] }}" target="_blank" rel="noopener" title="Website"><i class="fas fa-globe"></i></a>
                                @endif
                                @if(!empty($seller->social_links['instagram']))
                                    <a href="https://instagram.com/{{ ltrim($seller->social_links['instagram'], '@') }}" target="_blank" rel="noopener" title="Instagram"><i class="fab fa-instagram"></i></a>
                                @endif
                                @if(!empty($seller->social_links['facebook']))
                                    <a href="https://facebook.com/{{ $seller->social_links['facebook'] }}" target="_blank" rel="noopener" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                @endif
                                @if(!empty($seller->social_links['twitter']))
                                    <a href="https://x.com/{{ ltrim($seller->social_links['twitter'], '@') }}" target="_blank" rel="noopener" title="X / Twitter"><i class="fab fa-twitter"></i></a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="sidebar-card">
                    <div class="sc-header">Business Details</div>
                    <div class="sc-body">
                        <div class="detail-row">
                            <span class="dr-label">Name</span>
                            <span class="dr-value">{{ $sellerName }}</span>
                        </div>
                        @if($seller->city)
                        <div class="detail-row">
                            <span class="dr-label">Location</span>
                            <span class="dr-value">{{ implode(', ', array_filter([$seller->city, $seller->state])) }}</span>
                        </div>
                        @endif
                        <div class="detail-row">
                            <span class="dr-label">Member since</span>
                            <span class="dr-value">{{ $seller->created_at ? $seller->created_at->format('d M Y') : '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="dr-label">Products</span>
                            <span class="dr-value">{{ $products->total() }}</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Products -->
            <section class="products-main">
                <div class="pm-toolbar">
                    <div class="pm-count">Showing <strong>{{ $products->total() }}</strong> {{ $products->total() === 1 ? 'product' : 'products' }}</div>
                    <div class="pm-sort">
                        <select onchange="window.location.href=this.value">
                            @php $base = route('seller.storefront', $seller->slug); @endphp
                            <option value="{{ $base }}?sort=newest" {{ $sortBy === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="{{ $base }}?sort=price_low" {{ $sortBy === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ $base }}?sort=price_high" {{ $sortBy === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="{{ $base }}?sort=name_az" {{ $sortBy === 'name_az' ? 'selected' : '' }}>Name: A to Z</option>
                        </select>
                    </div>
                </div>

                @if($products->count())
                    <div class="p-grid">
                        @foreach($products as $product)
                            <a class="p-card" href="{{ route('shop.product', $product->id) }}">
                                <div class="p-card-img">
                                    @if($product->img_path)
                                        <img src="{{ $product->img_path }}" alt="{{ $product->name }}"
                                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                        <div class="img-placeholder" style="display:none;"><i class="fas fa-image"></i><span>Image not available</span></div>
                                    @else
                                        <div class="img-placeholder"><i class="fas fa-image"></i><span>No image</span></div>
                                    @endif
                                </div>
                                <div class="p-card-body">
                                    <div class="p-title">{{ $product->name }}</div>
                                    @if($product->quantity <= 5)
                                        <div class="p-stock p-stock-low">Only {{ $product->quantity }} left</div>
                                    @else
                                        <div class="p-stock p-stock-ok">In Stock</div>
                                    @endif
                                    <div class="p-price"><span class="p-rupee">₹</span>{{ number_format($product->price) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if($products->hasPages())
                        <div class="pg-wrap">{{ $products->links() }}</div>
                    @endif
                @else
                    <div class="empty-box">
                        <i class="fas fa-box-open"></i>
                        No products listed yet.
                    </div>
                @endif
            </section>

        </div>
    </div>

    <!-- Footer -->
    <div class="sf-footer">
        &copy; {{ date('Y') }} Front Store &middot; <a href="{{ route('shop.index') }}">Continue Shopping</a>
    </div>

</body>
</html>
