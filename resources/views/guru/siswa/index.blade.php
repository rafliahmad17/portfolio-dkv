<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa — DKV SMEKDA Portal</title>
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

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }

        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(220,38,38,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220,38,38,0.035) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none; z-index: 0;
        }

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
        .sidebar-logo { padding: 28px 24px 22px; border-bottom: 1px solid var(--border); }
        .logo-wordmark {
            font-size: 0.78rem; font-weight: 900; letter-spacing: 3px; text-transform: uppercase;
            color: rgba(255,255,255,0.85); display: flex; align-items: center; gap: 9px;
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
            font-size: 1rem; font-weight: 900; color: white;
            flex-shrink: 0; box-shadow: 0 0 18px rgba(220,38,38,0.3);
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
            width: 5px; height: 5px; background: var(--red);
            border-radius: 50%; box-shadow: 0 0 6px var(--red-glow);
            animation: livePulse 1.5s ease-in-out infinite;
        }
        @keyframes livePulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.3; transform: scale(0.6); } }
        .sidebar-nav { flex: 1; padding: 20px 14px; }
        .nav-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.18); padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
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
            width: 3px; height: 18px; background: var(--red); border-radius: 0 3px 3px 0;
            box-shadow: 0 0 10px var(--red-glow);
        }
        .nav-item.active svg { color: var(--red); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar-footer { padding: 14px; border-top: 1px solid var(--border); }
        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px; background: none; border: 1px solid transparent;
            color: rgba(255,255,255,0.28); font-size: 0.82rem; font-weight: 600;
            font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.22s ease;
        }
        .btn-logout:hover { color: #fca5a5; background: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.18); }
        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ================================================================
           MAIN
        ================================================================ */
        .main-content { margin-left: 260px; min-height: 100vh; position: relative; z-index: 1; }
        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(8,8,8,0.88); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border); padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .topbar-title {
            font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.22); letter-spacing: 0.5px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .topbar-title span { color: rgba(255,255,255,0.5); margin-left: 6px; }
        .topbar-pill {
            display: flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.18);
            border-radius: 30px; padding: 5px 13px; font-size: 0.68rem; font-weight: 700;
            color: rgba(220,38,38,0.65); letter-spacing: 0.5px; white-space: nowrap; flex-shrink: 0;
        }
        .page-inner { padding: 40px 36px 60px; }
        .mobile-menu-btn {
            display: none; width: 36px; height: 36px; border-radius: 9px;
            background: rgba(255,255,255,0.05); border: 1px solid var(--border);
            color: rgba(255,255,255,0.6); cursor: pointer; flex-shrink: 0;
            align-items: center; justify-content: center;
        }
        .mobile-menu-btn svg { width: 18px; height: 18px; }
        .mobile-menu-btn:hover { background: rgba(220,38,38,0.1); color: #fca5a5; }
        .sidebar-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 45;
            opacity: 0; visibility: hidden; transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .sidebar-backdrop.show { opacity: 1; visibility: visible; }

        .eyebrow {
            font-size: 0.68rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase;
            color: rgba(220,38,38,0.65); margin-bottom: 10px;
        }
        .greeting-headline {
            font-size: clamp(1.55rem, 2.2vw, 2.1rem); font-weight: 900; letter-spacing: -1px;
            line-height: 1.15; color: #f5f5f5; margin-bottom: 8px;
        }
        .greeting-headline .hl { color: var(--red); text-shadow: 0 0 28px rgba(220,38,38,0.4); }
        .greeting-sub { font-size: 0.875rem; color: rgba(255,255,255,0.28); font-weight: 400; }

        /* ── STAT CARDS ── */
        .summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 18px; padding: 24px; position: relative; overflow: hidden; transition: all 0.3s ease;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }
        .stat-card:hover {
            transform: translateY(-4px); border-color: rgba(220,38,38,0.25);
            box-shadow: 0 20px 50px rgba(0,0,0,0.4), 0 0 40px rgba(220,38,38,0.07);
            background: rgba(220,38,38,0.035);
        }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 12px; background: var(--red-soft);
            border: 1px solid rgba(220,38,38,0.18); display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px; transition: all 0.3s ease;
        }
        .stat-card:hover .stat-icon { background: rgba(220,38,38,0.16); box-shadow: 0 0 20px rgba(220,38,38,0.2); }
        .stat-icon svg { width: 20px; height: 20px; color: var(--red); }
        .stat-num { font-size: 2.4rem; font-weight: 900; letter-spacing: -2px; color: #f5f5f5; line-height: 1; margin-bottom: 6px; }
        .stat-lbl { font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.28); text-transform: uppercase; letter-spacing: 0.8px; }
        .stat-bg-num {
            position: absolute; bottom: -8px; right: 14px; font-size: 5rem; font-weight: 900;
            color: rgba(255,255,255,0.02); letter-spacing: -4px; pointer-events: none; line-height: 1; transition: color 0.3s ease;
        }
        .stat-card:hover .stat-bg-num { color: rgba(220,38,38,0.04); }

        /* ── FLASH MESSAGES (pola sama dengan guru/kategori) ── */
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
        .flash-icon { width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .flash-icon.success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.2); }
        .flash-icon.error   { background: rgba(220,38,38,0.12); border: 1px solid rgba(220,38,38,0.2); }
        .flash-close {
            position: absolute; top: 12px; right: 14px; background: none; border: none;
            color: rgba(255,255,255,0.2); cursor: pointer; padding: 4px;
            display: flex; align-items: center; transition: color 0.2s ease;
        }
        .flash-close:hover { color: rgba(255,255,255,0.5); }
        .flash-close svg { width: 14px; height: 14px; }

        /* ── TOOLBAR: SEARCH & ARSIP ── */
        .content-card {
            background: rgba(255,255,255,0.02); border: 1px solid var(--border);
            border-radius: 18px; padding: 20px 24px; margin-bottom: 24px;
            display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;
        }
        .form-row { display: flex; align-items: flex-end; gap: 12px; flex: 1; min-width: 240px; }
        .form-field { flex: 1; min-width: 0; }
        .form-label { display: block; font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.4); margin-bottom: 8px; letter-spacing: 0.3px; }
        .form-input {
            width: 100%; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 10px; padding: 12px 14px; font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif; color: #f5f5f5; outline: none; caret-color: var(--red);
            transition: all 0.25s ease;
        }
        .form-input::placeholder { color: rgba(255,255,255,0.18); }
        .form-input:focus { border-color: var(--red); background: rgba(220,38,38,0.05); box-shadow: 0 0 0 3px rgba(220,38,38,0.15); }
        .form-input.has-error { border-color: #f87171; background: rgba(220,38,38,0.06); }
        .form-input:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }
        .form-error { margin-top: 8px; font-size: 0.72rem; font-weight: 600; color: #fca5a5; display: flex; align-items: center; gap: 6px; }
        .form-error svg { width: 12px; height: 12px; flex-shrink: 0; }
        .form-hint { margin-top: 6px; font-size: 0.68rem; color: rgba(255,255,255,0.2); }

        /* ── BUTTONS ── */
        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            background: var(--red); color: white; border: none; border-radius: 10px; padding: 12px 22px;
            font-size: 0.82rem; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all 0.3s ease; white-space: nowrap;
        }
        .btn-primary:hover { background: #b91c1c; box-shadow: 0 6px 24px var(--red-glow); transform: translateY(-1px); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
        .btn-primary:focus-visible { outline: 2px solid #fca5a5; outline-offset: 2px; }
        .btn-primary svg { width: 15px; height: 15px; flex-shrink: 0; }
        .btn-secondary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.5); border-radius: 10px; padding: 12px 20px;
            font-size: 0.82rem; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all 0.22s ease; white-space: nowrap; text-decoration: none;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.08); color: #f5f5f5; }
        .btn-secondary:focus-visible { outline: 2px solid rgba(255,255,255,0.4); outline-offset: 2px; }
        .btn-secondary svg { width: 14px; height: 14px; }

        /* ── TABLE PANEL ── */
        .table-panel { background: rgba(255,255,255,0.018); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; }
        .table-topbar {
            padding: 18px 24px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        }
        .table-title { font-size: 0.92rem; font-weight: 800; color: #f5f5f5; letter-spacing: -0.2px; }
        .table-sub { font-size: 0.72rem; color: rgba(255,255,255,0.22); margin-top: 2px; font-weight: 500; }
        .results-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.04); border: 1px solid var(--border);
            border-radius: 20px; padding: 4px 12px; font-size: 0.68rem; font-weight: 700; color: rgba(255,255,255,0.3);
        }
        .results-badge.archive { background: rgba(234,179,8,0.08); border-color: rgba(234,179,8,0.2); color: #fde68a; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead tr { background: #111111; border-bottom: 1px solid var(--border); }
        .data-table thead th {
            padding: 12px 20px; font-size: 0.62rem; font-weight: 800; letter-spacing: 2px;
            text-transform: uppercase; color: rgba(255,255,255,0.28); text-align: left; white-space: nowrap;
        }
        .data-table thead th:first-child { padding-left: 24px; width: 40px; }
        .data-table thead th:last-child  { padding-right: 24px; text-align: right; }
        .data-table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.04); transition: all 0.22s ease;
            position: relative; animation: rowIn 0.4s ease both;
        }
        @keyframes rowIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        .data-table tbody tr:last-child { border-bottom: none; }
        .data-table tbody tr:hover { background: rgba(220,38,38,0.04); }
        .data-table tbody tr:hover td:first-child::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px;
            background: var(--red); box-shadow: 0 0 8px var(--red-glow);
        }
        .data-table tbody td { padding: 14px 20px; vertical-align: middle; position: relative; }
        .data-table tbody td:first-child { padding-left: 24px; }
        .data-table tbody td:last-child  { padding-right: 24px; }

        .name-cell { display: flex; align-items: center; gap: 12px; }
        .avatar-mini {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 900; color: white; box-shadow: 0 0 14px rgba(220,38,38,0.25);
        }
        .cell-name { font-size: 0.85rem; font-weight: 700; color: #f5f5f5; }
        .cell-slug { font-size: 0.68rem; color: rgba(255,255,255,0.22); margin-top: 2px; font-weight: 500; }
        .cell-email { font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.75); }
        .cell-nis { font-size: 0.68rem; color: rgba(255,255,255,0.22); margin-top: 2px; font-weight: 500; }

        .row-actions { display: flex; align-items: center; justify-content: flex-end; gap: 7px; }

        .btn-icon, .btn-icon-info, .btn-icon-warning, .btn-icon-danger, .btn-icon-success {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 13px; border-radius: 8px; font-size: 0.72rem; font-weight: 700;
            cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.22s ease; white-space: nowrap; border: none;
        }
        .btn-icon svg, .btn-icon-info svg, .btn-icon-warning svg, .btn-icon-danger svg, .btn-icon-success svg { width: 13px; height: 13px; }

        .btn-icon-info {
            background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.2); color: #93c5fd;
        }
        .btn-icon-info:hover { background: rgba(59,130,246,0.16); border-color: rgba(59,130,246,0.35); }
        .btn-icon-info:focus-visible { outline: 2px solid #60a5fa; outline-offset: 2px; }

        .btn-icon-warning {
            background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.2); color: #fde68a;
        }
        .btn-icon-warning:hover { background: rgba(234,179,8,0.16); border-color: rgba(234,179,8,0.35); }
        .btn-icon-warning:focus-visible { outline: 2px solid #eab308; outline-offset: 2px; }

        .btn-icon-danger { background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.18); color: #fca5a5; }
        .btn-icon-danger:hover { background: rgba(220,38,38,0.16); border-color: rgba(220,38,38,0.35); }
        .btn-icon-danger:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }

        .btn-icon-success { background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2); color: #86efac; }
        .btn-icon-success:hover { background: rgba(34,197,94,0.16); border-color: rgba(34,197,94,0.35); }
        .btn-icon-success:focus-visible { outline: 2px solid #22c55e; outline-offset: 2px; }

        /* ── EMPTY STATE ── */
        .table-empty { padding: 80px 40px; text-align: center; }
        .table-empty-icon {
            width: 64px; height: 64px; background: rgba(255,255,255,0.03);
            border: 1px solid var(--border); border-radius: 18px;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;
        }
        .table-empty-icon svg { width: 28px; height: 28px; color: rgba(255,255,255,0.15); }
        .table-empty-title { font-size: 0.9rem; font-weight: 800; color: rgba(255,255,255,0.35); margin-bottom: 8px; }
        .table-empty-sub { font-size: 0.78rem; color: rgba(255,255,255,0.18); line-height: 1.6; max-width: 320px; margin: 0 auto; }

        /* ── PAGINATION ── */
        .pagination-wrap {
            padding: 18px 24px; border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        }
        .pagination-info { font-size: 0.72rem; color: rgba(255,255,255,0.22); font-weight: 500; }
        .pagination-info strong { color: rgba(255,255,255,0.45); }
        .page-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;
            text-decoration: none; transition: all 0.22s ease;
        }
        .page-btn.inactive { background: rgba(255,255,255,0.04); border: 1px solid var(--border); color: rgba(255,255,255,0.3); }
        .page-btn.inactive:hover { background: rgba(255,255,255,0.08); color: #f5f5f5; }
        .page-btn.active { background: var(--red); border: 1px solid var(--red); color: white; box-shadow: 0 0 14px rgba(220,38,38,0.4); }
        .page-btn.disabled { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); color: rgba(255,255,255,0.12); cursor: not-allowed; pointer-events: none; }

        /* ================================================================
           MODAL (Tambah / Edit / Detail Siswa)
        ================================================================ */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 200; background: rgba(0,0,0,0.65);
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            display: flex; align-items: center; justify-content: center; padding: 20px;
            opacity: 0; visibility: hidden; transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .modal-overlay.open { opacity: 1; visibility: visible; }
        .modal-box {
            width: 100%; max-width: 460px; background: #101010; border: 1px solid var(--border);
            border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,0.6), 0 0 60px rgba(220,38,38,0.08);
            transform: translateY(14px) scale(0.98); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-overlay.open .modal-box { transform: translateY(0) scale(1); }
        .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid var(--border); }
        .modal-title { font-size: 1rem; font-weight: 800; color: #f5f5f5; }
        .modal-close {
            width: 30px; height: 30px; border-radius: 8px; background: none; border: 1px solid var(--border);
            color: rgba(255,255,255,0.4); cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }
        .modal-close:hover { color: #fca5a5; border-color: rgba(220,38,38,0.3); background: rgba(220,38,38,0.08); }
        .modal-close:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }
        .modal-close svg { width: 14px; height: 14px; }
        .modal-body { padding: 22px 24px; display: flex; flex-direction: column; gap: 16px; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px 22px; }

        .detail-row { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.35); }
        .detail-value { font-size: 0.82rem; font-weight: 600; color: #f5f5f5; text-align: right; }

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
            .btn-primary, .btn-secondary { width: 100%; }
            .modal-box { max-width: 94%; }
            .modal-footer { flex-direction: column-reverse; }
            .modal-footer .btn-primary, .modal-footer .btn-secondary { width: 100%; }
            .table-topbar { flex-direction: column; align-items: flex-start; }
            .content-card { flex-direction: column; align-items: stretch; }
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

        {{-- Data Siswa --}}
        <a href="{{ route('guru.siswa.index') }}"
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

    <div class="topbar">
        <div class="topbar-left">
            <button type="button" class="mobile-menu-btn" id="btnMobileMenu" aria-label="Buka menu navigasi">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="topbar-title">
                Portal DKV SMEKDA <span>/</span> Data Siswa
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
            <div class="eyebrow">&#9654; Manajemen Akun Pengguna</div>
            <h1 class="greeting-headline">
                Data <span class="hl">Siswa.</span>
            </h1>
            <p class="greeting-sub">Kelola akun siswa DKV — daftarkan siswa baru, perbarui data, atau arsipkan akun yang tidak aktif.</p>
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
                <div class="stat-bg-num">{{ $totalSiswa }}</div>
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="stat-num">{{ $totalSiswa }}</div>
                <div class="stat-lbl">Total Siswa Aktif</div>
            </div>

            <div class="stat-card">
                <div class="stat-bg-num">{{ $totalArsip }}</div>
                <div class="stat-icon" style="background: rgba(234,179,8,0.1); border-color: rgba(234,179,8,0.2);">
                    <svg fill="none" stroke="#fde68a" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <div class="stat-num">{{ $totalArsip }}</div>
                <div class="stat-lbl">Akun Diarsipkan</div>
            </div>
        </div>

        {{-- ── TOOLBAR: PENCARIAN & ARSIP ── --}}
        <div class="content-card">
            <form method="GET" action="{{ route('guru.siswa.index') }}" class="form-row">
                @if($showTrashed)
                    <input type="hidden" name="trashed" value="1">
                @endif
                <div class="form-field">
                    <input
                        type="text"
                        name="search"
                        class="form-input"
                        placeholder="Cari nama, email, atau NIS..."
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
            </form>

            @if($showTrashed)
                <a href="{{ route('guru.siswa.index', array_filter(['search' => request('search')])) }}" class="btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Data Aktif
                </a>
            @else
                <a href="{{ route('guru.siswa.index', array_filter(['search' => request('search'), 'trashed' => 1])) }}" class="btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/></svg>
                    Lihat Arsip ({{ $totalArsip }})
                </a>
            @endif
        </div>

        {{-- ── TABEL DATA SISWA ── --}}
        <div class="table-panel">

            <div class="table-topbar">
                <div>
                    <div class="table-title">{{ $showTrashed ? 'Arsip Akun Siswa' : 'Daftar Siswa' }}</div>
                    <div class="table-sub">
                        Menampilkan {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}
                        dari {{ $students->total() }} akun
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="results-badge {{ $showTrashed ? 'archive' : '' }}">
                        <div class="live-dot"></div>
                        {{ $students->total() }} {{ $showTrashed ? 'Diarsipkan' : 'Total Siswa' }}
                    </div>

                    @unless($showTrashed)
                        {{-- Tombol "Tambah Data Siswa" — membuka modal (bukan route terpisah,
                             karena resource route sengaja tidak memiliki create/edit,
                             mengikuti pola modal yang sama seperti Kelola Kategori) --}}
                        <button type="button" class="btn-primary" id="btnBukaTambahSiswa">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span class="btn-label">Tambah Data Siswa</span>
                        </button>
                    @endunless
                </div>
            </div>

            @forelse($students as $index => $student)
                @if($loop->first)
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Email / NISN</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif

                            <tr
                                style="animation-delay: {{ $loop->index * 0.03 }}s;"
                                data-id="{{ $student->id }}"
                                data-name="{{ $student->name }}"
                                data-email="{{ $student->email }}"
                                data-nis="{{ $student->nis_nip }}"
                            >
                                <td>
                                    <span style="font-size:0.72rem; font-weight:800; color:rgba(220,38,38,0.55);">
                                        {{ $students->firstItem() + $index }}
                                    </span>
                                </td>
                                <td>
                                    <div class="name-cell">
                                        <div class="avatar-mini">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="cell-name">{{ $student->name }}</div>
                                            <div class="cell-slug">
                                                {{ $student->portfolios_count }} karya &bull; {{ $student->achievements_count }} prestasi
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="cell-email">{{ $student->email }}</div>
                                    <div class="cell-nis">NISN: {{ $student->nis_nip ?? '—' }}</div>
                                </td>
                                <td style="text-align:right;">
                                    <div class="row-actions">

                                        {{-- Detail — read-only, data diambil dari attribute baris (tidak perlu route baru) --}}
                                        <button
                                            type="button"
                                            class="btn-icon-info"
                                            data-detail-btn
                                            data-name="{{ $student->name }}"
                                            data-email="{{ $student->email }}"
                                            data-nis="{{ $student->nis_nip ?? '—' }}"
                                            data-portfolios="{{ $student->portfolios_count }}"
                                            data-achievements="{{ $student->achievements_count }}"
                                            data-joined="{{ $student->created_at?->translatedFormat('d F Y') ?? '—' }}"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </button>

                                        @if($showTrashed)
                                            {{-- Pulihkan --}}
                                            <form method="POST" action="{{ route('guru.siswa.restore', $student) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-icon-success">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    Pulihkan
                                                </button>
                                            </form>

                                            {{-- Hapus Permanen --}}
                                            <form
                                                method="POST"
                                                action="{{ route('guru.siswa.force-delete', $student) }}"
                                                onsubmit="return confirm('Akun ini beserta SELURUH portofolio & prestasinya akan dihapus permanen dan tidak bisa dikembalikan. Lanjutkan?')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon-danger">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus Permanen
                                                </button>
                                            </form>
                                        @else
                                            {{-- Edit — membuka modal --}}
                                            <button
                                                type="button"
                                                class="btn-icon-warning"
                                                data-edit-btn
                                                data-id="{{ $student->id }}"
                                                data-name="{{ $student->name }}"
                                                data-email="{{ $student->email }}"
                                                data-nis="{{ $student->nis_nip }}"
                                            >
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </button>

                                            {{-- Hapus (soft-delete / arsipkan) --}}
                                            <form
                                                method="POST"
                                                action="{{ route('guru.siswa.destroy', $student) }}"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini? Tindakan ini tidak dapat dibatalkan.')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon-danger">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                @if($loop->last)
                        </tbody>
                    </table>
                </div>
                @endif
            @empty
                <div class="table-empty">
                    <div class="table-empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="table-empty-title">
                        {{ $showTrashed ? 'Arsip masih kosong.' : 'Belum ada data siswa saat ini.' }}
                    </div>
                    <div class="table-empty-sub">
                        {{ $showTrashed
                            ? 'Akun siswa yang dihapus akan muncul di sini dan bisa dipulihkan kapan saja.'
                            : 'Klik tombol "Tambah Data Siswa" di atas untuk mendaftarkan akun siswa pertama Anda.' }}
                    </div>
                </div>
            @endforelse

            @if($students->hasPages())
                <div class="pagination-wrap">
                    <div class="pagination-info">
                        Halaman <strong>{{ $students->currentPage() }}</strong>
                        dari <strong>{{ $students->lastPage() }}</strong>
                        &nbsp;&bull;&nbsp; Total <strong>{{ $students->total() }}</strong> akun
                    </div>
                    <div style="display:flex; align-items:center; gap:5px;">
                        @if($students->onFirstPage())
                            <span class="page-btn disabled">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        @else
                            <a href="{{ $students->previousPageUrl() }}" class="page-btn inactive">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @endif

                        @foreach($students->getUrlRange(max(1, $students->currentPage()-2), min($students->lastPage(), $students->currentPage()+2)) as $page => $url)
                            @if($page == $students->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn inactive">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}" class="page-btn inactive">
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
     MODAL: TAMBAH DATA SISWA
================================================================ --}}
<div
    class="modal-overlay"
    id="modalTambahSiswa"
    data-auto-open="{{ (!old('id') && $errors->any()) ? '1' : '0' }}"
