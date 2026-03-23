<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — FrontStore Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:    #0a0e1a;
            --navy2:   #0d1526;
            --blue:    #1a2d5a;
            --accent:  #3b82f6;
            --accent2: #60a5fa;
            --cyan:    #06b6d4;
            --white:   #ffffff;
            --gray:    #94a3b8;
            --border:  rgba(255,255,255,0.08);
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: var(--navy);
            overflow: hidden;
        }

        /* ── FULL-SCREEN CANVAS LAYER ── */
        #bg-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* ── WAVE SVG LAYER ── */
        .waves-layer {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }
        .wave-path {
            fill: none;
            stroke-width: 1.5;
            opacity: 0;
            animation: waveDraw 4s ease forwards;
        }
        .wave-path:nth-child(1) { stroke: rgba(59,130,246,0.45); animation-delay: 0s; }
        .wave-path:nth-child(2) { stroke: rgba(6,182,212,0.35);  animation-delay: 0.4s; }
        .wave-path:nth-child(3) { stroke: rgba(99,102,241,0.30); animation-delay: 0.8s; }
        .wave-path:nth-child(4) { stroke: rgba(59,130,246,0.25); animation-delay: 1.2s; }
        .wave-path:nth-child(5) { stroke: rgba(6,182,212,0.20);  animation-delay: 1.6s; }

        @keyframes waveDraw {
            0%   { opacity: 0; stroke-dashoffset: 3000; }
            10%  { opacity: 1; }
            100% { opacity: 1; stroke-dashoffset: 0; }
        }

        /* continuous drift after draw */
        .wave-container {
            animation: waveDrift 18s linear infinite;
        }
        @keyframes waveDrift { from { transform: translateX(0); } to { transform: translateX(-120px); } }

        /* ── LAYOUT ── */
        .page {
            position: relative;
            z-index: 10;
            display: grid;
            grid-template-columns: 1fr 420px;
            height: 100vh;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 70px;
            position: relative;
        }

        .brand-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(59,130,246,0.12);
            border: 1px solid rgba(59,130,246,0.25);
            border-radius: 50px;
            padding: 6px 16px;
            margin-bottom: 40px;
            width: fit-content;
        }
        .brand-tag .dot {
            width: 8px; height: 8px;
            background: #22d3ee;
            border-radius: 50%;
            box-shadow: 0 0 8px #22d3ee;
            animation: blink 1.4s ease-in-out infinite;
        }
        @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:0.3; } }
        .brand-tag span { font-size: 12px; font-weight: 600; color: #93c5fd; letter-spacing: 1px; text-transform: uppercase; }

        .hero-title {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(36px, 4vw, 56px);
            font-weight: 900;
            line-height: 1.1;
            color: var(--white);
            margin-bottom: 20px;
        }
        .hero-title .highlight {
            font-family: 'Dancing Script', cursive;
            background: linear-gradient(90deg, #3b82f6, #06b6d4, #818cf8);
            background-size: 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 4s linear infinite;
        }
        @keyframes shimmer { from { background-position: 0% } to { background-position: 200% } }

        .hero-sub {
            font-size: 15px;
            color: var(--gray);
            max-width: 420px;
            line-height: 1.7;
            margin-bottom: 48px;
        }

        /* ── MOCKUP DASHBOARD CARDS ── */
        .mockup {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            max-width: 480px;
        }
        .mock-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            backdrop-filter: blur(4px);
            animation: floatUp .6s ease both;
        }
        .mock-card:nth-child(1) { animation-delay: .1s; }
        .mock-card:nth-child(2) { animation-delay: .25s; }
        .mock-card:nth-child(3) { animation-delay: .4s; }
        .mock-card:nth-child(4) { animation-delay: .55s; grid-column: span 2; }
        .mock-card:nth-child(5) { animation-delay: .7s; }
        @keyframes floatUp { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: translateY(0); } }

        .mock-card-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray);
            margin-bottom: 8px;
        }
        .mock-card-value {
            font-size: 22px;
            font-weight: 700;
            color: var(--white);
        }
        .mock-card-badge {
            font-size: 10px;
            color: #34d399;
            margin-top: 4px;
        }
        .mock-bar { height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; margin-top: 10px; overflow: hidden; }
        .mock-bar-fill {
            height: 100%;
            border-radius: 2px;
            animation: barGrow 1.5s ease both;
        }
        @keyframes barGrow { from { width: 0%; } }
        .mock-bar-fill.blue  { width: 72%; background: linear-gradient(90deg,#3b82f6,#06b6d4); animation-delay: .8s; }
        .mock-bar-fill.green { width: 55%; background: linear-gradient(90deg,#10b981,#34d399); animation-delay: 1s; }
        .mock-bar-fill.purple{ width: 88%; background: linear-gradient(90deg,#8b5cf6,#a78bfa); animation-delay: 1.2s; }

        .mock-sparkline {
            margin-top: 10px;
            height: 30px;
        }

        /* ── RIGHT PANEL (FORM) ── */
        .right-panel {
            background: rgba(255,255,255,0.03);
            border-left: 1px solid var(--border);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }

        .form-box {
            width: 100%;
            max-width: 340px;
        }

        .form-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }
        .form-logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .form-logo-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: var(--white);
        }
        .form-logo-text span { color: #60a5fa; }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 6px;
        }
        .form-subtitle {
            font-size: 13px;
            color: var(--gray);
            margin-bottom: 32px;
        }

        .field-group { margin-bottom: 18px; }
        .field-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #64748b;
            margin-bottom: 7px;
        }
        .field-wrap {
            position: relative;
        }
        .field-wrap .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 15px;
            color: #475569;
            pointer-events: none;
        }
        .field-group input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            font-size: 14px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 8px;
            color: var(--white);
            transition: border-color .2s, background .2s;
            font-family: 'Poppins', sans-serif;
        }
        .field-group input::placeholder { color: #475569; }
        .field-group input:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(59,130,246,0.07);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .remember-row input[type=checkbox] {
            width: 16px; height: 16px;
            accent-color: #3b82f6;
            cursor: pointer;
        }
        .remember-row label {
            font-size: 13px;
            color: var(--gray);
            font-weight: 400;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            background: linear-gradient(90deg, #2563eb, #0891b2);
            color: var(--white);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            letter-spacing: .3px;
            position: relative;
            overflow: hidden;
            transition: opacity .15s, transform .1s;
        }
        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.15) 50%, transparent 100%);
            transform: translateX(-100%);
            animation: btnShimmer 2.5s ease-in-out infinite;
        }
        @keyframes btnShimmer { 0%,100% { transform: translateX(-100%); } 50% { transform: translateX(100%); } }
        .btn-submit:hover { opacity: .9; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .error-msg {
            background: rgba(153,27,27,0.2);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #fca5a5;
            margin-bottom: 20px;
        }

        .form-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 11px;
            color: #334155;
        }
    </style>
