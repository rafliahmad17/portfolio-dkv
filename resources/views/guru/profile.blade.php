<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — DKV SMEKDA Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']}}}}</script>
    <style>
        :root {
            --red:        #dc2626;
            --red-bright: #ef4444;
            --red-glow:   rgba(220,38,38,0.45);
            --red-soft:   rgba(220,38,38,0.10);
            --border:     rgba(255,255,255,0.07);
            --green:      #22c55e;
            --green-glow: rgba(34,197,94,0.35);
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

        /* ── BLOBS ── */
        .blob { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
        .blob-1 {
            top: -180px; right: 100px; width: 580px; height: 580px;
            background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, transparent 65%);
            animation: blobF 11s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -120px; left: -80px; width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(220,38,38,0.06) 0%, transparent 65%);
            animation: blobF 14s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobF {
            0%   { transform: scale(1) translate(0,0); }
            100% { transform: scale(1.14) translate(18px,14px); }
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
            background: rgba(8,8,8,0.9);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 50; overflow-y: auto;
        }
        .sidebar::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220,38,38,0.4), transparent);
        }
        .sidebar-logo { padding: 28px 24px 22px; border-bottom: 1px solid var(--border); }
        .logo-wordmark {
            font-size: 0.78rem; font-weight: 900; letter-spacing: 3px;
            text-transform: uppercase; color: rgba(255,255,255,0.85);
            display: flex; align-items: center; gap: 9px;
        }
        .logo-icon {
            width: 26px; height: 26px; background: var(--red); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px var(--red-glow); flex-shrink: 0;
        }
        .logo-icon svg { width: 13px; height: 13px; }
        .sidebar-profile { padding: 20px 24px; border-bottom: 1px solid var(--border); }
        .profile-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: white; flex-shrink: 0;
            box-shadow: 0 0 18px rgba(220,38,38,0.3);
        }
        .profile-name {
            font-size: 0.78rem; font-weight: 700; color: #f5f5f5;
            line-height: 1.3; margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .profile-nip { font-size: 0.68rem; color: rgba(255,255,255,0.28); margin-bottom: 7px; }
        .badge-role {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.22);
            color: #fca5a5; padding: 2px 9px; border-radius: 30px;
            font-size: 0.63rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;
        }
        .live-dot {
            width: 5px; height: 5px; background: var(--red); border-radius: 50%;
            box-shadow: 0 0 6px var(--red-glow);
            animation: livePulse 1.5s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.3; transform: scale(0.6); }
        }
        .sidebar-nav { flex: 1; padding: 20px 14px; }
        .nav-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: rgba(255,255,255,0.18);
            padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.35);
            text-decoration: none; transition: all 0.22s ease;
            border: 1px solid transparent; margin-bottom: 3px; position: relative;
        }
        .nav-item:hover { color: rgba(255,255,255,0.7); background: rgba(255,255,255,0.04); border-color: var(--border); }
        .nav-item.active { color: #fca5a5; background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.2); }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 18px; background: var(--red);
            border-radius: 0 3px 3px 0; box-shadow: 0 0 10px var(--red-glow);
        }
        .nav-item.active svg { color: var(--red); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar-footer { padding: 14px; border-top: 1px solid var(--border); }
        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px; background: none;
            border: 1px solid transparent; color: rgba(255,255,255,0.28);
            font-size: 0.82rem; font-weight: 600; font-family: 'Inter', sans-serif;
            cursor: pointer; transition: all 0.22s ease;
        }
        .btn-logout:hover { color: #fca5a5; background: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.18); }
        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ================================================================
           MAIN
        ================================================================ */
        .main-content { margin-left: 260px; min-height: 100vh; position: relative; z-index: 1; }
        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(8,8,8,0.88); backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px); border-bottom: 1px solid var(--border);
            padding: 16px 36px; display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-title { font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.22); letter-spacing: 0.5px; }
        .topbar-title span { color: rgba(255,255,255,0.5); margin-left: 6px; }
        .topbar-pill {
            display: flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.18);
            border-radius: 30px; padding: 5px 13px;
            font-size: 0.68rem; font-weight: 700; color: rgba(220,38,38,0.65); letter-spacing: 0.5px;
        }
        .page-inner { padding: 40px 36px 60px; }

        /* ================================================================
           PROFILE PAGE — Glass Card
        ================================================================ */
        .glass-card {
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: 24px; position: relative; overflow: hidden;
        }
        .glass-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.07), transparent);
        }

        /* ── Avatar ── */
        .avatar-outer-ring {
            width: 130px; height: 130px; border-radius: 50%;
            padding: 3px;
            background: linear-gradient(135deg, var(--red) 0%, #7c3aed 55%, var(--red) 100%);
            box-shadow: 0 0 36px rgba(220,38,38,0.3), 0 0 70px rgba(124,58,237,0.15);
            display: block; margin: 0 auto;
            position: relative; cursor: pointer;
            transition: box-shadow 0.3s ease;
        }
        .avatar-outer-ring:hover {
            box-shadow: 0 0 50px rgba(220,38,38,0.5), 0 0 90px rgba(124,58,237,0.25);
        }
        .avatar-inner {
            width: 100%; height: 100%; border-radius: 50%;
            overflow: hidden; position: relative;
            background: #111;
        }
        .avatar-img {
            position: absolute; inset: 0;
            width: 100%; height: 100%; object-fit: cover;
        }
        .avatar-initials-lg {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; font-weight: 900; color: white;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            border-radius: 50%;
            letter-spacing: -1px;
        }
        .avatar-overlay {
            position: absolute; inset: 0; border-radius: 50%;
            background: rgba(0,0,0,0.72);
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px;
            opacity: 0; transition: opacity 0.25s ease;
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
        }
        .avatar-outer-ring:hover .avatar-overlay { opacity: 1; }
        .avatar-overlay svg { width: 22px; height: 22px; color: white; }
        .avatar-overlay span {
            font-size: 0.6rem; font-weight: 900; color: white;
            letter-spacing: 1.2px; text-transform: uppercase;
        }

        /* ── Profile Card Info ── */
        .pc-name {
            font-size: 1.15rem; font-weight: 900; color: #f5f5f5;
            letter-spacing: -0.5px; text-align: center;
            margin-top: 18px; line-height: 1.2;
        }
        .pc-nip { font-size: 0.7rem; color: rgba(255,255,255,0.28); font-weight: 600; text-align: center; margin-top: 3px; }
        .badge-guru {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.22);
            color: #fca5a5; padding: 5px 14px; border-radius: 30px;
            font-size: 0.62rem; font-weight: 800; letter-spacing: 0.9px; text-transform: uppercase;
        }
        .green-dot {
            width: 6px; height: 6px; background: var(--green); border-radius: 50%;
            box-shadow: 0 0 8px var(--green-glow);
            animation: livePulse 1.8s ease-in-out infinite;
        }
        .card-divider { height: 1px; background: var(--border); margin: 22px 0; }
        .info-row {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 9px 0;
        }
        .info-row + .info-row { border-top: 1px solid rgba(255,255,255,0.04); }
        .info-row-icon {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--red-soft); border: 1px solid rgba(220,38,38,0.15);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .info-row-icon svg { width: 13px; height: 13px; color: rgba(220,38,38,0.65); }
        .info-row-lbl { font-size: 0.59rem; font-weight: 800; color: rgba(255,255,255,0.2); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px; }
        .info-row-val { font-size: 0.74rem; font-weight: 600; color: rgba(255,255,255,0.5); word-break: break-all; }
        .member-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.03); border: 1px solid var(--border);
            border-radius: 20px; padding: 5px 12px;
            font-size: 0.62rem; font-weight: 700; color: rgba(255,255,255,0.22);
        }
        .member-badge svg { width: 10px; height: 10px; color: rgba(220,38,38,0.45); }

        /* ================================================================
           PROFILE PAGE — Tab System
        ================================================================ */
        .tab-nav {
            display: flex; gap: 4px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 14px; padding: 5px;
            margin-bottom: 28px;
        }
        .tab-btn {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 16px; border-radius: 10px;
            font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.3);
            background: none; border: 1px solid transparent;
            cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all 0.22s ease; white-space: nowrap;
        }
        .tab-btn svg { width: 15px; height: 15px; flex-shrink: 0; transition: color 0.22s ease; }
        .tab-btn:hover { color: rgba(255,255,255,0.55); background: rgba(255,255,255,0.04); }
        .tab-btn.active {
            color: #fca5a5; background: rgba(220,38,38,0.12);
            border-color: rgba(220,38,38,0.22);
            box-shadow: 0 0 20px rgba(220,38,38,0.1);
        }
        .tab-btn.active svg { color: var(--red); }
        .tab-panel { transition: opacity 0.2s ease; opacity: 1; }
        .tab-panel.hidden { display: none; }

        /* ── Panel Header ── */
        .panel-eyebrow { font-size: 0.6rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: rgba(220,38,38,0.6); margin-bottom: 5px; }
        .panel-title { font-size: 1rem; font-weight: 800; color: #f5f5f5; letter-spacing: -0.3px; }
        .panel-sub { font-size: 0.75rem; color: rgba(255,255,255,0.2); margin-top: 4px; font-weight: 400; }

        /* ================================================================
           PROFILE PAGE — Form Elements
        ================================================================ */
        .field-group { margin-bottom: 18px; }
        .field-label {
            display: block; font-size: 0.68rem; font-weight: 700;
            color: rgba(255,255,255,0.35); letter-spacing: 0.5px;
            text-transform: uppercase; margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; top: 50%; left: 14px;
            transform: translateY(-50%);
            width: 15px; height: 15px; color: rgba(255,255,255,0.18);
            pointer-events: none; transition: color 0.22s ease; flex-shrink: 0;
        }
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 11px; padding: 12px 14px 12px 42px;
            font-size: 0.82rem; font-weight: 500; font-family: 'Inter', sans-serif;
            color: #f5f5f5; outline: none; caret-color: var(--red);
            transition: all 0.25s ease;
        }
        .form-input::placeholder { color: rgba(255,255,255,0.14); }
        .form-input:focus {
            border-color: var(--red);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.14), 0 0 22px rgba(220,38,38,0.08);
        }
        .input-wrap:focus-within .input-icon { color: rgba(220,38,38,0.65); }
        .form-input.with-toggle { padding-right: 46px; }
        .pw-toggle {
            position: absolute; top: 50%; right: 12px;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: rgba(255,255,255,0.2); padding: 4px;
            display: flex; align-items: center; justify-content: center;
            transition: color 0.22s ease;
        }
        .pw-toggle:hover { color: rgba(255,255,255,0.5); }
        .pw-toggle svg { width: 16px; height: 16px; }
        .error-msg {
            display: flex; align-items: center; gap: 5px;
            font-size: 0.68rem; color: #f87171; font-weight: 600; margin-top: 6px;
        }
        .error-msg::before {
            content: '!'; width: 14px; height: 14px; flex-shrink: 0;
            background: rgba(248,113,113,0.18); border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.6rem; font-weight: 900;
        }

        /* ── Password Strength ── */
        .strength-wrap { margin-top: 10px; display: flex; align-items: center; gap: 10px; }
        .strength-track { flex: 1; height: 3px; background: rgba(255,255,255,0.06); border-radius: 3px; overflow: hidden; }
        .strength-fill { height: 100%; width: 0; border-radius: 3px; transition: width 0.35s ease, background 0.35s ease, box-shadow 0.35s ease; }
        .strength-label { font-size: 0.62rem; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; color: rgba(255,255,255,0.2); min-width: 60px; transition: color 0.3s ease; }

        /* ── Buttons ── */
        .btn-red {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--red); color: white; border: none;
            border-radius: 11px; padding: 13px 24px;
            font-size: 0.82rem; font-weight: 800; font-family: 'Inter', sans-serif;
            cursor: pointer; letter-spacing: 0.2px; transition: all 0.3s ease;
        }
        .btn-red:hover { background: #b91c1c; box-shadow: 0 8px 28px var(--red-glow); transform: translateY(-2px); }
        .btn-red:active { transform: translateY(0); }
        .btn-red svg { width: 15px; height: 15px; }
        .btn-ghost {
            display: inline-flex; align-items: center;
            background: none; border: none; cursor: pointer;
            font-size: 0.75rem; font-weight: 700; color: rgba(255,255,255,0.22);
            font-family: 'Inter', sans-serif; padding: 6px 0;
            text-decoration: none; transition: color 0.2s ease;
        }
        .btn-ghost:hover { color: rgba(255,255,255,0.5); }

        /* ── Avatar Info Banner ── */
        .avatar-info-banner {
            background: rgba(34,197,94,0.07); border: 1px solid rgba(34,197,94,0.18);
            border-radius: 10px; padding: 10px 14px; margin-bottom: 20px;
            font-size: 0.72rem; color: rgba(134,239,172,0.85); font-weight: 600;
            align-items: center; gap: 8px; /* display:flex set by JS */
        }
        .avatar-info-banner svg { flex-shrink: 0; }

        /* ── Flash Messages ── */
        .flash-success {
            display: flex; align-items: flex-start; gap: 14px;
            background: rgba(34,197,94,0.07); border: 1px solid rgba(34,197,94,0.2);
            border-radius: 14px; padding: 16px 20px; margin-bottom: 28px;
            position: relative;
        }
        .flash-error {
            display: flex; align-items: flex-start; gap: 14px;
            background: rgba(220,38,38,0.07); border: 1px solid rgba(220,38,38,0.2);
            border-radius: 14px; padding: 16px 20px; margin-bottom: 28px;
            position: relative;
        }
        .flash-icon {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .flash-icon.success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.2); }
        .flash-icon.error   { background: rgba(220,38,38,0.12); border: 1px solid rgba(220,38,38,0.2); }
        .flash-close {
            position: absolute; top: 12px; right: 14px; background: none; border: none;
            color: rgba(255,255,255,0.2); cursor: pointer; padding: 4px;
            display: flex; align-items: center; transition: color 0.2s ease;
        }
        .flash-close:hover { color: rgba(255,255,255,0.5); }
        .flash-close svg { width: 14px; height: 14px; }

        /* ── Security Tips ── */
        .tips-box {
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 11px; padding: 14px 16px; margin-bottom: 24px;
        }
        .tips-box-title { font-size: 0.6rem; font-weight: 800; color: rgba(255,255,255,0.2); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .tip-item { display: flex; align-items: center; gap: 8px; font-size: 0.71rem; color: rgba(255,255,255,0.22); }
        .tip-item + .tip-item { margin-top: 6px; }
        .tip-item svg { width: 11px; height: 11px; flex-shrink: 0; color: rgba(220,38,38,0.5); }

        /* ── Card Glow Decor ── */
        .card-glow { position: absolute; border-radius: 50%; pointer-events: none; }
        .card-glow-tl { top: -60px; left: -60px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(220,38,38,0.07) 0%, transparent 65%); }
        .card-glow-tr { top: -50px; right: -50px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(124,58,237,0.05) 0%, transparent 65%); }

        /* ── Section Header ── */
        .section-eyebrow { font-size: 0.62rem; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; color: rgba(220,38,38,0.65); margin-bottom: 8px; }
        .section-headline { font-size: clamp(1.35rem, 2vw, 1.75rem); font-weight: 900; letter-spacing: -0.8px; color: #f5f5f5; line-height: 1.2; margin-bottom: 6px; }
        .section-sub { font-size: 0.82rem; color: rgba(255,255,255,0.24); font-weight: 400; }
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
        <div style="font-size:0.62rem;color:rgba(255,255,255,0.2);margin-top:4px;letter-spacing:1px;text-transform:uppercase;font-weight:600;padding-left:35px;">
            Portal Guru
        </div>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
            <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div style="flex:1;min-width:0;">
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-nip">NIP: {{ auth()->user()->nis_nip ?? '—' }}</div>
            </div>
        </div>
        <div class="badge-role">
            <div class="live-dot"></div>
            Guru Pembimbing
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('guru.dashboard') }}"
           class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard Monitor
        </a>

        <a href="#"
           class="nav-item {{ request()->routeIs('guru.siswa*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Data Siswa
        </a>

        <a href="#"
           class="nav-item {{ request()->routeIs('guru.kategori*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Kelola Kategori
        </a>

        <a href="{{ route('guru.profile') }}"
           class="nav-item {{ request()->routeIs('guru.profile*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Profil Saya
        </a>

        <div class="nav-label" style="margin-top:20px;">Laporan</div>

        <a href="#"
           class="nav-item {{ request()->routeIs('guru.rekap*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Rekap & Statistik
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

    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-title">
            Portal DKV SMEKDA <span>/</span> Profil Saya
        </div>
        <div class="topbar-pill">
            <div class="live-dot"></div>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="page-inner">

        {{-- ── Section Header ── --}}
        <div style="margin-bottom:36px;">
            <div class="section-eyebrow">&#9654; Manajemen Akun</div>
            <h1 class="section-headline">
                Profil <span style="color:var(--red);text-shadow:0 0 28px rgba(220,38,38,0.4);">Saya</span>
            </h1>
            <p class="section-sub">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>

        {{-- ── Flash: Success ── --}}
        @if(session('success'))
            <div class="flash-success" id="flashSuccess">
                <div class="flash-icon success">
                    <svg width="18" height="18" fill="none" stroke="#86efac" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.78rem;font-weight:800;color:#86efac;margin-bottom:3px;">Berhasil Disimpan!</div>
                    <div style="font-size:0.72rem;color:rgba(134,239,172,0.65);font-weight:500;">{{ session('success') }}</div>
                </div>
                <button type="button" class="flash-close" onclick="document.getElementById('flashSuccess').remove()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Flash: Error ── --}}
        @if(session('error'))
            <div class="flash-error" id="flashError">
                <div class="flash-icon error">
                    <svg width="18" height="18" fill="none" stroke="#fca5a5" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.78rem;font-weight:800;color:#fca5a5;margin-bottom:3px;">Terjadi Kesalahan</div>
                    <div style="font-size:0.72rem;color:rgba(252,165,165,0.65);font-weight:500;">{{ session('error') }}</div>
                </div>
                <button type="button" class="flash-close" onclick="document.getElementById('flashError').remove()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Main Grid ── --}}
        <div style="display:grid;grid-template-columns:300px 1fr;gap:22px;align-items:start;">

            {{-- ════════════════════════════════════════
                 LEFT — Profile Visual Card
            ════════════════════════════════════════ --}}
            <div class="glass-card" style="padding:30px 24px;">
                <div class="card-glow card-glow-tl"></div>
                <div class="card-glow card-glow-tr"></div>

                {{-- Avatar --}}
                <div style="text-align:center;">
                    <div class="avatar-outer-ring" onclick="document.getElementById('avatarInput').click()" title="Klik untuk mengubah foto">
                        <div class="avatar-inner">
                            @if(auth()->user()->avatar)
                                <img id="avatarPreview"
                                     src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                     class="avatar-img" alt="Foto Profil">
                            @else
                                <div id="avatarInitials" class="avatar-initials-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <img id="avatarPreview" src="" class="avatar-img"
                                     style="display:none;" alt="Foto Profil">
                            @endif
                        </div>
                        <div class="avatar-overlay">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Ubah Foto</span>
                        </div>
                    </div>
                    <div style="font-size:0.6rem;color:rgba(255,255,255,0.15);margin-top:10px;font-weight:500;">
                        Klik avatar untuk mengubah foto
                    </div>
                </div>

                {{-- Name & NIP --}}
                <div class="pc-name">{{ auth()->user()->name }}</div>
                <div class="pc-nip">NIP: {{ auth()->user()->nis_nip ?? 'Belum diisi' }}</div>

                {{-- Role Badge --}}
                <div style="display:flex;justify-content:center;margin-top:14px;">
                    <div class="badge-guru">
                        <div class="green-dot"></div>
                        Guru Pembimbing DKV
                    </div>
                </div>

                <div class="card-divider"></div>

                {{-- Info Rows --}}
                <div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="info-row-lbl">Email</div>
                            <div class="info-row-val">{{ auth()->user()->email }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="info-row-lbl">Instansi</div>
                            <div class="info-row-val">SMK Negeri 2 Padang Panjang</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="info-row-lbl">Bergabung Sejak</div>
                            <div class="info-row-val">{{ auth()->user()->created_at->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-divider"></div>

                <div style="display:flex;justify-content:center;">
                    <div class="member-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Akun Terverifikasi &bull; DKV SMEKDA
                    </div>
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 RIGHT — Settings Tabs Card
            ════════════════════════════════════════ --}}
            <div class="glass-card" style="padding:32px;">
                <div class="card-glow card-glow-tr"></div>

                {{-- Tab Navigation --}}
                <div class="tab-nav">
                    <button type="button" class="tab-btn active" id="tab-biodata"
                            onclick="switchTab('biodata')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Edit Biodata
                    </button>
                    <button type="button" class="tab-btn" id="tab-keamanan"
                            onclick="switchTab('keamanan')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Keamanan
                    </button>
                </div>

                {{-- ════ TAB 1: Edit Biodata ════ --}}
                <div id="panel-biodata" class="tab-panel">
                    <div style="margin-bottom:24px;">
                        <div class="panel-eyebrow">&#9654; Edit Biodata</div>
                        <div class="panel-title">Informasi Pribadi</div>
                        <div class="panel-sub">Perbarui nama, NIP, email, dan foto profil Anda.</div>
                    </div>

                    <form method="POST" action="{{ route('guru.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Hidden file input — triggered by avatar click in left card --}}
                        <input type="file" id="avatarInput" name="avatar"
                               class="hidden" accept="image/jpeg,image/png,image/webp">

                        {{-- Avatar info banner (shown after file select) --}}
                        <div id="avatarInfoBanner" class="avatar-info-banner" style="display:none;">
                            <svg width="13" height="13" fill="none" stroke="#86efac" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="avatarFileName">Foto baru dipilih — akan diunggah saat menyimpan.</span>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="field-group">
                            <label class="field-label" for="name">Nama Lengkap</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <input type="text" id="name" name="name" class="form-input"
                                       placeholder="Masukkan nama lengkap"
                                       value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            @error('name')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIP --}}
                        <div class="field-group">
                            <label class="field-label" for="nis_nip">NIP (Nomor Induk Pegawai)</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                                <input type="text" id="nis_nip" name="nis_nip" class="form-input"
                                       placeholder="Contoh: 198801012010011001"
                                       value="{{ old('nis_nip', auth()->user()->nis_nip) }}">
                            </div>
                            @error('nis_nip')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="field-group">
                            <label class="field-label" for="email">Alamat Email</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                <input type="email" id="email" name="email" class="form-input"
                                       placeholder="guru@smkn2padangpanjang.sch.id"
                                       value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                            @error('email')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex;align-items:center;gap:14px;padding-top:6px;">
                            <button type="submit" class="btn-red">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('guru.profile') }}" class="btn-ghost">Batalkan</a>
                        </div>
                    </form>
                </div>

                {{-- ════ TAB 2: Keamanan ════ --}}
                <div id="panel-keamanan" class="tab-panel hidden">
                    <div style="margin-bottom:24px;">
                        <div class="panel-eyebrow">&#9654; Keamanan Akun</div>
                        <div class="panel-title">Ganti Password</div>
                        <div class="panel-sub">Gunakan password yang kuat dan unik untuk melindungi akun Anda.</div>
                    </div>

                    <form method="POST" action="{{ route('guru.profile.password') }}">
                        @csrf
                        @method('PUT')

                        {{-- Password Saat Ini --}}
                        <div class="field-group">
                            <label class="field-label" for="currentPassword">Password Saat Ini</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <input type="password" id="currentPassword" name="current_password"
                                       class="form-input with-toggle" placeholder="••••••••••••"
                                       autocomplete="current-password">
                                <button type="button" class="pw-toggle" onclick="togglePw('currentPassword','eyeIcon1')">
                                    <svg id="eyeIcon1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="field-group">
                            <label class="field-label" for="newPassword">Password Baru</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                <input type="password" id="newPassword" name="password"
                                       class="form-input with-toggle"
                                       placeholder="Minimal 8 karakter"
                                       autocomplete="new-password"
                                       oninput="checkStrength(this.value)">
                                <button type="button" class="pw-toggle" onclick="togglePw('newPassword','eyeIcon2')">
                                    <svg id="eyeIcon2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            {{-- Strength Indicator --}}
                            <div class="strength-wrap" id="strengthWrap" style="display:none;">
                                <div class="strength-track">
                                    <div id="strengthFill" class="strength-fill"></div>
                                </div>
                                <span id="strengthLabel" class="strength-label"></span>
                            </div>
                            @error('password')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="field-group">
                            <label class="field-label" for="confirmPassword">Konfirmasi Password Baru</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <input type="password" id="confirmPassword" name="password_confirmation"
                                       class="form-input with-toggle"
                                       placeholder="Ulangi password baru"
                                       autocomplete="new-password">
                                <button type="button" class="pw-toggle" onclick="togglePw('confirmPassword','eyeIcon3')">
                                    <svg id="eyeIcon3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Security Tips --}}
                        <div class="tips-box">
                            <div class="tips-box-title">Tips Password Kuat</div>
                            <div class="tip-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Minimal 8 karakter
                            </div>
                            <div class="tip-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Kombinasi huruf besar dan huruf kecil
                            </div>
                            <div class="tip-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Sertakan angka dan karakter spesial (!@#$)
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex;align-items:center;gap:14px;padding-top:4px;">
                            <button type="submit" class="btn-red">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>{{-- /glass-card right --}}

        </div>{{-- /grid --}}

        {{-- ── Footer ── --}}
        <div style="margin-top:48px;padding-top:24px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
            <span style="font-size:0.7rem;color:rgba(255,255,255,0.14);">
                &copy; {{ date('Y') }}
                <strong style="color:rgba(255,255,255,0.26);">DKV SMEKDA</strong>
                &nbsp;&bull;&nbsp; SMK Negeri 2 Padang Panjang
            </span>
            <span style="font-size:0.7rem;color:rgba(255,255,255,0.12);">
                Dikembangkan untuk Skripsi oleh
                <strong style="color:rgba(255,255,255,0.22);">Rafli</strong> &mdash; 2026
            </span>
        </div>

    </div>