>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTambahTitle">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTambahTitle">Tambah Data Siswa</h3>
            <button type="button" class="modal-close" id="btnCloseModalTambah" aria-label="Tutup modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('guru.siswa.store') }}" id="formTambahSiswa">
            @csrf
            <div class="modal-body">
                <div class="form-field">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name"
                           class="form-input {{ $errors->has('name') ? 'has-error' : '' }}"
                           value="{{ old('id') ? '' : old('name') }}" required maxlength="255" autocomplete="off">
                    @error('name')
                        <div class="form-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email"
                           class="form-input {{ $errors->has('email') ? 'has-error' : '' }}"
                           value="{{ old('id') ? '' : old('email') }}" required maxlength="255" autocomplete="off">
                    @error('email')
                        <div class="form-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="nis_nip" class="form-label">NISN <span style="opacity:.5;font-weight:500;">(opsional)</span></label>
                    <input type="text" name="nis_nip" id="nis_nip"
                           class="form-input {{ $errors->has('nis_nip') ? 'has-error' : '' }}"
                           value="{{ old('id') ? '' : old('nis_nip') }}" maxlength="50" autocomplete="off">
                    @error('nis_nip')
                        <div class="form-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password" class="form-label">Password Awal</label>
                    <input type="password" name="password" id="password"
                           class="form-input {{ $errors->has('password') ? 'has-error' : '' }}"
                           required minlength="8" autocomplete="new-password">
                    @error('password')
                        <div class="form-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @else
                        <div class="form-hint">Minimal 8 karakter. Siswa bisa menggantinya nanti lewat profil.</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="btnBatalTambah">Batal</button>
                <button type="submit" class="btn-primary">
                    <span class="btn-label">Simpan Siswa</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================================================================
     MODAL: EDIT DATA SISWA
