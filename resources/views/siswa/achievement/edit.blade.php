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

@push('styles')
@vite('resources/css/pages/siswa-achievement-edit.css')
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
<main class="main-content" id="kontenEditPrestasi">

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
                <span class="topbar-crumb-current">Edit Prestasi &amp; Sertifikat</span>
            </div>
        </div>
        <div class="badge-pill">
            <span class="badge-role-dot" aria-hidden="true"></span>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </header>

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
                            <img src="{{ asset('storage/' . $achievement->image_path) }}" alt="{{ $achievement->title }}" width="44" height="44" class="current-file-thumb">
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
</main>

<script>
    function updateFileLabel(input, labelId, fallback) {
        var label = document.getElementById(labelId);
        label.textContent = input.files && input.files.length > 0 ? input.files[0].name : fallback;
    }

    // ── OFF-CANVAS SIDEBAR (mobile) ── identik dengan siswa/dashboard.blade.php,
    // siswa/profile/edit.blade.php & siswa/achievement/index.blade.php
    // (shell bersama dashboard-shell-siswa.css). ──
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
