{{-- resources/views/siswa/profile/edit.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — DKV SMEKDA Portal</title>
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
            top: -160px; right: 80px; width: 560px; height: 560px;
            background: radial-gradient(circle, rgba(220,38,38,0.09) 0%, transparent 65%);
            animation: blobF 10s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -140px; left: -80px; width: 460px; height: 460px;
            background: radial-gradient(circle, rgba(220,38,38,0.06) 0%, transparent 65%);
            animation: blobF 13s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobF {
            0%   { transform: scale(1)    translate(0,0); }
            100% { transform: scale(1.14) translate(18px,14px); }
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #080808; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: 260px; height: 100vh;
            background: rgba(8,8,8,0.9);
            backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 50; overflow-y: auto;
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

        .sidebar-profile { padding: 20px 24px; border-bottom: 1px solid var(--border); }
        .profile-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: white;
            flex-shrink: 0; box-shadow: 0 0 18px rgba(220,38,38,0.3);
            overflow: hidden;
        }
        .profile-name {
            font-size: 0.78rem; font-weight: 700; color: #f5f5f5;
            line-height: 1.3; margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .profile-nis { font-size: 0.68rem; color: rgba(255,255,255,0.28); margin-bottom: 7px; }
        .badge-role {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.22);
            color: #fca5a5; padding: 2px 9px; border-radius: 30px;
            font-size: 0.63rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;
        }
        .badge-role-dot {
            width: 5px; height: 5px; background: var(--red);
            border-radius: 50%; box-shadow: 0 0 6px var(--red-glow);
        }

        .sidebar-nav { flex: 1; padding: 20px 14px; }
        .nav-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.18); padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600;
            color: rgba(255,255,255,0.35); text-decoration: none;
            transition: all 0.22s ease; border: 1px solid transparent;
            margin-bottom: 3px; position: relative;
        }
        .nav-item:hover {
            color: rgba(255,255,255,0.7); background: rgba(255,255,255,0.04); border-color: var(--border);
        }
        .nav-item.active {
            color: #fca5a5; background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.2);
        }
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
            padding: 10px 12px; border-radius: 10px;
            background: none; border: 1px solid transparent;
            color: rgba(255,255,255,0.28); font-size: 0.82rem; font-weight: 600;
            font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.22s ease;
        }
        .btn-logout:hover {
            color: #fca5a5; background: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.18);
        }
        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ── MAIN ── */
        .main-content { margin-left: 260px; min-height: 100vh; position: relative; z-index: 1; }
        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
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

        .page-inner { padding: 40px 36px 60px; max-width: 760px; }

        .form-headline {
            font-size: clamp(1.5rem, 2.2vw, 2rem); font-weight: 900; letter-spacing: -1px; line-height: 1.15;
            color: #f5f5f5; margin-bottom: 6px;
        }
        .form-headline .hl { color: var(--red); text-shadow: 0 0 26px rgba(220,38,38,0.4); }
        .form-sub { font-size: 0.875rem; color: rgba(255,255,255,0.28); font-weight: 400; margin-bottom: 32px; }

        .flash-success {
            display: flex; align-items: center; gap: 12px;
            background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2);
            border-radius: 12px; padding: 14px 18px; margin-bottom: 28px;
            font-size: 0.82rem; font-weight: 600; color: #86efac;
        }
        .flash-success svg { width: 16px; height: 16px; flex-shrink: 0; color: #4ade80; }

        .field-label {
            display: block; font-size: 0.7rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
            color: rgba(255,255,255,0.35); margin-bottom: 8px;
        }
        .field-label .req { color: var(--red); margin-left: 3px; }
        .field-hint { font-size: 0.68rem; color: rgba(255,255,255,0.2); margin-top: 6px; }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; top: 50%; left: 14px; transform: translateY(-50%);
            width: 15px; height: 15px; color: rgba(255,255,255,0.2);
            pointer-events: none; transition: color 0.22s ease;
        }
        .form-input {
            width: 100%; background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08); border-radius: 11px;
            padding: 12px 14px 12px 42px; font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif; color: #f5f5f5; outline: none;
            caret-color: var(--red); transition: all 0.25s ease;
        }
        .form-input.no-icon { padding-left: 14px; }
        .form-input::placeholder { color: rgba(255,255,255,0.18); }
        .form-input:focus {
            border-color: var(--red); background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15), 0 0 18px rgba(220,38,38,0.08);
        }
        .input-wrap:focus-within .input-icon { color: var(--red); }
        .form-input.is-error {
            border-color: var(--red-bright); background: rgba(239,68,68,0.06);
            box-shadow: 0 0 0 3px rgba(239,68,68,0.14);
        }

        .form-textarea {
            width: 100%; resize: none; background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08); border-radius: 11px;
            padding: 12px 14px; font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif; color: #f5f5f5; outline: none;
            caret-color: var(--red); transition: all 0.25s ease; line-height: 1.6;
        }
        .form-textarea::placeholder { color: rgba(255,255,255,0.18); }
        .form-textarea:focus {
            border-color: var(--red); background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15), 0 0 18px rgba(220,38,38,0.08);
        }

        .field-error {
            margin-top: 7px; font-size: 0.73rem; font-weight: 600; color: #f87171;
            display: flex; align-items: center; gap: 6px;
        }
        .field-error svg { width: 12px; height: 12px; flex-shrink: 0; }
        .field-wrap { margin-bottom: 20px; }

        .form-card {
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 20px; overflow: hidden; position: relative; margin-bottom: 24px;
        }
        .form-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }
        .form-card-header {
            padding: 18px 24px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }
        .card-header-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--red-soft); border: 1px solid rgba(220,38,38,0.18);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .card-header-icon svg { width: 16px; height: 16px; color: var(--red); }
        .card-header-title { font-size: 0.85rem; font-weight: 800; color: #f5f5f5; }
        .card-header-sub { font-size: 0.7rem; color: rgba(255,255,255,0.25); margin-top: 1px; }
        .form-card-body { padding: 24px; }

        /* ── PHOTO UPLOAD ── */
        .photo-row { display: flex; align-items: center; gap: 20px; margin-bottom: 24px; }
        .photo-preview {
            width: 84px; height: 84px; border-radius: 16px; flex-shrink: 0;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 900; color: white; overflow: hidden;
            border: 2px solid var(--border);
        }
        .photo-preview img { width: 100%; height: 100%; object-fit: cover; }
        .photo-upload-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.05); border: 1px solid var(--border);
            color: rgba(255,255,255,0.6); font-size: 0.78rem; font-weight: 700;
            padding: 9px 16px; border-radius: 10px; cursor: pointer;
            transition: all 0.22s ease;
        }
        .photo-upload-btn:hover { border-color: rgba(220,38,38,0.3); color: #fca5a5; }
        .photo-upload-btn svg { width: 14px; height: 14px; }
        .photo-filename { font-size: 0.72rem; color: rgba(255,255,255,0.25); margin-top: 8px; }

        .btn-submit {
            width: 100%; background: var(--red); color: white; border: none;
            border-radius: 12px; padding: 15px 24px; font-size: 0.9rem; font-weight: 800;
            font-family: 'Inter', sans-serif; letter-spacing: 0.3px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            position: relative; overflow: hidden; transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(220,38,38,0.3);
        }
        .btn-submit::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, #b91c1c, #ef4444);
            opacity: 0; transition: opacity 0.3s ease;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 40px var(--red-glow), 0 0 0 4px rgba(220,38,38,0.15); }
        .btn-submit:hover::before { opacity: 1; }
        .btn-submit span, .btn-submit svg { position: relative; z-index: 1; }
        .btn-submit svg { width: 18px; height: 18px; }
        .btn-submit:active { transform: translateY(0); }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    {{-- ── SIDEBAR ── --}}
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
                Portal Siswa
            </div>
        </div>

        <div class="sidebar-profile">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                <div class="profile-avatar">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div style="flex:1; min-width:0;">
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-nis">NIS: {{ $user->nis_nip ?? '—' }}</div>
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

            <a href="{{ route('siswa.portfolio.print') }}" target="_blank" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
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

    {{-- ── MAIN ── --}}
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-title">Akun <span>/ Profil Saya</span></div>
            <div class="topbar-pill">
                <div class="badge-role-dot"></div>
                Siswa DKV
            </div>
        </div>

        <div class="page-inner">

            @if(session('success'))
                <div class="flash-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-headline">Profil <span class="hl">Saya</span></div>
            <div class="form-sub">Kelola informasi akun dan tampilan portofolio publik Anda.</div>

            <form method="POST" action="{{ route('siswa.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Foto Profil --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="card-header-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Foto Profil</div>
                            <div class="card-header-sub">Tampil di halaman portofolio publik Anda</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="photo-row">
                            <div class="photo-preview" id="photoPreview">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <label for="photo" class="photo-upload-btn">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Ganti Foto
                                </label>
                                <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png" style="display:none;">
                                <div class="photo-filename">JPG/PNG, maks 2MB</div>
                            </div>
                        </div>
                        @error('photo')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Informasi Akun --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="card-header-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Informasi Akun</div>
                            <div class="card-header-sub">Nama dan NIS akan tampil di portofolio publik</div>
                        </div>
                    </div>
                    <div class="form-card-body">

                        <div class="field-wrap">
                            <label for="name" class="field-label">Nama Lengkap <span class="req">*</span></label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name', $user->name) }}"
                                       class="form-input {{ $errors->has('name') ? 'is-error' : '' }}">
                            </div>
                            @error('name')
                                <div class="field-error">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="field-wrap">
                            <label for="nis_nip" class="field-label">NIS</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                                <input type="text" id="nis_nip" name="nis_nip"
                                       value="{{ old('nis_nip', $user->nis_nip) }}"
                                       class="form-input {{ $errors->has('nis_nip') ? 'is-error' : '' }}">
                            </div>
                            @error('nis_nip')
                                <div class="field-error">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="field-wrap">
                            <label for="bio" class="field-label">Bio Singkat</label>
                            <textarea id="bio" name="bio" rows="3"
                                      placeholder="Ceritakan singkat tentang minat desain Anda (opsional)"
                                      class="form-textarea {{ $errors->has('bio') ? 'is-error' : '' }}">{{ old('bio', $user->bio) }}</textarea>
                            <div class="field-hint">Akan tampil di halaman portofolio publik Anda. Maks 500 karakter.</div>
                            @error('bio')
                                <div class="field-error">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="field-wrap" style="margin-bottom:0;">
                            <label for="contact" class="field-label">Kontak (WA / Instagram / Email)</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <input type="text" id="contact" name="contact"
                                       placeholder="Contoh: 0812xxxxxxx atau @username"
                                       value="{{ old('contact', $user->contact) }}"
                                       class="form-input {{ $errors->has('contact') ? 'is-error' : '' }}">
                            </div>
                            <div class="field-hint">Opsional — memudahkan pihak industri menghubungi Anda dari portofolio publik.</div>
                            @error('contact')
                                <div class="field-error">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Skill & Kompetensi --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="card-header-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Skill & Kompetensi</div>
                            <div class="card-header-sub">Akan tampil sebagai halaman tersendiri di katalog PDF portofolio Anda</div>
                        </div>
                    </div>
                    <div class="form-card-body">

                        @foreach($skillOptions as $group => $options)
                            @php
                                $existingByName = collect(old('skills_active') ? [] : ($user->skills ?? []))->keyBy('name');
                            @endphp
                            <div class="field-wrap">
                                <label class="field-label">{{ $group }}</label>
                                <div style="display:flex; flex-direction:column; gap:12px; margin-top:6px;">
                                    @foreach($options as $skillName)
                                        @php
                                            $isChecked = old('skills_active')
                                                ? in_array($skillName, old('skills_active', []))
                                                : $existingByName->has($skillName);
                                            $level = old("skills_level.$skillName", $existingByName->get($skillName)['level'] ?? 50);
                                        @endphp
                                        <div style="display:flex; align-items:center; gap:10px;" class="skill-row">
                                            <input type="checkbox"
                                                   name="skills_active[]"
                                                   value="{{ $skillName }}"
                                                   class="skill-checkbox"
                                                   data-target="skill-level-{{ Str::slug($skillName) }}"
                                                   {{ $isChecked ? 'checked' : '' }}>
                                            <span style="flex:0 0 190px; font-size:0.85rem; color:#e5e5e5;">{{ $skillName }}</span>
                                            <input type="range" min="0" max="100" step="5"
                                                   name="skills_level[{{ $skillName }}]"
                                                   id="skill-level-{{ Str::slug($skillName) }}"
                                                   value="{{ $level }}"
                                                   oninput="this.nextElementSibling.textContent = this.value + '%'"
                                                   style="flex:1;">
                                            <span style="flex:0 0 40px; font-size:0.75rem; color:#a3a3a3; text-align:right;">{{ $level }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        {{-- Skill Custom --}}
                        <div class="field-wrap" style="margin-bottom:0;">
                            <label class="field-label">Skill Lainnya (Custom)</label>
                            <div id="customSkillList" style="display:flex; flex-direction:column; gap:10px; margin-top:6px;">
                                @php $existingCustom = collect($user->skills ?? [])->where('type', 'Custom')->values(); @endphp
                                @forelse($existingCustom as $custom)
                                    <div class="custom-skill-row" style="display:flex; align-items:center; gap:10px;">
                                        <input type="text" name="custom_skill_name[]" value="{{ $custom['name'] }}"
                                               placeholder="Nama skill" class="form-input" style="flex:0 0 190px;">
                                        <input type="range" min="0" max="100" step="5" name="custom_skill_level[]"
                                               value="{{ $custom['level'] }}"
                                               oninput="this.nextElementSibling.textContent = this.value + '%'" style="flex:1;">
                                        <span style="flex:0 0 40px; font-size:0.75rem; color:#a3a3a3; text-align:right;">{{ $custom['level'] }}%</span>
                                        <button type="button" onclick="this.closest('.custom-skill-row').remove()"
                                                style="color:#f87171; background:none; border:none; cursor:pointer; font-size:1rem;">&times;</button>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                            <button type="button" onclick="addCustomSkillRow()" class="btn-outline" style="margin-top:12px; font-size:0.8rem;">
                                + Tambah Skill Custom
                            </button>
                            <div class="field-hint">Contoh: Blender 3D, Adobe Premiere, Sablon Manual, dsb.</div>
                        </div>

                    </div>
                </div>

                {{-- Ubah Password --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="card-header-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Ubah Password</div>
                            <div class="card-header-sub">Kosongkan jika tidak ingin mengganti password</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="field-wrap">
                            <label for="password" class="field-label">Password Baru</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <input type="password" id="password" name="password"
                                       placeholder="Minimal 8 karakter"
                                       class="form-input {{ $errors->has('password') ? 'is-error' : '' }}">
                            </div>
                            @error('password')
                                <div class="field-error">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="field-wrap" style="margin-bottom:0;">
                            <label for="password_confirmation" class="field-label">Konfirmasi Password Baru</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       placeholder="Ulangi password baru"
                                       class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>

            </form>
        </div>
    </div>

    <script>
        // Live-preview foto profil sebelum di-upload
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                photoPreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        });

        // Tambah baris skill custom secara dinamis
        function addCustomSkillRow() {
            const wrap = document.createElement('div');
            wrap.className = 'custom-skill-row';
            wrap.style = 'display:flex; align-items:center; gap:10px;';
            wrap.innerHTML = `
                <input type="text" name="custom_skill_name[]" placeholder="Nama skill" class="form-input" style="flex:0 0 190px;">
                <input type="range" min="0" max="100" step="5" name="custom_skill_level[]" value="50"
                       oninput="this.nextElementSibling.textContent = this.value + '%'" style="flex:1;">
                <span style="flex:0 0 40px; font-size:0.75rem; color:#a3a3a3; text-align:right;">50%</span>
                <button type="button" onclick="this.closest('.custom-skill-row').remove()"
                        style="color:#f87171; background:none; border:none; cursor:pointer; font-size:1rem;">&times;</button>
            `;
            document.getElementById('customSkillList').appendChild(wrap);
        }
    </script>
</body>
</html>