<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $search ? 'Search: ' . $search . ' - ' : '' }}{{ $activeCategory ? $activeCategory->name . ' - ' : '' }}Front Store</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }

        .container { max-width: 1300px; margin: 0 auto; padding: 25px; }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 50%, #485769 100%);
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,189,105,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-banner h1 { font-size: 32px; font-weight: 700; margin-bottom: 10px; position: relative; }
        .hero-banner p { font-size: 16px; opacity: 0.9; position: relative; }
        .hero-banner .highlight { color: #febd69; font-weight: 700; font-family: 'Dancing Script', cursive; font-size: 1.15em; }

        /* ───── Category Browse Section ───── */
        .categories-section {
            margin-bottom: 30px;
        }
        .categories-scroll {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding: 4px 0 10px;
            scrollbar-width: none;
        }
        .categories-scroll::-webkit-scrollbar { display: none; }
        .category-chip {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            border: 1.5px solid #e3e6e6;
            border-radius: 25px;
            text-decoration: none;
            color: #0f1111;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .category-chip:hover {
            border-color: #ff9900;
            background: #fff8ee;
            color: #c7511f;
            box-shadow: 0 2px 8px rgba(255,153,0,0.15);
        }
        .category-chip.active {
            background: linear-gradient(135deg, #232f3e, #37475a);
            color: #febd69;
            border-color: #232f3e;
        }
        .category-chip .cat-count {
            background: #f0f2f2;
            color: #565959;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
        .category-chip.active .cat-count {
            background: rgba(254,189,105,0.2);
            color: #febd69;
        }

        /* ───── Offers Carousel ───── */
        .offers-carousel-wrapper {
            position: relative;
            margin-top: 30px;
            margin-bottom: 35px;
            padding: 0 50px;
            overflow: hidden;
        }
        .offers-carousel {
            display: flex;
            
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
        }
        .offer-slide {
            min-width: 100%;
            flex-shrink: 0;
            padding: 0;
        }
        .offer-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            margin-right: 20px;
            margin-left: 20px;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* box-shadow: 0 10px 30px rgba(0,0,0,0.2); */
            min-height: 200px;
            transition: all 0.3s ease;
            animation: fadeInSlide 0.8s ease-out;
        }
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .offer-card:hover {
            /* transform: translateY(-1px); */
            /* box-shadow: 15px 15px 15px 15px rgba(0,0,0,0.3); */
        }
        .offer-gradient-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .offer-gradient-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .offer-gradient-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .offer-gradient-4 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        
        .offer-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            border: 2px solid rgba(255,255,255,0.3);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .offer-content {
            flex: 1;
            z-index: 2;
        }
        .offer-content h3 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .offer-content p {
            font-size: 16px;
            opacity: 0.95;
            margin-bottom: 20px;
            max-width: 500px;
        }
        .offer-btn {
            background: white;
            color: #764ba2;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .offer-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .offer-icon {
            font-size: 120px;
            opacity: 0.15;
            position: absolute;
            right: 50px;
            bottom: -20px;
            z-index: 1;
        }
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            z-index: 10;
            color: #232f3e;
        }
        .carousel-nav:hover {
            background: #232f3e;
            color: white;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
        }
        .carousel-nav.prev { left: 0; }
        .carousel-nav.next { right: 0; }
        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
        }
        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #cbd5e0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .carousel-dot.active {
            background: #232f3e;
            width: 30px;
            border-radius: 5px;
        }

        /* ───── Main Layout: Sidebar + Grid ───── */
        .shop-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 25px;
            align-items: start;
        }

        /* ───── Sidebar Filters ───── */
        .filter-sidebar {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            position: sticky;
            top: 85px;
            overflow: hidden;
        }
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid #f0f2f2;
        }
        .filter-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: #0f1111;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .filter-header h3 i { color: #ff9900; font-size: 14px; }
        .filter-clear {
            font-size: 12px;
            color: #007185;
            text-decoration: none;
            font-weight: 500;
        }
        .filter-clear:hover { text-decoration: underline; }

        .filter-group {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f2f2;
        }
        .filter-group:last-child { border-bottom: none; }
        .filter-group-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f1111;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Category list in sidebar */
        .filter-cat-list { list-style: none; }
        .filter-cat-list li { margin-bottom: 2px; }
        .filter-cat-list a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 10px;
            border-radius: 8px;
            text-decoration: none;
            color: #565959;
            font-size: 13px;
            transition: all 0.15s;
        }
        .filter-cat-list a:hover { background: #f7fafa; color: #007185; }
        .filter-cat-list a.active { background: #e7f4f7; color: #007185; font-weight: 600; }
        .filter-cat-list .cat-cnt {
            font-size: 11px;
            color: #8d9096;
            background: #f0f2f2;
            padding: 2px 7px;
            border-radius: 10px;
        }
        .filter-cat-list a.active .cat-cnt { background: #d1ecf1; color: #007185; }

        /* Price range */
        .price-inputs {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .price-input {
            width: 100%;
            padding: 8px 10px;
            border: 1.5px solid #d5d9d9;
            border-radius: 8px;
            font-size: 13px;
            color: #0f1111;
            outline: none;
            transition: border 0.2s;
        }
        .price-input:focus { border-color: #ff9900; }
        .price-sep { color: #8d9096; font-size: 13px; }

        /* Sort select */
        .filter-select {
            width: 100%;
            padding: 8px 10px;
            border: 1.5px solid #d5d9d9;
            border-radius: 8px;
            font-size: 13px;
            color: #0f1111;
            background: white;
            outline: none;
            cursor: pointer;
        }
        .filter-select:focus { border-color: #ff9900; }

        /* Checkbox */
        .filter-check {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #0f1111;
            cursor: pointer;
        }
        .filter-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #ff9900;
            cursor: pointer;
        }

        .filter-apply-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            border: none;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: #0f1111;
            cursor: pointer;
            margin-top: 14px;
            transition: all 0.2s;
        }
        .filter-apply-btn:hover {
            background: linear-gradient(180deg, #f7ca00 0%, #e6b800 100%);
            box-shadow: 0 2px 8px rgba(247, 202, 0, 0.4);
        }

        /* Mobile filter toggle */
        .filter-toggle-btn {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            border: 1.5px solid #d5d9d9;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            color: #0f1111;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .filter-toggle-btn i { color: #ff9900; }

        /* ───── Section Header / Search ───── */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 22px;
            color: #0f1111;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i { color: #ff9900; }
        .product-count {
            background: #232f3e;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .search-header { 
            background: white; 
            padding: 20px 25px; 
            margin-bottom: 25px; 
            border-radius: 12px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-info h2 { margin: 0 0 5px; font-size: 20px; color: #0f1111; }
        .search-info .result-count { color: #565959; font-size: 14px; }
        .clear-search { 
            background: #f0f2f2; color: #0f1111; text-decoration: none; font-size: 14px;
            padding: 10px 20px; border-radius: 20px; font-weight: 500; transition: all 0.2s;
            display: flex; align-items: center; gap: 8px;
        }
        .clear-search:hover { background: #e3e6e6; }

        /* Active filters bar */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
        }
        .active-filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            background: #e7f4f7;
            color: #007185;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }
        .active-filter-tag i { font-size: 10px; }
        .active-filter-tag:hover { background: #d1ecf1; }

        /* ───── Product Grid ───── */
        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); 
            gap: 20px; 
        }
        
        .product-card { 
            background: white; 
            border-radius: 16px;
            overflow: hidden;
            /* box-shadow: 0 4px 15px rgba(103, 103, 103, 0.08); */
            /* transition: all 0.3s ease; */
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            /* transform: translateY(-2px); */
            /* box-shadow: 0 12px 30px rgba(0,0,0,0.15); */
        }

        .product-image-container {
            position: relative;
            background: linear-gradient(180deg, #f8f9fa 0%, #fff 100%);
            padding: 20px;
        }
        .product-image { height: 200px; display: flex; align-items: center; justify-content: center; }
        .product-image img { max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.3s ease; }
        .product-card:hover 
        .product-image img { 
            /* transform: scale(1.05);  */
        }

        .badge-container { position: absolute; top: 15px; left: 15px; display: flex; flex-direction: column; gap: 8px; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-sponsored { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .badge-category { background: linear-gradient(135deg, #232f3e, #37475a); color: #febd69; }

        .quick-actions {
            position: absolute; top: 15px; right: 15px; display: flex; flex-direction: column; gap: 8px;
            opacity: 0; transform: translateX(10px); transition: all 0.3s ease;
        }
        .product-card:hover .quick-actions { opacity: 1; transform: translateX(0); }
        .quick-btn {
            width: 36px; height: 36px; border-radius: 50%; background: white; border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15); cursor: pointer; display: flex;
            align-items: center; justify-content: center; color: #565959; transition: all 0.2s ease;
        }
        .quick-btn:hover { background: #febd69; color: #232f3e; }

        .product-info { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .product-title { 
            font-size: 15px; font-weight: 600; line-height: 1.4; color: #0f1111; margin-bottom: 8px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .product-title:hover { color: #c7511f; }

        .product-category-tag {
            display: inline-block;
            padding: 3px 10px;
            background: #f0f2f2;
            color: #565959;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .seller-info { display: flex; align-items: center; gap: 6px; margin-bottom: 10px; }
        .seller-avatar {
            width: 20px; height: 20px; background: linear-gradient(135deg, #232f3e, #37475a);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-size: 10px; font-weight: 600;
        }
        .seller-name { font-size: 12px; color: #007185; font-weight: 500; }
        .seller-name:hover { text-decoration: underline; }
        .seller-reputation {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }
        .seller-reputation .score { font-weight: 800; }
        .rep-trusted { background: #e8f5e9; color: #1b5e20; }
        .rep-reliable { background: #e3f2fd; color: #0d47a1; }
        .rep-growing { background: #fff8e1; color: #8d6e63; }
        .rep-new { background: #f3e5f5; color: #6a1b9a; }
        .rep-attention { background: #ffebee; color: #b71c1c; }

        .rating-container { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
        .stars { display: flex; gap: 2px; }
        .stars i { color: #ffa41c; font-size: 13px; }
        .stars i.empty { color: #ddd; }
        .rating-count { color: #007185; font-size: 12px; }

        .price-section { display: flex; align-items: baseline; gap: 10px; margin-bottom: 10px; }
        .product-price { font-size: 24px; font-weight: 700; color: #0F1111; }

        .stock-status { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; margin-bottom: 15px; }
        .stock-dot { width: 8px; height: 8px; border-radius: 50%; }
        .in-stock .stock-dot { background: #007600; }
        .in-stock { color: #007600; }
        .low-stock .stock-dot { background: #c45500; }
        .low-stock { color: #c45500; }
        .out-stock .stock-dot { background: #b12704; }
        .out-stock { color: #b12704; }
        .stock-urgency {
            margin-left: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #c45500;
        }

        .add-btn { 
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; 
            width: 100%; font-size: 14px; font-weight: 600; color: #0f1111;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s ease; margin-top: auto;
        }
        .add-btn:hover { 
            background: linear-gradient(180deg, #f7ca00 0%, #e6b800 100%);
            box-shadow: 0 4px 12px rgba(247, 202, 0, 0.4);
        }
        .add-btn:disabled { background: #e7e9ec; color: #8d9096; cursor: not-allowed; box-shadow: none; }
        .add-btn i { font-size: 16px; }

        /* No Results */
        .no-results {
            text-align: center; padding: 80px 20px; background: white;
            border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .no-results-icon {
            width: 100px; height: 100px; background: linear-gradient(135deg, #f0f2f2, #e3e6e6);
            border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;
        }
        .no-results-icon i { font-size: 40px; color: #8d9096; }
        .no-results h3 { color: #0f1111; font-size: 22px; margin-bottom: 10px; }
        .no-results p { color: #565959; margin-bottom: 25px; }
        .browse-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            color: #0f1111; text-decoration: none; padding: 12px 30px;
            border-radius: 25px; font-weight: 600; transition: all 0.2s;
        }
        .browse-btn:hover { box-shadow: 0 4px 12px rgba(247, 202, 0, 0.4); }

        /* ───── Responsive ───── */
        @media (max-width: 900px) {
            .shop-layout { grid-template-columns: 1fr; }
            .filter-sidebar {
                position: fixed;
                top: 0; left: -300px;
                width: 280px; height: 100vh;
                z-index: 2000;
                border-radius: 0;
                transition: left 0.3s ease;
                overflow-y: auto;
            }
            .filter-sidebar.open { left: 0; }
            .filter-overlay {
                display: none;
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.4); z-index: 1999;
            }
            .filter-overlay.open { display: block; }
            .filter-toggle-btn { display: flex; }
            .filter-sidebar .filter-close {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 32px; height: 32px;
                background: #f0f2f2;
                border: none; border-radius: 50%;
                cursor: pointer; font-size: 14px; color: #0f1111;
            }
        }
        @media (min-width: 901px) {
            .filter-close { display: none !important; }
            .filter-overlay { display: none !important; }
        }
        @media (max-width: 768px) {
            .hero-banner { padding: 25px; }
            .hero-banner h1 { font-size: 24px; }
            .product-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; }
            .product-image { height: 140px; }
            .search-header { flex-direction: column; gap: 15px; text-align: center; }
            
            /* Carousel mobile adjustments */
            .offers-carousel-wrapper { padding: 0 40px; }
            .offer-card { 
                padding: 25px; 
                min-height: 180px; 
                flex-direction: column;
                text-align: center;
            }
            .offer-content h3 { font-size: 22px; }
            .offer-content p { 
                font-size: 14px; 
                max-width: 100%;
                margin-bottom: 15px;
            }
            .offer-badge { 
                top: 15px; 
                right: 15px; 
                font-size: 12px;
                padding: 6px 14px;
            }
            .offer-icon { 
                font-size: 80px; 
                right: 20px; 
                bottom: -10px;
            }
            .carousel-nav {
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>
<body>

    @include('shop.partials.navbar')

    <div class="container">
        
        @if(!$search && !$activeCategory)
        <!-- Hero Banner -->
        <div class="hero-banner">
            <h1><i class="fas fa-fire"></i> Welcome to <span class="highlight">FrontStore</span></h1>
            <p>Discover amazing products from verified sellers. Shop with confidence!</p>
        </div>

        <!-- Offers Carousel -->
        <div class="offers-carousel-wrapper">
            <button class="carousel-nav prev" onclick="moveCarousel(-1)"><i class="fas fa-chevron-left"></i></button>
            <div class="offers-carousel" id="offersCarousel">
                <div class="offer-slide">
                    <div class="offer-card offer-gradient-1">
                        <div class="offer-badge">🎉 50% OFF</div>
                        <div class="offer-content">
                            <h3>Electronics Mega Sale</h3>
                            <p>Get up to 50% off on laptops, mobiles & accessories</p>
                            <button class="offer-btn" onclick="scrollToProducts()">Shop Now</button>
                        </div>
                        <div class="offer-icon"><i class="fas fa-laptop"></i></div>
                    </div>
                </div>
                <div class="offer-slide">
                    <div class="offer-card offer-gradient-2">
                        <div class="offer-badge">💥 Buy 2 Get 1</div>
                        <div class="offer-content">
                            <h3>Fashion Fiesta</h3>
                            <p>Buy 2 products, get 1 free on all fashion items</p>
                            <button class="offer-btn" onclick="scrollToProducts()">Grab Deal</button>
                        </div>
                        <div class="offer-icon"><i class="fas fa-tshirt"></i></div>
                    </div>
                </div>
                <div class="offer-slide">
                    <div class="offer-card offer-gradient-3">
                        <div class="offer-badge">🚀 Free Shipping</div>
                        <div class="offer-content">
                            <h3>Home Essentials</h3>
                            <p>Free delivery on all home & kitchen products above ₹999</p>
                            <button class="offer-btn" onclick="scrollToProducts()">Explore</button>
                        </div>
                        <div class="offer-icon"><i class="fas fa-home"></i></div>
                    </div>
                </div>
                <div class="offer-slide">
                    <div class="offer-card offer-gradient-4">
                        <div class="offer-badge">⚡ Flash Sale</div>
                        <div class="offer-content">
                            <h3>Limited Time Deals</h3>
                            <p>Up to 70% off on select products. Hurry, ends soon!</p>
                            <button class="offer-btn" onclick="scrollToProducts()">View Deals</button>
                        </div>
                        <div class="offer-icon"><i class="fas fa-bolt"></i></div>
                    </div>
                </div>
            </div>
            <button class="carousel-nav next" onclick="moveCarousel(1)"><i class="fas fa-chevron-right"></i></button>
            <div class="carousel-dots" id="carouselDots"></div>
        </div>
        @endif

        <!-- ───── Category Browse Chips ───── -->
        @if($categories->count() > 0)
        <div class="categories-section">
            <div class="section-header" style="margin-bottom:12px;">
                <h2 class="section-title" style="font-size:18px;"><i class="fas fa-tags"></i> Browse Categories</h2>
            </div>
            <div class="categories-scroll">
                <a href="{{ route('shop.index', array_merge(request()->except('category','page'), [])) }}" 
                   class="category-chip {{ !$categorySlug ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> All
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" 
                   class="category-chip {{ $categorySlug === $cat->slug ? 'active' : '' }}">
                    {{ $cat->name }}
                    <span class="cat-count">{{ $cat->products_count }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($search)
            <div class="search-header">
                <div class="search-info">
                    <h2><i class="fas fa-search" style="color: #ff9900;"></i> Results for "{{ $search }}"</h2>
                    <span class="result-count">{{ count($products) }} product{{ count($products) != 1 ? 's' : '' }} found</span>
                </div>
                <a href="{{ route('shop.index') }}" class="clear-search">
                    <i class="fas fa-times"></i> Clear Search
                </a>
            </div>
        @endif

        <!-- Mobile filter toggle -->
        <button class="filter-toggle-btn" onclick="openFilters()">
            <i class="fas fa-sliders-h"></i> Filters & Sort
        </button>

        <!-- Active filter tags -->
        @if($activeCategory || $minPrice || $maxPrice || $inStockOnly || $sortBy !== 'newest')
        <div class="active-filters">
            @if($activeCategory)
                <a href="{{ route('shop.index', request()->except(['category','page'])) }}" class="active-filter-tag">
                    {{ $activeCategory->name }} <i class="fas fa-times"></i>
                </a>
            @endif
            @if($minPrice)
                <a href="{{ route('shop.index', request()->except(['min_price','page'])) }}" class="active-filter-tag">
                    Min: ₹{{ $minPrice }} <i class="fas fa-times"></i>
                </a>
            @endif
            @if($maxPrice)
                <a href="{{ route('shop.index', request()->except(['max_price','page'])) }}" class="active-filter-tag">
                    Max: ₹{{ $maxPrice }} <i class="fas fa-times"></i>
                </a>
            @endif
            @if($inStockOnly)
                <a href="{{ route('shop.index', request()->except(['in_stock','page'])) }}" class="active-filter-tag">
                    In Stock Only <i class="fas fa-times"></i>
                </a>
            @endif
            @if($sortBy !== 'newest')
                <a href="{{ route('shop.index', request()->except(['sort','page'])) }}" class="active-filter-tag">
                    @php
                        $sortLabels = ['price_low'=>'Price Low','price_high'=>'Price High','name_az'=>'A-Z','name_za'=>'Z-A','oldest'=>'Oldest'];
                        $sortKey = is_string($sortBy) ? $sortBy : '';
                        $sortLabel = $sortLabels[$sortKey] ?? $sortKey;
                    @endphp
                    Sort: {{ $sortLabel }} <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
        @endif

        <!-- ───── Main Layout ───── -->
        <div class="shop-layout">

            <!-- Overlay for mobile -->
            <div class="filter-overlay" id="filterOverlay" onclick="closeFilters()"></div>

            <!-- Sidebar Filters -->
            <aside class="filter-sidebar" id="filterSidebar">
                <form method="GET" action="{{ route('shop.index') }}" id="filterForm">
                    @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif

                    <div class="filter-header">
                        <h3><i class="fas fa-sliders-h"></i> Filters</h3>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <a href="{{ route('shop.index') }}" class="filter-clear">Clear All</a>
                            <button type="button" class="filter-close" onclick="closeFilters()"><i class="fas fa-times"></i></button>
                        </div>
                    </div>

                    <!-- Category filter -->
                    <div class="filter-group">
                        <div class="filter-group-title">Category</div>
                        <ul class="filter-cat-list">
                            <li>
                                <a href="{{ route('shop.index', array_merge(request()->except(['category','page']), [])) }}" class="{{ !$categorySlug ? 'active' : '' }}">
                                    All Categories
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" 
                                   class="{{ $categorySlug === $cat->slug ? 'active' : '' }}">
                                    {{ $cat->name }}
                                    <span class="cat-cnt">{{ $cat->products_count }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price filter -->
                    <div class="filter-group">
                        <div class="filter-group-title">Price Range</div>
                        @if($priceRange['min'] < $priceRange['max'])
                        <p style="font-size:11px;color:#8d9096;margin-bottom:10px;">₹{{ number_format($priceRange['min']) }} — ₹{{ number_format($priceRange['max']) }}</p>
                        @endif
                        <div class="price-inputs">
                            <input type="number" name="min_price" class="price-input" placeholder="Min" value="{{ $minPrice }}" min="0">
                            <span class="price-sep">–</span>
                            <input type="number" name="max_price" class="price-input" placeholder="Max" value="{{ $maxPrice }}" min="0">
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="filter-group">
                        <div class="filter-group-title">Availability</div>
                        <label class="filter-check">
                            <input type="checkbox" name="in_stock" value="1" {{ $inStockOnly ? 'checked' : '' }}> In Stock Only
                        </label>
                    </div>

                    <!-- Sort -->
                    <div class="filter-group">
                        <div class="filter-group-title">Sort By</div>
                        <select name="sort" class="filter-select">
                            <option value="newest" {{ $sortBy === 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ $sortBy === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ $sortBy === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_az" {{ $sortBy === 'name_az' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="name_za" {{ $sortBy === 'name_za' ? 'selected' : '' }}>Name: Z to A</option>
                            <option value="oldest" {{ $sortBy === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>

                    @if($categorySlug)
                        <input type="hidden" name="category" value="{{ $categorySlug }}">
                    @endif

                    <div class="filter-group" style="border-bottom:none;">
                        <button type="submit" class="filter-apply-btn"><i class="fas fa-check"></i> Apply Filters</button>
                    </div>
                </form>
            </aside>

            <!-- Products Area -->
            <div id="productsSection">
                @if(!$search)
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-th-large"></i> 
                        {{ $activeCategory ? $activeCategory->name : 'All Products' }}
                    </h2>
                    <span class="product-count">{{ count($products) }} Products</span>
                </div>
                @endif

                @if(count($products) > 0)
                    <div class="product-grid">
                    @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-image-container">
                            <div class="badge-container">
                                @if(!empty($product->is_sponsored))
                                    <span class="badge badge-sponsored"><i class="fas fa-bolt"></i> Sponsored</span>
                                @endif
                            </div>
                            <div class="quick-actions">
                                <a href="{{ route('shop.product', $product->id) }}" class="quick-btn" title="Quick View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                            <a href="{{ route('shop.product', $product->id) }}" style="text-decoration:none;">
                                <div class="product-image">
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                         onerror="this.onerror=null;this.src='https://placehold.co/200?text=No+Image';">
                                </div>
                            </a>
                        </div>
                        
                        <div class="product-info">
                            @if($product->category)
                            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="text-decoration:none;">
                                <span class="product-category-tag">{{ $product->category->name }}</span>
                            </a>
                            @endif

                            <a href="{{ route('shop.product', $product->id) }}" style="text-decoration:none;">
                                <div class="product-title">{{ $product->name }}</div>
                            </a>

                            <div class="seller-info">
                                <h5>Sold by</h5>
                                <div class="seller-avatar">{{ strtoupper(substr($product->seller->business_name ?? 'B', 0, 1)) }}</div>
                                @if($product->seller->storefront_enabled && $product->seller->slug)
                                    <a href="{{ route('seller.storefront', $product->seller->slug) }}" class="seller-name">{{ $product->seller->business_name ?? 'Marketplace Seller' }}</a>
                                @else
                                    <span class="seller-name">{{ $product->seller->business_name ?? 'Marketplace Seller' }}</span>
                                @endif
                            </div>

                            @php
                                $badge = $product->seller->seller_reputation_badge ?? 'New Seller';
                                $score = $product->seller->seller_reputation_score;
                                $badgeClass = match ($badge) {
                                    'Trusted Seller' => 'rep-trusted',
                                    'Reliable' => 'rep-reliable',
                                    'Growing Seller' => 'rep-growing',
                                    'Needs Attention' => 'rep-attention',
                                    default => 'rep-new',
                                };
                            @endphp
                            <div class="seller-reputation {{ $badgeClass }}">
                                <i class="fas fa-shield-alt"></i>
                                <span>{{ $badge }}</span>
                                @if($score !== null)
                                    <span class="score">{{ $score }}/100</span>
                                @endif
                            </div>
                            
                            <div class="rating-container">
                                <div class="stars">
                                    @php
                                        $avg = method_exists($product, 'getAverageRating') ? ($product->getAverageRating() ?? 0) : 0;
                                        $full = floor($avg);
                                        $half = ($avg - $full) >= 0.5 ? 1 : 0;
                                    @endphp
                                    @for($i = 0; $i < $full; $i++)<i class="fas fa-star"></i>@endfor
                                    @if($half)<i class="fas fa-star-half-alt"></i>@endif
                                    @for($i = 0; $i < (5 - $full - $half); $i++)<i class="far fa-star empty"></i>@endfor
                                </div>
                                <span class="rating-count">({{ method_exists($product, 'getReviewCount') ? $product->getReviewCount() : 0 }})</span>
                            </div>

                            <div class="price-section">
                                <div class="product-price">₹{{ number_format($product->price, 0) }}</div>
                            </div>

                            <div class="stock-status {{ $product->quantity <= 0 ? 'out-stock' : ($product->quantity <= 5 ? 'low-stock' : 'in-stock') }}">
                                <span class="stock-dot"></span>
                                {{ $product->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                @if($product->quantity > 0 && $product->quantity <= 5)
                                    <span class="stock-urgency">Only {{ $product->quantity }} left!</span>
                                @endif
                            </div>

                            <form class="add-to-cart-form">
                            @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="add-btn" {{ $product->quantity > 0 ? '' : 'disabled' }}>
                                    <i class="fas fa-shopping-cart"></i>
                                    {{ $product->quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    </div>
                @else
                    <div class="no-results">
                        <div class="no-results-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No products found</h3>
                        <p>
                            @if($search)
                                We couldn't find any products matching "{{ $search }}"
                            @elseif($activeCategory)
                                No products in "{{ $activeCategory->name }}" yet
                            @else
                                Try adjusting your filters
                            @endif
                        </p>
                        <a href="{{ route('shop.index') }}" class="browse-btn">
                            <i class="fas fa-arrow-left"></i> Browse All Products
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('shop.partials.footer')

    <script>
        // ───── Offers Carousel ─────
        let currentSlide = 0;
        const totalSlides = 4;
        let autoSlideInterval;

        function initCarousel() {
            // Create dots
            const dotsContainer = document.getElementById('carouselDots');
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('div');
                dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
                dot.onclick = () => goToSlide(i);
                dotsContainer.appendChild(dot);
            }
            
            // Start auto-slide
            startAutoSlide();
        }

        function moveCarousel(direction) {
            currentSlide += direction;
            if (currentSlide < 0) currentSlide = totalSlides - 1;
            if (currentSlide >= totalSlides) currentSlide = 0;
            updateCarousel();
            resetAutoSlide();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
            resetAutoSlide();
        }

        function updateCarousel() {
            const carousel = document.getElementById('offersCarousel');
            carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update dots
            document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
            
            // Trigger animation on active card
            const allCards = document.querySelectorAll('.offer-card');
            allCards.forEach((card, index) => {
                if (index === currentSlide) {
                    card.style.animation = 'none';
                    setTimeout(() => {
                        card.style.animation = 'fadeInSlide 0.8s ease-out';
                    }, 10);
                }
            });
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                moveCarousel(1);
            }, 5000); // Change slide every 5 seconds
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        // Initialize carousel on page load
        if (document.getElementById('offersCarousel')) {
            initCarousel();
        }

        // Scroll to products function
        function scrollToProducts() {
            const productsSection = document.getElementById('productsSection');
            if (productsSection) {
                productsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Mobile filter panel
        function openFilters() {
            document.getElementById('filterSidebar').classList.add('open');
            document.getElementById('filterOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeFilters() {
            document.getElementById('filterSidebar').classList.remove('open');
            document.getElementById('filterOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        // Update cart count on page load
        updateCartCount();

        // Handle add to cart
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch('{{ route("shop.addToCart") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        updateCartCount();
                    }
                });
            });
        });

        function updateCartCount() {
            fetch('{{ route("shop.cart") }}')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const count = doc.querySelectorAll('.cart-item').length;
                    document.getElementById('cart-count').textContent = '(' + count + ')';
                });
        }
    </script>

</body>
</html>