================================================================ --}}
<div
    class="modal-overlay"
    id="modalEditSiswa"
    data-auto-open="{{ (old('id') && $errors->hasBag('editStudent'.old('id'))) ? '1' : '0' }}"
    data-reopen-id="{{ old('id') }}"
    data-reopen-name="{{ old('name') }}"
    data-reopen-email="{{ old('email') }}"
    data-reopen-nis="{{ old('nis_nip') }}"
>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalEditTitle">
        <div class="modal-header">
            <h3 class="modal-title" id="modalEditTitle">Edit Data Siswa</h3>
            <button type="button" class="modal-close" id="btnCloseModalEdit" aria-label="Tutup modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form
            method="POST"
            action="{{ route('guru.siswa.update', ['siswa' => old('id', 0)]) }}"
            id="formEditSiswa"
            data-url-template="{{ route('guru.siswa.update', ['siswa' => '__ID__']) }}"
        >
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editStudentId" value="{{ old('id') }}">
            <div class="modal-body">
                <div class="form-field">
                    <label for="editName" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="editName"
                           class="form-input {{ $errors->hasBag('editStudent'.old('id')) && $errors->getBag('editStudent'.old('id'))->has('name') ? 'has-error' : '' }}"
                           value="{{ old('name') }}" required maxlength="255" autocomplete="off">
                    @error('name', 'editStudent'.old('id'))
                        <div class="form-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="editEmail" class="form-label">Email</label>
                    <input type="email" name="email" id="editEmail"
                           class="form-input {{ $errors->hasBag('editStudent'.old('id')) && $errors->getBag('editStudent'.old('id'))->has('email') ? 'has-error' : '' }}"
                           value="{{ old('email') }}" required maxlength="255" autocomplete="off">
                    @error('email', 'editStudent'.old('id'))
                        <div class="form-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="editNis" class="form-label">NISN <span style="opacity:.5;font-weight:500;">(opsional)</span></label>
                    <input type="text" name="nis_nip" id="editNis"
                           class="form-input {{ $errors->hasBag('editStudent'.old('id')) && $errors->getBag('editStudent'.old('id'))->has('nis_nip') ? 'has-error' : '' }}"
                           value="{{ old('nis_nip') }}" maxlength="50" autocomplete="off">
                    @error('nis_nip', 'editStudent'.old('id'))
                        <div class="form-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="editPassword" class="form-label">Password Baru <span style="opacity:.5;font-weight:500;">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" id="editPassword"
                           class="form-input {{ $errors->hasBag('editStudent'.old('id')) && $errors->getBag('editStudent'.old('id'))->has('password') ? 'has-error' : '' }}"
                           minlength="8" autocomplete="new-password">
                    @error('password', 'editStudent'.old('id'))
                        <div class="form-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
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

