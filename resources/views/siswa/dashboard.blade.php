
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — DKV SMEKDA Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <style>
        :root {
            --red:        #dc2626;
            --red-bright: #ef4444;
            --red-glow:   rgba(220,38,38,0.45);
            --red-soft:   rgba(220,38,38,0.10);
            --red-border: rgba(220,38,38,0.35);
            --surface:    rgba(255,255,255,0.03);
            --border:     rgba(255,255,255,0.07);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #080808;
            color: #f5f5f5;
            overflow-x: hidden;
        }

        /* ── NOISE ── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }

        /* ── GRID ── */
        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(220,38,38,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220,38,38,0.035) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none; z-index: 0;
        }

        /* ── AMBIENT BLOBS ── */
        .blob {
            position: fixed; border-radius: 50%;
            pointer-events: none; z-index: 0;
        }
        .blob-1 {
            top: -200px; left: 180px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(220,38,38,0.09) 0%, transparent 65%);
            animation: blobFloat 10s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -150px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(220,38,38,0.06) 0%, transparent 65%);
            animation: blobFloat 13s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobFloat {
            0%   { transform: scale(1)    translate(0,0);       }
            100% { transform: scale(1.15) translate(20px,15px); }
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #080808; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        /* ================================================================
           SIDEBAR
        ================================================================ */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 260px; height: 100vh;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 50;
            overflow-y: auto;
        }

        .sidebar::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220,38,38,0.4), transparent);
        }

        /* Logo */
        .sidebar-logo {
            padding: 28px 24px 22px;
            border-bottom: 1px solid var(--border);
        }

        .logo-wordmark {
            font-size: 0.78rem;
            font-weight: 900;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.85);
            display: flex; align-items: center; gap: 9px;
        }

        .logo-icon {
            width: 26px; height: 26px;
            background: var(--red);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px var(--red-glow);
            flex-shrink: 0;
        }

        .logo-icon svg { width: 13px; height: 13px; }

        /* Profile Block */
        .sidebar-profile {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
        }

        .profile-avatar {
            width: 42px; height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: white;
            flex-shrink: 0;
            box-shadow: 0 0 18px rgba(220,38,38,0.3);
        }

        .profile-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: #f5f5f5;
            line-height: 1.3;
            margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .profile-nis {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.3);
            margin-bottom: 6px;
        }

        .badge-role {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.12);
            border: 1px solid rgba(220,38,38,0.25);
            color: #fca5a5;
            padding: 2px 9px;
            border-radius: 30px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .badge-role-dot {
            width: 5px; height: 5px;
            background: var(--red);
            border-radius: 50%;
            animation: pulseDot 1.5s ease-in-out infinite;
            box-shadow: 0 0 6px var(--red-glow);
        }

        @keyframes pulseDot {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.35; transform: scale(0.65); }
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 20px 14px;
        }

        .nav-label {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.18);
            padding: 0 10px;
            margin-bottom: 8px;
            margin-top: 4px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255,255,255,0.38);
            text-decoration: none;
            transition: all 0.22s ease;
            border: 1px solid transparent;
            margin-bottom: 3px;
            position: relative;
        }

        .nav-item:hover {
            color: rgba(255,255,255,0.75);
            background: rgba(255,255,255,0.04);
            border-color: var(--border);
        }

        .nav-item.active {
            color: #fca5a5;
            background: rgba(220,38,38,0.1);
            border-color: rgba(220,38,38,0.2);
        }

        .nav-item.active::before {
            content: '';
            position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 18px;
            background: var(--red);
            border-radius: 0 3px 3px 0;
            box-shadow: 0 0 10px var(--red-glow);
        }

        .nav-item.active svg { color: var(--red); }

        .nav-item svg {
            width: 16px; height: 16px;
            flex-shrink: 0;
            transition: color 0.22s ease;
        }

        /* Logout */
        .sidebar-footer {
            padding: 14px;
            border-top: 1px solid var(--border);
        }

        .btn-logout {
            width: 100%;
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px;
            border-radius: 10px;
            background: none; border: 1px solid transparent;
            color: rgba(255,255,255,0.28);
            font-size: 0.82rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.22s ease;
        }

        .btn-logout:hover {
            color: #fca5a5;
            background: rgba(220,38,38,0.08);
            border-color: rgba(220,38,38,0.18);
        }

        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ================================================================
           MAIN CONTENT
        ================================================================ */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            position: relative; z-index: 1;
        }

        /* Top Bar */
        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(8,8,8,0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between;
        }

        .topbar-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: rgba(255,255,255,0.25);
            letter-spacing: 0.5px;
        }

        .topbar-title span {
            color: rgba(255,255,255,0.55);
            margin-left: 6px;
        }

        .topbar-badge {
            display: flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.08);
            border: 1px solid rgba(220,38,38,0.18);
            border-radius: 30px;
            padding: 5px 13px;
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(220,38,38,0.7);
            letter-spacing: 0.5px;
        }

        /* ── INNER PAGE ── */
        .page-inner {
            padding: 40px 36px 60px;
        }

        /* Greeting */
        .greeting-eyebrow {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(220,38,38,0.7);
            margin-bottom: 10px;
        }

        .greeting-headline {
            font-size: clamp(1.6rem, 2.5vw, 2.2rem);
            font-weight: 900;
            letter-spacing: -1px;
            line-height: 1.15;
            color: #f5f5f5;
            margin-bottom: 8px;
        }

        .greeting-headline .name-highlight {
            color: var(--red);
            text-shadow: 0 0 30px rgba(220,38,38,0.4);
        }

        .greeting-sub {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.3);
            font-weight: 400;
        }

        /* ── STAT CARDS ── */
        .stat-card {
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.07), transparent);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(220,38,38,0.25);
            box-shadow:
                0 20px 50px rgba(0,0,0,0.4),
                0 0 0 1px rgba(220,38,38,0.1),
                0 0 40px rgba(220,38,38,0.07);
            background: rgba(220,38,38,0.04);
        }

        .stat-icon-wrap {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: var(--red-soft);
            border: 1px solid rgba(220,38,38,0.18);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon-wrap {
            background: rgba(220,38,38,0.16);
            box-shadow: 0 0 20px rgba(220,38,38,0.2);
        }

        .stat-icon-wrap svg { width: 20px; height: 20px; color: var(--red); }

        .stat-number {
            font-size: 2.4rem;
            font-weight: 900;
            letter-spacing: -2px;
            color: #f5f5f5;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .stat-bar-wrap {
            margin-top: 16px;
            height: 2px;
            background: rgba(255,255,255,0.05);
            border-radius: 2px;
            overflow: hidden;
        }

        .stat-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--red), #ef4444);
            border-radius: 2px;
            box-shadow: 0 0 8px rgba(220,38,38,0.4);
        }

        .stat-bg-num {
            position: absolute;
            bottom: -8px; right: 16px;
            font-size: 5rem;
            font-weight: 900;
            color: rgba(255,255,255,0.02);
            letter-spacing: -4px;
            pointer-events: none;
            line-height: 1;
            transition: color 0.3s ease;
        }

        .stat-card:hover .stat-bg-num { color: rgba(220,38,38,0.04); }

        /* ── SECTION HEADER ── */
        .section-header {
            display: flex; align-items: flex-end; justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px; flex-wrap: wrap;
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #f5f5f5;
            letter-spacing: -0.3px;
        }

        .section-sub {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.25);
            margin-top: 3px;
            font-weight: 500;
        }

        .btn-export {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.55);
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-export:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.2);
            color: #f5f5f5;
        }

        .btn-export svg { width: 15px; height: 15px; }

        .btn-add {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--red);
            border: 1px solid var(--red);
            color: white;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative; overflow: hidden;
        }

        .btn-add::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, #b91c1c, #ef4444);
            opacity: 0; transition: opacity 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px var(--red-glow), 0 0 0 3px rgba(220,38,38,0.15);
        }

        .btn-add:hover::before { opacity: 1; }
        .btn-add span, .btn-add svg { position: relative; z-index: 1; }
        .btn-add svg { width: 15px; height: 15px; }

        /* ── PORTFOLIO CARD ── */
        .portfolio-card {
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .portfolio-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }

        .portfolio-card:hover {
            transform: translateY(-5px);
            border-color: rgba(220,38,38,0.22);
            box-shadow:
                0 24px 60px rgba(0,0,0,0.45),
                0 0 0 1px rgba(220,38,38,0.08),
                0 0 50px rgba(220,38,38,0.06);
        }

        .portfolio-thumb {
            width: 100%; height: 170px;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
            background: #111;
        }

        .portfolio-card:hover .portfolio-thumb { transform: scale(1.04); }

        .thumb-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; height: 170px;
            background: linear-gradient(to bottom, transparent 50%, rgba(8,8,8,0.6) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .portfolio-card:hover .thumb-overlay { opacity: 1; }

        .thumb-wrapper { position: relative; overflow: hidden; }

        .pdf-pill {
            position: absolute; top: 10px; left: 10px;
            background: rgba(220,38,38,0.85);
            color: white;
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 6px;
            backdrop-filter: blur(8px);
        }

        .portfolio-body { padding: 16px; }

        .portfolio-category {
            display: inline-flex; align-items: center;
            background: rgba(220,38,38,0.1);
            border: 1px solid rgba(220,38,38,0.2);
            color: #fca5a5;
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 2px 9px;
            border-radius: 20px;
            margin-bottom: 9px;
        }

        .portfolio-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: #f5f5f5;
            letter-spacing: -0.2px;
            line-height: 1.35;
            margin-bottom: 5px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .portfolio-desc {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.28);
            line-height: 1.5;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .portfolio-meta {
            display: flex; align-items: center; gap: 5px;
            font-size: 0.68rem;
            color: rgba(255,255,255,0.2);
            margin-bottom: 14px;
        }

        .portfolio-meta svg { width: 11px; height: 11px; }

        .portfolio-actions {
            display: flex; gap: 8px;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .btn-action-edit {
            flex: 1;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px;
            border-radius: 9px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.35);
            font-size: 0.73rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.22s ease;
        }

        .btn-action-edit:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.15);
            color: #f5f5f5;
        }

        .btn-action-edit svg { width: 13px; height: 13px; }

        .btn-action-delete {
            flex: 1;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px;
            border-radius: 9px;
            background: rgba(220,38,38,0.06);
            border: 1px solid rgba(220,38,38,0.12);
            color: rgba(220,38,38,0.5);
            font-size: 0.73rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            font-family: 'Inter', sans-serif;
            transition: all 0.22s ease;
        }

        .btn-action-delete:hover {
            background: rgba(220,38,38,0.14);
            border-color: rgba(220,38,38,0.3);
            color: #f87171;
        }

        .btn-action-delete svg { width: 13px; height: 13px; }

        /* ── EMPTY STATE ── */
        .empty-wrap {
            padding: 80px 40px;
            text-align: center;
        }

        .empty-icon {
            width: 72px; height: 72px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }

        .empty-icon svg { width: 32px; height: 32px; color: rgba(255,255,255,0.12); }

        .empty-title {
            font-size: 1rem;
            font-weight: 800;
            color: rgba(255,255,255,0.4);
            margin-bottom: 8px;
        }

        .empty-sub {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.2);
            margin-bottom: 28px;
            line-height: 1.6;
        }

        /* ── FLASH MESSAGE ── */
        .flash-success {
            display: flex; align-items: center; gap: 12px;
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.2);
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 28px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #86efac;
        }

        .flash-success svg { width: 16px; height: 16px; flex-shrink: 0; color: #4ade80; }

        /* ── ADD PLACEHOLDER CARD ── */
        .add-card {
            border: 1.5px dashed rgba(255,255,255,0.07);
            border-radius: 16px;
            min-height: 300px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 12px;
        }

        .add-card:hover {
            border-color: rgba(220,38,38,0.3);
            background: rgba(220,38,38,0.03);
        }

        .add-card-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s ease;
        }

        .add-card:hover .add-card-icon {
            background: var(--red-soft);
            border-color: rgba(220,38,38,0.25);
        }

        .add-card-icon svg { width: 18px; height: 18px; color: rgba(255,255,255,0.2); transition: color 0.3s ease; }
        .add-card:hover .add-card-icon svg { color: var(--red); }

        .add-card-text { font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.2); transition: color 0.3s ease; }
        .add-card:hover .add-card-text { color: rgba(220,38,38,0.7); }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

