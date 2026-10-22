{{-- resources/views/siswa/achievement/index.blade.php --}}
{{-- Halaman "Prestasi & Sertifikat" milik siswa yang login.
     Struktur & gaya mengikuti resources/views/siswa/dashboard.blade.php.
     Migrasi ke tema editorial ("Kertas & Oxblood") sudah SELESAI: sidebar,
     topbar, modal Tambah/Edit Prestasi, grid kartu prestasi, tombol di luar
     modal (.btn-add, .btn-action-*), header sambutan, dan error alert semua
     memakai token resmi (var(--color-*) / var(--oxblood-*)). --}}
@extends('layouts.app')

@section('title', 'Prestasi & Sertifikat — DKV SMEKDA Portal')

@section('navbar')@endsection
@section('footer')@endsection

@push('styles')
@vite('resources/css/pages/siswa-achievement-index.css')
@endpush

@section('content')

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="sidebar-overlay" id="siswaSidebarOverlay" aria-hidden="true"></div>

{{-- ================================================================
     SIDEBAR
================================================================ --}}
<aside class="sidebar" id="siswaSidebar" aria-label="Navigasi utama siswa">

    <div class="sidebar-logo">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
            <div>
                <div class="logo-wordmark">
                    <div class="logo-mark">
                        <x-school-logo alt="Logo SMK" />
                    </div>
                    DKV<span class="dot">.</span>SMEKDA
                </div>
                <div class="logo-sub">Portal Siswa</div>
            </div>
            <button type="button" class="sidebar-close-btn" id="siswaSidebarClose" aria-label="Tutup menu navigasi">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="sidebar-profile">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
            <div class="profile-avatar" style="overflow:hidden;">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" width="64" height="64" decoding="async" style="width:100%; height:100%; object-fit:cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1; min-width:0;">
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-nis">NIS {{ auth()->user()->nis_nip ?? '—' }}</div>
            </div>
        </div>
        <div class="badge-role">
            <span class="badge-role-dot" aria-hidden="true"></span>
            Siswa DKV
        </div>
    </div>

    <nav class="sidebar-nav" aria-label="Menu utama">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('siswa.dashboard') }}" class="nav-item">
            <span class="nav-index">01</span><span>Dashboard</span>
        </a>
        <a href="{{ route('siswa.portfolio.create') }}" class="nav-item">
            <span class="nav-index">02</span><span>Tambah Karya</span>
        </a>
        <a href="{{ route('siswa.portfolio.print') }}" class="nav-item">
            <span class="nav-index">03</span><span>Cetak Portfolio</span>
        </a>
        <a href="{{ route('siswa.achievement.index') }}" class="nav-item active" aria-current="page">
            <span class="nav-index">04</span><span>Prestasi &amp; Sertifikat</span>
        </a>

        <div class="nav-label" style="margin-top:20px;">Akun</div>

        <a href="{{ route('siswa.profile.edit') }}" class="nav-item">
            <span class="nav-index">05</span><span>Profil Saya</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
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
<main class="main-content" id="kontenPrestasi">

    <header class="topbar">
        <div style="display:flex; align-items:center; gap:14px; min-width:0;">
            <button type="button" class="hamburger-btn" id="siswaSidebarOpen"
                    aria-label="Buka menu navigasi" aria-controls="siswaSidebar" aria-expanded="false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
            <div class="topbar-title">
                <span>Portal DKV SMEKDA</span>
                <span class="topbar-crumb-sep">/</span>
                <span class="topbar-crumb-current">Prestasi &amp; Sertifikat</span>
            </div>
        </div>
        <div class="badge-pill">
            <span class="badge-role-dot" aria-hidden="true"></span>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </header>

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

        {{-- Global error alert (ditampilkan jika submit modal tambah gagal validasi) --}}
        @if($errors->any())
            <div class="error-alert" role="alert" aria-live="assertive" aria-atomic="true">
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

        {{-- Greeting --}}
        <div style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:20px; margin-bottom:32px;">
            <div>
                <div class="greeting-eyebrow">&#9654; Rekam Jejak Prestasi</div>
                <h1 class="greeting-headline">
                    Prestasi &amp; <span class="name-highlight">Sertifikat</span>
                </h1>
                <p class="greeting-sub">Kelola daftar penghargaan dan sertifikat yang pernah kamu raih.</p>
            </div>
        </div>

        {{-- ── ACHIEVEMENT SECTION ── --}}
        <div style="background:var(--color-paper-elevated); border:1px solid var(--color-paper-border); border-radius:20px; overflow:hidden;">

            <div style="padding:20px 24px; border-bottom:1px solid var(--color-paper-border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div>
                    <div class="section-title">Daftar Prestasi &amp; Sertifikat</div>
                    <div class="section-sub">
                        {{ $achievements->count() }} data tersimpan &bull; Diurutkan dari tanggal perolehan terbaru
                    </div>
                </div>
                <button type="button" class="btn-add" onclick="openAchievementModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Prestasi/Sertifikat</span>
                </button>
            </div>

            <div style="padding:24px;">

                @if($achievements->isEmpty())

                    <div class="empty-wrap">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
                            </svg>
                        </div>
                        <div class="empty-title">Belum ada prestasi atau sertifikat.</div>
                        <div class="empty-sub">
                            Simpan rekam jejak prestasi dan sertifikat yang pernah kamu raih<br>
                            agar bisa ditampilkan di portofolio publikmu.
                        </div>
                        <button type="button" class="btn-add" style="margin:0 auto;" onclick="openAchievementModal()">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Tambah Data Pertama</span>
                        </button>
                    </div>

                @else

                    <div class="achv-grid">

                        @foreach($achievements as $achievement)
                        <div class="achv-card">
                            <div class="achv-thumb-wrap">
                                @if($achievement->image_path)
                                    <img src="{{ asset('storage/' . $achievement->image_path) }}" alt="{{ $achievement->title }}" width="640" height="480" class="achv-thumb" loading="lazy" decoding="async">
                                @else
                                    <div class="achv-thumb-placeholder">
                                        @if($achievement->type === 'prestasi')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
                                            </svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="achv-body">
                                <div class="achv-type">
                                    @if($achievement->type === 'prestasi')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
                                        </svg>
                                        Prestasi
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Sertifikat
                                    @endif
                                </div>
                                <div class="achv-title">{{ $achievement->title }}</div>

                                @if($achievement->issuer)
                                    <div class="achv-issuer">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m4-14h6m-6 4h6m-6 4h6M9 21v-4a1 1 0 011-1h4a1 1 0 011 1v4"/>
                                        </svg>
                                        {{ $achievement->issuer }}
                                    </div>
                                @endif

                                @if($achievement->description)
                                    <div class="achv-desc">{{ $achievement->description }}</div>
                                @endif

                                @if($achievement->achieved_at)
                                    <div class="achv-meta">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $achievement->achieved_at->translatedFormat('d M Y') }}
                                    </div>
                                @endif

                                <div class="achv-actions">
                                    @if($achievement->file_path)
                                        <a href="{{ asset('storage/' . $achievement->file_path) }}" target="_blank" rel="noopener" class="btn-action-file">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Dokumen
                                        </a>
                                    @endif

                                    <a href="{{ route('siswa.achievement.edit', $achievement) }}" class="btn-action-edit">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                </div>

                                <form
                                    method="POST"
                                    action="{{ route('siswa.achievement.destroy', $achievement) }}"
                                    style="margin-top:8px;"
                                    class="js-loading-form"
                                    data-loading-text="Menghapus..."
                                    onsubmit="return confirm('Hapus prestasi/sertifikat ini? Tindakan tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach

                    </div>

                @endif

            </div>

        </div>

        {{-- Footer Strip --}}
        <div style="margin-top:48px; padding-top:24px; border-top:1px solid var(--color-paper-border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <span style="font-size:0.7rem; color:var(--color-ink-faint);">
                &copy; {{ date('Y') }} <strong style="color:var(--color-ink-muted);">DKV SMEKDA</strong>
                &nbsp;&bull;&nbsp; SMK Negeri 2 Padang Panjang
            </span>
            <span style="font-size:0.7rem; color:var(--color-ink-faint);">
                Dikembangkan untuk Skripsi oleh <strong style="color:var(--color-ink-muted);">Rafli</strong> &mdash; 2026
            </span>
        </div>

    </div>
