@extends('layouts.app')

@section('title', 'Dashboard Guru — DKV SMEKDA Portal')

{{-- Dashboard guru sudah punya sidebar sendiri sebagai navigasi + "footer
     strip" kecil di dalam kontennya sendiri, jadi navbar/footer default
     dari layout tidak dipakai di halaman ini. --}}
@section('navbar')@endsection
@section('footer')@endsection

@push('styles')
<style>
        :root {
            --surface-sunk:    #F6F1E7;
            --hairline:        rgba(25,24,22,0.10);
            --hairline-strong: rgba(25,24,22,0.18);
            --oxblood-soft:    rgba(122,46,46,0.08);
            --oxblood-border:  rgba(122,46,46,0.26);
            --oxblood-ink:     #6E2A2A;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: var(--font-sans);
            background-color: var(--color-paper);
            color: var(--color-ink);
            overflow-x: hidden;
        }

        /* ── NOISE ── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
            mix-blend-mode: multiply;
        }

        /* ── GRID ── */
        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(122,46,46,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(122,46,46,0.035) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none; z-index: 0;
        }

        /* ── BLOBS ── */
        .blob { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
        .blob-1 {
            top: -180px; right: 100px;
            width: 580px; height: 580px;
            background: radial-gradient(circle, rgba(122,46,46,0.08) 0%, transparent 65%);
            animation: blobF 11s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -120px; left: -80px;
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(122,46,46,0.06) 0%, transparent 65%);
            animation: blobF 14s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobF {
            0%   { transform: scale(1)    translate(0,0); }
            100% { transform: scale(1.14) translate(18px,14px); }
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--hairline-strong); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--oxblood-border); }

        /* ================================================================
           SIDEBAR
        ================================================================ */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 260px; height: 100vh;
            background: var(--color-paper-elevated);
            border-right: 1px solid var(--hairline);
            display: flex; flex-direction: column;
            z-index: 50; overflow-y: auto;
        }

        .sidebar::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(122,46,46,0.4), transparent);
        }

        .sidebar-logo {
            padding: 28px 24px 22px;
            border-bottom: 1px solid var(--hairline);
        }

        .logo-wordmark {
            font-size: 0.78rem; font-weight: 900;
            letter-spacing: 3px; text-transform: uppercase;
            color: var(--color-ink);
            display: flex; align-items: center; gap: 9px;
        }

        .logo-icon {
            width: 26px; height: 26px;
            background: var(--color-accent-600); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px rgba(122,46,46,0.45); flex-shrink: 0;
        }

        .logo-icon svg { width: 13px; height: 13px; }

        .sidebar-profile {
            padding: 20px 24px;
            border-bottom: 1px solid var(--hairline);
        }

        .profile-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, var(--color-accent-500), var(--color-accent-700));
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: var(--color-paper);
            flex-shrink: 0;
            box-shadow: 0 0 18px rgba(122,46,46,0.3);
        }

        .profile-name {
            font-size: 0.78rem; font-weight: 700; color: var(--color-ink);
            line-height: 1.3; margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .profile-nip {
            font-family: var(--font-mono);
            font-size: 0.68rem; color: var(--color-ink-faint); margin-bottom: 7px;
        }

        .badge-role {
            font-family: var(--font-mono);
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--oxblood-soft);
            border: 1px solid var(--oxblood-border);
            color: var(--oxblood-ink);
            padding: 2px 9px; border-radius: 30px;
            font-size: 0.63rem; font-weight: 700;
            letter-spacing: 0.8px; text-transform: uppercase;
        }

        .live-dot {
            width: 5px; height: 5px; background: var(--color-accent-600);
            border-radius: 50%; box-shadow: 0 0 6px rgba(122,46,46,0.45);
            animation: livePulse 1.5s ease-in-out infinite;
        }

        .sidebar-nav { flex: 1; padding: 20px 14px; }

        .nav-label {
            font-size: 0.62rem; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase;
            color: var(--color-ink-faint);
            padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600;
            color: var(--color-ink-muted);
            text-decoration: none;
            transition: all 0.22s ease;
            border: 1px solid transparent;
            margin-bottom: 3px; position: relative;
        }

        .nav-item:hover {
            color: var(--color-ink);
            background: var(--surface-sunk);
            border-color: var(--hairline);
        }

        .nav-item.active {
            color: var(--oxblood-ink);
            background: var(--oxblood-soft);
            border-color: var(--oxblood-border);
        }

        .nav-item.active::before {
            content: '';
            position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 18px;
            background: var(--color-accent-600); border-radius: 0 3px 3px 0;
            box-shadow: 0 0 10px rgba(122,46,46,0.45);
        }

        .nav-item.active svg { color: var(--color-accent-600); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }

