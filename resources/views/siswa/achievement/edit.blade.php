{{-- resources/views/siswa/achievement/edit.blade.php --}}
{{-- Form edit Prestasi & Sertifikat milik siswa yang login.
     MIGRASI: sebelumnya masih dark theme legacy (HTML mandiri, Inter,
     #080808, var(--red)/var(--border), tanpa navigasi mobile). Sekarang
     dipindahkan ke sistem editorial "Kertas & Oxblood" yang sama dengan
     resources/views/siswa/achievement/index.blade.php — sidebar/topbar,
     off-canvas mobile nav, dan style field form (form-input/select/textarea,
     file-drop, error-alert) memakai class & token yang identik dengan modal
     "Tambah Prestasi/Sertifikat" di halaman index, supaya Tambah & Edit
     terasa sebagai satu sistem yang sama. Backend (route, method, nama
     field, validasi) TIDAK diubah sama sekali. --}}
@extends('layouts.app')

@section('title', 'Edit Prestasi/Sertifikat — DKV SMEKDA Portal')

@section('navbar')@endsection
@section('footer')@endsection

@section('content')
<style>
        :root {
            --oxblood-soft:   rgba(122,46,46,0.08);
            --oxblood-border: rgba(122,46,46,0.26);
            --oxblood-ink:    #6E2A2A;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: var(--font-sans);
            background-color: var(--color-paper);
            color: var(--color-ink);
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.028'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
            mix-blend-mode: multiply;
        }

        .bg-grid {
            position: fixed; top: -280px; left: 50%;
            width: 900px; height: 900px;
            transform: translateX(-40%);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(122,46,46,0.055) 0%, transparent 62%);
            pointer-events: none; z-index: 0;
            animation: gridGlowDrift 26s ease-in-out infinite alternate;
        }
        @keyframes gridGlowDrift {
            0%   { transform: translateX(-42%) translateY(0); }
            100% { transform: translateX(-34%) translateY(26px); }
        }

        .blob { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
        .blob-1 {
            top: -200px; left: 180px; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(122,46,46,0.07) 0%, transparent 65%);
            animation: blobFloat 10s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -150px; right: -100px; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(122,46,46,0.05) 0%, transparent 65%);
            animation: blobFloat 13s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobFloat {
            0%   { transform: scale(1)    translate(0,0);       }
            100% { transform: scale(1.15) translate(20px,15px); }
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--color-paper-border); border-radius: var(--radius-pill); }
        ::-webkit-scrollbar-thumb:hover { background: var(--color-accent-500); }
        /* ── SIDEBAR (identik dengan siswa/achievement/index.blade.php) ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: 260px; height: 100vh;
            background: var(--color-paper-elevated);
            border-right: 1px solid var(--color-paper-border);
            display: flex; flex-direction: column; z-index: 50; overflow-y: auto;
        }
        .sidebar-logo { padding: 28px 24px 22px; border-bottom: 1px solid var(--color-paper-border); }
        .logo-wordmark {
            font-size: 0.82rem; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase;
            color: var(--color-ink); display: flex; align-items: center; gap: 10px;
        }
        .logo-icon {
            width: 26px; height: 26px; background: var(--color-accent-600); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo-icon svg { width: 13px; height: 13px; }
        .sidebar-profile { padding: 20px 24px; border-bottom: 1px solid var(--color-paper-border); display:flex; flex-direction:column; gap:12px; }
        .sidebar-profile-row { display:flex; align-items:center; gap:12px; }
        .profile-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: var(--color-accent-600);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: var(--color-paper); flex-shrink: 0;
        }
        .profile-name {
            font-size: 0.82rem; font-weight: 700; color: var(--color-ink); line-height: 1.3; margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .profile-nis { font-size: 0.7rem; color: var(--color-ink-faint); margin-bottom: 6px; }
        .badge-role {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--oxblood-soft); border: 1px solid var(--oxblood-border);
            color: var(--oxblood-ink); padding: 2px 9px; border-radius: 30px;
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;
        }
        .badge-role-dot {
            width: 5px; height: 5px; background: var(--color-accent-600); border-radius: 50%; flex-shrink: 0;
        }
        .sidebar-nav { flex: 1; padding: 20px 14px; }
        .nav-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            color: var(--color-ink-faint); padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 11px; padding: 10px 12px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600; color: var(--color-ink-muted);
            text-decoration: none; transition: all 0.22s ease; border: 1px solid transparent;
            margin-bottom: 3px; position: relative;
        }
        .nav-item:hover { color: var(--color-ink); background: var(--color-paper-muted); }
        .nav-item.active { color: var(--oxblood-ink); background: var(--oxblood-soft); border-color: var(--oxblood-border); }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 18px; background: var(--color-accent-600); border-radius: 0 3px 3px 0;
        }
        .nav-item.active svg { color: var(--color-accent-600); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; transition: color 0.22s ease; }
        .sidebar-footer { padding: 14px; border-top: 1px solid var(--color-paper-border); }
        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 11px; padding: 10px 12px;
            border-radius: 10px; background: none; border: 1px solid transparent;
            color: var(--color-ink-muted); font-size: 0.82rem; font-weight: 600;
            font-family: var(--font-sans); cursor: pointer; transition: all 0.22s ease;
        }
        .btn-logout:hover { color: var(--oxblood-ink); background: var(--oxblood-soft); border-color: var(--oxblood-border); }
        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        .main-content { margin-left: 260px; min-height: 100vh; position: relative; z-index: 1; }
        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(250,247,242,0.86);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--color-paper-border); padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-title { font-size: 0.82rem; font-weight: 700; color: var(--color-ink-faint); letter-spacing: 0.5px; }
        .topbar-title span { color: var(--color-ink-muted); margin-left: 6px; }
        .topbar-badge {
            display: flex; align-items: center; gap: 6px;
            background: var(--color-paper-muted); border: 1px dashed var(--color-paper-border);
            border-radius: 30px; padding: 5px 13px; font-size: 0.7rem; font-weight: 700;
            color: var(--color-ink-muted); letter-spacing: 0.5px;
        }
        .page-inner { padding: 40px 36px 70px; max-width: 760px; margin: 0 auto; }
        /* ── BACK BUTTON ── */
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.78rem; font-weight: 700; color: var(--color-ink-faint);
            text-decoration: none; margin-bottom: 22px; transition: color 0.22s ease;
        }
        .btn-back:hover { color: var(--oxblood-ink); }
        .btn-back svg { width: 14px; height: 14px; transition: transform 0.22s ease; }
        .btn-back:hover svg { transform: translateX(-3px); }

        .form-headline {
            font-size: clamp(1.4rem, 2.2vw, 1.9rem); font-weight: 900; letter-spacing: -1px;
            line-height: 1.2; color: var(--color-ink); margin-bottom: 8px;
        }
        .form-headline .hl { color: var(--color-accent-600); }
        .form-sub { font-size: 0.85rem; color: var(--color-ink-muted); margin-bottom: 30px; }

        .form-card {
            background: var(--color-paper-elevated); border: 1px solid var(--color-paper-border);
            border-radius: 20px; padding: 28px;
        }

        .field-label {
            display: block; font-size: 0.7rem; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: var(--color-ink-muted); margin-bottom: 8px;
        }
        .field-label .req { color: var(--color-accent-600); margin-left: 3px; }
        .field-wrap { margin-bottom: 18px; }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-input, .form-select, .form-textarea {
            width: 100%; background: var(--color-paper);
            border: 1.5px solid var(--color-paper-border); border-radius: 11px;
            padding: 11px 14px; font-size: 0.85rem; font-weight: 500;
            color: var(--color-ink); outline: none;
            caret-color: var(--color-accent-600); transition: all 0.25s ease;
            min-height: 44px;
        }
        .form-select { appearance: none; -webkit-appearance: none; cursor: pointer; color: var(--color-ink-muted); }
        .form-select option { background: var(--color-paper-elevated); color: var(--color-ink); }
        .form-textarea { resize: none; line-height: 1.6; }
        .form-input::placeholder, .form-textarea::placeholder { color: var(--color-ink-faint); }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--color-accent-600); background: color-mix(in srgb, var(--color-accent-600) 4.5%, transparent);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 12%, transparent); color: var(--color-ink);
        }
        .form-input.is-error, .form-select.is-error, .form-textarea.is-error {
            border-color: var(--color-accent-500) !important; background: color-mix(in srgb, var(--color-accent-500) 5%, transparent) !important;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-500) 14%, transparent) !important;
        }
        .field-error {
            margin-top: 7px; font-size: 0.73rem; font-weight: 600; color: var(--color-accent-700);
            display: flex; align-items: center; gap: 6px;
        }
        .field-error svg { width: 12px; height: 12px; flex-shrink: 0; }

        .current-file-box {
            display: flex; align-items: center; gap: 12px;
            background: var(--color-paper-muted); border: 1px solid var(--color-paper-border);
            border-radius: 12px; padding: 10px 14px; margin-bottom: 10px;
        }
        .current-file-thumb {
            width: 44px; height: 44px; border-radius: 9px; object-fit: cover; flex-shrink: 0;
            background: var(--color-paper-border);
        }
        .current-file-icon {
            width: 44px; height: 44px; border-radius: 9px; background: var(--oxblood-soft);
            border: 1px solid var(--oxblood-border); display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .current-file-icon svg { width: 19px; height: 19px; color: var(--color-accent-600); }
        .current-file-text { font-size: 0.76rem; font-weight: 700; color: var(--color-ink-muted); }
        .current-file-sub {
            font-size: 0.68rem; color: var(--color-ink-faint); margin-top: 1px;
            display: flex; align-items: center; gap: 6px;
        }
        .current-file-sub a { color: var(--color-accent-600); text-decoration: none; font-weight: 700; }
        .current-file-sub a:hover { text-decoration: underline; }
        /* ── FILE DROP ── */
        .file-drop {
            border: 1.5px dashed var(--color-paper-border); border-radius: 12px; padding: 14px 16px;
            display: flex; align-items: center; gap: 12px; cursor: pointer;
            background: var(--color-paper-muted); transition: all 0.25s ease;
        }
        .file-drop:hover { border-color: var(--color-accent-600); background: color-mix(in srgb, var(--color-accent-600) 4.5%, transparent); }
        .file-drop:focus-within {
            border-color: var(--color-accent-600); background: color-mix(in srgb, var(--color-accent-600) 4.5%, transparent);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 12%, transparent);
        }
        .file-drop-icon {
            width: 38px; height: 38px; border-radius: 10px; background: var(--color-paper-elevated);
            border: 1px solid var(--color-paper-border); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .file-drop-icon svg { width: 16px; height: 16px; color: var(--color-ink-faint); }
        .file-drop:hover .file-drop-icon { background: var(--color-accent-50); border-color: var(--color-accent-200); }
        .file-drop:hover .file-drop-icon svg { color: var(--color-accent-600); }
        .file-drop-text { font-size: 0.78rem; font-weight: 700; color: var(--color-ink-muted); }
        .file-drop-sub { font-size: 0.66rem; color: var(--color-ink-faint); margin-top: 1px; }
        .visually-hidden-file {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
        }

        /* ── ERROR ALERT (identik dengan achievement/index.blade.php) ── */
        .error-alert {
            background: color-mix(in srgb, var(--color-accent-600) 8%, transparent); border: 1px solid color-mix(in srgb, var(--color-accent-600) 30%, transparent);
            border-left: 3px solid var(--color-accent-600); border-radius: 14px; padding: 16px 20px; margin-bottom: 24px;
        }
        .error-alert-title { font-size: 0.8rem; font-weight: 800; color: var(--color-accent-700); display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .error-alert-title svg { width: 16px; height: 16px; flex-shrink: 0; }
        .error-alert-list { list-style: none; }
        .error-alert-list li { font-size: 0.75rem; color: var(--color-ink-muted); padding: 3px 0; display: flex; align-items: flex-start; gap: 7px; }
        .error-alert-list li::before { content: '✕'; color: var(--color-accent-600); font-weight: 900; font-size: 0.65rem; margin-top: 1px; flex-shrink: 0; }

        /* ── TOMBOL ── */
        .btn-row { display: flex; gap: 12px; margin-top: 8px; }
        .btn-submit {
            flex: 1; background: var(--color-accent-600); color: var(--color-paper); border: none;
            border-radius: 12px; padding: 14px 24px; font-size: 0.88rem; font-weight: 800;
            letter-spacing: 0.3px; cursor: pointer; min-height: 44px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            position: relative; overflow: hidden; transition: all 0.3s ease;
            box-shadow: 0 4px 20px color-mix(in srgb, var(--color-accent-600) 30%, transparent);
        }
        .btn-submit::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, var(--color-accent-700), var(--color-accent-500)); opacity: 0; transition: opacity 0.3s ease; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 40px color-mix(in srgb, var(--color-accent-600) 45%, transparent), 0 0 0 4px color-mix(in srgb, var(--color-accent-600) 15%, transparent); }
        .btn-submit:hover::before { opacity: 1; }
        .btn-submit span, .btn-submit svg { position: relative; z-index: 1; }
        .btn-submit svg { width: 17px; height: 17px; }
        .btn-submit:disabled { opacity: 0.65; cursor: not-allowed; }

        .btn-cancel {
            background: var(--color-paper-muted); border: 1px solid var(--color-paper-border); color: var(--color-ink-muted);
            border-radius: 12px; padding: 14px 22px; font-size: 0.85rem; font-weight: 700; min-height: 44px;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; justify-content: center; transition: all 0.22s ease;
        }
        .btn-cancel:hover { background: var(--color-paper-border); color: var(--color-ink); }

        .nav-item:focus-visible,
        .btn-logout:focus-visible,
        .btn-back:focus-visible,
        .btn-submit:focus-visible,
        .btn-cancel:focus-visible,
        .hamburger-btn:focus-visible,
        .sidebar-close-btn:focus-visible {
            outline: none; box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 35%, transparent);
        }

        @media (max-width: 768px) {
            .field-row { grid-template-columns: 1fr; }
            .btn-row { flex-direction: column; }
        }
        /* ================================================================
           MOBILE-FIRST & OFF-CANVAS SIDEBAR (identik dengan
           siswa/achievement/index.blade.php)
        ================================================================ */
        .sidebar { transform: translateX(0); transition: transform .3s ease-in-out; max-width: 85vw; }
        .sidebar-overlay {
            position: fixed; inset: 0; z-index: 45; background: rgba(25,24,22,0.35);
            backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);
            opacity: 0; pointer-events: none; transition: opacity .3s ease;
        }
        .sidebar-overlay.open { opacity: 1; pointer-events: auto; }
        .sidebar-logo-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .sidebar-close-btn {
            display: none; width: 44px; height: 44px; align-items: center; justify-content: center;
            border-radius: 10px; background: var(--color-paper-muted); border: 1px solid var(--color-paper-border);
            color: var(--color-ink-muted); cursor: pointer; flex-shrink: 0; transition: all .2s ease; font: inherit;
        }
        .sidebar-close-btn:hover { background: var(--oxblood-soft); border-color: var(--oxblood-border); color: var(--oxblood-ink); }
        .sidebar-close-btn svg { width: 16px; height: 16px; }
        .hamburger-btn {
            display: none; width: 44px; height: 44px; align-items: center; justify-content: center;
            border-radius: 10px; background: var(--color-paper-elevated); border: 1px solid var(--color-paper-border);
            color: var(--color-ink-muted); cursor: pointer; flex-shrink: 0; transition: all .2s ease; font: inherit;
        }
        .hamburger-btn:hover { background: var(--oxblood-soft); border-color: var(--oxblood-border); color: var(--oxblood-ink); }
        .hamburger-btn svg { width: 19px; height: 19px; }
        .topbar-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .topbar-title { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        @media (max-width: 860px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 28px 0 60px rgba(0,0,0,0.5); }
            .sidebar-close-btn { display: flex; }
            .hamburger-btn { display: flex; }
            .main-content { margin-left: 0; }
            .topbar { padding: 12px 16px; }
            .topbar-badge { display: none; }
            .page-inner { padding: 28px 18px 48px; }
        }
        @media (max-width: 480px) {
            .page-inner { padding: 22px 14px 40px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition-duration: .001ms !important;
                animation-duration: .001ms !important;
                scroll-behavior: auto !important;
            }
        }