{{-- ================================================================
     SIDEBAR
================================================================ --}}
<aside class="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="logo-wordmark">
            <div class="logo-icon">
                <svg fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            DKV<span style="color:var(--red);">.</span>SMEKDA
        </div>
        <div style="font-size:0.62rem; color:rgba(255,255,255,0.2); margin-top:4px; letter-spacing:1px; text-transform:uppercase; font-weight:600; padding-left:35px;">
            Portal Siswa
        </div>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="flex:1; min-width:0;">
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-nis">NIS: {{ auth()->user()->nis_nip ?? '—' }}</div>
            </div>
        </div>
        <div class="badge-role">
            <div class="badge-role-dot"></div>
            Siswa DKV
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('siswa.dashboard') }}" class="nav-item active">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('siswa.portfolio.create') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Karya
        </a>

        <a href="{{ route('siswa.portfolio.print') }}" target="_blank"
           class="inline-flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 text-sm font-bold rounded-lg shadow-md hover:bg-red-600 hover:shadow-lg transition-all duration-300">
            {{-- Ikon Printer SVG --}}
           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
               <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Katalog Portofolio
        </a>

        <div class="nav-label" style="margin-top:20px;">Akun</div>

        <a href="#" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
        </a>
    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar dari Portal
            </button>
        </form>
    </div>