/* Menu nonaktif — fitur belum tersedia (persiapan sidang) */
        .nav-item-disabled { opacity: 0.5; cursor: not-allowed; }
        .nav-item.nav-item-disabled:hover {
            color: var(--color-ink-muted);
            background: transparent;
            border-color: transparent;
        }
        .nav-item-label {
            display: flex; align-items: center; flex-wrap: wrap;
            gap: 6px; row-gap: 2px;
        }
        .badge-soon {
            font-family: var(--font-mono);
            font-size: 0.58rem; font-weight: 700; letter-spacing: 0.3px;
            color: var(--color-ink-muted);
            background: var(--surface-sunk);
            border: 1px solid var(--hairline-strong);
            padding: 1px 6px; border-radius: 20px;
            white-space: nowrap;
        }

        .sidebar-footer { padding: 14px; border-top: 1px solid var(--hairline); }

        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            background: none; border: 1px solid transparent;
            color: var(--color-ink-muted);
            font-size: 0.82rem; font-weight: 600;
            font-family: var(--font-sans); cursor: pointer;
            transition: all 0.22s ease;
        }

        .btn-logout:hover {
            color: var(--oxblood-ink);
            background: var(--oxblood-soft);
            border-color: var(--oxblood-border);
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
            background: rgba(250,247,242,0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--hairline);
            padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between;
        }

        .topbar-title {
            font-family: var(--font-mono);
            font-size: 0.8rem; font-weight: 700;
            color: var(--color-ink-faint); letter-spacing: 0.5px;
        }

        .topbar-title span { color: var(--color-ink-muted); margin-left: 6px; }

        .topbar-pill {
            font-family: var(--font-mono);
            display: flex; align-items: center; gap: 6px;
            background: var(--oxblood-soft);
            border: 1px solid var(--oxblood-border);
            border-radius: 30px; padding: 5px 13px;
            font-size: 0.68rem; font-weight: 700;
            color: var(--oxblood-ink); letter-spacing: 0.5px;
        }

        .page-inner { padding: 40px 36px 60px; }

        /* ── GREETING ── */
        .eyebrow {
            font-family: var(--font-mono);
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 3px; text-transform: uppercase;
            color: var(--oxblood-ink); margin-bottom: 10px;
        }

        .greeting-headline {
            font-family: var(--font-serif);
            font-size: clamp(1.55rem, 2.2vw, 2.1rem);
            font-weight: 900; letter-spacing: -1px; line-height: 1.15;
            color: var(--color-ink); margin-bottom: 8px;
        }

        .greeting-headline .hl {
            color: var(--color-accent-600);
            text-shadow: none;
        }

        .greeting-sub {
            font-size: 0.875rem;
            color: var(--color-ink-muted); font-weight: 400;
        }

        /* ── STAT CARDS ── */
        .stat-card {
            background: var(--color-paper-elevated);
            border: 1px solid var(--hairline);
            border-radius: 18px; padding: 24px;
            position: relative; overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, var(--hairline-strong), transparent);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(122,46,46,0.25);
            box-shadow: 0 20px 50px rgba(28,26,23,0.15), 0 0 40px rgba(122,46,46,0.07);
            background: rgba(122,46,46,0.035);
        }

        .stat-icon {
            width: 44px; height: 44px; border-radius: 12px;
            background: var(--oxblood-soft);
            border: 1px solid var(--oxblood-border);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px; transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            background: rgba(122,46,46,0.16);
            box-shadow: 0 0 20px rgba(122,46,46,0.2);
        }

        .stat-icon svg { width: 20px; height: 20px; color: var(--color-accent-600); }

        .stat-num {
            font-family: var(--font-mono);
            font-size: 2.4rem; font-weight: 900;
            letter-spacing: -2px; color: var(--color-ink);
            line-height: 1; margin-bottom: 6px;
        }

        .stat-lbl {
            font-size: 0.72rem; font-weight: 700;
            color: var(--color-ink-faint);
            text-transform: uppercase; letter-spacing: 0.8px;
        }

        .stat-bar-track {
            margin-top: 16px; height: 2px;
            background: var(--hairline); border-radius: 2px; overflow: hidden;
        }

        .stat-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--color-accent-600), var(--color-accent-500));
            border-radius: 2px;
            box-shadow: 0 0 8px rgba(122,46,46,0.4);
        }

        .stat-bg-num {
            font-family: var(--font-serif);
            position: absolute; bottom: -8px; right: 14px;
            font-size: 5rem; font-weight: 900;
            color: rgba(28,26,23,0.02); letter-spacing: -4px;
            pointer-events: none; line-height: 1;
            transition: color 0.3s ease;
        }

        .stat-card:hover .stat-bg-num { color: rgba(122,46,46,0.04); }

        /* ── FILTER BAR ── */
        .filter-bar {
            background: var(--color-paper-elevated);
            border: 1px solid var(--hairline);
            border-radius: 16px; padding: 18px 20px;
            margin-bottom: 20px;
        }

        .search-wrap { position: relative; flex: 1; }

        .search-icon {
            position: absolute; top: 50%; left: 14px;
            transform: translateY(-50%);
            width: 15px; height: 15px;
            color: var(--color-ink-faint);
            pointer-events: none;
            transition: color 0.22s ease;
        }

        .search-input {
            width: 100%;
            background: var(--surface-sunk);
            border: 1.5px solid var(--hairline);
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            font-size: 0.82rem; font-weight: 500;
            font-family: var(--font-sans);
            color: var(--color-ink);
            outline: none;
            caret-color: var(--color-accent-600);
            transition: all 0.25s ease;
        }

        .search-input::placeholder { color: var(--color-ink-faint); }

        .search-input:focus {
            border-color: var(--color-accent-600);
            background: var(--color-paper-elevated);
            box-shadow: 0 0 0 3px rgba(122,46,46,0.15), 0 0 18px rgba(122,46,46,0.1);
        }

        .search-wrap:focus-within .search-icon { color: var(--color-accent-600); }

        .select-filter {
            background: var(--surface-sunk);
            border: 1.5px solid var(--hairline);
            border-radius: 10px;
            padding: 11px 36px 11px 14px;
            font-size: 0.82rem; font-weight: 600;
            font-family: var(--font-sans);
            color: var(--color-ink-muted);
            outline: none; cursor: pointer;
            appearance: none; -webkit-appearance: none;
            transition: all 0.25s ease;
        }

        .select-filter:focus {
            border-color: var(--color-accent-600);
            background: var(--color-paper-elevated);
            box-shadow: 0 0 0 3px rgba(122,46,46,0.15);
            color: var(--color-ink);
        }

        .select-wrap { position: relative; }

        .select-arrow {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            width: 13px; height: 13px;
            color: var(--color-ink-faint); pointer-events: none;
        }

        .btn-filter {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--color-accent-600); color: var(--color-paper);
            border: none; border-radius: 10px;
            padding: 11px 20px;
            font-size: 0.8rem; font-weight: 700;
            font-family: var(--font-sans); cursor: pointer;
            transition: all 0.3s ease; white-space: nowrap;
        }

        .btn-filter:hover {
            background: var(--color-accent-700);
            box-shadow: 0 6px 24px rgba(122,46,46,0.45);
            transform: translateY(-1px);
        }

        .btn-filter svg { width: 14px; height: 14px; }

        .btn-reset {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--surface-sunk);
            border: 1px solid var(--hairline-strong);
            color: var(--color-ink-muted);
            border-radius: 10px; padding: 11px 16px;
            font-size: 0.8rem; font-weight: 700;
            font-family: var(--font-sans); cursor: pointer;
            text-decoration: none;
            transition: all 0.22s ease; white-space: nowrap;
        }

        .btn-reset:hover {
            background: var(--hairline-strong);
            color: var(--color-ink);
        }

        .btn-reset svg { width: 13px; height: 13px; }

        /* Active Filter Pills */
        .filter-pill {
            font-family: var(--font-mono);
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--oxblood-soft);
            border: 1px solid var(--oxblood-border);
            color: var(--oxblood-ink);
            padding: 3px 10px; border-radius: 20px;
            font-size: 0.68rem; font-weight: 700;
        }

        /* ── TABLE PANEL ── */
        .table-panel {
            background: var(--color-paper-elevated);
            border: 1px solid var(--hairline);
            border-radius: 20px; overflow: hidden;
        }

        .table-topbar {
            padding: 18px 24px;
            border-bottom: 1px solid var(--hairline);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }

        .table-title {
            font-family: var(--font-serif);
            font-size: 0.92rem; font-weight: 800;
            color: var(--color-ink); letter-spacing: -0.2px;
        }

        .table-sub {
            font-size: 0.72rem; color: var(--color-ink-faint);
            margin-top: 2px; font-weight: 500;
        }

        .results-badge {
            font-family: var(--font-mono);
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--surface-sunk);
            border: 1px solid var(--hairline);
            border-radius: 20px; padding: 4px 12px;
            font-size: 0.68rem; font-weight: 700;
            color: var(--color-ink-muted);
        }

        /* Table */
        .data-table { width: 100%; border-collapse: collapse; }

        .data-table thead tr {
            background: var(--surface-sunk);
            border-bottom: 1px solid var(--hairline);
        }

        .data-table thead th {
            font-family: var(--font-mono);
            padding: 12px 20px;
            font-size: 0.62rem; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase;
            color: var(--color-ink-faint);
            text-align: left; white-space: nowrap;
        }

        .data-table thead th:first-child { padding-left: 24px; }
        .data-table thead th:last-child  { padding-right: 24px; text-align: right; }

        .data-table tbody tr {
            border-bottom: 1px solid var(--hairline);
            transition: all 0.22s ease;
            position: relative;
        }

        .data-table tbody tr:last-child { border-bottom: none; }

        .data-table tbody tr:hover {
            background: var(--oxblood-soft);
        }

        .data-table tbody tr:hover td:first-child::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 2px; background: var(--color-accent-600);
            box-shadow: 0 0 8px rgba(122,46,46,0.45);
        }

        .data-table tbody td {
            padding: 14px 20px;
            vertical-align: middle;
            position: relative;
        }

        .data-table tbody td:first-child { padding-left: 24px; }
        .data-table tbody td:last-child  { padding-right: 24px; }

        /* Cell: Siswa Info */
        .cell-avatar {
            width: 34px; height: 34px; border-radius: 9px;
            background: linear-gradient(135deg, var(--color-accent-500), var(--color-accent-700));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 900; color: var(--color-paper);
            flex-shrink: 0; box-shadow: 0 0 12px rgba(122,46,46,0.25);
        }

        .cell-name {
            font-size: 0.82rem; font-weight: 700; color: var(--color-ink);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 160px;
        }

        .cell-nis {
            font-family: var(--font-mono);
            font-size: 0.68rem; color: var(--color-ink-faint);
            margin-top: 2px; font-weight: 500;
        }

        /* Cell: Karya */
        .cell-title {
            font-size: 0.8rem; font-weight: 700; color: var(--color-ink);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 200px; margin-bottom: 5px;
        }

        .cell-category {
            font-family: var(--font-mono);
            display: inline-flex; align-items: center;
            background: var(--oxblood-soft);
            border: 1px solid var(--oxblood-border);
            color: var(--oxblood-ink);
            font-size: 0.6rem; font-weight: 800;
            letter-spacing: 1.2px; text-transform: uppercase;
            padding: 1px 7px; border-radius: 20px;
        }

        /* Cell: Date */
        .cell-date {
            font-family: var(--font-mono);
            font-size: 0.75rem; font-weight: 600; color: var(--color-ink-muted);
            white-space: nowrap;
        }

        .cell-date-sub {
            font-family: var(--font-mono);
            font-size: 0.65rem; color: var(--color-ink-faint); margin-top: 2px;
        }

        /* Cell: Actions */
        .btn-view {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--surface-sunk);
            border: 1px solid var(--hairline-strong);
            color: var(--color-ink-muted);
            padding: 6px 12px; border-radius: 8px;
            font-size: 0.7rem; font-weight: 700;
            text-decoration: none;
            transition: all 0.22s ease; white-space: nowrap;
        }

        .btn-view:hover {
            background: var(--hairline-strong);
            border-color: var(--color-ink-faint);
            color: var(--color-ink);
        }

        .btn-view svg { width: 12px; height: 12px; }

        .btn-pdf {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--oxblood-soft);
            border: 1px solid var(--oxblood-border);
            color: var(--oxblood-ink);
            padding: 6px 12px; border-radius: 8px;
            font-size: 0.7rem; font-weight: 700;
            text-decoration: none;
            transition: all 0.22s ease; white-space: nowrap;
        }

        .btn-pdf:hover {
            background: var(--color-accent-600);
            border-color: var(--color-accent-600);
            color: var(--color-paper);
        }

        .btn-pdf svg { width: 12px; height: 12px; }

        /* ── PAGINATION ── */
        .pagination-wrap {
            padding: 18px 24px;
            border-top: 1px solid var(--hairline);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }

        .pagination-info {
            font-family: var(--font-mono);
            font-size: 0.72rem; color: var(--color-ink-faint); font-weight: 500;
        }

        .pagination-info strong { color: var(--color-ink-muted); }

        .page-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 8px;
            font-size: 0.75rem; font-weight: 700;
            text-decoration: none;
            transition: all 0.22s ease;
        }

        .page-btn.inactive {
            background: var(--surface-sunk);
            border: 1px solid var(--hairline);
            color: var(--color-ink-muted);
        }

        .page-btn.inactive:hover {
            background: var(--hairline-strong);
            color: var(--color-ink);
        }

        .page-btn.active {
            background: var(--color-accent-600);
            border: 1px solid var(--color-accent-600);
            color: var(--color-paper);
            box-shadow: 0 0 14px rgba(122,46,46,0.4);
        }

        .page-btn.disabled {
            background: var(--color-paper-muted);
            border: 1px solid var(--hairline);
            color: var(--color-ink-faint);
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ── EMPTY TABLE ── */
        .table-empty {
            padding: 80px 40px; text-align: center;
        }

        .table-empty-icon {
            width: 64px; height: 64px;
            background: var(--surface-sunk);
            border: 1px solid var(--hairline); border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }

        .table-empty-icon svg { width: 28px; height: 28px; color: var(--color-ink-faint); }

        .table-empty-title {
            font-family: var(--font-serif);
            font-size: 0.9rem; font-weight: 800;
            color: var(--color-ink-muted); margin-bottom: 8px;
        }

        .table-empty-sub {
            font-size: 0.78rem; color: var(--color-ink-faint); line-height: 1.6;
        }

        /* ── NAV DIVIDER ── */
        .nav-divider {
            height: 1px;
            background: var(--hairline);
            margin: 14px 10px;
        }
    </style>
@endpush

@section('content')

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
            <div class="logo-icon" style="background: rgba(255,255,255,0.04); border: 1px solid var(--border); box-shadow: none;">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK" style="width: 100%; height: 100%; object-fit: contain; padding: 2px;">
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

        {{-- Dashboard --}}
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

        {{-- Kelola Kategori --}}
        <a href="{{ route('guru.kategori.index') }}"
           class="nav-item {{ request()->routeIs('guru.kategori*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Kelola Kategori
        </a>

        {{-- ════════════════════════════════════════
             PROFIL SAYA — tambahan baru
        ════════════════════════════════════════ --}}
        <a href="{{ route('guru.profile') }}"
           class="nav-item {{ request()->routeIs('guru.profile*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Profil Saya
        </a>

        <div class="nav-label" style="margin-top:20px;">Laporan</div>

        <a href="javascript:void(0)" onclick="return false;"
           class="nav-item nav-item-disabled {{ request()->routeIs('guru.rekap*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="nav-item-label">
                Rekap & Statistik
                <span class="badge-soon">Segera Hadir</span>
            </span>
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
            Portal DKV SMEKDA <span>/</span> Dashboard Guru Pembimbing
        </div>
        <div class="topbar-pill">
            <div class="live-dot"></div>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="page-inner">

        {{-- ── GREETING ── --}}
        <div style="margin-bottom:40px;">
            <div class="eyebrow">&#9654; Panel Monitoring Karya</div>
            <h1 class="greeting-headline">
                Selamat datang,<br>
                <span class="hl">{{ auth()->user()->name }}.</span>
            </h1>
            <p class="greeting-sub">Pantau dan evaluasi karya siswa DKV dengan mudah dari satu panel terpusat.</p>
        </div>

        {{-- ── STAT CARDS ── --}}
        @php
            $karya7Hari = $portfolios->filter(fn($p) => $p->created_at->gte(now()->subDays(7)))->count();
        @endphp

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:40px;">

            {{-- Card 1: Total Siswa --}}
            <div class="stat-card">
                <div class="stat-bg-num">{{ $totalSiswa }}</div>
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="stat-num">{{ $totalSiswa }}</div>
                <div class="stat-lbl">Siswa Bimbingan</div>
                <div class="stat-bar-track">
                    <div class="stat-bar-fill" style="width:85%;"></div>
                </div>
            </div>

            {{-- Card 2: Total Karya --}}
            <div class="stat-card">
                <div class="stat-bg-num">{{ $totalKarya }}</div>
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="stat-num">{{ $totalKarya }}</div>
                <div class="stat-lbl">Total Seluruh Karya</div>
                <div class="stat-bar-track">
                    <div class="stat-bar-fill" style="width:{{ min(100, $totalKarya * 4) }}%;"></div>
                </div>
            </div>

            {{-- Card 3: Karya Minggu Ini --}}
            <div class="stat-card">
                <div class="stat-bg-num">{{ $karya7Hari }}</div>
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="stat-num">{{ $karya7Hari }}</div>
                <div class="stat-lbl">Karya Minggu Ini</div>
                <div class="stat-bar-track">
                    <div class="stat-bar-fill" style="width:{{ $totalKarya > 0 ? min(100, ($karya7Hari / $totalKarya) * 100) : 0 }}%;"></div>
                </div>
            </div>

        </div>

        {{-- ── FILTER BAR ── --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('guru.dashboard') }}">
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">

                    {{-- Search --}}
                    <div class="search-wrap" style="flex:1; min-width:220px;">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari judul karya atau nama siswa..."
                            class="search-input"
                        >
                    </div>

                    {{-- Category --}}
                   <div class="select-wrap">
    <select name="category" class="select-filter">
        {{-- OPSI DEFAULT --}}
        <option value="" class="bg-[#18181b] text-white" style="background-color: #18181b; color: #ffffff;">
            Semua Kategori
        </option>

        {{-- LOOP KATEGORI --}}
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ request('category') == $cat->id ? 'selected' : '' }}
                class="bg-[#18181b] text-white"
                style="background-color: #18181b; color: #ffffff;">
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    <svg class="select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
    </svg>
