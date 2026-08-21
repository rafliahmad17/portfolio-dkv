{{-- resources/views/siswa/achievement/edit.blade.php --}}
{{-- Form edit Prestasi & Sertifikat milik siswa yang login.
     Struktur & gaya mengikuti resources/views/siswa/dashboard.blade.php
     dan resources/views/siswa/achievement/index.blade.php
     (dark theme #080808, aksen merah #dc2626, Tailwind CDN). --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Prestasi/Sertifikat — DKV SMEKDA Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
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
            top: -200px; left: 180px; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(220,38,38,0.09) 0%, transparent 65%);
            animation: blobFloat 10s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -150px; right: -100px; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(220,38,38,0.06) 0%, transparent 65%);
            animation: blobFloat 13s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobFloat {
            0%   { transform: scale(1)    translate(0,0);       }
            100% { transform: scale(1.15) translate(20px,15px); }
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #080808; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        /* ── SIDEBAR (sama seperti dashboard & index) ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: 260px; height: 100vh;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column; z-index: 50; overflow-y: auto;
        }
        .sidebar::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
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
        .sidebar-profile { padding: 20px 24px; border-bottom: 1px solid var(--border); display:flex; flex-direction:column; gap:12px; }
        .sidebar-profile-row { display:flex; align-items:center; gap:12px; }
        .profile-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: white; flex-shrink: 0;
            box-shadow: 0 0 18px rgba(220,38,38,0.3);
        }
        .profile-name {
            font-size: 0.82rem; font-weight: 700; color: #f5f5f5; line-height: 1.3; margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .profile-nis { font-size: 0.7rem; color: rgba(255,255,255,0.3); margin-bottom: 6px; }
        .badge-role {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.12); border: 1px solid rgba(220,38,38,0.25);
            color: #fca5a5; padding: 2px 9px; border-radius: 30px;
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;
        }
        .badge-role-dot {
            width: 5px; height: 5px; background: var(--red); border-radius: 50%;
            animation: pulseDot 1.5s ease-in-out infinite; box-shadow: 0 0 6px var(--red-glow);
        }
        @keyframes pulseDot {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.35; transform: scale(0.65); }
        }
        .sidebar-nav { flex: 1; padding: 20px 14px; }
        .nav-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.18); padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 11px; padding: 10px 12px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.38);
            text-decoration: none; transition: all 0.22s ease; border: 1px solid transparent;
            margin-bottom: 3px; position: relative;
        }
        .nav-item:hover { color: rgba(255,255,255,0.75); background: rgba(255,255,255,0.04); border-color: var(--border); }
        .nav-item.active { color: #fca5a5; background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.2); }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 18px; background: var(--red); border-radius: 0 3px 3px 0;
            box-shadow: 0 0 10px var(--red-glow);
        }
        .nav-item.active svg { color: var(--red); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; transition: color 0.22s ease; }
        .sidebar-footer { padding: 14px; border-top: 1px solid var(--border); }
        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 11px; padding: 10px 12px;
            border-radius: 10px; background: none; border: 1px solid transparent;
            color: rgba(255,255,255,0.28); font-size: 0.82rem; font-weight: 600;
            font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.22s ease;
        }
        .btn-logout:hover { color: #fca5a5; background: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.18); }
        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        .main-content { margin-left: 260px; min-height: 100vh; position: relative; z-index: 1; }
        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(8,8,8,0.85);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border); padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-title { font-size: 0.82rem; font-weight: 700; color: rgba(255,255,255,0.25); letter-spacing: 0.5px; }
        .topbar-title span { color: rgba(255,255,255,0.55); margin-left: 6px; }
        .topbar-badge {
            display: flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.18);
            border-radius: 30px; padding: 5px 13px; font-size: 0.7rem; font-weight: 700;
            color: rgba(220,38,38,0.7); letter-spacing: 0.5px;
        }
        .page-inner { padding: 40px 36px 70px; max-width: 760px; margin: 0 auto; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.35);
            text-decoration: none; margin-bottom: 22px; transition: color 0.22s ease;
        }
        .btn-back:hover { color: #fca5a5; }
        .btn-back svg { width: 14px; height: 14px; transition: transform 0.22s ease; }
        .btn-back:hover svg { transform: translateX(-3px); }

        .form-headline {
            font-size: clamp(1.4rem, 2.2vw, 1.9rem); font-weight: 900; letter-spacing: -1px;
            line-height: 1.2; color: #f5f5f5; margin-bottom: 8px;
        }
        .form-headline .hl { color: var(--red); text-shadow: 0 0 26px rgba(220,38,38,0.4); }
        .form-sub { font-size: 0.85rem; color: rgba(255,255,255,0.3); margin-bottom: 30px; }

        .form-card {
            background: rgba(255,255,255,0.02); border: 1px solid var(--border);
            border-radius: 20px; padding: 28px;
        }

        .field-label {
            display: block; font-size: 0.7rem; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 8px;
        }
        .field-label .req { color: var(--red); margin-left: 3px; }
        .field-wrap { margin-bottom: 20px; }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-input, .form-select, .form-textarea {
            width: 100%; background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08); border-radius: 11px;
            padding: 12px 14px; font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif; color: #f5f5f5; outline: none;
            caret-color: var(--red); transition: all 0.25s ease;
        }
        .form-select { appearance: none; -webkit-appearance: none; cursor: pointer; color: rgba(255,255,255,0.7); }
        .form-select option { background: #1a1a1a; color: #f5f5f5; }
        .form-textarea { resize: none; line-height: 1.6; }
        .form-input::placeholder, .form-textarea::placeholder { color: rgba(255,255,255,0.18); }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--red); background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15); color: #f5f5f5;
        }
        .form-input.is-error, .form-select.is-error, .form-textarea.is-error {
            border-color: var(--red-bright) !important; background: rgba(239,68,68,0.06) !important;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.14) !important;
        }
        .field-error {
            margin-top: 7px; font-size: 0.73rem; font-weight: 600; color: #f87171;
            display: flex; align-items: center; gap: 6px;
        }
        .field-error svg { width: 12px; height: 12px; flex-shrink: 0; }

        .current-file-box {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.03); border: 1px solid var(--border);
            border-radius: 12px; padding: 10px 14px; margin-bottom: 10px;
        }
        .current-file-thumb {
            width: 44px; height: 44px; border-radius: 9px; object-fit: cover; flex-shrink: 0;
            background: #111;
        }
        .current-file-icon {
            width: 44px; height: 44px; border-radius: 9px; background: rgba(220,38,38,0.08);
            border: 1px solid rgba(220,38,38,0.2); display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .current-file-icon svg { width: 19px; height: 19px; color: var(--red); }
        .current-file-text { font-size: 0.76rem; font-weight: 700; color: rgba(255,255,255,0.55); }
        .current-file-sub {
            font-size: 0.68rem; color: rgba(255,255,255,0.25); margin-top: 1px;
            display: flex; align-items: center; gap: 6px;
        }
        .current-file-sub a { color: #fca5a5; text-decoration: none; font-weight: 700; }
        .current-file-sub a:hover { text-decoration: underline; }

        .file-drop {
            border: 1.5px dashed rgba(255,255,255,0.1); border-radius: 12px; padding: 14px 16px;
            display: flex; align-items: center; gap: 12px; cursor: pointer;
            background: rgba(255,255,255,0.02); transition: all 0.25s ease;
        }
        .file-drop:hover { border-color: rgba(220,38,38,0.3); background: rgba(220,38,38,0.04); }
        .file-drop-icon {
            width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.04);
            border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .file-drop-icon svg { width: 16px; height: 16px; color: rgba(255,255,255,0.2); }
        .file-drop:hover .file-drop-icon { background: var(--red-soft); border-color: rgba(220,38,38,0.25); }
        .file-drop:hover .file-drop-icon svg { color: var(--red); }
        .file-drop-text { font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.4); }
        .file-drop-sub { font-size: 0.66rem; color: rgba(255,255,255,0.18); margin-top: 1px; }

        .error-alert {
            background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.3);
            border-left: 3px solid var(--red); border-radius: 14px; padding: 16px 20px; margin-bottom: 24px;
        }
        .error-alert-title { font-size: 0.8rem; font-weight: 800; color: #fca5a5; display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .error-alert-title svg { width: 16px; height: 16px; flex-shrink: 0; }
        .error-alert-list { list-style: none; }
        .error-alert-list li { font-size: 0.75rem; color: rgba(252,165,165,0.8); padding: 3px 0; display: flex; align-items: flex-start; gap: 7px; }
        .error-alert-list li::before { content: '✕'; color: var(--red); font-weight: 900; font-size: 0.65rem; margin-top: 1px; flex-shrink: 0; }

        .btn-row { display: flex; gap: 12px; margin-top: 8px; }
        .btn-submit {
            flex: 1; background: var(--red); color: white; border: none;
            border-radius: 12px; padding: 14px 24px; font-size: 0.88rem; font-weight: 800;
            font-family: 'Inter', sans-serif; letter-spacing: 0.3px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            position: relative; overflow: hidden; transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(220,38,38,0.3);
        }
        .btn-submit::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, #b91c1c, #ef4444); opacity: 0; transition: opacity 0.3s ease; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 40px var(--red-glow), 0 0 0 4px rgba(220,38,38,0.15); }
        .btn-submit:hover::before { opacity: 1; }
        .btn-submit span, .btn-submit svg { position: relative; z-index: 1; }
        .btn-submit svg { width: 17px; height: 17px; }

        .btn-cancel {
            background: rgba(255,255,255,0.04); border: 1px solid var(--border); color: rgba(255,255,255,0.45);
            border-radius: 12px; padding: 14px 22px; font-size: 0.85rem; font-weight: 700;
            font-family: 'Inter', sans-serif; cursor: pointer; text-decoration: none;
            display: flex; align-items: center; justify-content: center; transition: all 0.22s ease;
        }
        .btn-cancel:hover { background: rgba(255,255,255,0.08); color: #f5f5f5; border-color: rgba(255,255,255,0.15); }

        @media (max-width: 768px) {
            .field-row { grid-template-columns: 1fr; }
            .btn-row { flex-direction: column; }
        }
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
    <div class="sidebar-logo">
        <div class="logo-wordmark">
            <div class="logo-icon" style="background: rgba(255,255,255,0.04); border: 1px solid var(--border); box-shadow: none;">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK" style="width: 100%; height: 100%; object-fit: contain; padding: 2px;">
            </div>  
            DKV<span style="color:var(--red);">.</span>SMEKDA
        </div>
        <div style="font-size:0.62rem; color:rgba(255,255,255,0.2); margin-top:4px; letter-spacing:1px; text-transform:uppercase; font-weight:600; padding-left:35px;">
            Portal Siswa
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

        <a href="{{ route('siswa.achievement.index') }}" class="nav-item active">
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
        <div class="topbar-title">
            Portal DKV SMEKDA <span>/</span> Edit Prestasi &amp; Sertifikat
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

        {{-- Error alert --}}
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
            <form method="POST" action="{{ route('siswa.achievement.update', $achievement) }}" enctype="multipart/form-data">
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

                {{-- Thumbnail / Foto --}}
                <div class="field-wrap">
                    <label class="field-label">
                        Thumbnail / Foto
                        <span style="color:rgba(255,255,255,0.2); font-weight:500; text-transform:none; letter-spacing:0; margin-left:6px; font-size:0.65rem;">(Opsional &bull; JPG/PNG, maks 2MB)</span>
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

                    <div class="file-drop" onclick="document.getElementById('imageInput').click()">
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
                    </div>
                    <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/jpg" style="display:none;" onchange="updateFileLabel(this, 'imageFileText', '{{ $achievement->image_path ? 'Klik untuk mengganti gambar' : 'Klik untuk memilih gambar' }}')">
                    @error('image')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Dokumen PDF --}}
                <div class="field-wrap">
                    <label class="field-label">
                        Dokumen PDF
                        <span style="color:rgba(255,255,255,0.2); font-weight:500; text-transform:none; letter-spacing:0; margin-left:6px; font-size:0.65rem;">(Opsional &bull; PDF, maks 4MB)</span>
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

                    <div class="file-drop" onclick="document.getElementById('fileInput').click()">
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
                    </div>
                    <input type="file" id="fileInput" name="file" accept=".pdf" style="display:none;" onchange="updateFileLabel(this, 'fileFileText', '{{ $achievement->file_path ? 'Klik untuk mengganti dokumen PDF' : 'Klik untuk memilih file PDF' }}')">
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
        const label = document.getElementById(labelId);
        label.textContent = input.files && input.files.length > 0 ? input.files[0].name : fallback;
    }
</script>

</body>
 </html>