{{-- ================================================================
     MODAL: DETAIL SISWA (read-only)
================================================================ --}}
<div class="modal-overlay" id="modalDetailSiswa">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalDetailTitle">
        <div class="modal-header">
            <h3 class="modal-title" id="modalDetailTitle">Detail Siswa</h3>
            <button type="button" class="modal-close" id="btnCloseModalDetail" aria-label="Tutup modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="detail-row"><span class="detail-label">Nama Lengkap</span><span class="detail-value" id="detailName">—</span></div>
            <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value" id="detailEmail">—</span></div>
            <div class="detail-row"><span class="detail-label">NISN</span><span class="detail-value" id="detailNis">—</span></div>
            <div class="detail-row"><span class="detail-label">Total Karya</span><span class="detail-value" id="detailPortfolios">—</span></div>
            <div class="detail-row"><span class="detail-label">Total Prestasi</span><span class="detail-value" id="detailAchievements">—</span></div>
            <div class="detail-row"><span class="detail-label">Terdaftar Sejak</span><span class="detail-value" id="detailJoined">—</span></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnTutupDetail">Tutup</button>
        </div>
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

    // ── Modal Tambah Siswa ──
    (function () {
        const overlay = document.getElementById('modalTambahSiswa');
        const btnOpen = document.getElementById('btnBukaTambahSiswa');
        const btnClose = document.getElementById('btnCloseModalTambah');
        const btnBatal = document.getElementById('btnBatalTambah');

        function open()  { overlay.classList.add('open'); }
        function close() { overlay.classList.remove('open'); }

        btnOpen?.addEventListener('click', open);
        btnClose?.addEventListener('click', close);
        btnBatal?.addEventListener('click', close);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });

        if (overlay.dataset.autoOpen === '1') open();
        window.closeTambahModal = close;
    })();

    // ── Modal Edit Siswa (form dipakai ulang, action ditulis lewat JS) ──
    (function () {
        const overlay    = document.getElementById('modalEditSiswa');
        const form       = document.getElementById('formEditSiswa');
        const inputId    = document.getElementById('editStudentId');
        const inputName  = document.getElementById('editName');
        const inputEmail = document.getElementById('editEmail');
        const inputNis   = document.getElementById('editNis');
        const urlTemplate = form.dataset.urlTemplate;

        function openEditModal(id, name, email, nis) {
            form.action = urlTemplate.replace('__ID__', id);
            inputId.value = id;
            inputName.value = name;
            inputEmail.value = email;
            inputNis.value = nis === 'null' ? '' : nis;
            overlay.classList.add('open');
            setTimeout(() => inputName.focus(), 50);
        }
        function closeEditModal() { overlay.classList.remove('open'); }

        document.querySelectorAll('[data-edit-btn]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openEditModal(btn.dataset.id, btn.dataset.name, btn.dataset.email, btn.dataset.nis);
            });
        });

        document.getElementById('btnCloseModalEdit').addEventListener('click', closeEditModal);
        document.getElementById('btnBatalEdit').addEventListener('click', closeEditModal);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) closeEditModal(); });

        if (overlay.dataset.autoOpen === '1') {
            openEditModal(overlay.dataset.reopenId, overlay.dataset.reopenName, overlay.dataset.reopenEmail, overlay.dataset.reopenNis);
        }
        window.openEditModal = openEditModal;
    })();

    // ── Modal Detail Siswa (read-only, tanpa request baru) ──
    (function () {
        const overlay = document.getElementById('modalDetailSiswa');
        const els = {
            name: document.getElementById('detailName'),
            email: document.getElementById('detailEmail'),
            nis: document.getElementById('detailNis'),
            portfolios: document.getElementById('detailPortfolios'),
            achievements: document.getElementById('detailAchievements'),
            joined: document.getElementById('detailJoined'),
        };

        function openDetailModal(data) {
            els.name.textContent = data.name;
            els.email.textContent = data.email;
            els.nis.textContent = data.nis;
            els.portfolios.textContent = data.portfolios;
            els.achievements.textContent = data.achievements;
            els.joined.textContent = data.joined;
            overlay.classList.add('open');
        }
        function closeDetailModal() { overlay.classList.remove('open'); }

        document.querySelectorAll('[data-detail-btn]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openDetailModal({
                    name: btn.dataset.name,
                    email: btn.dataset.email,
                    nis: btn.dataset.nis,
                    portfolios: btn.dataset.portfolios,
                    achievements: btn.dataset.achievements,
                    joined: btn.dataset.joined,
                });
            });
        });

        document.getElementById('btnCloseModalDetail').addEventListener('click', closeDetailModal);
        document.getElementById('btnTutupDetail').addEventListener('click', closeDetailModal);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) closeDetailModal(); });
    })();

    // ── Escape menutup modal/sidebar yang terbuka ──
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.modal-overlay.open').forEach(el => el.classList.remove('open'));
        if (window.closeSidebarOnEscape) window.closeSidebarOnEscape();
    });

    // ── State loading saat submit form tambah/edit siswa ──
    ['formTambahSiswa', 'formEditSiswa'].forEach(function (formId) {
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