</main>

{{-- ================================================================
     MODAL — TAMBAH PRESTASI/SERTIFIKAT
================================================================ --}}
<div class="modal-overlay" id="achievementModal" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="achievementModalTitle">
        <div class="modal-header">
            <div>
                <div class="modal-header-title" id="achievementModalTitle">Tambah Prestasi/Sertifikat</div>
                <div class="modal-header-sub">Lengkapi data di bawah ini, lalu simpan.</div>
            </div>
            <button type="button" class="modal-close" onclick="closeAchievementModal()" aria-label="Tutup modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('siswa.achievement.store') }}" enctype="multipart/form-data" class="js-loading-form" data-loading-text="Menyimpan...">
            @csrf
            <div class="modal-body">

                <div class="field-row">
                    <div class="field-wrap">
                        <label for="type" class="field-label">Jenis <span class="req">*</span></label>
                        <select id="type" name="type" class="form-select {{ $errors->has('type') ? 'is-error' : '' }}">
                            <option value="sertifikat" {{ old('type') == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                            <option value="prestasi" {{ old('type') == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
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
                            value="{{ old('achieved_at') }}"
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
                        value="{{ old('title') }}"
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
                        value="{{ old('issuer') }}"
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
                    >{{ old('description') }}</textarea>
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
                    <label class="file-drop" for="imageInput">
                        <div class="file-drop-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="file-drop-text" id="imageFileText">Klik untuk memilih gambar</div>
                            <div class="file-drop-sub">Format JPG/PNG &bull; Maksimal 2MB</div>
                        </div>
                    </label>
                    <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/jpg" class="visually-hidden-file" onchange="updateFileLabel(this, 'imageFileText', 'Klik untuk memilih gambar')">
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
                    <label class="file-drop" for="fileInput">
                        <div class="file-drop-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="file-drop-text" id="fileFileText">Klik untuk memilih file PDF</div>
                            <div class="file-drop-sub">Format PDF &bull; Maksimal 4MB</div>
                        </div>
                    </label>
                    <input type="file" id="fileInput" name="file" accept=".pdf" class="visually-hidden-file" onchange="updateFileLabel(this, 'fileFileText', 'Klik untuk memilih file PDF')">
                    @error('file')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Prestasi/Sertifikat</span>
                </button>

            </div>
        </form>
    </div>
</div>

<script>
    // ── Util: daftar elemen yang bisa menerima fokus di dalam container ──
    function getFocusable(container) {
        if (!container) return [];
        return Array.from(container.querySelectorAll(
            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        )).filter(function (el) { return el.offsetParent !== null; });
    }
    // ── Modal Tambah Prestasi/Sertifikat ──
    var lastFocusedBeforeModal = null;

    function openAchievementModal() {
        var modal = document.getElementById('achievementModal');
        lastFocusedBeforeModal = document.activeElement;
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        var focusables = getFocusable(modal.querySelector('.modal-box'));
        if (focusables.length) focusables[0].focus();
    }
    function closeAchievementModal() {
        var modal = document.getElementById('achievementModal');
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (lastFocusedBeforeModal && typeof lastFocusedBeforeModal.focus === 'function') {
            lastFocusedBeforeModal.focus();
        }
    }
    function updateFileLabel(input, labelId, fallback) {
        var label = document.getElementById(labelId);
        label.textContent = input.files && input.files.length > 0 ? input.files[0].name : fallback;
    }
    // Klik di luar modal-box untuk menutup
    document.getElementById('achievementModal').addEventListener('click', function (e) {
        if (e.target === this) closeAchievementModal();
    });
    // Trap fokus (Tab / Shift+Tab) selama modal terbuka
    document.getElementById('achievementModal').addEventListener('keydown', function (e) {
        if (e.key !== 'Tab') return;
        var focusables = getFocusable(this.querySelector('.modal-box'));
        if (!focusables.length) return;
        var first = focusables[0];
        var last = focusables[focusables.length - 1];
        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault(); last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault(); first.focus();
        }
    });

    // ── Escape menutup modal Tambah Prestasi/Sertifikat ──
    // (Escape untuk sidebar mobile ditangani oleh skrip shell bersama di bawah.)
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var modal = document.getElementById('achievementModal');
        if (modal && modal.classList.contains('open')) { closeAchievementModal(); }
    });

    // ── Loading state ringan pada submit form (cegah submit ganda) ──
    document.querySelectorAll('.js-loading-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            var label = btn.querySelector('span');
            if (label && form.dataset.loadingText) label.textContent = form.dataset.loadingText;
            btn.style.opacity = '0.65';
            btn.style.cursor = 'not-allowed';
        });
    });

    @if($errors->any())
        // Buka kembali modal secara otomatis jika submit gagal validasi,
        // supaya pesan error langsung terlihat oleh siswa.
        openAchievementModal();
    @endif
