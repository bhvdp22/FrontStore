<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Central')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Loading Spinner */
        #loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff; display: flex; justify-content: center; align-items: center; z-index: 9999; transition: opacity 0.5s ease-out; }
        #loader.hide { opacity: 0; pointer-events: none; }
        .spinner { width: 60px; height: 60px; border: 6px solid #f3f3f3; border-top: 6px solid #002e36; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* 1. Reset & Basic Styles */
        body { margin: 0; padding: 0; font-family: 'Poppins', sans-serif; background-color: #f1f1f1; display: flex; flex-direction: column; min-height: 100vh; }

        /* 2. Top Navigation Bar */
        .navbar {
            background-color: #002e36;
            height: 50px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 15px; color: white; position: sticky; top: 0; z-index: 1000;
        }

        /* --- LEFT SECTION --- */
        .nav-left { display: flex; align-items: center; gap: 15px; min-width: 300px; }
        .menu-icon { font-size: 20px; cursor: pointer; padding: 5px; }
        .menu-icon:hover { border: 1px solid white; border-radius: 2px; }

        .logo { font-size: 20px; font-weight: bold; letter-spacing: -0.5px; white-space: nowrap; }
        .logo span { font-weight: normal; font-size: 18px; }
        .logo .country { font-size: 12px; vertical-align: super; margin-left: 2px; }

        /* Store Picker */
        .store-picker {
            background-color: #fff; color: #333; padding: 4px 10px; border-radius: 3px;
            font-size: 13px; font-weight: bold; display: flex; align-items: center; gap: 5px;
            cursor: pointer; white-space: nowrap; border: 1px solid #ccc;
        }
        .store-picker .flag { font-weight: normal; color: #555; border-left: 1px solid #ccc; padding-left: 5px; margin-left: 5px;}

        /* --- CENTER SECTION (Search) --- */
        .nav-center { flex-grow: 1; display: flex; justify-content: center; max-width: 600px; margin: 0 20px; }
        .search-container { display: flex; width: 100%; height: 32px; border-radius: 3px; overflow: hidden; border: 1px solid #577076; }
        .search-input { flex-grow: 1; background-color: #0d4e58; border: none; padding: 0 10px; color: white; font-size: 14px; }
        .search-input::placeholder { color: #aab7b9; font-style: italic; }
        .search-input:focus { outline: 2px solid #e77600; background-color: #fff; color: #333; }
        .search-btn { background-color: #0d4e58; border: none; color: white; padding: 0 12px; cursor: pointer; border-left: 1px solid #577076; }
        .search-btn:hover { background-color: #166e7c; }

        /* --- RIGHT SECTION --- */
        .nav-right { display: flex; align-items: center; gap: 20px; font-size: 14px; min-width: 300px; justify-content: flex-end; }
        .lang-select { display: flex; align-items: center; gap: 5px; cursor: pointer; font-weight: bold; }
        .icon-link { color: white; font-size: 18px; cursor: pointer; position: relative; }
        .help-link { text-decoration: none; color: white; font-weight: bold; }

        /* --- SIDE MENU STYLES --- */
        .side-menu-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 2000; display: none;
        }
        .side-menu {
            position: fixed; top: 0; left: -320px; width: 300px; height: 100%;
            background: white; z-index: 2001; transition: left 0.3s ease;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2); display: flex; flex-direction: column;
        }
        .menu-header {
            background-color: #f7fafa; padding: 15px 20px; font-weight: bold; font-size: 16px;
            display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #eaeded;
        }
        .close-btn { cursor: pointer; font-size: 18px; }
        .menu-items { overflow-y: auto; flex: 1; padding: 10px 0; }
        .menu-item {
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
            color: #111;
            text-decoration: none;
        }
        .menu-item:hover { 
            background-color: #f0f2f2; 
            color: #111;
            text-decoration: none;
        }
        .menu-item i.fa-chevron-right { 
            color: #555; 
            font-size: 12px;
            margin-left: auto;
        }
        .submenu { display: none; background-color: #fff; padding-left: 20px; }
        .submenu.active { display: block; }
        .submenu a { 
            color: #111; 
            font-weight: normal;
            text-decoration: none;
        }
        .submenu a:hover {
            color: #111;
            text-decoration: none;
        }

        /* --- DASHBOARD CONTENT --- */
        .sub-banner {
            background-color: #002126; color: white; padding: 8px 20px; font-size: 13px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .bookmark-icon { border: 1px solid #fff; padding: 0 3px; font-size: 10px; margin: 0 3px; }
        .hide-link { color: #fff; text-decoration: none; font-size: 12px; opacity: 0.8; }

        .main-content { padding: 20px 30px; flex: 1; }
        
        /* Footer Styles */
        footer {
            background-color: #fcfcfc; border-top: 1px solid #ddd; padding: 30px 0; text-align: center; font-size: 12px; color: #555; margin-top: auto;
        }
        footer ul { list-style: none; padding: 0; margin-bottom: 10px; display: flex; justify-content: center; gap: 20px; }
        footer a { text-decoration: none; color: #007185; }
        footer a:hover { text-decoration: underline; color: #c7511f; }
    </style>
    @yield('extra_styles')
</head>
<body>
    <!-- Loading Spinner -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <div class="side-menu-overlay" id="menuOverlay" onclick="toggleMenu()"></div>
    <div class="side-menu" id="sideMenu">
        <div class="menu-header">
            <i class="fas fa-times close-btn" onclick="toggleMenu()"></i> Menu
        </div>
        <div class="menu-items">
            <a href="/" class="menu-item">Dashboard <i class="fas fa-chevron-right"></i></a>
            <div class="menu-item" onclick="toggleSubmenu('inventorySub')">
                <span>Inventory</span> <i class="fas fa-chevron-right"></i>
            </div>
            <div class="submenu" id="inventorySub">
                <a href="/products" class="menu-item">Manage Inventory</a>
                <a href="/products/create" class="menu-item">Add a Product</a>
            </div>
            <a href="{{ route('orders.index') }}" class="menu-item"><span>Orders</span> <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('seller.returns.index') }}" class="menu-item"><span>Returns & Refunds</span> <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('ads.index') }}" class="menu-item"><span>Advertising</span> <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('reviews.index') }}" class="menu-item"><span>Customer Reviews</span> <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('payments.index') }}" class="menu-item"><span>Payments</span> <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('report') }}" class="menu-item"><span>Reports</span> <i class="fas fa-chevron-right"></i></a>

            <div style="border-top:1px solid #ddd; margin:10px 0;"></div>
            <a href="{{ route('seller.profile') }}" class="menu-item">
                <span><i class="fas fa-user-circle" style="margin-right: 8px;"></i> My Profile</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            @php
                $currentSeller = \App\Models\User::where('name', session('loginusername'))->first();
            @endphp
            @if($currentSeller && $currentSeller->storefront_enabled && $currentSeller->slug)
            <a href="{{ route('seller.storefront', $currentSeller->slug) }}" class="menu-item" target="_blank">
                <span><i class="fas fa-store-alt" style="margin-right: 8px;"></i> My Storefront</span>
                <i class="fas fa-external-link-alt" style="font-size:10px;"></i>
            </a>
            @endif
            <a href="{{ route('shop.index') }}" class="menu-item" target="_blank">
                <span><i class="fas fa-store" style="margin-right: 8px;"></i> Visit My Shop</span>
                <i class="fas fa-external-link-alt" style="font-size:10px;"></i>
            </a>
        </div>
    </div>

    <nav class="navbar">
        <div class="nav-left">
            <i class="fas fa-bars menu-icon" onclick="toggleMenu()"></i>
            <div class="logo"><span>Seller Central</span> <span class="country">India</span></div>
            <div class="store-picker">
                {{ session('loginusername', 'RADHE SALES') }}
                <span class="flag">India</span>
                <i class="fas fa-caret-down" style="margin-left: 5px; font-size: 10px;"></i>
            </div>
        </div>
        <div class="nav-center">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="nav-right">
            <div class="lang-select">EN <i class="fas fa-caret-down" style="font-size: 10px;"></i></div>
            <i class="fas fa-envelope icon-link"></i>
            <a href="{{ route('seller.profile') }}" style="color: white;"><i class="fas fa-cog icon-link"></i></a>
            <a style="color:#aab7b9; font-size:12px;" href="{{ route('help') }}">Help</a>
            <a href="/logout" style="color:#aab7b9; font-size:12px; text-decoration:none;">Logout</a>
        </div>
    </nav>

    <div class="sub-banner">
        <span> <span class="bookmark-icon"><i class="far fa-bookmark"></i></span> </span>
        <a href="#" class="hide-link">Hi</a>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <footer>
        <ul>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Help Center</a></li>
        </ul>
        <p>&copy; 2026 FrontStore Seller Central. All rights reserved.</p>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('sideMenu');
            const overlay = document.getElementById('menuOverlay');
            if (menu.style.left === '0px') {
                menu.style.left = '-320px';
                overlay.style.display = 'none';
            } else {
                menu.style.left = '0px';
                overlay.style.display = 'block';
            }
        }

        function toggleSubmenu(id) {
            const submenu = document.getElementById(id);
            submenu.classList.toggle('active');
        }

        window.addEventListener('load', function() {
            setTimeout(() => {
                const loader = document.getElementById('loader');
                loader.classList.add('hide');
            }, 500);
        });

        // Fallback: force-hide spinner after 4 seconds even if external resources fail
        setTimeout(() => {
            const loader = document.getElementById('loader');
            if (loader) loader.classList.add('hide');
        }, 4000);

        @yield('extra_scripts')
    </script>
</body>
</html>