</head>
<body>

<!-- ── PARTICLE CANVAS ── -->
<canvas id="bg-canvas"></canvas>

<!-- ── ANIMATED WAVE SVG ── -->
<div class="waves-layer">
    <svg width="100%" height="100%" preserveAspectRatio="none" viewBox="0 0 1440 900" xmlns="http://www.w3.org/2000/svg">
        <g class="wave-container">
            <!-- Wave 1 -->
            <path class="wave-path" stroke-dasharray="3000" stroke-dashoffset="3000"
                d="M-100,200 C100,120 300,280 500,200 C700,120 900,280 1100,200 C1300,120 1500,280 1700,200" />
            <!-- Wave 2 -->
            <path class="wave-path" stroke-dasharray="3000" stroke-dashoffset="3000"
                d="M-100,340 C150,260 350,420 550,340 C750,260 950,420 1150,340 C1350,260 1550,420 1750,340" />
            <!-- Wave 3 -->
            <path class="wave-path" stroke-dasharray="3000" stroke-dashoffset="3000"
                d="M-100,480 C200,400 400,560 600,480 C800,400 1000,560 1200,480 C1400,400 1600,560 1800,480" />
            <!-- Wave 4 -->
            <path class="wave-path" stroke-dasharray="3000" stroke-dashoffset="3000"
                d="M-100,620 C120,540 320,700 520,620 C720,540 920,700 1120,620 C1320,540 1520,700 1720,620" />
            <!-- Wave 5 -->
            <path class="wave-path" stroke-dasharray="3000" stroke-dashoffset="3000"
                d="M-100,760 C180,680 380,840 580,760 C780,680 980,840 1180,760 C1380,680 1580,840 1780,760" />
        </g>
    </svg>
</div>

