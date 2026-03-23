@extends('admin.layout')
@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('extra_styles')
    /* ── Chart Section ────────────────────────────── */
    .charts-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }
    .charts-row-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }
    .chart-card {
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 22px 24px;
    }
    .chart-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .chart-card-title {
        font-family: "Montserrat", sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-700);
        letter-spacing: -.1px;
    }
    .chart-toggle {
        display: flex;
        gap: 4px;
    }
    .chart-toggle button {
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 500;
        border: 1px solid var(--gray-200);
        background: var(--white);
        color: var(--gray-500);
        border-radius: 4px;
        cursor: pointer;
        transition: all .15s;
    }
    .chart-toggle button.active,
    .chart-toggle button:hover {
        background: var(--gray-900);
        color: var(--white);
        border-color: var(--gray-900);
    }

    /* ── Top Products List ────────────────────────── */
    .top-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 0;
        border-bottom: 1px solid var(--gray-100);
    }
    .top-item:last-child { border-bottom: none; }
    .top-item-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .top-rank {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "Montserrat", sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: var(--gray-600);
    }
    .top-rank.rank-1 { background: #fef3c7; color: #92400e; }
    .top-rank.rank-2 { background: #e5e7eb; color: #374151; }
    .top-rank.rank-3 { background: #fed7aa; color: #9a3412; }
    .top-item-name {
        font-size: 13px;
        font-weight: 500;
        color: var(--gray-800);
    }
    .top-item-sub {
        font-size: 11px;
        color: var(--gray-400);
    }
    .top-item-value {
        font-family: "Montserrat", sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: var(--success);
    }

    /* ── Seller List ──────────────────────────────── */
    .seller-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "Montserrat", sans-serif;
        font-weight: 700;
        font-size: 13px;
        color: var(--gray-600);
    }

    /* ── Bottom Row ───────────────────────────────── */
    .bottom-row {
        display: grid;
        grid-template-columns: 5fr 3fr;
        gap: 20px;
        margin-bottom: 28px;
    }

    .empty-chart {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray-400);
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .charts-row, .charts-row-equal, .bottom-row {
            grid-template-columns: 1fr;
        }
    }
@endsection

@section('content')
    <h1 class="page-title">Overview</h1>
    <p class="page-subtitle">Platform snapshot at a glance</p>

    {{-- ── Stat Cards ───────────────────────────────── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Active Sellers</div>
            <div class="value">{{ $totalSellers }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending Approvals</div>
            <div class="value">{{ $pendingSellers }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Categories</div>
            <div class="value">{{ $totalCategories }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Products</div>
            <div class="value">{{ $totalProducts }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Total Orders</div>
            <div class="value">{{ $totalOrders }}</div>
        </div>
    </div>

    {{-- ── Revenue Summary ──────────────────────────── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="label">Platform Revenue</div>
            <div class="value"><span class="rupee">₹</span>{{ number_format($totalRevenue, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Commission Earned</div>
            <div class="value"><span class="rupee">₹</span>{{ number_format($totalCommission, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Platform Fees</div>
            <div class="value"><span class="rupee">₹</span>{{ number_format($totalPlatformFees, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Tax Collected</div>
            <div class="value"><span class="rupee">₹</span>{{ number_format($totalTaxCollected, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Seller Earnings</div>
            <div class="value"><span class="rupee">₹</span>{{ number_format($totalSellerEarnings, 2) }}</div>
        </div>
    </div>

    {{-- ── Charts Row 1: Revenue + Order Status ─────── --}}
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-card-header">
                <span class="chart-card-title">Revenue & Commission</span>
                <div class="chart-toggle">
                    <button class="active" onclick="switchRevenueChart('line', this)">Line</button>
                    <button onclick="switchRevenueChart('bar', this)">Bar</button>
                </div>
            </div>
            <canvas id="revenueChart" height="110"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <span class="chart-card-title">Orders by Status</span>
            </div>
            <canvas id="statusChart" height="180"></canvas>
        </div>
    </div>

    {{-- ── Charts Row 2: Monthly Orders + Top Products ─ --}}
    <div class="charts-row-equal">
        <div class="chart-card">
            <div class="chart-card-header">
                <span class="chart-card-title">Monthly Orders</span>
            </div>
            <canvas id="monthlyOrdersChart" height="130"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <span class="chart-card-title">Top Selling Products</span>
            </div>
            @if(count($topProducts) > 0)
                @foreach($topProducts as $index => $product)
                    <div class="top-item">
                        <div class="top-item-left">
                            <div class="top-rank rank-{{ $index + 1 }}">{{ $index + 1 }}</div>
                            <div>
                                <div class="top-item-name">{{ $product->product_name }}</div>
                                <div class="top-item-sub">{{ $product->total_sold }} units sold</div>
                            </div>
                        </div>
                        <div class="top-item-value"><span class="rupee">₹</span>{{ number_format($product->total_revenue, 2) }}</div>
                    </div>
                @endforeach
            @else
                <div class="empty-chart">No sales data yet</div>
            @endif
        </div>
    </div>

    {{-- ── Recent Orders + Top Sellers ──────────────── --}}
    <div class="bottom-row">
        <div class="table-wrap">
            <div class="table-header">
                <h2>Recent Orders</h2>
                <a href="{{ route('admin.orders') }}" class="btn-link">View all</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td><span class="rupee">₹</span>{{ number_format($order->grand_total ?? $order->total_price, 2) }}</td>
                            <td><span class="status-text {{ $order->status }}">{{ $order->status }}</span></td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <span class="chart-card-title">Top Sellers</span>
            </div>
            @if(count($topSellers) > 0)
                @foreach($topSellers as $index => $seller)
                    <div class="top-item">
                        <div class="top-item-left">
                            <div class="seller-avatar">{{ strtoupper(substr($seller->name, 0, 1)) }}</div>
                            <div>
                                <div class="top-item-name">{{ $seller->business_name ?? $seller->name }}</div>
                                <div class="top-item-sub">{{ $seller->total_orders }} orders</div>
                            </div>
                        </div>
                        <div class="top-item-value"><span class="rupee">₹</span>{{ number_format($seller->total_earnings, 2) }}</div>
                    </div>
                @endforeach
            @else
                <div class="empty-chart">No seller data yet</div>
            @endif
        </div>
    </div>
@endsection

@section('extra_scripts')
    // ── Chart.js Dynamic Charts ─────────────────────────

    // Data from backend (dynamic)
    const monthlyRevenueData = @json($monthlyRevenue);
    const ordersByStatus     = @json($ordersByStatus);
    const monthlyOrdersData  = @json($monthlyOrders);

    // Palette
    const C = {
        dark:    '#18181b',
        mid:     '#71717a',
        light:   '#e4e4e7',
        accent:  '#334155',
        green:   '#166534',
        greenBg: 'rgba(22,101,52,0.08)',
        blue:    '#1e40af',
        blueBg:  'rgba(30,64,175,0.08)',
        orange:  '#c2410c',
        red:     '#991b1b',
        purple:  '#6d28d9',
        amber:   '#b45309',
        teal:    '#0f766e',
        slate:   '#475569',
    };

    const statusColorMap = {
        'delivered':  C.green,
        'pending':    C.amber,
        'cancelled':  C.red,
        'processing': C.blue,
        'shipped':    C.purple,
        'confirmed':  C.teal,
    };

    // ── Revenue Chart ───────────────────────────────
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart = createRevenueChart('line');

    function createRevenueChart(type) {
        return new Chart(revenueCtx, {
            type: type,
            data: {
                labels: monthlyRevenueData.map(i => i.month),
                datasets: [
                    {
                        label: 'Revenue (₹)',
                        data: monthlyRevenueData.map(i => i.revenue),
                        borderColor: C.dark,
                        backgroundColor: type === 'bar' ? C.dark : 'rgba(24,24,27,0.06)',
                        borderWidth: type === 'bar' ? 0 : 2.5,
                        fill: type !== 'bar',
                        tension: 0.35,
                        borderRadius: type === 'bar' ? 6 : 0,
                        pointBackgroundColor: C.dark,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: type === 'bar' ? 0 : 4,
                        pointHoverRadius: type === 'bar' ? 0 : 6,
                    },
                    {
                        label: 'Commission (₹)',
                        data: monthlyRevenueData.map(i => i.commission),
                        borderColor: C.mid,
                        backgroundColor: type === 'bar' ? C.mid : 'rgba(113,113,122,0.06)',
                        borderWidth: type === 'bar' ? 0 : 2,
                        fill: type !== 'bar',
                        tension: 0.35,
                        borderRadius: type === 'bar' ? 6 : 0,
                        borderDash: type === 'bar' ? [] : [5,4],
                        pointBackgroundColor: C.mid,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: type === 'bar' ? 0 : 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { usePointStyle: true, pointStyle: 'circle', padding: 16, font: { size: 11, weight: '500' } }
                    },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleFont: { size: 12 },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: ctx => ctx.dataset.label + ': ₹' + ctx.parsed.y.toLocaleString('en-IN')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                        ticks: { callback: v => '₹' + v.toLocaleString('en-IN'), font: { size: 10 }, color: '#a1a1aa' },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#a1a1aa' },
                        border: { display: false }
                    }
                }
            }
        });
    }

    function switchRevenueChart(type, btn) {
        document.querySelectorAll('.chart-toggle button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        revenueChart.destroy();
        revenueChart = createRevenueChart(type);
    }

    // ── Order Status Doughnut ───────────────────────
    const statusLabels = Object.keys(ordersByStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1));
    const statusValues = Object.values(ordersByStatus);
    const statusColors = Object.keys(ordersByStatus).map(s => statusColorMap[s] || C.slate);

    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: statusLabels.length ? statusLabels : ['No Data'],
            datasets: [{
                data: statusValues.length ? statusValues : [1],
                backgroundColor: statusColors.length ? statusColors : [C.light],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, font: { size: 11 } }
                },
                tooltip: {
                    backgroundColor: '#18181b',
                    padding: 10,
                    cornerRadius: 6,
                    callbacks: {
                        label: ctx => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = ((ctx.parsed / total) * 100).toFixed(1);
                            return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

    // ── Monthly Orders Bar Chart ────────────────────
    new Chart(document.getElementById('monthlyOrdersChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthlyOrdersData.map(i => i.month),
            datasets: [{
                label: 'Orders',
                data: monthlyOrdersData.map(i => i.count),
                backgroundColor: C.accent,
                borderRadius: 6,
                borderSkipped: false,
                barThickness: 28,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#18181b',
                    padding: 10,
                    cornerRadius: 6,
                    callbacks: { label: ctx => 'Orders: ' + ctx.parsed.y }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: { stepSize: 1, font: { size: 10 }, color: '#a1a1aa' },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, color: '#a1a1aa' },
                    border: { display: false }
                }
            }
        }
    });

    // ── Auto-refresh every 60s ──────────────────────
    setInterval(function() {
        fetch('{{ route("admin.dashboard.chart-data") }}?type=revenue')
            .then(r => r.json())
            .then(data => {
                revenueChart.data.labels = data.map(i => i.month);
                revenueChart.data.datasets[0].data = data.map(i => i.revenue);
                revenueChart.data.datasets[1].data = data.map(i => i.commission);
                revenueChart.update('none');
            })
            .catch(() => {});
    }, 60000);
@endsection