</style>

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ================================================================
     SIDEBAR
================================================================ --}}
<aside class="sidebar" id="appSidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-row">
            <div class="logo-wordmark">
                <div class="logo-icon" style="background: var(--color-paper-muted); border: 1px solid var(--color-paper-border); box-shadow: none;">
                    <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK" style="width: 100%; height: 100%; object-fit: contain; padding: 2px;">
                </div>
                DKV<span style="color:var(--color-accent-600);">.</span>SMEKDA
            </div>
            <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" onclick="closeSidebar()" aria-label="Tutup menu navigasi">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="sidebar-profile">
        <div class="sidebar-profile-row">
            <div class="profile-avatar" style="overflow: hidden;">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
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

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('siswa.dashboard') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('siswa.portfolio.create') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Karya
        </a>

        <a href="{{ route('siswa.portfolio.print') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Cetak Portfolio
        </a>

        <a href="{{ route('siswa.achievement.index') }}" class="nav-item active" aria-current="page">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
            </svg>
            Prestasi &amp; Sertifikat
        </a>

        <div class="nav-label" style="margin-top:20px;">Akun</div>

        <a href="{{ route('siswa.profile.edit') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
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
            <button type="button" class="hamburger-btn" id="hamburgerBtn" onclick="openSidebar()" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="appSidebar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                </svg>
            </button>
            <div class="topbar-title">
                Portal DKV SMEKDA <span>/</span> Edit Prestasi &amp; Sertifikat
            </div>
        </div>
        <div class="topbar-badge">
            <div class="badge-role-dot"></div>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="page-inner">

        <a href="{{ route('siswa.achievement.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Prestasi &amp; Sertifikat
        </a>

        <h1 class="form-headline">Edit <span class="hl">{{ $achievement->type === 'prestasi' ? 'Prestasi' : 'Sertifikat' }}</span></h1>
        <p class="form-sub">Perbarui data prestasi atau sertifikat yang sudah tersimpan.</p>

        @if($errors->any())
            <div class="error-alert">
                <div class="error-alert-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Form gagal disimpan — ada {{ $errors->count() }} kesalahan yang harus diperbaiki:
                </div>
                <ul class="error-alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <form method="POST" action="{{ route('siswa.achievement.update', $achievement) }}" enctype="multipart/form-data" class="js-loading-form" data-loading-text="Menyimpan...">
                @csrf
                @method('PUT')

                <div class="field-row">
                    <div class="field-wrap">
                        <label for="type" class="field-label">Jenis <span class="req">*</span></label>
                        <select id="type" name="type" class="form-select {{ $errors->has('type') ? 'is-error' : '' }}">
                            <option value="sertifikat" {{ old('type', $achievement->type) == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                            <option value="prestasi" {{ old('type', $achievement->type) == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                        </select>
                        @error('type')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-wrap">
                        <label for="achieved_at" class="field-label">Tanggal Diperoleh</label>
                        <input
                            type="date"
                            id="achieved_at"
                            name="achieved_at"
                            value="{{ old('achieved_at', optional($achievement->achieved_at)->format('Y-m-d')) }}"
                            class="form-input {{ $errors->has('achieved_at') ? 'is-error' : '' }}"
                        >
                        @error('achieved_at')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="field-wrap">
                    <label for="title" class="field-label">Judul <span class="req">*</span></label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Contoh: Juara 1 Lomba Desain Poster Tingkat Provinsi"
                        value="{{ old('title', $achievement->title) }}"
                        class="form-input {{ $errors->has('title') ? 'is-error' : '' }}"
                    >
                    @error('title')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label for="issuer" class="field-label">Penyelenggara / Penerbit</label>
                    <input
                        type="text"
                        id="issuer"
                        name="issuer"
                        placeholder="Contoh: Dinas Pendidikan Provinsi Sumatera Barat"
                        value="{{ old('issuer', $achievement->issuer) }}"
                        class="form-input {{ $errors->has('issuer') ? 'is-error' : '' }}"
                    >
                    @error('issuer')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label for="description" class="field-label">Deskripsi</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Ceritakan singkat konteks prestasi/sertifikat ini..."
                        class="form-textarea {{ $errors->has('description') ? 'is-error' : '' }}"
                    >{{ old('description', $achievement->description) }}</textarea>
                    @error('description')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label class="field-label">
                        Thumbnail / Foto
                        <span style="color:var(--color-ink-faint); font-weight:500; text-transform:none; letter-spacing:0; margin-left:6px; font-size:0.65rem;">(Opsional &bull; JPG/PNG, maks 2MB)</span>
                    </label>

                    @if($achievement->image_path)
                        <div class="current-file-box">
                            <img src="{{ asset('storage/' . $achievement->image_path) }}" alt="{{ $achievement->title }}" class="current-file-thumb">
                            <div>
                                <div class="current-file-text">Gambar saat ini tersimpan</div>
                                <div class="current-file-sub">Unggah gambar baru di bawah untuk menggantinya.</div>
                            </div>
                        </div>
                    @endif

                    <label class="file-drop" for="imageInput">
                        <div class="file-drop-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="file-drop-text" id="imageFileText">
                                {{ $achievement->image_path ? 'Klik untuk mengganti gambar' : 'Klik untuk memilih gambar' }}
                            </div>
                            <div class="file-drop-sub">Format JPG/PNG &bull; Maksimal 2MB</div>
                        </div>
                    </label>
                    <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/jpg" class="visually-hidden-file" onchange="updateFileLabel(this, 'imageFileText', '{{ $achievement->image_path ? 'Klik untuk mengganti gambar' : 'Klik untuk memilih gambar' }}')">
                    @error('image')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-wrap" style="margin-bottom:4px;">
                    <label class="field-label">
                        Dokumen PDF
                        <span style="color:var(--color-ink-faint); font-weight:500; text-transform:none; letter-spacing:0; margin-left:6px; font-size:0.65rem;">(Opsional &bull; PDF, maks 4MB)</span>
                    </label>

                    @if($achievement->file_path)
                        <div class="current-file-box">
                            <div class="current-file-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="current-file-text">Dokumen PDF tersimpan</div>
                                <div class="current-file-sub">
                                    <a href="{{ asset('storage/' . $achievement->file_path) }}" target="_blank" rel="noopener">Lihat dokumen saat ini</a>
                                    &bull; unggah file baru di bawah untuk menggantinya.
                                </div>
                            </div>
                        </div>
                    @endif

                    <label class="file-drop" for="fileInput">
                        <div class="file-drop-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="file-drop-text" id="fileFileText">
                                {{ $achievement->file_path ? 'Klik untuk mengganti dokumen PDF' : 'Klik untuk memilih file PDF' }}
                            </div>
                            <div class="file-drop-sub">Format PDF &bull; Maksimal 4MB</div>
                        </div>
                    </label>
                    <input type="file" id="fileInput" name="file" accept=".pdf" class="visually-hidden-file" onchange="updateFileLabel(this, 'fileFileText', '{{ $achievement->file_path ? 'Klik untuk mengganti dokumen PDF' : 'Klik untuk memilih file PDF' }}')">
                    @error('file')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="btn-row">
                    <a href="{{ route('siswa.achievement.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    function updateFileLabel(input, labelId, fallback) {
        var label = document.getElementById(labelId);
        label.textContent = input.files && input.files.length > 0 ? input.files[0].name : fallback;
    }

    // ── Sidebar off-canvas (mobile ≤ 860px) ── identik dengan achievement/index.blade.php ──
    function isMobileNav() {
        return window.matchMedia('(max-width: 860px)').matches;
    }
    function openSidebar() {
        var sidebar  = document.getElementById('appSidebar');
        var overlay  = document.getElementById('sidebarOverlay');
        var hamburger = document.getElementById('hamburgerBtn');
        var closeBtn = document.getElementById('sidebarCloseBtn');
        sidebar.classList.add('open');
        overlay.classList.add('open');
        sidebar.inert = false;
        document.body.style.overflow = 'hidden';
        hamburger.setAttribute('aria-expanded', 'true');
        if (closeBtn) closeBtn.focus();
    }
    function closeSidebar() {
        var sidebar  = document.getElementById('appSidebar');
        var overlay  = document.getElementById('sidebarOverlay');
        var hamburger = document.getElementById('hamburgerBtn');
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        if (isMobileNav()) sidebar.inert = true;
        document.body.style.overflow = '';
        hamburger.setAttribute('aria-expanded', 'false');
        if (hamburger) hamburger.focus();
    }
    function syncSidebarForViewport() {
        var sidebar = document.getElementById('appSidebar');
        var overlay = document.getElementById('sidebarOverlay');
        if (!sidebar) return;
        if (isMobileNav()) {
            if (!sidebar.classList.contains('open')) sidebar.inert = true;
        } else {
            sidebar.inert = false;
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    }
    window.addEventListener('resize', syncSidebarForViewport);
    syncSidebarForViewport();

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var sidebar = document.getElementById('appSidebar');
        if (sidebar.classList.contains('open')) closeSidebar();
    });

    // ── Loading state ringan pada submit form (cegah submit ganda) ──
    document.querySelectorAll('.js-loading-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            var label = btn.querySelector('span');
            if (label && form.dataset.loadingText) label.textContent = form.dataset.loadingText;
        });
    });
</script>

@endsection
