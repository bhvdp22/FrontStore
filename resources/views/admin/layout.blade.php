<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — FrontStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Reset ────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── Palette ──────────────────────────────────── */
        :root {
            --white:       #ffffff;
            --gray-50:     #fafafa;
            --gray-100:    #f4f4f5;
            --gray-200:    #e4e4e7;
            --gray-300:    #d4d4d8;
            --gray-400:    #a1a1aa;
            --gray-500:    #71717a;
            --gray-600:    #52525b;
            --gray-700:    #3f3f46;
            --gray-800:    #27272a;
            --gray-900:    #18181b;
            --accent:      #334155;
            --accent-light:#475569;
            --accent-hover:#1e293b;
            --danger:      #991b1b;
            --danger-light:#fef2f2;
            --danger-border:#fecaca;
            --success:     #166534;
            --success-light:#f0fdf4;
            --success-border:#bbf7d0;
            --warn:        #92400e;
            --warn-light:  #fffbeb;
            --warn-border: #fde68a;
            --sidebar-w:   232px;
        }

        body {
            font-family: "Poppins", -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            color: var(--gray-800);
            background: var(--gray-50);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }

        /* ── Rupee styling ────────────────────────────── */
        .rupee {
            font-family: 'Poppins', system-ui, sans-serif;
            font-size: 0.82em;
            font-weight: 400;
            opacity: .7;
            margin-right: 1px;
        }

        /* ── Layout Shell ─────────────────────────────── */
        .admin-shell   { display: flex; min-height: 100vh; }
        .admin-sidebar { width: var(--sidebar-w); background: var(--gray-900); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 10; }
        .admin-main    { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* ── Sidebar ──────────────────────────────────── */
        .sidebar-brand {
            font-family: 'Dancing Script', cursive;
            padding: 22px 24px 18px;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: .4px;
            color: var(--white);
        }

        .sidebar-section {
            padding: 6px 16px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-500);
            margin-top: 12px;
        }

        .sidebar-nav { list-style: none; padding: 4px 0; flex: 1; overflow-y: auto; }

        .sidebar-nav li a {
            display: block;
            padding: 9px 24px;
            font-size: 13.5px;
            font-weight: 400;
            color: var(--gray-400);
            transition: color .15s, background .15s;
            border-left: 2px solid transparent;
        }
        .sidebar-nav li a:hover {
            color: var(--gray-200);
            background: rgba(255,255,255,.04);
        }
        .sidebar-nav li a.active {
            color: var(--white);
            font-weight: 500;
            background: rgba(255,255,255,.07);
            border-left-color: var(--white);
        }

        .sidebar-divider { height: 1px; background: rgba(255,255,255,.08); margin: 8px 16px; }

        .sidebar-footer {
            padding: 14px 24px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-footer button {
            background: none; border: none; cursor: pointer;
            font-size: 13px; font-weight: 400;
            color: var(--gray-500);
            transition: color .15s;
        }
        .sidebar-footer button:hover { color: #fca5a5; }

        /* ── Top Header ───────────────────────────────── */
        .admin-header {
            height: 54px;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            padding: 0 32px;
        }
        .admin-header h1 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
        }
        .admin-header .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 18px;
            font-size: 12.5px;
            color: var(--gray-400);
            font-weight: 500;
        }

        /* ── Admin Notification Bell ── */
        .admin-notif-bell { position: relative; cursor: pointer; }
        .admin-notif-bell .fa-bell { font-size: 17px; color: var(--gray-400); transition: color .15s; }
        .admin-notif-bell:hover .fa-bell { color: var(--primary); }
        .admin-notif-bell .badge {
            position: absolute; top: -6px; right: -8px;
            background: #e74c3c; color: #fff; font-size: 9px; font-weight: 700;
            border-radius: 50%; min-width: 16px; height: 16px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 3px; line-height: 1;
        }
        .admin-notif-dropdown {
            display: none; position: absolute; top: 28px; right: -10px;
            width: 360px; max-height: 420px; overflow-y: auto;
            background: #fff; border-radius: 8px; box-shadow: 0 8px 30px rgba(0,0,0,0.18);
            z-index: 9999;
        }
        .admin-notif-dropdown.open { display: block; }
        .admin-ndd-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 16px; border-bottom: 1px solid #eee;
            font-size: 13px; font-weight: 600; color: var(--gray-700);
        }
        .admin-ndd-header a { font-size: 11px; color: var(--primary); text-decoration: none; }
        .admin-ndd-header a:hover { text-decoration: underline; }
        .admin-notif-item {
            display: flex; gap: 10px; padding: 10px 16px; border-bottom: 1px solid #f5f5f5;
            cursor: pointer; transition: background 0.15s;
        }
        .admin-notif-item:hover { background: #f0f4ff; }
        .admin-notif-item.unread { background: #eff5ff; }
        .admin-notif-item .ni-icon {
            width: 30px; height: 30px; border-radius: 50%; display: flex;
            align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
        }
        .ni-icon.info    { background: #e3f2fd; color: #1976d2; }
        .ni-icon.success  { background: #e8f5e9; color: #388e3c; }
        .ni-icon.warning  { background: #fff3e0; color: #f57c00; }
        .ni-icon.danger   { background: #fce4ec; color: #d32f2f; }
        .admin-notif-item .ni-body { flex: 1; min-width: 0; }
        .admin-notif-item .ni-title { font-size: 12.5px; font-weight: 600; color: var(--gray-700); margin-bottom: 2px; }
        .admin-notif-item .ni-msg { font-size: 11.5px; color: #666; line-height: 1.3; }
        .admin-notif-item .ni-time { font-size: 10px; color: #aaa; margin-top: 3px; }
        .admin-notif-empty { text-align: center; padding: 30px 16px; color: #aaa; font-size: 12.5px; }

        /* ── Content Area ─────────────────────────────── */
        .admin-content { padding: 28px 32px; flex: 1; }

        /* ── Page Title ───────────────────────────────── */
        .page-title {
            font-family: "Montserrat", sans-serif;
            font-size: 21px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 4px;
            letter-spacing: -.2px;
        }
        .page-subtitle {
            font-size: 13px;
            color: var(--gray-400);
            margin-bottom: 24px;
        }

        /* ── Stat Cards ───────────────────────────────── */
        .stat-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(175px, 1fr)); gap: 14px; margin-bottom: 28px; }
        .stat-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 18px 20px;
            transition: border-color .15s;
        }
        .stat-card:hover { border-color: var(--gray-300); }
        .stat-card .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--gray-400);
            margin-bottom: 8px;
            font-weight: 500;
        }
        .stat-card .value {
            font-family: "Montserrat", sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -.3px;
        }

        /* ── Data Tables ──────────────────────────────── */
        .table-wrap {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .table-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .table-header h2 {
            font-family: "Montserrat", sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
        }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            text-align: left;
            padding: 10px 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--gray-400);
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            white-space: nowrap;
        }
        tbody td {
            padding: 12px 20px;
            font-size: 13.5px;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--gray-50); }

        /* ── Text-only Status ─────────────────────────── */
        .status-text                 { font-weight: 600; font-size: 11.5px; text-transform: uppercase; letter-spacing: .4px; }
        .status-text.pending         { color: var(--gray-500); }
        .status-text.active,
        .status-text.approved        { color: var(--success); }
        .status-text.banned,
        .status-text.rejected,
        .status-text.failed          { color: var(--danger); }
        .status-text.delivered,
        .status-text.completed,
        .status-text.refund_completed { color: var(--success); }
        .status-text.cancelled       { color: var(--danger); }
        .status-text.shipped,
        .status-text.processing,
        .status-text.refund_initiated { color: var(--accent); }
        .status-text.received,
        .status-text.inspected,
        .status-text.pickup_scheduled { color: var(--gray-600); }

        /* ── Buttons ──────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            background: var(--white);
            color: var(--gray-700);
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
            line-height: 1;
        }
        .btn:hover { background: var(--gray-50); border-color: var(--gray-400); }

        .btn-primary {
            background: var(--accent);
            color: var(--white);
            border-color: var(--accent);
        }
        .btn-primary:hover { background: var(--accent-hover); border-color: var(--accent-hover); }

        .btn-danger {
            color: var(--danger);
            border-color: var(--danger-border);
        }
        .btn-danger:hover { background: var(--danger-light); }

        .btn-success {
            color: var(--success);
            border-color: var(--success-border);
        }
        .btn-success:hover { background: var(--success-light); }

        .btn-sm { padding: 7px 14px; font-size: 12px; }

        .btn-link {
            background: none; border: none; padding: 0;
            font-size: 13px; font-weight: 600;
            color: var(--accent);
            cursor: pointer;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .btn-link:hover { color: var(--gray-900); }

        /* ── Forms ─────────────────────────────────────── */
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-600);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .form-control {
            width: 100%;
            padding: 9px 12px;
            font-size: 13.5px;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            background: var(--white);
            color: var(--gray-800);
            transition: border-color .15s;
        }
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 2px rgba(51,65,85,.08); }

        select.form-control { appearance: auto; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

        .form-check { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .form-check input { accent-color: var(--accent); width: 16px; height: 16px; }
        .form-check label { margin-bottom: 0; text-transform: none; letter-spacing: 0; font-weight: 500; font-size: 13px; }

        .search-bar { position: relative; }
        .search-bar input { padding-left: 12px; }

        /* ── Alert ─────────────────────────────────────── */
        .alert {
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-success { background: var(--success-light); color: var(--success); border-color: var(--success-border); }
        .alert-danger  { background: var(--danger-light); color: var(--danger); border-color: var(--danger-border); }
        .alert-warning { background: var(--warn-light); color: var(--warn); border-color: var(--warn-border); }

        /* ── Card Section ─────────────────────────────── */
        .card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 22px 24px;
            margin-bottom: 20px;
        }
        .card-title {
            font-family: "Montserrat", sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--gray-100);
        }

        /* ── Detail Grid ──────────────────────────────── */
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 28px; }
        .detail-item .dt {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--gray-400);
            margin-bottom: 2px;
            font-weight: 500;
        }
        .detail-item .dd {
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-800);
        }

        /* ── Pagination ───────────────────────────────── */
        .pagination-wrap {
            padding: 12px 20px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: flex-end;
        }
        .pagination-wrap nav span,
        .pagination-wrap nav a {
            display: inline-block;
            padding: 5px 12px;
            font-size: 12px;
            border: 1px solid var(--gray-200);
            border-radius: 4px;
            margin-left: 4px;
            color: var(--gray-600);
            transition: all .15s;
        }
        .pagination-wrap nav a:hover { background: var(--gray-50); }
        .pagination-wrap nav span[aria-current] {
            background: var(--accent);
            color: var(--white);
            border-color: var(--accent);
        }
        .pagination-wrap .relative { display: flex; align-items: center; }

        /* ── Empty State ──────────────────────────────── */
        .empty-state { text-align: center; padding: 48px 20px; color: var(--gray-400); font-size: 14px; }

        /* ── Back Link ────────────────────────────────── */
        .back-link {
            display: inline-block;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-400);
            margin-bottom: 16px;
            transition: color .15s;
        }
        .back-link:hover { color: var(--gray-700); }
        .back-link::before { content: "← "; }

        /* ── Filter Row ───────────────────────────────── */
        .filter-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-row .filter-pill {
            padding: 6px 16px;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--gray-500);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            background: var(--white);
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
        }
        .filter-row .filter-pill:hover { border-color: var(--gray-400); color: var(--gray-700); }
        .filter-row .filter-pill.active { background: var(--gray-900); color: var(--white); border-color: var(--gray-900); }

        /* ── Star Rating ──────────────────────────────── */
        .stars { color: var(--gray-300); font-size: 13px; letter-spacing: 1px; }
        .stars .filled { color: var(--gray-600); }

        /* ── Utility ──────────────────────────────────── */
        .mb-0  { margin-bottom: 0; }
        .mt-8  { margin-top: 8px; }
        .mt-16 { margin-top: 16px; }
        .mt-24 { margin-top: 24px; }
        .text-right { text-align: right; }
        .text-muted { color: var(--gray-400); }
        .inline-form { display: inline; }
        .gap-6 { gap: 6px; }
        .gap-8 { gap: 8px; }
        .gap-12 { gap: 12px; }
        .flex { display: flex; }
        .flex-wrap { flex-wrap: wrap; }
        .items-center { align-items: center; }
        .justify-end { justify-content: flex-end; }
        .w-full { width: 100%; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        @yield('extra_styles')
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="admin-shell">
        {{-- ── Sidebar ──────────────────────────────── --}}
        <aside class="admin-sidebar">
            <div class="sidebar-brand">FrontStore Admin</div>
            <ul class="sidebar-nav">
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>

                <li class="sidebar-section">Commerce</li>
                <li><a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">Orders</a></li>
                <li><a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">Products</a></li>
                <li><a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">Categories</a></li>

                <li class="sidebar-section">People</li>
                <li><a href="{{ route('admin.sellers') }}" class="{{ request()->routeIs('admin.sellers*') ? 'active' : '' }}">Sellers</a></li>
                <li><a href="{{ route('admin.customers') }}" class="{{ request()->routeIs('admin.customers*') ? 'active' : '' }}">Customers</a></li>

                <li class="sidebar-section">Finance</li>
                <li><a href="{{ route('admin.payouts') }}" class="{{ request()->routeIs('admin.payouts*') ? 'active' : '' }}">Payouts</a></li>

                <li class="sidebar-section">Support</li>
                <li><a href="{{ route('admin.returns') }}" class="{{ request()->routeIs('admin.returns*') ? 'active' : '' }}">Returns</a></li>
                <li><a href="{{ route('admin.refunds') }}" class="{{ request()->routeIs('admin.refunds*') ? 'active' : '' }}">Refunds</a></li>
                <li><a href="{{ route('admin.reviews') }}" class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">Reviews</a></li>

                <li class="sidebar-divider"></li>
                <li><a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">Platform Settings</a></li>
            </ul>
            <div class="sidebar-footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </aside>

        {{-- ── Main ─────────────────────────────────── --}}
        <div class="admin-main">
            <header class="admin-header">
                <h1>@yield('header', 'Dashboard')</h1>
                <div class="header-right">
                    <!-- Admin Notification Bell -->
                    <div class="admin-notif-bell" id="adminNotifBell" onclick="toggleAdminNotif(event)">
                        <i class="fas fa-bell"></i>
                        <span class="badge" id="adminNotifBadge" style="display:none;">0</span>
                        <div class="admin-notif-dropdown" id="adminNotifDropdown">
                            <div class="admin-ndd-header">
                                <span>Notifications</span>
                                <a href="#" onclick="markAllReadAdmin(event)">Mark all read</a>
                            </div>
                            <div id="adminNotifList">
                                <div class="admin-notif-empty">No notifications</div>
                            </div>
                        </div>
                    </div>
                    <span>{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            <div class="admin-content">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('danger'))
                    <div class="alert alert-danger">{{ session('danger') }}</div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    <script>
        @yield('extra_scripts')
    </script>

    {{-- ── Admin Notification Bell JS ── --}}
    <script>
        function toggleAdminNotif(e) {
            e.stopPropagation();
            const dd = document.getElementById('adminNotifDropdown');
            dd.classList.toggle('open');
            if (dd.classList.contains('open')) fetchAdminNotifications();
        }
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('adminNotifDropdown');
            if (dd && !e.target.closest('#adminNotifBell')) dd.classList.remove('open');
        });

        function fetchAdminNotifications() {
            fetch('{{ route("admin.notifications") }}')
              .then(r => r.json())
              .then(data => {
                const badge = document.getElementById('adminNotifBadge');
                const list = document.getElementById('adminNotifList');

                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }

                if (!data.notifications || data.notifications.length === 0) {
                    list.innerHTML = '<div class="admin-notif-empty">No notifications yet</div>';
                    return;
                }

                let iconMap = {
                    order: 'fa-shopping-bag', payout: 'fa-money-bill-wave',
                    ad: 'fa-ad', stock: 'fa-box-open'
                };

                list.innerHTML = data.notifications.map(n => {
                    let iconCls = iconMap[n.type] || 'fa-bell';
                    return `<div class="admin-notif-item ${n.is_read ? '' : 'unread'}" onclick="readAdminNotif(${n.id}, '${n.action_url || ''}')">
                        <div class="ni-icon ${n.icon}"><i class="fas ${iconCls}"></i></div>
                        <div class="ni-body">
                            <div class="ni-title">${n.title}</div>
                            <div class="ni-msg">${n.message}</div>
                            <div class="ni-time">${n.time_ago}</div>
                        </div>
                    </div>`;
                }).join('');
              });
        }

        function readAdminNotif(id, url) {
            fetch('/admin/notifications/' + id + '/read', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
            }).then(() => {
                fetchAdminNotifications();
                if (url) window.location.href = url;
            });
        }

        function markAllReadAdmin(e) {
            e.preventDefault(); e.stopPropagation();
            fetch('{{ route("admin.notifications.markAllRead") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
            }).then(() => fetchAdminNotifications());
        }

        // Auto-refresh badge every 30s
        setInterval(function() {
            fetch('{{ route("admin.notifications") }}')
              .then(r => r.json())
              .then(data => {
                const badge = document.getElementById('adminNotifBadge');
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
            fetch('{{ route("admin.notifications") }}')
              .then(r => r.json())
              .then(data => {
                const badge = document.getElementById('adminNotifBadge');
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                    badge.style.display = 'flex';
                }
              });
        });
    </script>
</body>
</html>
