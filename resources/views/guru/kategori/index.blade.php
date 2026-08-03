<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori — DKV SMEKDA Portal</title>
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

        /* ── BLOBS ── */
        .blob { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
        .blob-1 {
            top: -180px; right: 100px;
            width: 580px; height: 580px;
            background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, transparent 65%);
            animation: blobF 11s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -120px; left: -80px;
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(220,38,38,0.06) 0%, transparent 65%);
            animation: blobF 14s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobF {
            0%   { transform: scale(1)    translate(0,0); }
            100% { transform: scale(1.14) translate(18px,14px); }
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #080808; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        /* ================================================================
           SIDEBAR (struktur sama persis dengan guru/dashboard.blade.php)
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

        .sidebar-logo {
            padding: 28px 24px 22px;
            border-bottom: 1px solid var(--border);
        }

        .logo-wordmark {
            font-size: 0.78rem; font-weight: 900;
            letter-spacing: 3px; text-transform: uppercase;
            color: rgba(255,255,255,0.85);
            display: flex; align-items: center; gap: 9px;
        }

        .logo-icon {
            width: 26px; height: 26px;
            background: var(--red); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px var(--red-glow); flex-shrink: 0;
        }

        .logo-icon svg { width: 13px; height: 13px; }

        .sidebar-profile {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
        }

        .profile-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: white;
            flex-shrink: 0;
            box-shadow: 0 0 18px rgba(220,38,38,0.3);
        }

        .profile-name {
            font-size: 0.78rem; font-weight: 700; color: #f5f5f5;
            line-height: 1.3; margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .profile-nip {
            font-size: 0.68rem; color: rgba(255,255,255,0.28); margin-bottom: 7px;
        }

        .badge-role {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.1);
            border: 1px solid rgba(220,38,38,0.22);
            color: #fca5a5;
            padding: 2px 9px; border-radius: 30px;
            font-size: 0.63rem; font-weight: 700;
            letter-spacing: 0.8px; text-transform: uppercase;
        }

        .live-dot {
            width: 5px; height: 5px; background: var(--red);
            border-radius: 50%; box-shadow: 0 0 6px var(--red-glow);
            animation: livePulse 1.5s ease-in-out infinite;
        }

        @keyframes livePulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.3; transform: scale(0.6); }
        }

        .sidebar-nav { flex: 1; padding: 20px 14px; }

        .nav-label {
            font-size: 0.62rem; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.18);
            padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: all 0.22s ease;
            border: 1px solid transparent;
            margin-bottom: 3px; position: relative;
        }

        .nav-item:hover {
            color: rgba(255,255,255,0.7);
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
            background: var(--red); border-radius: 0 3px 3px 0;
            box-shadow: 0 0 10px var(--red-glow);
        }

        .nav-item.active svg { color: var(--red); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }

        .sidebar-footer { padding: 14px; border-top: 1px solid var(--border); }

        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            background: none; border: 1px solid transparent;
            color: rgba(255,255,255,0.28);
            font-size: 0.82rem; font-weight: 600;
            font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all 0.22s ease;
        }

        .btn-logout:hover {
            color: #fca5a5;
            background: rgba(220,38,38,0.08);
            border-color: rgba(220,38,38,0.18);
        }

        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ================================================================
           MAIN
        ================================================================ */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            position: relative; z-index: 1;
        }

        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }

        .topbar-left { display: flex; align-items: center; gap: 14px; min-width: 0; }

        .topbar-title {
            font-size: 0.8rem; font-weight: 700;
            color: rgba(255,255,255,0.22); letter-spacing: 0.5px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .topbar-title span { color: rgba(255,255,255,0.5); margin-left: 6px; }

        .topbar-pill {
            display: flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.08);
            border: 1px solid rgba(220,38,38,0.18);
            border-radius: 30px; padding: 5px 13px;
            font-size: 0.68rem; font-weight: 700;
            color: rgba(220,38,38,0.65); letter-spacing: 0.5px;
            white-space: nowrap; flex-shrink: 0;
        }

        .page-inner { padding: 40px 36px 60px; }

        /* ── MOBILE MENU TOGGLE (tambahan agar nyaman di layar sempit) ── */
        .mobile-menu-btn {
            display: none;
            width: 36px; height: 36px; border-radius: 9px;
            background: rgba(255,255,255,0.05); border: 1px solid var(--border);
            color: rgba(255,255,255,0.6); cursor: pointer; flex-shrink: 0;
            align-items: center; justify-content: center;
        }
        .mobile-menu-btn svg { width: 18px; height: 18px; }
        .mobile-menu-btn:hover { background: rgba(220,38,38,0.1); color: #fca5a5; }

        .sidebar-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,0.6);
            z-index: 45; opacity: 0; visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .sidebar-backdrop.show { opacity: 1; visibility: visible; }

        /* ── GREETING ── */
        .eyebrow {
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 3px; text-transform: uppercase;
            color: rgba(220,38,38,0.65); margin-bottom: 10px;
        }

        .greeting-headline {
            font-size: clamp(1.55rem, 2.2vw, 2.1rem);
            font-weight: 900; letter-spacing: -1px; line-height: 1.15;
            color: #f5f5f5; margin-bottom: 8px;
        }

        .greeting-headline .hl {
            color: var(--red);
            text-shadow: 0 0 28px rgba(220,38,38,0.4);
        }

        .greeting-sub {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.28); font-weight: 400;
        }

        /* ── STAT CARDS ── */
        .summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 32px; }

        .stat-card {
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: 18px; padding: 24px;
            position: relative; overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(220,38,38,0.25);
            box-shadow: 0 20px 50px rgba(0,0,0,0.4), 0 0 40px rgba(220,38,38,0.07);
            background: rgba(220,38,38,0.035);
        }

        .stat-icon {
            width: 44px; height: 44px; border-radius: 12px;
            background: var(--red-soft);
            border: 1px solid rgba(220,38,38,0.18);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px; transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            background: rgba(220,38,38,0.16);
            box-shadow: 0 0 20px rgba(220,38,38,0.2);
        }

        .stat-icon svg { width: 20px; height: 20px; color: var(--red); }

        .stat-num {
            font-size: 2.4rem; font-weight: 900;
            letter-spacing: -2px; color: #f5f5f5;
            line-height: 1; margin-bottom: 6px;
        }

        .stat-lbl {
            font-size: 0.72rem; font-weight: 700;
            color: rgba(255,255,255,0.28);
            text-transform: uppercase; letter-spacing: 0.8px;
        }

        .stat-bar-track {
            margin-top: 16px; height: 2px;
            background: rgba(255,255,255,0.05); border-radius: 2px; overflow: hidden;
        }

        .stat-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--red), #ef4444);
            border-radius: 2px;
            box-shadow: 0 0 8px rgba(220,38,38,0.4);
        }

        .stat-bg-num {
            position: absolute; bottom: -8px; right: 14px;
            font-size: 5rem; font-weight: 900;
            color: rgba(255,255,255,0.02); letter-spacing: -4px;
            pointer-events: none; line-height: 1;
            transition: color 0.3s ease;
        }

        .stat-card:hover .stat-bg-num { color: rgba(220,38,38,0.04); }

        /* ── FLASH MESSAGES (pola sama dengan guru/profile.blade.php) ── */
        .flash-success {
            display: flex; align-items: flex-start; gap: 14px;
            background: rgba(34,197,94,0.07); border: 1px solid rgba(34,197,94,0.2);
            border-radius: 14px; padding: 16px 20px; margin-bottom: 24px;
            position: relative; transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .flash-error {
            display: flex; align-items: flex-start; gap: 14px;
            background: rgba(220,38,38,0.07); border: 1px solid rgba(220,38,38,0.2);
            border-radius: 14px; padding: 16px 20px; margin-bottom: 24px;
            position: relative; transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .flash-hide { opacity: 0; transform: translateY(-8px); pointer-events: none; }
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

        /* ── CONTENT CARD (panel form tambah kategori) ── */
        .content-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border);
            border-radius: 18px; padding: 24px;
            margin-bottom: 24px;
        }
        .content-card-title { font-size: 0.95rem; font-weight: 800; color: #f5f5f5; margin-bottom: 4px; }
        .content-card-sub { font-size: 0.75rem; color: rgba(255,255,255,0.28); margin-bottom: 18px; }

        .form-row { display: flex; align-items: flex-end; gap: 12px; }
        .form-field { flex: 1; min-width: 0; }

        .form-label {
            display: block; font-size: 0.72rem; font-weight: 700;
            color: rgba(255,255,255,0.4); margin-bottom: 8px; letter-spacing: 0.3px;
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif;
            color: #f5f5f5;
            outline: none;
            caret-color: var(--red);
            transition: all 0.25s ease;
        }
        .form-input::placeholder { color: rgba(255,255,255,0.18); }
        .form-input:focus {
            border-color: var(--red);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15);
        }
        .form-input.has-error { border-color: #f87171; background: rgba(220,38,38,0.06); }
        .form-input:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }

        .form-error {
            margin-top: 8px; font-size: 0.72rem; font-weight: 600;
            color: #fca5a5; display: flex; align-items: center; gap: 6px;
        }
        .form-error svg { width: 12px; height: 12px; flex-shrink: 0; }

        /* ── BUTTONS ── */
        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            background: var(--red); color: white;
            border: none; border-radius: 10px;
            padding: 12px 22px;
            font-size: 0.82rem; font-weight: 700;
            font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all 0.3s ease; white-space: nowrap;
        }
        .btn-primary:hover { background: #b91c1c; box-shadow: 0 6px 24px var(--red-glow); transform: translateY(-1px); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
        .btn-primary:focus-visible { outline: 2px solid #fca5a5; outline-offset: 2px; }
        .btn-primary svg { width: 15px; height: 15px; flex-shrink: 0; }

        .btn-secondary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.5);
            border-radius: 10px; padding: 12px 20px;
            font-size: 0.82rem; font-weight: 700;
            font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all 0.22s ease; white-space: nowrap;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.08); color: #f5f5f5; }
        .btn-secondary:focus-visible { outline: 2px solid rgba(255,255,255,0.4); outline-offset: 2px; }

        /* ── TABLE PANEL ── */
        .table-panel {
            background: rgba(255,255,255,0.018);
            border: 1px solid var(--border);
            border-radius: 20px; overflow: hidden;
        }

        .table-topbar {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }

        .table-title { font-size: 0.92rem; font-weight: 800; color: #f5f5f5; letter-spacing: -0.2px; }
        .table-sub { font-size: 0.72rem; color: rgba(255,255,255,0.22); margin-top: 2px; font-weight: 500; }

        .results-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 20px; padding: 4px 12px;
            font-size: 0.68rem; font-weight: 700;
            color: rgba(255,255,255,0.3);
        }

        .data-table { width: 100%; border-collapse: collapse; }

        .data-table thead tr { background: #111111; border-bottom: 1px solid var(--border); }

        .data-table thead th {
            padding: 12px 20px;
            font-size: 0.62rem; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.28);
            text-align: left; white-space: nowrap;
        }

        .data-table thead th:first-child { padding-left: 24px; }
        .data-table thead th:last-child  { padding-right: 24px; text-align: right; }

        .data-table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: all 0.22s ease;
            position: relative;
            animation: rowIn 0.4s ease both;
        }
        @keyframes rowIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

        .data-table tbody tr:last-child { border-bottom: none; }
        .data-table tbody tr:hover { background: rgba(220,38,38,0.04); }

        .data-table tbody tr:hover td:first-child::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 2px; background: var(--red);
            box-shadow: 0 0 8px var(--red-glow);
        }

        .data-table tbody td { padding: 14px 20px; vertical-align: middle; position: relative; }
        .data-table tbody td:first-child { padding-left: 24px; }
        .data-table tbody td:last-child  { padding-right: 24px; }

        .cell-name { font-size: 0.85rem; font-weight: 700; color: #f5f5f5; }
        .cell-slug { font-size: 0.68rem; color: rgba(255,255,255,0.22); margin-top: 2px; font-weight: 500; }

        .badge-count {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 26px; height: 24px; padding: 0 9px;
            background: rgba(255,255,255,0.05); border: 1px solid var(--border);
            border-radius: 20px; font-size: 0.72rem; font-weight: 800;
            color: rgba(255,255,255,0.55);
        }
        .badge-count.zero { background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.2); }
        .badge-count.active { background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.2); color: #fca5a5; }

        .btn-icon {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.5);
            padding: 7px 13px; border-radius: 8px;
            font-size: 0.72rem; font-weight: 700;
            cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all 0.22s ease; white-space: nowrap;
        }
        .btn-icon:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.16); color: #f5f5f5; }
        .btn-icon:focus-visible { outline: 2px solid rgba(255,255,255,0.4); outline-offset: 2px; }
        .btn-icon svg { width: 13px; height: 13px; }

        .btn-icon-danger {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.08);
            border: 1px solid rgba(220,38,38,0.18);
            color: #fca5a5;
            padding: 7px 13px; border-radius: 8px;
            font-size: 0.72rem; font-weight: 700;
            cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all 0.22s ease; white-space: nowrap;
        }
        .btn-icon-danger:hover { background: rgba(220,38,38,0.16); border-color: rgba(220,38,38,0.35); }
        .btn-icon-danger:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }
        .btn-icon-danger svg { width: 13px; height: 13px; }
        .btn-icon-danger.muted { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.06); color: rgba(255,255,255,0.22); }
        .btn-icon-danger.muted:hover { background: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.16); color: #fca5a5; }

        .row-actions { display: flex; align-items: center; justify-content: flex-end; gap: 7px; }

        /* ── PAGINATION ── */
        .pagination-wrap {
            padding: 18px 24px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }
        .pagination-info { font-size: 0.72rem; color: rgba(255,255,255,0.22); font-weight: 500; }
        .pagination-info strong { color: rgba(255,255,255,0.45); }

        .page-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 8px;
            font-size: 0.75rem; font-weight: 700;
            text-decoration: none;
            transition: all 0.22s ease;
        }
        .page-btn.inactive { background: rgba(255,255,255,0.04); border: 1px solid var(--border); color: rgba(255,255,255,0.3); }
        .page-btn.inactive:hover { background: rgba(255,255,255,0.08); color: #f5f5f5; }
        .page-btn.active { background: var(--red); border: 1px solid var(--red); color: white; box-shadow: 0 0 14px rgba(220,38,38,0.4); }
        .page-btn.disabled { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); color: rgba(255,255,255,0.12); cursor: not-allowed; pointer-events: none; }

        /* ── EMPTY TABLE ── */
        .table-empty { padding: 80px 40px; text-align: center; }
        .table-empty-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border); border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .table-empty-icon svg { width: 28px; height: 28px; color: rgba(255,255,255,0.15); }
        .table-empty-title { font-size: 0.9rem; font-weight: 800; color: rgba(255,255,255,0.35); margin-bottom: 8px; }
        .table-empty-sub { font-size: 0.78rem; color: rgba(255,255,255,0.18); line-height: 1.6; max-width: 320px; margin: 0 auto; }

        /* ================================================================
           MODAL EDIT KATEGORI
        ================================================================ */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
            opacity: 0; visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .modal-overlay.open { opacity: 1; visibility: visible; }

        .modal-box {
            width: 100%; max-width: 440px;
            background: #101010;
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6), 0 0 60px rgba(220,38,38,0.08);
            transform: translateY(14px) scale(0.98);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-overlay.open .modal-box { transform: translateY(0) scale(1); }

        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px; border-bottom: 1px solid var(--border);
        }
        .modal-title { font-size: 1rem; font-weight: 800; color: #f5f5f5; }
        .modal-close {
            width: 30px; height: 30px; border-radius: 8px;
            background: none; border: 1px solid var(--border);
            color: rgba(255,255,255,0.4); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }
        .modal-close:hover { color: #fca5a5; border-color: rgba(220,38,38,0.3); background: rgba(220,38,38,0.08); }
        .modal-close:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }
        .modal-close svg { width: 14px; height: 14px; }
        .modal-body { padding: 22px 24px; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px 22px; }

        /* ── RESPONSIVE ── */
        @media (max-width: 860px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; box-shadow: 20px 0 60px rgba(0,0,0,0.5); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-menu-btn { display: flex; }
        }

        @media (max-width: 640px) {
            .page-inner { padding: 24px 16px 48px; }
            .topbar { padding: 14px 16px; }
            .topbar-title span { display: none; }
            .greeting-headline { font-size: 1.5rem; }
            .summary-grid { grid-template-columns: 1fr; }
            .form-row { flex-direction: column; align-items: stretch; }
            .btn-primary { width: 100%; }
            .modal-box { max-width: 94%; }
            .modal-footer { flex-direction: column-reverse; }
            .modal-footer .btn-primary, .modal-footer .btn-secondary { width: 100%; }
            .table-topbar { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

{{-- ================================================================
     SIDEBAR (struktur & isi disalin dari guru/dashboard.blade.php)
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
            Portal Guru
        </div>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <div class="profile-avatar" style="overflow: hidden;">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1; min-width:0;">
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

        <a href="{{ route('guru.kategori.index') }}"
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
        <div class="topbar-left">
            <button type="button" class="mobile-menu-btn" id="btnMobileMenu" aria-label="Buka menu navigasi">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="topbar-title">
                Portal DKV SMEKDA <span>/</span> Kelola Kategori
            </div>
        </div>
        <div class="topbar-pill">
            <div class="live-dot"></div>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="page-inner">

        {{-- ── GREETING ── --}}
        <div style="margin-bottom:32px;">
            <div class="eyebrow">&#9654; Manajemen Data Master</div>
            <h1 class="greeting-headline">
                Kelola <span class="hl">Kategori.</span>
            </h1>
            <p class="greeting-sub">Atur kategori karya agar portofolio siswa DKV lebih terstruktur dan mudah ditemukan.</p>
        </div>

        {{-- ── FLASH: SUCCESS ── --}}
        @if(session('success'))
            <div class="flash-success" id="flashSuccess">
                <div class="flash-icon success">
                    <svg width="18" height="18" fill="none" stroke="#86efac" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.78rem;font-weight:800;color:#86efac;margin-bottom:3px;">Berhasil!</div>
                    <div style="font-size:0.72rem;color:rgba(134,239,172,0.65);font-weight:500;">{{ session('success') }}</div>
                </div>
                <button type="button" class="flash-close" onclick="document.getElementById('flashSuccess').remove()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── FLASH: ERROR ── --}}
        @if(session('error'))
            <div class="flash-error" id="flashError">
                <div class="flash-icon error">
                    <svg width="18" height="18" fill="none" stroke="#fca5a5" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.78rem;font-weight:800;color:#fca5a5;margin-bottom:3px;">Tidak Bisa Diproses</div>
                    <div style="font-size:0.72rem;color:rgba(252,165,165,0.65);font-weight:500;">{{ session('error') }}</div>
                </div>
                <button type="button" class="flash-close" onclick="document.getElementById('flashError').remove()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── KARTU RINGKASAN ── --}}
        <div class="summary-grid">
            <div class="stat-card">
                <div class="stat-bg-num">{{ $totalKategori }}</div>
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="stat-num">{{ $totalKategori }}</div>
                <div class="stat-lbl">Total Kategori</div>
                <div class="stat-bar-track">
                    <div class="stat-bar-fill" style="width:{{ min(100, $totalKategori * 8) }}%;"></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-bg-num">{{ $totalKaryaTerkategori }}</div>
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="stat-num">{{ $totalKaryaTerkategori }}</div>
                <div class="stat-lbl">Karya Sudah Dikategorikan</div>
                <div class="stat-bar-track">
                    <div class="stat-bar-fill" style="width:{{ min(100, $totalKaryaTerkategori * 3) }}%;"></div>
                </div>
            </div>
        </div>

        {{-- ── FORM TAMBAH KATEGORI ── --}}
        <div class="content-card">
            <div class="content-card-title">Tambah Kategori Baru</div>
            <div class="content-card-sub">Kategori baru akan langsung tersedia untuk dipilih siswa saat mengunggah karya.</div>

            <form method="POST" action="{{ route('guru.kategori.store') }}" id="formTambahKategori">
                @csrf
                <div class="form-row">
                    <div class="form-field">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-input {{ $errors->has('name') ? 'has-error' : '' }}"
                            placeholder="mis. Fotografi, Ilustrasi, Videografi..."
                            value="{{ old('name') }}"
                            required
                            maxlength="100"
                            autocomplete="off"
                        >
                        @error('name')
                            <div class="form-error">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span class="btn-label">Tambah Kategori</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ── TABEL DAFTAR KATEGORI ── --}}
        <div class="table-panel">

            <div class="table-topbar">
                <div>
                    <div class="table-title">Daftar Kategori</div>
                    <div class="table-sub">
                        Menampilkan {{ $categories->firstItem() ?? 0 }}–{{ $categories->lastItem() ?? 0 }}
                        dari {{ $categories->total() }} kategori
                    </div>
                </div>
                <div class="results-badge">
                    <div class="live-dot"></div>
                    {{ $categories->total() }} Total Kategori
                </div>
            </div>

            @if($categories->isEmpty())
                <div class="table-empty">
                    <div class="table-empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div class="table-empty-title">Belum ada kategori, tambahkan yang pertama.</div>
                    <div class="table-empty-sub">
                        Gunakan form di atas untuk membuat kategori pertama, misalnya "Fotografi" atau "Ilustrasi", agar siswa bisa mulai mengelompokkan karyanya.
                    </div>
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>Nama Kategori</th>
                                <th>Jumlah Karya</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                            <tr style="animation-delay: {{ $loop->index * 0.03 }}s;">
                                <td>
                                    <span style="font-size:0.72rem; font-weight:800; color:rgba(220,38,38,0.55);">
                                        {{ $categories->firstItem() + $index }}
                                    </span>
                                </td>
                                <td>
                                    <div class="cell-name">{{ $category->name }}</div>
                                    <div class="cell-slug">/{{ $category->slug }}</div>
                                </td>
                                <td>
                                    <span class="badge-count {{ $category->portfolios_count > 0 ? 'active' : 'zero' }}">
                                        {{ $category->portfolios_count }}
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="row-actions">
                                        <button
                                            type="button"
                                            class="btn-icon"
                                            data-edit-btn
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route('guru.kategori.destroy', $category) }}"
                                            class="form-delete-category"
                                            data-category-name="{{ $category->name }}"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn-icon-danger {{ $category->portfolios_count > 0 ? 'muted' : '' }}"
                                                title="{{ $category->portfolios_count > 0 ? 'Masih dipakai '.$category->portfolios_count.' karya' : 'Hapus kategori ini' }}"
                                            >
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($categories->hasPages())
                    <div class="pagination-wrap">
                        <div class="pagination-info">
                            Halaman <strong>{{ $categories->currentPage() }}</strong>
                            dari <strong>{{ $categories->lastPage() }}</strong>
                            &nbsp;&bull;&nbsp; Total <strong>{{ $categories->total() }}</strong> kategori
                        </div>
                        <div style="display:flex; align-items:center; gap:5px;">
                            @if($categories->onFirstPage())
                                <span class="page-btn disabled">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </span>
                            @else
                                <a href="{{ $categories->previousPageUrl() }}" class="page-btn inactive">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </a>
                            @endif

                            @foreach($categories->getUrlRange(max(1, $categories->currentPage()-2), min($categories->lastPage(), $categories->currentPage()+2)) as $page => $url)
                                @if($page == $categories->currentPage())
                                    <span class="page-btn active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="page-btn inactive">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($categories->hasMorePages())
                                <a href="{{ $categories->nextPageUrl() }}" class="page-btn inactive">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <span class="page-btn disabled">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>

        {{-- Footer Strip --}}
        <div style="margin-top:48px; padding-top:24px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <span style="font-size:0.7rem; color:rgba(255,255,255,0.14);">
                &copy; {{ date('Y') }} <strong style="color:rgba(255,255,255,0.26);">DKV SMEKDA</strong>
                &nbsp;&bull;&nbsp; SMK Negeri 2 Padang Panjang
            </span>
            <span style="font-size:0.7rem; color:rgba(255,255,255,0.12);">
                Dikembangkan untuk Skripsi oleh <strong style="color:rgba(255,255,255,0.22);">Rafli</strong> &mdash; 2026
            </span>
        </div>

    </div>
</div>

{{-- ================================================================
     MODAL: EDIT KATEGORI
================================================================ --}}
<div
    class="modal-overlay"
    id="modalEditKategori"
    data-auto-open="{{ $errors->editCategory->has('name') ? '1' : '0' }}"
    data-reopen-id="{{ old('category_id') }}"
    data-reopen-name="{{ old('name') }}"
>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalEditTitle">
        <div class="modal-header">
            <h3 class="modal-title" id="modalEditTitle">Edit Kategori</h3>
            <button type="button" class="modal-close" id="btnCloseModalEdit" aria-label="Tutup modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form
            method="POST"
            action="{{ route('guru.kategori.update', ['category' => old('category_id', 0)]) }}"
            id="formEditKategori"
            data-url-template="{{ route('guru.kategori.update', ['category' => '__ID__']) }}"
        >
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" id="editCategoryId" value="{{ old('category_id') }}">
            <div class="modal-body">
                <label for="editName" class="form-label">Nama Kategori</label>
                <input
                    type="text"
                    name="name"
                    id="editName"
                    class="form-input {{ $errors->editCategory->has('name') ? 'has-error' : '' }}"
                    value="{{ old('name') }}"
                    required
                    maxlength="100"
                    autocomplete="off"
                >
                @error('name', 'editCategory')
                    <div class="form-error">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="btnBatalEdit">Batal</button>
                <button type="submit" class="btn-primary">
                    <span class="btn-label">Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Toggle sidebar di layar sempit (<=860px) ──
    (function () {
        const sidebar  = document.querySelector('.sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const btnMenu  = document.getElementById('btnMobileMenu');

        function openSidebar()  { sidebar.classList.add('open');    backdrop.classList.add('show'); }
        function closeSidebar() { sidebar.classList.remove('open'); backdrop.classList.remove('show'); }

        btnMenu?.addEventListener('click', function () {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        backdrop?.addEventListener('click', closeSidebar);

        window.closeSidebarOnEscape = closeSidebar;
    })();

    // ── Modal Edit Kategori ──
    (function () {
        const overlay   = document.getElementById('modalEditKategori');
        const form      = document.getElementById('formEditKategori');
        const inputId   = document.getElementById('editCategoryId');
        const inputName = document.getElementById('editName');
        const urlTemplate = form.dataset.urlTemplate;

        function openEditModal(id, name) {
            form.action = urlTemplate.replace('__ID__', id);
            inputId.value = id;
            inputName.value = name;
            overlay.classList.add('open');
            setTimeout(() => inputName.focus(), 50);
        }

        function closeEditModal() {
            overlay.classList.remove('open');
        }

        document.querySelectorAll('[data-edit-btn]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openEditModal(btn.dataset.id, btn.dataset.name);
            });
        });

        document.getElementById('btnCloseModalEdit').addEventListener('click', closeEditModal);
        document.getElementById('btnBatalEdit').addEventListener('click', closeEditModal);

        // Tutup jika klik di luar kotak modal (area overlay gelap)
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeEditModal();
        });

        // Tutup dengan tombol Escape (juga menutup sidebar mobile jika terbuka)
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (overlay.classList.contains('open')) closeEditModal();
                if (window.closeSidebarOnEscape) window.closeSidebarOnEscape();
            }
        });

        // Buka kembali modal otomatis jika validasi update gagal di server
        if (overlay.dataset.autoOpen === '1') {
            openEditModal(overlay.dataset.reopenId, overlay.dataset.reopenName);
        }

        window.openEditModal = openEditModal;
    })();

    // ── Konfirmasi & state loading saat hapus kategori ──
    document.querySelectorAll('.form-delete-category').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const name = form.dataset.categoryName;
            if (!confirm("Yakin hapus kategori '" + name + "'?")) {
                e.preventDefault();
                return;
            }
            const btn = form.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = true; }
        });
    });

    // ── State loading saat submit form tambah & edit kategori ──
    ['formTambahKategori', 'formEditKategori'].forEach(function (formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        form.addEventListener('submit', function () {
            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            btn.disabled = true;
            const label = btn.querySelector('.btn-label');
            if (label) label.textContent = 'Menyimpan...';
        });
    });

    // ── Toast: hilangkan flash message otomatis setelah beberapa detik ──
    ['flashSuccess', 'flashError'].forEach(function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        setTimeout(function () {
            el.classList.add('flash-hide');
            setTimeout(function () { el.remove(); }, 400);
        }, 4500);
    });
</script>

</body>
</html>