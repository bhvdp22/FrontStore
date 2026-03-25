<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Admin Central</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Loading Spinner */
        #loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff; display: flex; justify-content: center; align-items: center; z-index: 9999; transition: opacity 0.5s ease-out; }
        #loader.hide { opacity: 0; pointer-events: none; }
        .spinner { width: 60px; height: 60px; border: 6px solid #f3f3f3; border-top: 6px solid #1a3a4a; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Reset & Base */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #eaeded; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation Bar */
        .admin-navbar {
            background: linear-gradient(135deg, #1a3a4a 0%, #0d2833 100%);
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .menu-toggle:hover {
            background: rgba(255,255,255,0.1);
        }

        .admin-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
        }

        .admin-logo i {
            font-size: 24px;
            color: #ff9900;
        }

        .admin-logo .logo-text {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .admin-logo .logo-sub {
            font-size: 11px;
            opacity: 0.7;
            font-weight: normal;
        }

        .navbar-center {
            flex: 1;
            max-width: 500px;
            margin: 0 30px;
        }

        .search-box {
            display: flex;
            background: rgba(255,255,255,0.1);
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.2s;
        }

        .search-box:focus-within {
            background: #fff;
            border-color: #ff9900;
        }

        .search-box input {
            flex: 1;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            background: transparent;
            color: #fff;
        }

        .search-box:focus-within input {
            color: #111;
        }

        .search-box input::placeholder {
            color: rgba(255,255,255,0.6);
        }

        .search-box:focus-within input::placeholder {
            color: #888;
        }

        .search-box button {
            background: transparent;
            border: none;
            padding: 8px 15px;
            color: rgba(255,255,255,0.7);
            cursor: pointer;
        }

        .search-box:focus-within button {
            color: #1a3a4a;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-icon {
            color: rgba(255,255,255,0.8);
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s;
            position: relative;
        }

        .nav-icon:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }

        .nav-icon .badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #ff9900;
            color: #111;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 10px;
            font-weight: 600;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 12px;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .admin-profile:hover {
            background: rgba(255,255,255,0.2);
        }

        .admin-profile img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .admin-profile .avatar-placeholder {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff9900, #ffad33);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111;
            font-weight: 600;
            font-size: 14px;
        }

        .admin-profile span {
            color: #fff;
            font-size: 13px;
            font-weight: 500;
        }

        /* Sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1001;
            display: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        .admin-sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            width: 260px;
            height: calc(100vh - 56px);
            background: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1002;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .admin-sidebar.collapsed {
            transform: translateX(-260px);
        }

        .sidebar-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #fff);
            border-bottom: 1px solid #eee;
        }

        .sidebar-header h5 {
            margin: 0;
            color: #1a3a4a;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 15px 0;
        }

        .menu-section {
            margin-bottom: 20px;
        }

        .menu-section-title {
            padding: 8px 20px;
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #444;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .menu-link:hover {
            background: #f8f9fa;
            color: #1a3a4a;
            text-decoration: none;
        }

        .menu-link.active {
            background: linear-gradient(90deg, rgba(255,153,0,0.1), transparent);
            color: #1a3a4a;
            border-left-color: #ff9900;
            font-weight: 600;
        }

        .menu-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: #888;
        }

        .menu-link.active i,
        .menu-link:hover i {
            color: #ff9900;
        }

        .menu-link .menu-badge {
            margin-left: auto;
            background: #ff9900;
            color: #111;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background: #f8f9fa;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 10px 15px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            transform: translateY(-1px);
        }

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            margin-top: 56px;
            min-height: calc(100vh - 56px);
            padding: 25px;
            transition: margin-left 0.3s ease;
        }

        .admin-main.expanded {
            margin-left: 0;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1a3a4a;
            margin: 0 0 5px 0;
        }

        .page-header .breadcrumb {
            font-size: 13px;
            color: #888;
        }

        .page-header .breadcrumb a {
            color: #1a3a4a;
            text-decoration: none;
        }

        .page-header .breadcrumb a:hover {
            color: #ff9900;
        }

        /* Cards - Bootstrap Override */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card-header {
            background: linear-gradient(135deg, #1a3a4a, #0d2833);
            color: #fff;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.primary { background: linear-gradient(135deg, #1a3a4a, #2d5a6e); color: #fff; }
        .stat-icon.success { background: linear-gradient(135deg, #28a745, #34ce57); color: #fff; }
        .stat-icon.warning { background: linear-gradient(135deg, #ff9900, #ffad33); color: #111; }
        .stat-icon.danger { background: linear-gradient(135deg, #dc3545, #e4606d); color: #fff; }
        .stat-icon.info { background: linear-gradient(135deg, #17a2b8, #3dd5f3); color: #fff; }

        .stat-content h3 {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            margin: 0 0 5px 0;
        }

        .stat-content p {
            font-size: 13px;
            color: #666;
            margin: 0;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #1a3a4a, #2d5a6e);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0d2833, #1a3a4a);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ff9900, #ffad33);
            border: none;
            color: #111;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #e68a00, #ff9900);
            color: #111;
        }

        /* Footer */
        .admin-footer {
            margin-left: 260px;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #ddd;
            background: #fff;
            transition: margin-left 0.3s ease;
        }

        .admin-footer.expanded {
            margin-left: 0;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-260px);
            }

            .admin-sidebar.mobile-open {
                transform: translateX(0);
            }

            .admin-main,
            .admin-footer {
                margin-left: 0;
            }

            .navbar-center {
                display: none;
            }
        }

        /* Alert customization */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 15px 20px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd, #ffeeba);
            color: #856404;
        }

        @yield('extra_styles')
    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <!-- Top Navigation -->
    <nav class="admin-navbar">
        <div class="navbar-left">
            <div class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </div>
            <div class="admin-logo">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <div class="logo-text">Admin Central</div>
                    <div class="logo-sub">Management Portal</div>
                </div>
            </div>
        </div>

        <div class="navbar-center">
            <div class="search-box">
                <input type="text" placeholder="Search anything...">
                <button><i class="fas fa-search"></i></button>
            </div>
        </div>

        <div class="navbar-right">
            <div class="nav-icon">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </div>
            <div class="nav-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="admin-profile">
                <div class="avatar-placeholder">A</div>
                <span>Admin</span>
                <i class="fas fa-chevron-down" style="font-size: 10px; color: rgba(255,255,255,0.7);"></i>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h5><i class="fas fa-th-large me-2"></i> Navigation</h5>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Management</div>
                <a href="{{ route('admin.orders') }}" class="menu-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Orders</span>
                    @php $pendingOrderCount = \App\Models\Order::where('status', 'pending')->count(); @endphp
                    @if($pendingOrderCount > 0)
                        <span class="menu-badge">{{ $pendingOrderCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.sellers') }}" class="menu-link {{ request()->routeIs('admin.sellers*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Seller Management</span>
                    @php $pendingCount = \App\Models\User::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="menu-badge">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.categories') }}" class="menu-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Settings</div>
                <a href="{{ route('admin.settings') }}" class="menu-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Business Settings</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main" id="adminMain">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="admin-footer" id="adminFooter">
        <p>&copy; 2026 Admin Central. All rights reserved. | Powered by FrontStore</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hide loader
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loader').classList.add('hide');
            }, 300);
        });

        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const main = document.getElementById('adminMain');
            const footer = document.getElementById('adminFooter');
            const overlay = document.getElementById('sidebarOverlay');

            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('expanded');
                footer.classList.toggle('expanded');
            }
        }

        // Handle responsive
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth > 992) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        });

        @yield('extra_scripts')
    </script>
</body>
</html>