</aside>

{{-- ================================================================
     MAIN CONTENT
================================================================ --}}
<div class="main-content">

    {{-- Top Bar --}}
    <div class="topbar">
        <div class="topbar-title">
            Portal DKV SMEKDA <span>/</span> Dashboard Siswa
        </div>
        <div class="topbar-badge">
            <div class="badge-role-dot"></div>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="page-inner">

        {{-- Flash Success --}}
        @if(session('success'))
            <div class="flash-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ── GREETING ── --}}
        <div style="margin-bottom:40px;">
            <div class="greeting-eyebrow">&#9654; Workspace Kreatif Anda</div>
            <h1 class="greeting-headline">
                Halo, <span class="name-highlight">{{ explode(' ', auth()->user()->name)[0] }}.</span>
            </h1>
            <p class="greeting-sub">Selamat datang di workspace kreatifmu. Terus berkarya dan biarkan karyamu berbicara.</p>
        </div>

        {{-- ── STAT CARDS ── --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:48px;">

            {{-- Card 1: Total Karya --}}
            <div class="stat-card">
                <div class="stat-bg-num">{{ $totalKarya }}</div>
                <div class="stat-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="stat-number">{{ $totalKarya }}</div>
                <div class="stat-label">Total Karya</div>
                <div class="stat-bar-wrap">
                    <div class="stat-bar" style="width:{{ min(100, $totalKarya * 8) }}%;"></div>
                </div>
            </div>

            {{-- Card 2: Poster --}}
            <div class="stat-card">
                <div class="stat-bg-num">{{ $totalPoster }}</div>
                <div class="stat-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ $totalPoster }}</div>
                <div class="stat-label">Karya Poster</div>
                <div class="stat-bar-wrap">
                    <div class="stat-bar" style="width:{{ $totalKarya > 0 ? min(100, ($totalPoster / $totalKarya) * 100) : 0 }}%;"></div>
                </div>
            </div>

            {{-- Card 3: UI/UX --}}
            <div class="stat-card">
                <div class="stat-bg-num">{{ $totalUIUX }}</div>
                <div class="stat-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ $totalUIUX }}</div>
                <div class="stat-label">Karya UI/UX</div>
                <div class="stat-bar-wrap">
                    <div class="stat-bar" style="width:{{ $totalKarya > 0 ? min(100, ($totalUIUX / $totalKarya) * 100) : 0 }}%;"></div>
                </div>
            </div>

        </div>

        {{-- ── PORTFOLIO SECTION ── --}}
        <div style="background:rgba(255,255,255,0.018); border:1px solid var(--border); border-radius:20px; overflow:hidden;">

            {{-- Section Top Bar --}}
            <div style="padding:20px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div>
                    <div class="section-title">Karya Portofolio Saya</div>
                    <div class="section-sub">
                        {{ $portfolios->count() }} karya tersimpan &bull; Diurutkan dari terbaru
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <a href="{{ route('siswa.portfolio.print') }}" class="btn-export">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('siswa.portfolio.create') }}" class="btn-add">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Karya</span>
                    </a>
                </div>
            </div>

            {{-- Grid --}}
            <div style="padding:24px;">

                @if($portfolios->isEmpty())

                    {{-- Empty State --}}
                    <div class="empty-wrap">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="empty-title">Kanvas masih kosong.</div>
                        <div class="empty-sub">
                            Belum ada karya yang kamu unggah.<br>
                            Mulai upload karya pertamamu dan tampilkan kreativitasmu ke dunia.
                        </div>
                        <a href="{{ route('siswa.portfolio.create') }}" class="btn-add" style="margin:0 auto;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Upload Karya Pertama</span>
                        </a>
                    </div>

                @else

                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:16px;">

                        @foreach($portfolios as $portfolio)
                        <div class="portfolio-card">
                            <div class="thumb-wrapper">
                                <img
                                    src="{{ asset('storage/' . $portfolio->image_path) }}"
                                    alt="{{ $portfolio->title }}"
                                    class="portfolio-thumb"
                                >
                                <div class="thumb-overlay"></div>
                                @if($portfolio->file_pdf_path)
                                    <div class="pdf-pill">PDF</div>
                                @endif
                            </div>
                            <div class="portfolio-body">
                                <div class="portfolio-category">
                                    {{ $portfolio->category?->name ?? 'Umum' }}
                                </div>
                                <div class="portfolio-title">{{ $portfolio->title }}</div>
                                <div class="portfolio-desc">{{ $portfolio->description }}</div>
                                <div class="portfolio-meta">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $portfolio->created_at->diffForHumans() }}
                                    &nbsp;&bull;&nbsp;
                                    {{ $portfolio->created_at->format('d M Y') }}
                                </div>
                                <div class="portfolio-actions">
                                    <a href="{{ route('siswa.portfolio.edit', $portfolio) }}" class="btn-action-edit">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('siswa.portfolio.destroy', $portfolio) }}"
                                        style="flex:1; display:flex;"
                                        onsubmit="return confirm('Hapus karya ini? Tindakan tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-delete">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Add Placeholder --}}
                        <a href="{{ route('siswa.portfolio.create') }}" class="add-card">
                            <div class="add-card-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div class="add-card-text">+ Tambah Karya</div>
                        </a>

                    </div>

                @endif

            </div>

        </div>

        {{-- Footer Strip --}}
        <div style="margin-top:48px; padding-top:24px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <span style="font-size:0.7rem; color:rgba(255,255,255,0.15);">
                &copy; {{ date('Y') }} <strong style="color:rgba(255,255,255,0.28);">DKV SMEKDA</strong>
                &nbsp;&bull;&nbsp; SMK Negeri 2 Padang Panjang
            </span>
            <span style="font-size:0.7rem; color:rgba(255,255,255,0.12);">
                Dikembangkan untuk Skripsi oleh <strong style="color:rgba(255,255,255,0.22);">Rafli</strong> &mdash; 2026
            </span>
        </div>

    </div>
</div>

</body>
</html>