</div>

{{-- Submit --}}
<button type="submit" class="btn-filter">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    Filter
</button>

{{-- Reset --}}
@if(request('search') || request('category'))
    <a href="{{ route('guru.dashboard') }}" class="btn-reset">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        Reset
    </a>
@endif
                {{-- Active Filter Tags --}}
                @if(request('search') || request('category'))
                    <div style="margin-top:12px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <span style="font-size:0.68rem; color:rgba(255,255,255,0.2); font-weight:600;">Filter aktif:</span>
                        @if(request('search'))
                            <span class="filter-pill">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('category'))
                            @php $ac = $categories->firstWhere('id', request('category')); @endphp
                            @if($ac)
                                <span class="filter-pill">{{ $ac->name }}</span>
                            @endif
                        @endif
                        <span style="font-size:0.68rem; color:rgba(255,255,255,0.2);">
                            — {{ $portfolios->total() }} hasil
                        </span>
                    </div>
                @endif
            </form>
        </div>

        {{-- ── TABLE PANEL ── --}}
        <div class="table-panel">

            {{-- Panel Top --}}
            <div class="table-topbar">
                <div>
                    <div class="table-title">Karya Portofolio Siswa</div>
                    <div class="table-sub">
                        Menampilkan {{ $portfolios->firstItem() ?? 0 }}–{{ $portfolios->lastItem() ?? 0 }}
                        dari {{ $portfolios->total() }} karya
                    </div>
                </div>
                <div class="results-badge">
                    <div class="live-dot"></div>
                    {{ $portfolios->total() }} Total Karya
                </div>
            </div>

            {{-- Table --}}
            @if($portfolios->isEmpty())
                <div class="table-empty">
                    <div class="table-empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="table-empty-title">Tidak ada data ditemukan.</div>
                    <div class="table-empty-sub">
                        Coba ubah kata kunci atau kategori filter.<br>
                        Atau reset filter untuk melihat semua karya.
                    </div>
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>Info Siswa</th>
                                <th>Detail Karya</th>
                                <th>Tanggal Upload</th>
                                <th>Berkas</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($portfolios as $index => $portfolio)
                            <tr>
                                {{-- No --}}
                                <td>
                                    <span style="font-size:0.72rem; font-weight:800; color:rgba(220,38,38,0.55);">
                                        {{ $portfolios->firstItem() + $index }}
                                    </span>
                                </td>

                                {{-- Siswa --}}
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div class="cell-avatar">
                                            {{ strtoupper(substr($portfolio->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="cell-name">{{ $portfolio->user->name }}</div>
                                            <div class="cell-nis">NIS: {{ $portfolio->user->nis_nip ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Karya --}}
                                <td>
                                    <div class="cell-title">{{ $portfolio->title }}</div>
                                    <div class="cell-category">
                                        {{ $portfolio->category?->name ?? 'Umum' }}
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td>
                                    <div class="cell-date">{{ $portfolio->created_at->format('d M Y') }}</div>
                                    <div class="cell-date-sub">{{ $portfolio->created_at->format('H:i') }} WIB</div>
                                </td>

                                {{-- Berkas --}}
                                <td>
                                    <div style="display:flex; align-items:center; gap:5px; flex-wrap:wrap;">
                                        <span style="font-size:0.62rem; font-weight:700; color:rgba(255,255,255,0.25); background:rgba(255,255,255,0.04); border:1px solid var(--border); padding:2px 7px; border-radius:6px;">
                                            IMG
                                        </span>
                                        @if($portfolio->file_pdf_path)
                                            <span style="font-size:0.62rem; font-weight:700; color:rgba(220,38,38,0.6); background:rgba(220,38,38,0.08); border:1px solid rgba(220,38,38,0.18); padding:2px 7px; border-radius:6px;">
                                                PDF
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td style="text-align:right;">
                                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:7px;">
                                        <a href="{{ asset('storage/' . $portfolio->image_path) }}"
                                           target="_blank"
                                           class="btn-view">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat
                                        </a>
                                        @if($portfolio->file_pdf_path)
                                            <a href="{{ asset('storage/' . $portfolio->file_pdf_path) }}"
                                               target="_blank"
                                               class="btn-pdf">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                PDF
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── PAGINATION ── --}}
                @if($portfolios->hasPages())
                    <div class="pagination-wrap">
                        <div class="pagination-info">
                            Halaman <strong>{{ $portfolios->currentPage() }}</strong>
                            dari <strong>{{ $portfolios->lastPage() }}</strong>
                            &nbsp;&bull;&nbsp; Total <strong>{{ $portfolios->total() }}</strong> karya
                        </div>
                        <div style="display:flex; align-items:center; gap:5px;">
                            {{-- Prev --}}
                            @if($portfolios->onFirstPage())
                                <span class="page-btn disabled">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $portfolios->previousPageUrl() }}" class="page-btn inactive">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Pages --}}
                            @foreach($portfolios->getUrlRange(max(1, $portfolios->currentPage()-2), min($portfolios->lastPage(), $portfolios->currentPage()+2)) as $page => $url)
                                @if($page == $portfolios->currentPage())
                                    <span class="page-btn active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="page-btn inactive">{{ $page }}</a>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if($portfolios->hasMorePages())
                                <a href="{{ $portfolios->nextPageUrl() }}" class="page-btn inactive">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @else
                                <span class="page-btn disabled">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
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
@endsection