</script>
<script>
    /* ── OFF-CANVAS SIDEBAR (mobile) ── identik dengan siswa/dashboard.blade.php,
       siswa/profile/edit.blade.php, siswa/portfolio/create.blade.php &
       siswa/portfolio/edit.blade.php (shell bersama). ── */
    (function () {
        var sidebar  = document.getElementById('siswaSidebar');
        var overlay  = document.getElementById('siswaSidebarOverlay');
        var openBtn  = document.getElementById('siswaSidebarOpen');
        var closeBtn = document.getElementById('siswaSidebarClose');

        if (!sidebar || !overlay || !openBtn) return;

        function isMobile() {
            return window.innerWidth <= 860;
        }

        function syncA11y() {
            if (isMobile() && !sidebar.classList.contains('sidebar-open')) {
                sidebar.setAttribute('aria-hidden', 'true');
            } else {
                sidebar.removeAttribute('aria-hidden');
            }
        }

        function openSidebar() {
            sidebar.classList.add('sidebar-open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            openBtn.setAttribute('aria-expanded', 'true');
            syncA11y();
            window.requestAnimationFrame(function () {
                if (closeBtn) closeBtn.focus();
            });
        }

        function closeSidebar(returnFocus) {
            sidebar.classList.remove('sidebar-open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            openBtn.setAttribute('aria-expanded', 'false');
            syncA11y();
            if (returnFocus !== false) openBtn.focus();
        }

        function trapFocus(e) {
            if (e.key !== 'Tab' || !sidebar.classList.contains('sidebar-open')) return;
            var focusable = sidebar.querySelectorAll('a[href], button:not([disabled])');
            if (!focusable.length) return;
            var first = focusable[0];
            var last  = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }

        openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', function () { closeSidebar(); });
        overlay.addEventListener('click', function () { closeSidebar(); });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sidebar.classList.contains('sidebar-open')) {
                closeSidebar();
            }
            trapFocus(e);
        });

        document.querySelectorAll('.sidebar-nav .nav-item, .sidebar-footer .btn-logout').forEach(function (el) {
            el.addEventListener('click', function () { closeSidebar(false); });
        });

        window.addEventListener('resize', function () {
            if (!isMobile()) {
                closeSidebar(false);
            } else {
                syncA11y();
            }
        });

        syncA11y();
    })();
</script>

@endsection