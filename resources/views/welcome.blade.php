<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Central Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            background-color: #002e36; /* Amazon Deep Teal */
            height: 50px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 15px; color: white; position: sticky; top: 0; z-index: 1000;
        }

        /* --- LEFT SECTION --- */
        .nav-left { display: flex; align-items: center; gap: 15px; min-width: 300px; }
        .menu-icon { font-size: 20px; cursor: pointer; padding: 5px; }
        .menu-icon:hover { border: 1px solid white; border-radius: 2px; }

        .logo { font-family: 'Dancing Script', cursive; font-size: 24px; font-weight: bold; letter-spacing: -0.5px; white-space: nowrap; }
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

        /* --- NOTIFICATION BELL --- */
        .notif-bell { position: relative; cursor: pointer; }
        .notif-bell .badge {
            position: absolute; top: -6px; right: -8px;
            background: #e74c3c; color: #fff; font-size: 10px; font-weight: bold;
            border-radius: 50%; min-width: 16px; height: 16px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px; line-height: 1;
        }
        .notif-dropdown {
            display: none; position: absolute; top: 30px; right: -10px;
            width: 340px; max-height: 420px; overflow-y: auto;
            background: #fff; border-radius: 8px; box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            z-index: 9999;
        }
        .notif-dropdown.open { display: block; }
        .notif-dropdown-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 16px; border-bottom: 1px solid #eee;
            font-size: 14px; font-weight: 600; color: #232f3e;
        }
        .notif-dropdown-header a { font-size: 12px; color: #007185; text-decoration: none; }
        .notif-dropdown-header a:hover { text-decoration: underline; }
        .notif-item {
            display: flex; gap: 10px; padding: 10px 16px; border-bottom: 1px solid #f5f5f5;
            cursor: pointer; transition: background 0.15s;
        }
        .notif-item:hover { background: #f0f7fb; }
        .notif-item.unread { background: #eff8ff; }
        .notif-item .notif-icon {
            width: 32px; height: 32px; border-radius: 50%; display: flex;
            align-items: center; justify-content: center; font-size: 14px;
            flex-shrink: 0;
        }
        .notif-icon.info   { background: #e3f2fd; color: #1976d2; }
        .notif-icon.success { background: #e8f5e9; color: #388e3c; }
        .notif-icon.warning { background: #fff3e0; color: #f57c00; }
        .notif-icon.danger  { background: #fce4ec; color: #d32f2f; }
        .notif-item .notif-body { flex: 1; min-width: 0; }
        .notif-item .notif-title { font-size: 13px; font-weight: 600; color: #232f3e; margin-bottom: 2px; }
        .notif-item .notif-msg { font-size: 12px; color: #555; line-height: 1.3; }
        .notif-item .notif-time { font-size: 10px; color: #999; margin-top: 3px; }
        .notif-empty { text-align: center; padding: 30px 16px; color: #999; font-size: 13px; }

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
            padding: 12px 20px; display: flex; justify-content: space-between; align-items: center;
            cursor: pointer; font-size: 14px; color: #111; text-decoration: none;
        }
        .menu-item:hover { background-color: #f0f2f2; }
        .menu-item i { color: #555; font-size: 12px; }
        .submenu { display: none; background-color: #fff; padding-left: 20px; }
        .submenu.active { display: block; }
        .submenu a { color: #111; font-weight: normal; }

        /* --- DASHBOARD CONTENT --- */
        .sub-banner {
            background-color: #002126; color: white; padding: 8px 20px; font-size: 13px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .bookmark-icon { border: 1px solid #fff; padding: 0 3px; font-size: 10px; margin: 0 3px; }
        .hide-link { color: #fff; text-decoration: none; font-size: 12px; opacity: 0.8; }

        .main-content { padding: 20px 30px; flex: 1; }
        
        /* Grid Layout for Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card { background: white; border: 1px solid #ddd; padding: 20px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card h3 { margin-top: 0; color: #333; font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .card p { color: #555; font-size: 14px; line-height: 1.4; }
        
        /* Buttons inside cards */
        .btn-action {
            display: inline-block; padding: 8px 12px; border: 1px solid #d5d9d9; 
            border-radius: 3px; text-decoration: none; color: #111; font-size: 13px; 
            background: #fff; margin-top: 10px; cursor: pointer;
        }
        .btn-action:hover { background-color: #f7fafa; border-color: #adb1b8; }

        /* Sales Summary Specifics */
        .stat-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .stat-value { font-weight: bold; color: #111; }
        .stat-label { color: #555; }
        
        /* Footer Styles */
        footer {
            background-color: #fcfcfc; border-top: 1px solid #ddd; padding: 30px 0; text-align: center; font-size: 12px; color: #555; margin-top: auto;
        }
        footer ul { list-style: none; padding: 0; margin-bottom: 10px; display: flex; justify-content: center; gap: 20px; }
        footer a { text-decoration: none; color: #007185; }
        footer a:hover { text-decoration: underline; color: #c7511f; }

    </style>
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

            <div><a href="/" class="menu-item" style="color:#000">Dashboard<i class="fas fa-chevron-right"></i></a></div>
            
            <div class="menu-item" onclick="toggleSubmenu('inventorySub')">
                Inventory <i class="fas fa-chevron-right"></i>
            </div>

            <div class="submenu" id="inventorySub">
                <a href="/products" class="menu-item">Manage Inventory</a>
                
                @if(isset($seller) && $seller->status == 'active')
                    <a href="/products/create" class="menu-item">Add a Product</a>
                @else
                    <a href="#" class="menu-item" style="color: #ccc; cursor: not-allowed;" title="Account not active">Add a Product (Locked)</a>
                @endif
            </div>

            <a href="{{ route('orders.index') }}" class="menu-item">Orders <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('ads.index') }}" class="menu-item">Advertising <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('reviews.index') }}" class="menu-item">Customer Reviews <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('seller.returns.index') }}" class="menu-item">Returns & Refunds <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('payments.index') }}" class="menu-item">Payments <i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('report') }}" class="menu-item">Reports <i class="fas fa-chevron-right"></i></a>

            <div style="border-top:1px solid #ddd; margin:10px 0;"></div>
            <a href="{{ route('seller.profile') }}" class="menu-item">
                <i class="fas fa-user-circle" style="margin-right: 8px;"></i> My Profile 
                <i class="fas fa-chevron-right"></i>
            </a>

            <a href="{{ route('shop.index') }}" class="menu-item" target="_blank">
                <i class="fas fa-store"></i> 
                Visit My Shop 
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

            <!-- Notification Bell -->
            <div class="notif-bell" id="sellerNotifBell" onclick="toggleNotifDropdown(event)">
                <i class="fas fa-bell icon-link"></i>
                <span class="badge" id="sellerNotifBadge" style="display:none;">0</span>
                <div class="notif-dropdown" id="sellerNotifDropdown">
                    <div class="notif-dropdown-header">
                        <span>Notifications</span>
                        <a href="#" onclick="markAllReadSeller(event)">Mark all read</a>
                    </div>
                    <div id="sellerNotifList">
                        <div class="notif-empty">No notifications</div>
                    </div>
                </div>
            </div>

            <a href="{{ route('seller.profile') }}" style="color: white;"><i class="fas fa-cog icon-link"></i></a>
            <a style="color:#aab7b9; font-size:12px;" href="{{ route('help') }}">Help</a>
            <a href="/logout" style="color:#aab7b9; font-size:12px; text-decoration:none;">Logout</a>
        </div>
    </nav>

    <div class="sub-banner">
        <span> <span class="bookmark-icon"><i class="far fa-bookmark"></i></span> </span>
        <a href="#" class="hide-link">Hi</a>
    </div>

    @hasSection('content')
        @yield('content')
    @else
        <div class="main-content">

            <div class="main-content">
        
        @php 
            // We use the $seller object to get the live status from the database
            $status = $seller->status ?? 'pending'; 
        @endphp

        @if($status == 'pending')
            <div style="background-color: #fff4e5; border-left: 6px solid #f0ad4e; padding: 20px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); margin-bottom: 25px;">
                <div style="display: flex; align-items: flex-start; gap: 15px;">
                    <div style="font-size: 20px; color: #e77600; margin-top: 2px;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #111; font-weight: 700;">Account Under Review</h3>
                        <p style="margin: 0; color: #111; font-size: 14px; line-height: 1.5;">
                            Your account is currently <strong>Pending Approval</strong>. You cannot list new products until the Admin verifies your details.
                        </p>
                    </div>
                </div>
            </div>

        @elseif($status == 'banned')
            <div style="background-color: #fdecea; border-left: 6px solid #d9534f; padding: 20px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); margin-bottom: 25px;">
                <div style="display: flex; align-items: flex-start; gap: 15px;">
                    <div style="font-size: 20px; color: #c40000; margin-top: 2px;">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #c40000; font-weight: 700;">Account Suspended</h3>
                        <p style="margin: 0; color: #111; font-size: 14px; line-height: 1.5;">
                            Your selling privileges have been <strong>Revoked</strong>. Please contact support.
                        </p>
                    </div>
                </div>
            </div>
        @endif
            
            <div style="margin-bottom: 20px;">
                <h2 style="margin-bottom: 5px;">Welcome, {{ session('loginusername', 'Seller') }}!</h2>
                <p style="margin: 0; color: #555; font-size: 14px;">Here is what is happening with your store today.</p>
            </div>

            <div class="dashboard-grid">
                
                <div class="card">
                    <h3>Sales Summary 
                        <select id="salesPeriod" style="float:right; font-size:12px; padding:4px 8px; border:1px solid #ddd; border-radius:3px; cursor:pointer;" onchange="updateSales()">
                            <option value="today">Today</option>
                            <option value="last7days" selected>Last 7 Days</option>
                            <option value="last30days">Last 30 Days</option>
                        </select>
                    </h3>
                    
                    <div id="todaySales" style="display:none;">
                        <div class="stat-row">
                            <span class="stat-label">Total Sales</span>
                            <span class="stat-value">₹{{ number_format($todaySales, 2) }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Units Sold</span>
                            <span class="stat-value">{{ $todayUnits }}</span>
                        </div>
                    </div>

                    <div id="last7DaysSales" style="display:block;">
                        <div class="stat-row">
                            <span class="stat-label">Total Sales</span>
                            <span class="stat-value">₹{{ number_format($last7DaysSales, 2) }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Units Sold</span>
                            <span class="stat-value">{{ $last7DaysUnits }}</span>
                        </div>
                    </div>

                    <div id="last30DaysSales" style="display:none;">
                        <div class="stat-row">
                            <span class="stat-label">Total Sales</span>
                            <span class="stat-value">₹{{ number_format($last30DaysSales, 2) }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Units Sold</span>
                            <span class="stat-value">{{ $last30DaysUnits }}</span>
                        </div>
                        <div style="margin-top: 15px; height: 4px; background: #eee; border-radius: 2px;">
                            <div style="width: {{ $salesPercentage }}%; height: 100%; background: #007185; border-radius: 2px;"></div>
                        </div>
                    </div>

                    <a href="/reports" class="btn-action">View Sales Report</a>
                </div>

                <div class="card">
                    <h3>Your Orders</h3>
                    <div class="stat-row">
                        <span class="stat-label">Pending Orders</span>
                        <span class="stat-value" style="color:#e77600;">{{ $pendingOrders }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Unshipped</span>
                        <span class="stat-value" style="color:#d00;">{{ $unshippedOrders }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Return Requests</span>
                        <span class="stat-value">{{ $returnRequests }}</span>
                    </div>
                    <a href="/orders" class="btn-action">Manage Orders</a>
                </div>

                <div class="card">
                    <h3>Payments</h3>
                    <div class="stat-row">
                        <span class="stat-label">Total Balance</span>
                        <span class="stat-value">₹{{ number_format($totalBalance, 2) }}</span>
                    </div>
                    <p style="font-size: 12px; margin-top: 5px;">Next payout scheduled for <strong>{{ $nextPayoutDate }}</strong></p>
                    <a href="{{ route('payments.index') }}" class="btn-action">View Statement</a>
                </div>

                <div class="card">
                    <h3>Inventory Health</h3>
                    <p>Manage your listings and stock levels to ensure you never run out.</p>
                    <div style="display:flex; gap:10px;">
                        <a href="/products" class="btn-action">Manage Inventory</a>
        
                        @if(isset($seller) && $seller->status == 'active')
                            <a href="/products/create" class="btn-action">Add Product</a>
                        @else
                            <button class="btn-action" style="background:#f0f0f0; color:#999; cursor:not-allowed; border-color:#eee;" disabled>Add Product</button>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <h3>Account Health</h3>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="fas fa-check-circle" style="color: #2e8b57; font-size: 24px;"></i>
                        <div>
                            <span style="font-weight: bold; display: block;"> {{ $status }}</span>
                            <span style="font-size: 12px; color: #555;">No policy violations</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3>News</h3>
                    <div style="font-size: 13px; margin-bottom: 10px; border-left: 3px solid #007185; padding-left: 10px;">
                        <a href="#" style="text-decoration:none; color:#007185; font-weight:bold;">New Fee Structure Update</a>
                        <div style="color:#777; font-size:11px;">Oct 1, 2025</div>
                    </div>
                    <a href="#" style="font-size: 12px; color: #007185;">Read all news</a>
                </div>

            </div>
        </div>
    @endif

    <footer>
        <ul>
            <li><a href="#">Conditions of Use</a></li>
            <li><a href="#">Privacy Notice</a></li>
            <li><a href="#">Help</a></li>
        </ul>
        <p>&copy; 1996-2026, Seller Central, Inc. or its affiliates</p>
    </footer>

    <script>
        function toggleMenu() {
            var menu = document.getElementById("sideMenu");
            var overlay = document.getElementById("menuOverlay");
            
            if (menu.style.left === "0px") {
                menu.style.left = "-320px";
                overlay.style.display = "none";
            } else {
                menu.style.left = "0px";
                overlay.style.display = "block";
            }
        }

        function toggleSubmenu(id) {
            var submenu = document.getElementById(id);
            if (submenu.style.display === "block") {
                submenu.style.display = "none";
            } else {
                submenu.style.display = "block";
            }
        }

        function updateSales() {
            var period = document.getElementById("salesPeriod").value;
            
            document.getElementById("todaySales").style.display = "none";
            document.getElementById("last7DaysSales").style.display = "none";
            document.getElementById("last30DaysSales").style.display = "none";
            
            if (period === "today") {
                document.getElementById("todaySales").style.display = "block";
            } else if (period === "last7days") {
                document.getElementById("last7DaysSales").style.display = "block";
            } else if (period === "last30days") {
                document.getElementById("last30DaysSales").style.display = "block";
            }
        }
    </script>

    <script>
        // Hide loader after 1.5 seconds
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('loader');
                loader.classList.add('hide');
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500);
            }, 1500);
        });
    </script>

    {{-- ── Seller Notification Bell JS ── --}}
    <script>
        function toggleNotifDropdown(e) {
            e.stopPropagation();
            const dd = document.getElementById('sellerNotifDropdown');
            dd.classList.toggle('open');
            if (dd.classList.contains('open')) fetchSellerNotifications();
        }
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('sellerNotifDropdown');
            if (dd && !e.target.closest('#sellerNotifBell')) dd.classList.remove('open');
        });

        function fetchSellerNotifications() {
            fetch('{{ route("notifications.seller") }}')
              .then(r => r.json())
              .then(data => {
                const badge = document.getElementById('sellerNotifBadge');
                const list = document.getElementById('sellerNotifList');

                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }

                if (!data.notifications || data.notifications.length === 0) {
                    list.innerHTML = '<div class="notif-empty">No notifications yet</div>';
                    return;
                }

                let iconMap = {
                    order: 'fa-shopping-bag', payout: 'fa-money-bill-wave',
                    ad: 'fa-ad', stock: 'fa-box-open'
                };

                list.innerHTML = data.notifications.map(n => {
                    let iconCls = iconMap[n.type] || 'fa-bell';
                    return `<div class="notif-item ${n.is_read ? '' : 'unread'}" onclick="readNotif(${n.id}, '${n.action_url || ''}')">
                        <div class="notif-icon ${n.icon}"><i class="fas ${iconCls}"></i></div>
                        <div class="notif-body">
                            <div class="notif-title">${n.title}</div>
                            <div class="notif-msg">${n.message}</div>
                            <div class="notif-time">${n.time_ago}</div>
                        </div>
                    </div>`;
                }).join('');
              });
        }

        function readNotif(id, url) {
            fetch('/notifications/' + id + '/read', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
            }).then(() => {
                fetchSellerNotifications();
                if (url) window.location.href = url;
            });
        }

        function markAllReadSeller(e) {
            e.preventDefault(); e.stopPropagation();
            fetch('{{ route("notifications.markAllReadSeller") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
            }).then(() => fetchSellerNotifications());
        }

        // Auto-refresh badge every 30 seconds
        setInterval(function() {
            fetch('{{ route("notifications.seller") }}')
              .then(r => r.json())
              .then(data => {
                const badge = document.getElementById('sellerNotifBadge');
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
              });
        }, 30000);

        // Initial load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("notifications.seller") }}')
              .then(r => r.json())
              .then(data => {
                const badge = document.getElementById('sellerNotifBadge');
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                    badge.style.display = 'flex';
                }
              });
        });
    </script>

</body>
</html>