<!-- ── PAGE ── -->
<div class="page">

    <!-- LEFT PANEL -->
    <div class="left-panel">

        <div class="brand-tag">
            <div class="dot"></div>
            <span>Admin System Live</span>
        </div>

        <h1 class="hero-title">
            Control Your<br>
            <span class="highlight">FrontStore</span><br>
            Dashboard
        </h1>
        <p class="hero-sub">
            Full visibility into orders, sellers, customers, payments, and reports — all in one secure panel.
        </p>

        <!-- Mockup cards -->
        <div class="mockup">
            <div class="mock-card">
                <div class="mock-card-label">Revenue</div>
                <div class="mock-card-value">₹4.2L</div>
                <div class="mock-card-badge">↑ 18% this week</div>
                <div class="mock-bar"><div class="mock-bar-fill blue"></div></div>
            </div>
            <div class="mock-card">
                <div class="mock-card-label">Orders</div>
                <div class="mock-card-value">1,382</div>
                <div class="mock-card-badge">↑ 9% today</div>
                <div class="mock-bar"><div class="mock-bar-fill green"></div></div>
            </div>
            <div class="mock-card">
                <div class="mock-card-label">Sellers</div>
                <div class="mock-card-value">247</div>
                <div class="mock-card-badge">↑ 4 new</div>
                <div class="mock-bar"><div class="mock-bar-fill purple"></div></div>
            </div>
            <div class="mock-card">
                <div class="mock-card-label">Transaction Activity</div>
                <div class="mock-card-value" style="font-size:14px;color:#94a3b8;font-weight:400;">Live chart ─ updates every 30s</div>
                <svg class="mock-sparkline" viewBox="0 0 200 30" preserveAspectRatio="none">
                    <polyline
                        fill="none" stroke="url(#sg)" stroke-width="1.5" stroke-linejoin="round"
                        points="0,25 20,18 40,22 60,10 80,16 100,8 120,14 140,5 160,12 180,4 200,9"/>
                    <defs>
                        <linearGradient id="sg" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#3b82f6"/>
                            <stop offset="100%" stop-color="#06b6d4"/>
                        </linearGradient>
                    </defs>
                    <circle r="3" fill="#06b6d4" cx="200" cy="9">
                        <animate attributeName="opacity" values="1;0;1" dur="1s" repeatCount="indefinite"/>
                    </circle>
                </svg>
            </div>
            <div class="mock-card">
                <div class="mock-card-label">Returns</div>
                <div class="mock-card-value">38</div>
                <div class="mock-card-badge" style="color:#f87171;">↑ 2 pending</div>
                <div class="mock-bar"><div class="mock-bar-fill" style="width:30%;background:linear-gradient(90deg,#f97316,#ef4444);animation:barGrow 1.5s ease both;animation-delay:1.4s;"></div></div>
            </div>
        </div>

    </div>

    <!-- RIGHT PANEL (FORM) -->
    <div class="right-panel">
        <div class="form-box">

            <div class="form-logo">
                <div class="form-logo-icon">⚡</div>
                <div class="form-logo-text">Front<span>Store</span></div>
            </div>

            <div class="form-title">Welcome back</div>
            <div class="form-subtitle">Sign in to your admin account</div>

            @if($errors->any())
                <div class="error-msg">
                    @foreach($errors->all() as $error)
                        <div>⚠ {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="field-group">
                    <label for="email">Email</label>
                    <div class="field-wrap">
                        <span class="icon">✉</span>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="admin@example.com"
                               required autofocus>
                    </div>
                </div>

                <div class="field-group">
                    <label for="password">Password</label>
                    <div class="field-wrap">
                        <span class="icon">🔒</span>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="form-footer">© 2026 FrontStore Admin Panel · Secure Access</div>
        </div>
    </div>

</div>

<script>
// ── FLOATING PARTICLES ──
const canvas = document.getElementById('bg-canvas');
const ctx    = canvas.getContext('2d');
let W, H, particles = [];

function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
}
resize();
window.addEventListener('resize', resize);

function makeParticle() {
    return {
        x:  Math.random() * W,
        y:  Math.random() * H,
        r:  Math.random() * 1.8 + 0.4,
        vx: (Math.random() - 0.5) * 0.3,
        vy: (Math.random() - 0.5) * 0.3,
        a:  Math.random() * 0.5 + 0.1,
        color: Math.random() > 0.5 ? '59,130,246' : '6,182,212',
    };
}
for (let i = 0; i < 120; i++) particles.push(makeParticle());

function draw() {
    ctx.clearRect(0, 0, W, H);
    // draw connections
    for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
            const dx = particles[i].x - particles[j].x;
            const dy = particles[i].y - particles[j].y;
            const dist = Math.sqrt(dx*dx + dy*dy);
            if (dist < 100) {
                ctx.beginPath();
                ctx.strokeStyle = `rgba(59,130,246,${0.12 * (1 - dist/100)})`;
                ctx.lineWidth = 0.5;
                ctx.moveTo(particles[i].x, particles[i].y);
                ctx.lineTo(particles[j].x, particles[j].y);
                ctx.stroke();
            }
        }
    }
    // draw dots
    particles.forEach(p => {
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(${p.color},${p.a})`;
        ctx.fill();
        p.x += p.vx;
        p.y += p.vy;
        if (p.x < 0 || p.x > W) p.vx *= -1;
        if (p.y < 0 || p.y > H) p.vy *= -1;
    });
    requestAnimationFrame(draw);
}
draw();
</script>

</body>
</html>