</div>

<script>
    /* ── Tab Switching ── */
    function switchTab(name) {
        document.querySelectorAll('.tab-panel').forEach(function(p) {
            p.classList.add('hidden');
            p.style.opacity = '0';
        });
        document.querySelectorAll('.tab-btn').forEach(function(b) {
            b.classList.remove('active');
        });

        var panel = document.getElementById('panel-' + name);
        panel.classList.remove('hidden');
        requestAnimationFrame(function() {
            panel.style.opacity = '1';
        });
        document.getElementById('tab-' + name).classList.add('active');
    }

    /* Init biodata panel opacity */
    document.getElementById('panel-biodata').style.opacity = '1';

    /* Auto-switch to keamanan tab if password errors exist */
    @if($errors->has('current_password') || $errors->has('password'))
        switchTab('keamanan');
    @endif

    /* ── Password Visibility Toggle ── */
    var eyeOpen  = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    var eyeClosed = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';

    function togglePw(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = eyeClosed;
        } else {
            input.type = 'password';
            icon.innerHTML = eyeOpen;
        }
    }

    /* ── Password Strength Checker ── */
    function checkStrength(value) {
        var wrap  = document.getElementById('strengthWrap');
        var fill  = document.getElementById('strengthFill');
        var label = document.getElementById('strengthLabel');

        if (!value || value.length === 0) {
            wrap.style.display = 'none';
            return;
        }
        wrap.style.display = 'flex';

        var score = 0;
        if (value.length >= 6)  score++;
        if (value.length >= 10) score++;
        if (/[A-Z]/.test(value) && /[a-z]/.test(value)) score++;
        if (/[0-9]/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        var levels = [
            { pct: '16%',  color: '#ef4444', shadow: 'rgba(239,68,68,0.5)',   text: 'Sangat Lemah' },
            { pct: '33%',  color: '#f97316', shadow: 'rgba(249,115,22,0.5)',  text: 'Lemah' },
            { pct: '50%',  color: '#eab308', shadow: 'rgba(234,179,8,0.5)',   text: 'Cukup' },
            { pct: '75%',  color: '#84cc16', shadow: 'rgba(132,204,22,0.45)', text: 'Baik' },
            { pct: '100%', color: '#22c55e', shadow: 'rgba(34,197,94,0.5)',   text: 'Kuat' }
        ];

        var lvl = levels[Math.min(score, 4)];
        fill.style.width      = lvl.pct;
        fill.style.background = lvl.color;
        fill.style.boxShadow  = '0 0 8px ' + lvl.shadow;
        label.textContent     = lvl.text;
        label.style.color     = lvl.color;
    }

    /* ── Avatar File Select & Preview ── */
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        var reader = new FileReader();
        reader.onload = function(ev) {
            var preview  = document.getElementById('avatarPreview');
            var initials = document.getElementById('avatarInitials');

            preview.src            = ev.target.result;
            preview.style.display  = 'block';

            if (initials) {
                initials.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);

        /* Show avatar info banner */
        var banner = document.getElementById('avatarInfoBanner');
        if (banner) {
            banner.style.display = 'flex';
            var span = document.getElementById('avatarFileName');
            if (span) {
                span.textContent = '"' + file.name + '" dipilih — akan diunggah saat menyimpan.';
            }
        }
    });
</script>

</body>
</html>