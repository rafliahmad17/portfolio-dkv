{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DKV SMEKDA — Platform Portfolio Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        red: {
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --red: #dc2626;
            --red-glow: rgba(220, 38, 38, 0.45);
            --red-soft: rgba(220, 38, 38, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            background-color: #080808;
            color: #f5f5f5;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* ====== SCROLLBAR ====== */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        /* ====== NOISE TEXTURE OVERLAY ====== */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        /* ====== NAVBAR ====== */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 18px 0;
            transition: all 0.4s ease;
        }

        .navbar.scrolled {
            background: rgba(8,8,8,0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 12px 0;
        }

        .nav-logo {
            font-size: 1.25rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: white;
            text-transform: uppercase;
        }

        .nav-logo span { color: var(--red); }

        .btn-outline-white {
            border: 1.5px solid rgba(255,255,255,0.25);
            color: white;
            padding: 9px 22px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-white:hover {
            border-color: white;
            background: rgba(255,255,255,0.07);
        }

        .btn-red {
            background: var(--red);
            color: white;
            padding: 9px 22px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: 1.5px solid var(--red);
        }

        .btn-red:hover {
            background: #b91c1c;
            box-shadow: 0 0 24px var(--red-glow);
            transform: translateY(-1px);
        }

        /* ====== HERO ====== */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
            position: relative;
            overflow: hidden;
        }

        /* Background grid */
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(220,38,38,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220,38,38,0.04) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
        }

        /* Red glow blob top-left */
        .glow-blob-1 {
            position: absolute;
            top: -100px;
            left: -150px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(220,38,38,0.15) 0%, transparent 65%);
            pointer-events: none;
            animation: blobPulse 6s ease-in-out infinite alternate;
        }

        /* Red glow blob bottom-right */
        .glow-blob-2 {
            position: absolute;
            bottom: -80px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(220,38,38,0.10) 0%, transparent 65%);
            pointer-events: none;
            animation: blobPulse 8s ease-in-out infinite alternate-reverse;
        }

        @keyframes blobPulse {
            0%   { transform: scale(1) translate(0, 0); opacity: 0.8; }
            100% { transform: scale(1.15) translate(20px, -20px); opacity: 1; }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--red-soft);
            border: 1px solid rgba(220,38,38,0.25);
            color: #fca5a5;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .hero-badge .dot {
            width: 6px; height: 6px;
            background: var(--red);
            border-radius: 50%;
            animation: dotPulse 1.5s ease-in-out infinite;
        }

        @keyframes dotPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.7); }
        }

        .hero-headline {
            font-size: clamp(2.4rem, 5.5vw, 4.4rem);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -2px;
            color: white;
            margin-bottom: 24px;
        }

        .hero-headline .highlight {
            position: relative;
            display: inline-block;
        }

        .hero-headline .highlight::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 0; right: 0;
            height: 3px;
            background: var(--red);
            border-radius: 2px;
            transform-origin: left;
            animation: underlineIn 1s ease 0.5s both;
        }

        @keyframes underlineIn {
            from { transform: scaleX(0); }
            to   { transform: scaleX(1); }
        }

        .hero-headline .text-red {
            color: var(--red);
            text-shadow: 0 0 40px rgba(220,38,38,0.4);
        }

        .hero-sub {
            font-size: 1rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.7;
            max-width: 560px;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--red);
            color: white;
            padding: 15px 36px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 rgba(220,38,38,0);
            position: relative;
            overflow: hidden;
        }

        .btn-cta::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px var(--red-glow), 0 0 0 4px rgba(220,38,38,0.15);
        }

        .btn-cta:hover::before { opacity: 1; }
        .btn-cta span { position: relative; z-index: 1; }
        .btn-cta svg { position: relative; z-index: 1; transition: transform 0.3s ease; }
        .btn-cta:hover svg { transform: translateX(4px); }

        /* ====== HERO VISUAL (Right Side) ====== */
        .hero-visual {
            position: relative;
        }

        .visual-frame {
            position: relative;
            width: 100%;
            max-width: 460px;
            margin-left: auto;
        }

        .visual-main {
            background: #111111;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .visual-main::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--red), transparent);
        }

        .v-topbar {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
        }

        .v-dot { width: 10px; height: 10px; border-radius: 50%; }
        .v-dot.r { background: #ef4444; }
        .v-dot.y { background: #f59e0b; }
        .v-dot.g { background: #22c55e; }

        .v-card {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: border-color 0.3s ease;
        }

        .v-card:hover { border-color: rgba(220,38,38,0.3); }

        .v-thumb {
            width: 48px; height: 48px;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .v-thumb.t1 { background: linear-gradient(135deg, #7c3aed, #4f46e5); }
        .v-thumb.t2 { background: linear-gradient(135deg, #dc2626, #9333ea); }
        .v-thumb.t3 { background: linear-gradient(135deg, #0ea5e9, #06b6d4); }

        .v-info { flex: 1; min-width: 0; }
        .v-title { font-size: 11px; font-weight: 700; color: white; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .v-sub { font-size: 9px; color: rgba(255,255,255,0.35); }

        .v-badge {
            font-size: 8px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .v-badge.ui { background: rgba(124,58,237,0.2); color: #a78bfa; border: 1px solid rgba(124,58,237,0.25); }
        .v-badge.ps { background: rgba(220,38,38,0.15); color: #fca5a5; border: 1px solid rgba(220,38,38,0.2); }
        .v-badge.il { background: rgba(14,165,233,0.15); color: #7dd3fc; border: 1px solid rgba(14,165,233,0.2); }

        .v-stat-row {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .v-stat {
            flex: 1;
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }

        .v-stat-num { font-size: 18px; font-weight: 900; color: white; }
        .v-stat-num.red { color: var(--red); }
        .v-stat-label { font-size: 8px; color: rgba(255,255,255,0.3); margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Floating badge */
        .float-badge {
            position: absolute;
            background: #111;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .float-badge.top {
            top: -20px;
            right: -20px;
            animation: floatY 4s ease-in-out infinite;
        }

        .float-badge.bottom {
            bottom: -18px;
            left: -18px;
            animation: floatY 5s ease-in-out infinite reverse;
        }

        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .float-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
        }

        .float-text-main { font-size: 11px; font-weight: 700; color: white; }
        .float-text-sub { font-size: 8.5px; color: rgba(255,255,255,0.35); }

        /* ====== FEATURES SECTION ====== */
        .features-section {
            padding: 100px 0;
            position: relative;
        }

        .section-eyebrow {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--red);
            margin-bottom: 14px;
        }

        .section-title {
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            font-weight: 900;
            color: white;
            letter-spacing: -1px;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .section-sub {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.4);
            max-width: 480px;
            line-height: 1.7;
        }

        .feature-card {
            background: #0f0f0f;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            padding: 32px 28px;
            position: relative;
            overflow: hidden;
            transition: all 0.35s ease;
            height: 100%;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top left, rgba(220,38,38,0.07), transparent 60%);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .feature-card:hover {
            border-color: rgba(220,38,38,0.3);
            transform: translateY(-6px);
            box-shadow: 0 24px 60px rgba(0,0,0,0.4), 0 0 0 1px rgba(220,38,38,0.1);
        }

        .feature-card:hover::before { opacity: 1; }

        .feature-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--red), transparent);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .feature-card:hover::after { opacity: 1; }

        .feature-icon-wrap {
            width: 54px; height: 54px;
            border-radius: 14px;
            background: var(--red-soft);
            border: 1px solid rgba(220,38,38,0.2);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 22px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon-wrap {
            background: rgba(220,38,38,0.2);
            box-shadow: 0 0 20px rgba(220,38,38,0.25);
        }

        .feature-number {
            position: absolute;
            top: 24px; right: 24px;
            font-size: 4rem;
            font-weight: 900;
            color: rgba(255,255,255,0.03);
            line-height: 1;
            letter-spacing: -3px;
            pointer-events: none;
            transition: color 0.35s ease;
        }

        .feature-card:hover .feature-number {
            color: rgba(220,38,38,0.06);
        }

        .feature-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: white;
            margin-bottom: 10px;
            letter-spacing: -0.3px;
        }

        .feature-desc {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.4);
            line-height: 1.7;
        }

        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 20px;
            font-size: 0.72rem;
            color: #fca5a5;
            font-weight: 600;
        }

        .feature-tag::before {
            content: '';
            width: 16px; height: 1.5px;
            background: var(--red);
            border-radius: 2px;
        }

        /* ====== STATS STRIP ====== */
        .stats-strip {
            padding: 60px 0;
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: #0b0b0b;
        }

        .stat-item { text-align: center; }

        .stat-number {
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            font-weight: 900;
            color: white;
            letter-spacing: -2px;
            line-height: 1;
        }

        .stat-number span { color: var(--red); }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.35);
            font-weight: 500;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.07);
            height: 50px;
            margin: auto;
        }

        /* ====== CTA SECTION ====== */
        .cta-section {
            padding: 100px 0;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 700px; height: 400px;
            background: radial-gradient(ellipse, rgba(220,38,38,0.12), transparent 70%);
            pointer-events: none;
        }

        .cta-title {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 900;
            color: white;
            letter-spacing: -1.5px;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .cta-sub {
            font-size: 1rem;
            color: rgba(255,255,255,0.4);
            max-width: 440px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        /* ====== FOOTER ====== */
        .footer {
            border-top: 1px solid rgba(255,255,255,0.06);
            background: #050505;
            padding: 48px 0 32px;
        }

        .footer-logo {
            font-size: 1.15rem;
            font-weight: 900;
            color: white;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .footer-logo span { color: var(--red); }

        .footer-desc {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.3);
            line-height: 1.7;
            max-width: 260px;
        }

        .footer-divider {
            border-color: rgba(255,255,255,0.06);
            margin: 32px 0 24px;
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-copy {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.2);
        }

        .footer-copy span { color: var(--red); }

        .footer-dev {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.2);
        }

        .footer-dev strong { color: rgba(255,255,255,0.45); }

        /* ====== ANIMATE ON SCROLL ====== */
        .anim-fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .anim-fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .anim-delay-1 { transition-delay: 0.1s; }
        .anim-delay-2 { transition-delay: 0.2s; }
        .anim-delay-3 { transition-delay: 0.35s; }

        /* ====== CONTAINER ====== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }
    </style>
</head>
<body>

{{-- ========================================================
     NAVBAR
======================================================== --}}
<nav class="navbar" id="navbar">
    <div class="container">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div class="nav-logo">DKV<span>.</span>SMEKDA</div>
            <div style="display:flex; align-items:center; gap:12px;">
                <a href="{{ route('login') }}" class="btn-outline-white">Login</a>
                <a href="{{ route('login') }}" class="btn-red">Register Siswa</a>
            </div>
        </div>
    </div>
</nav>

{{-- ========================================================
     HERO SECTION
======================================================== --}}
<section class="hero-section">
    <div class="glow-blob-1"></div>
    <div class="glow-blob-2"></div>

    <div class="container" style="position:relative; z-index:2; width:100%;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center;">

            {{-- LEFT: TEXT --}}
            <div>
                <div class="hero-badge">
                    <div class="dot"></div>
                    Platform Resmi DKV SMKN 2 Padang Panjang
                </div>

                <h1 class="hero-headline">
                    Kreativitas<br>
                    Tanpa Batas,<br>
                    <span class="highlight">
                        <span class="text-red">Terarsip</span>
                    </span><br>
                    Tanpa Kertas.
                </h1>

                <p class="hero-sub">
                    Platform resmi manajemen portofolio digital untuk siswa Desain Komunikasi Visual
                    SMKN 2 Padang Panjang. Mendukung Kurikulum Merdeka berbasis Industri 4.0.
                </p>

                <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                    <a href="{{ route('login') }}" class="btn-cta">
                        <span>Mulai Berkarya</span>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="display:flex;">
                            <div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#4f46e5);border:2px solid #080808;"></div>
                            <div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#dc2626,#9333ea);border:2px solid #080808;margin-left:-8px;"></div>
                            <div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#06b6d4);border:2px solid #080808;margin-left:-8px;"></div>
                        </div>
                        <span style="font-size:0.78rem; color:rgba(255,255,255,0.35);">Sudah digunakan siswa DKV</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: VISUAL --}}
            <div class="hero-visual">
                <div class="visual-frame">

                    {{-- Floating Top --}}
                    <div class="float-badge top">
                        <div class="float-icon" style="background: rgba(220,38,38,0.15);">📄</div>
                        <div>
                            <div class="float-text-main">PDF Generated!</div>
                            <div class="float-text-sub">Katalog siap diunduh</div>
                        </div>
                    </div>

                    {{-- Main Card --}}
                    <div class="visual-main">
                        <div class="v-topbar">
                            <div class="v-dot r"></div>
                            <div class="v-dot y"></div>
                            <div class="v-dot g"></div>
                            <div style="margin-left:8px; font-size:9px; color:rgba(255,255,255,0.2); font-weight:600;">Portfolio Dashboard</div>
                        </div>

                        <div class="v-card">
                            <div class="v-thumb t1"></div>
                            <div class="v-info">
                                <div class="v-title">Redesign App E-Commerce</div>
                                <div class="v-sub">Upload 2 jam lalu</div>
                            </div>
                            <div class="v-badge ui">UI/UX</div>
                        </div>

                        <div class="v-card">
                            <div class="v-thumb t2"></div>
                            <div class="v-info">
                                <div class="v-title">Poster Kampanye Lingkungan</div>
                                <div class="v-sub">Upload kemarin</div>
                            </div>
                            <div class="v-badge ps">Poster</div>
                        </div>

                        <div class="v-card">
                            <div class="v-thumb t3"></div>
                            <div class="v-info">
                                <div class="v-title">Brand Identity UMKM Lokal</div>
                                <div class="v-sub">Upload 3 hari lalu</div>
                            </div>
                            <div class="v-badge il">Ilustrasi</div>
                        </div>

                        <div class="v-stat-row">
                            <div class="v-stat">
                                <div class="v-stat-num red">12</div>
                                <div class="v-stat-label">Karya</div>
                            </div>
                            <div class="v-stat">
                                <div class="v-stat-num">4</div>
                                <div class="v-stat-label">Kategori</div>
                            </div>
                            <div class="v-stat">
                                <div class="v-stat-num red">1</div>
                                <div class="v-stat-label">PDF Siap</div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Bottom --}}
                    <div class="float-badge bottom">
                        <div class="float-icon" style="background: rgba(34,197,94,0.15);">✅</div>
                        <div>
                            <div class="float-text-main">Karya Tersimpan</div>
                            <div class="float-text-sub">Aman & terorganisir</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================
     STATS STRIP
======================================================== --}}
<div class="stats-strip">
    <div class="container">
        <div style="display:grid; grid-template-columns: 1fr auto 1fr auto 1fr auto 1fr; align-items:center; gap:16px;">

            <div class="stat-item anim-fade-up">
                <div class="stat-number">12<span>+</span></div>
                <div class="stat-label">Kompetensi DKV</div>
            </div>

            <div class="stat-divider"></div>

            <div class="stat-item anim-fade-up anim-delay-1">
                <div class="stat-number"><span>∞</span></div>
                <div class="stat-label">Karya Dapat Disimpan</div>
            </div>

            <div class="stat-divider"></div>

            <div class="stat-item anim-fade-up anim-delay-2">
                <div class="stat-number">1<span>-click</span></div>
                <div class="stat-label">Export ke PDF</div>
            </div>

            <div class="stat-divider"></div>

            <div class="stat-item anim-fade-up anim-delay-3">
                <div class="stat-number">100<span>%</span></div>
                <div class="stat-label">Berbasis Web</div>
            </div>

        </div>
    </div>
</div>

{{-- ========================================================
     FEATURES SECTION
======================================================== --}}
<section class="features-section">
    <div class="container">

        <div style="text-align:center; margin-bottom:64px;" class="anim-fade-up">
            <div class="section-eyebrow">Mengapa Platform Ini?</div>
            <h2 class="section-title" style="margin:0 auto 16px;">
                Dirancang Serius<br>untuk Generasi Kreatif
            </h2>
            <p class="section-sub" style="margin:0 auto;">
                Bukan sekadar penyimpanan biasa. Ini adalah ekosistem digital yang mendukung
                perjalanan kreatif siswa DKV dari awal hingga industri.
            </p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">

            {{-- Card 1 --}}
            <div class="feature-card anim-fade-up">
                <div class="feature-number">01</div>
                <div class="feature-icon-wrap">
                    <svg width="24" height="24" fill="none" stroke="#dc2626" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <h3 class="feature-title">Arsip Digital Terpusat</h3>
                <p class="feature-desc">
                    Semua karya desain tersimpan aman di server, bebas risiko hilang di flashdisk
                    yang rusak, file terhapus, atau laptop yang error. Akses kapan saja, di mana saja.
                </p>
                <div class="feature-tag">Aman & Terorganisir</div>
            </div>

            {{-- Card 2 --}}
            <div class="feature-card anim-fade-up anim-delay-1">
                <div class="feature-number">02</div>
                <div class="feature-icon-wrap">
                    <svg width="24" height="24" fill="none" stroke="#dc2626" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Siap Menuju Industri</h3>
                <p class="feature-desc">
                    Tampilan profesional yang siap dipresentasikan untuk kebutuhan Teaching Factory
                    (TEFA), Program Guru Tamu, dan pitching proyek kolaborasi industri kreatif.
                </p>
                <div class="feature-tag">Standar Industri Kreatif</div>
            </div>

            {{-- Card 3 --}}
            <div class="feature-card anim-fade-up anim-delay-2">
                <div class="feature-number">03</div>
                <div class="feature-icon-wrap">
                    <svg width="24" height="24" fill="none" stroke="#dc2626" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Auto-Generate PDF</h3>
                <p class="feature-desc">
                    Cetak katalog karya ke format PDF profesional hanya dengan satu klik.
                    Sempurna untuk keperluan lamaran PKL, portofolio wisuda, dan presentasi
                    akhir tahun kepada wali murid.
                </p>
                <div class="feature-tag">1-Klik, Langsung Jadi</div>
            </div>

        </div>
    </div>
</section>

{{-- ========================================================
     CTA SECTION
======================================================== --}}
<section class="cta-section">
    <div class="container" style="position:relative; z-index:2;">

        <div style="display:inline-block; background:var(--red-soft); border:1px solid rgba(220,38,38,0.2); border-radius:30px; padding:5px 14px; margin-bottom:24px;">
            <span style="font-size:0.7rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#fca5a5;">
                ✦ Bergabung Sekarang
            </span>
        </div>

        <h2 class="cta-title">
            Waktunya Karyamu<br>
            <span style="color:var(--red);">Bicara Sendiri.</span>
        </h2>

        <p class="cta-sub">
            Masuk ke sistem dan mulai upload karya terbaikmu. Guru pembimbingmu
            siap memantau perkembanganmu secara real-time.
        </p>

        <div style="display:flex; align-items:center; justify-content:center; gap:14px; flex-wrap:wrap;">
            <a href="{{ route('login') }}" class="btn-cta" style="font-size:1rem; padding:16px 40px;">
                <span>Masuk ke Platform</span>
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <div style="font-size:0.8rem; color:rgba(255,255,255,0.25);">
                Gratis · Tanpa Biaya Apapun · Khusus Siswa DKV SMEKDA
            </div>
        </div>

        {{-- Decorative line --}}
        <div style="margin-top:60px; display:flex; align-items:center; justify-content:center; gap:12px;">
            <div style="height:1px; width:80px; background:linear-gradient(90deg,transparent,rgba(255,255,255,0.08));"></div>
            <div style="width:6px;height:6px;border-radius:50%;background:var(--red); box-shadow:0 0 10px var(--red-glow);"></div>
            <div style="height:1px; width:80px; background:linear-gradient(90deg,rgba(255,255,255,0.08),transparent);"></div>
        </div>

    </div>
</section>

{{-- ========================================================
     FOOTER
======================================================== --}}
<footer class="footer">
    <div class="container">
        <div style="display:grid; grid-template-columns:1.5fr 1fr 1fr; gap:40px; margin-bottom:0;">

            {{-- Col 1: Brand --}}
            <div>
                <div class="footer-logo">DKV<span>.</span>SMEKDA</div>
                <p class="footer-desc">
                    Platform resmi manajemen portofolio digital untuk Jurusan Desain Komunikasi Visual
                    SMK Negeri 2 Padang Panjang.
                </p>
                <div style="margin-top:20px; display:flex; gap:10px;">
                    <div style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s ease;"
                         onmouseover="this.style.borderColor='rgba(220,38,38,0.4)'"
                         onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'">
                        <svg width="14" height="14" fill="rgba(255,255,255,0.4)" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Col 2: Alamat --}}
            <div>
                <h4 style="font-size:0.8rem; font-weight:700; color:white; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Lokasi</h4>
                <p style="font-size:0.82rem; color:rgba(255,255,255,0.3); line-height:1.8;">
                    SMK Negeri 2 Padang Panjang<br>
                    (SMEKDA)<br>
                    Jl. Syekh Ibrahim Musa No.26,<br>
                    Ganting, Padang Panjang,<br>
                    Sumatera Barat.
                </p>
            </div>

            {{-- Col 3: Links --}}
            <div>
                <h4 style="font-size:0.8rem; font-weight:700; color:white; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Akses Cepat</h4>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="{{ route('login') }}" style="font-size:0.82rem; color:rgba(255,255,255,0.3); text-decoration:none; transition:color 0.2s;"
                       onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                        → Login Siswa
                    </a>
                    <a href="{{ route('login') }}" style="font-size:0.82rem; color:rgba(255,255,255,0.3); text-decoration:none; transition:color 0.2s;"
                       onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                        → Login Guru
                    </a>
                    <a href="#" style="font-size:0.82rem; color:rgba(255,255,255,0.3); text-decoration:none; transition:color 0.2s;"
                       onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                        → Tentang DKV
                    </a>
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <div class="footer-copy">
                &copy; {{ date('Y') }} <span>DKV SMEKDA</span> — SMK Negeri 2 Padang Panjang. All rights reserved.
            </div>
            <div class="footer-dev">
                Dikembangkan untuk Skripsi oleh <strong>Rafli</strong> &mdash; 2026.
                Dibangun dengan <span style="color:var(--red);">♥</span> menggunakan Laravel.
            </div>
        </div>
    </div>
</footer>

<script>
    // ======== NAVBAR SCROLL ========
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 30) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // ======== ANIMATE ON SCROLL ========
    const animEls = document.querySelectorAll('.anim-fade-up');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.12 });

    animEls.forEach(el => observer.observe(el));
</script>

</body>
</html>