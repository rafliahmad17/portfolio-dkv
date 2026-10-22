{{-- resources/views/siswa/portfolio/edit.blade.php --}}
{{-- MIGRASI: sebelumnya halaman ini masih memakai dark theme legacy mandiri
     (HTML/head/body sendiri, font Inter, #080808, var(--red)/var(--border)).
     Sekarang dipindahkan ke sistem editorial "Kertas & Oxblood" yang sama
     dengan resources/views/siswa/profile/edit.blade.php dan
     resources/views/siswa/achievement/edit.blade.php — memakai
     @extends('layouts.app'), shell sidebar/topbar bersama
     (resources/css/components/dashboard-shell-siswa.css), dan token warna
     var(--color-*) / var(--font-*) resmi. Backend (route, controller,
     nama field, validasi, method form) TIDAK diubah sama sekali. --}}
@extends('layouts.app')

@section('title', 'Edit Karya — DKV SMEKDA Portal')

{{-- Halaman ini punya sidebar + topbar sendiri (pola yang sama dengan
     siswa/dashboard.blade.php & siswa/profile/edit.blade.php), jadi
     navbar/footer bawaan layout tidak dipakai di sini. --}}
@section('navbar')@endsection
@section('footer')@endsection

@push('styles')
@vite('resources/css/pages/siswa-portfolio-edit.css')
@endpush

@section('content')
<a href="#kontenEditKarya" class="skip-link">Lompat ke konten utama</a>
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
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
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
        <a href="{{ route('siswa.achievement.index') }}" class="nav-item">
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
<main class="main-content" id="kontenEditKarya">

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
                <span class="topbar-crumb-current">Edit Karya</span>
            </div>
        </div>
        <div class="badge-pill">
            <span class="badge-role-dot" aria-hidden="true"></span>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </header>

    <div class="page-inner">

        {{-- Back --}}
        <a href="{{ route('siswa.dashboard') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Dashboard
        </a>

        {{-- Header --}}
        <div class="page-header-block">
            <div class="edit-eyebrow">
                <span aria-hidden="true">&#9998;</span> Mode Edit Karya
            </div>
            <h1 class="form-headline">
                Perbarui <span class="hl">Karya Anda</span>
            </h1>
            <p class="form-sub">Edit metadata dan visual karya portofolio Anda.</p>
        </div>

        {{-- Info Banner --}}
        <div class="flash-note" role="status">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>
                Anda sedang mengedit karya: <strong>{{ $portfolio->title }}</strong>.
                Kosongkan field gambar atau PDF jika tidak ingin menggantinya — file lama akan tetap dipertahankan.
            </span>
        </div>

        {{-- Error Summary --}}
        @if ($errors->any())
            <div class="error-alert" role="alert">
                <div class="error-alert-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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

        {{-- ── FORM ── --}}
        <form
            method="POST"
            action="{{ route('siswa.portfolio.update', $portfolio) }}"
            enctype="multipart/form-data"
            id="editForm"
        >
            @csrf
            @method('PUT')

            <div class="edit-grid">

                {{-- ============ LEFT: IMAGE ============ --}}
                <div>
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-header-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="card-header-title">Thumbnail Karya</div>
                                <div class="card-header-sub">Klik atau seret gambar baru untuk mengganti</div>
                            </div>
                        </div>
                        <div class="form-card-body">

                            {{-- Drop Zone - Existing image shown by default --}}
                            <div
                                class="drop-zone has-preview"
                                id="imageDropZone"
                                onclick="document.getElementById('imageInput').click()"
                            >
                                {{-- Hidden prompt (shown when no existing image somehow) --}}
                                <div class="drop-zone-prompt" id="imagePrompt" style="display:none;">
                                    <div class="drop-icon-wrap">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="drop-title">Pilih atau letakkan gambar baru di sini</div>
                                        <div class="drop-sub" style="margin-top:5px;">
                                            Klik area ini atau seret file gambar.
                                        </div>
                                    </div>
                                    <div class="drop-types">
                                        <span class="drop-type-pill">JPG</span>
                                        <span class="drop-type-pill">PNG</span>
                                        <span class="drop-type-pill">WEBP</span>
                                        <span class="drop-type-pill">Max 2MB</span>
                                    </div>
                                </div>

                                {{-- Preview: shown with existing image by default --}}
                                <div class="preview-wrap" id="imagePreviewWrap">
                                    <img
                                        src="{{ $portfolio->image_url }}"
                                        {!! $portfolio->imageSizeAttributes() !!}
                                        alt="{{ $portfolio->title }}"
                                        class="preview-img"
                                        id="imagePreviewImg"
                                    >
                                    <div class="preview-overlay">
                                        <button
                                            type="button"
                                            class="preview-change-btn"
                                            onclick="event.stopPropagation(); document.getElementById('imageInput').click()"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            Ganti Gambar
                                        </button>
                                        <div class="preview-name" id="imageFileName">Gambar saat ini tersimpan</div>
                                    </div>
                                    <div class="preview-badge" id="previewBadge" style="display:block;">&#10003; Gambar Aktif</div>
                                    <div class="existing-badge" id="existingBadge">Foto Lama</div>
                                </div>
                            </div>

                            <input
                                type="file"
                                id="imageInput"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp"
                                style="display:none;"
                            >

                            @error('image')
                                <div class="field-error" style="margin-top:10px;">
                                    <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                    </div>

                    {{-- Tips --}}
                    <div class="tips-card">
                        <div class="tips-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tips Edit
                        </div>
                        <div class="tips-item">
                            <div class="tips-bullet"></div>
                            Jika tidak mengunggah gambar baru, gambar lama akan tetap dipertahankan secara otomatis.
                        </div>
                        <div class="tips-item">
                            <div class="tips-bullet"></div>
                            Perbarui deskripsi secara berkala agar karya terasa hidup dan relevan.
                        </div>
                        <div class="tips-item">
                            <div class="tips-bullet"></div>
                            Gunakan resolusi minimal <strong>800&times;600px</strong> untuk tampilan terbaik.
                        </div>
                    </div>
                </div>

                {{-- ============ RIGHT: METADATA ============ --}}
                <div>
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-header-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="card-header-title">Informasi Karya</div>
                                <div class="card-header-sub">Perbarui metadata karya Anda</div>
                            </div>
                        </div>
                        <div class="form-card-body">

                            {{-- Judul --}}
                            <div class="field-wrap">
                                <label for="title" class="field-label">
                                    Judul Karya <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <input
                                        type="text"
                                        id="title"
                                        name="title"
                                        value="{{ old('title', $portfolio->title) }}"
                                        placeholder="Judul karya Anda"
                                        class="form-input {{ $errors->has('title') ? 'is-error' : '' }}"
                                    >
                                </div>
                                @error('title')
                                    <div class="field-error">
                                        <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="field-wrap">
                                <label for="category_id" class="field-label">
                                    Kategori <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <select
                                        id="category_id"
                                        name="category_id"
                                        class="form-select {{ $errors->has('category_id') ? 'is-error' : '' }}"
                                    >
                                        <option value="" disabled>— Pilih Kategori Karya —</option>
                                        @foreach($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                {{ old('category_id', $portfolio->category_id) == $category->id ? 'selected' : '' }}
                                            >
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                @error('category_id')
                                    <div class="field-error">
                                        <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="field-wrap">
                                <label for="description" class="field-label">
                                    Deskripsi <span class="req">*</span>
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    placeholder="Ceritakan konsep, proses kreatif, tools yang digunakan..."
                                    class="form-textarea {{ $errors->has('description') ? 'is-error' : '' }}"
                                >{{ old('description', $portfolio->description) }}</textarea>
                                @error('description')
                                    <div class="field-error">
                                        <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PDF Upload --}}
                            <div class="field-wrap">
                                <label class="field-label">
                                    File Dokumen PDF
                                    <span class="opt-hint">(Opsional — ganti atau pertahankan)</span>
                                </label>

                                {{-- Existing PDF indicator --}}
                                @if($portfolio->file_pdf_path)
                                    <div class="current-file-box">
                                        <div class="current-file-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="current-file-text">File PDF saat ini: Tersimpan.</div>
                                            <div class="current-file-sub">Unggah file baru di bawah untuk menimpanya.</div>
                                        </div>
                                    </div>
                                @endif

                                <div
                                    class="pdf-upload-area"
                                    id="pdfUploadArea"
                                    onclick="document.getElementById('pdfInput').click()"
                                >
                                    <div class="pdf-icon-box">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div style="flex:1; min-width:0;">
                                        <div class="pdf-text-main" id="pdfTextMain">
                                            {{ $portfolio->file_pdf_path ? 'Klik untuk mengganti file PDF' : 'Klik untuk memilih file PDF' }}
                                        </div>
                                        <div class="pdf-text-sub">Format PDF &bull; Maksimal 5MB</div>
                                    </div>
                                    <div style="flex-shrink:0;">
                                        <span class="pdf-browse-pill">Browse</span>
                                    </div>
                                </div>

                                <input
                                    type="file"
                                    id="pdfInput"
                                    name="file_pdf"
                                    accept=".pdf"
                                    style="display:none;"
                                >

                                @error('file_pdf')
                                    <div class="field-error" style="margin-top:8px;">
                                        <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Buttons --}}
                            <div class="form-actions-wrap">
                                <button type="submit" class="btn-submit" id="editSubmitBtn">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span id="editSubmitText">Simpan Perubahan</span>
                                </button>

                                <a href="{{ route('siswa.dashboard') }}" class="btn-plain-cancel">
                                    Batalkan &amp; Kembali
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>

        {{-- Footer --}}
        <div class="page-footer-note">
            <span>
                &copy; {{ date('Y') }} <strong>DKV SMEKDA</strong>
                &nbsp;&bull;&nbsp; SMK Negeri 2 Padang Panjang
            </span>
            <span>
                Dikembangkan untuk Skripsi oleh <strong>Rafli</strong> &mdash; 2026
            </span>
        </div>

    </div>
</main>
@endsection

{{-- ================================================================
     JAVASCRIPT
================================================================ --}}
@push('scripts')
<script>
    /* ── OFF-CANVAS SIDEBAR (mobile) — identik dengan siswa/profile/edit.blade.php ── */
    (function () {
        var sidebar  = document.getElementById('siswaSidebar');
        var overlay  = document.getElementById('siswaSidebarOverlay');
        var openBtn  = document.getElementById('siswaSidebarOpen');
        var closeBtn = document.getElementById('siswaSidebarClose');

        if (!sidebar || !overlay || !openBtn) return;

        function isMobile() { return window.innerWidth <= 860; }

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
            window.requestAnimationFrame(function () { if (closeBtn) closeBtn.focus(); });
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
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }

        openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', function () { closeSidebar(); });
        overlay.addEventListener('click', function () { closeSidebar(); });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sidebar.classList.contains('sidebar-open')) closeSidebar();
            trapFocus(e);
        });

        document.querySelectorAll('.sidebar-nav .nav-item, .sidebar-footer .btn-logout').forEach(function (el) {
            el.addEventListener('click', function () { closeSidebar(false); });
        });

        window.addEventListener('resize', function () {
            if (!isMobile()) closeSidebar(false); else syncA11y();
        });

        syncA11y();
    })();

    /* ── IMAGE LIVE PREVIEW ── */
    const imageInput       = document.getElementById('imageInput');
    const imageDropZone    = document.getElementById('imageDropZone');
    const imagePrompt      = document.getElementById('imagePrompt');
    const imagePreviewWrap = document.getElementById('imagePreviewWrap');
    const imagePreviewImg  = document.getElementById('imagePreviewImg');
    const imageFileName    = document.getElementById('imageFileName');
    const previewBadge     = document.getElementById('previewBadge');
    const existingBadge    = document.getElementById('existingBadge');

    function applyNewImagePreview(file) {
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviewImg.src        = e.target.result;
            imagePrompt.style.display  = 'none';
            imagePreviewWrap.style.display = 'block';

            // Update badges
            previewBadge.textContent   = '✓ Foto Baru';
            previewBadge.style.display = 'block';
            previewBadge.style.borderColor = 'color-mix(in srgb, var(--color-accent-500) 55%, transparent)';
            previewBadge.style.color   = 'var(--color-accent-200)';

            if (existingBadge) existingBadge.style.display = 'none';

            // Update filename display
            const trimmed = file.name.length > 32
                ? file.name.substring(0, 29) + '...'
                : file.name;
            imageFileName.textContent = trimmed;

            imageDropZone.classList.add('has-preview');
        };
        reader.readAsDataURL(file);
    }

    imageInput.addEventListener('change', () => {
        if (imageInput.files[0]) applyNewImagePreview(imageInput.files[0]);
    });

    /* ── DRAG & DROP ── */
    ['dragenter', 'dragover'].forEach(evt => {
        imageDropZone.addEventListener(evt, (e) => {
            e.preventDefault(); e.stopPropagation();
            imageDropZone.classList.add('drag-over');
        });
    });

    ['dragleave', 'dragend'].forEach(evt => {
        imageDropZone.addEventListener(evt, (e) => {
            e.preventDefault(); e.stopPropagation();
            imageDropZone.classList.remove('drag-over');
        });
    });

    imageDropZone.addEventListener('drop', (e) => {
        e.preventDefault(); e.stopPropagation();
        imageDropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            imageInput.files = dt.files;
            applyNewImagePreview(file);
        }
    });

    /* ── PDF HANDLER ── */
    const pdfInput      = document.getElementById('pdfInput');
    const pdfUploadArea = document.getElementById('pdfUploadArea');
    const pdfTextMain   = document.getElementById('pdfTextMain');

    pdfInput.addEventListener('change', () => {
        const file = pdfInput.files[0];
        if (file) {
            const trimmed = file.name.length > 36
                ? file.name.substring(0, 33) + '...'
                : file.name;
            pdfTextMain.textContent = trimmed;
            pdfUploadArea.classList.add('has-file');
        }
    });

    /* ── INPUT ICON FOCUS COLOR ── */
    document.querySelectorAll('.form-input, .form-select').forEach(el => {
        const wrap = el.closest('.input-wrap');
        const icon = wrap?.querySelector('.input-icon');
        if (!icon) return;
        el.addEventListener('focus', () => { icon.style.color = 'var(--color-accent-600)'; });
        el.addEventListener('blur',  () => { icon.style.color = 'var(--color-ink-faint)'; });
    });

    /* ── SUBMIT LOADING STATE (cegah submit ganda) ── */
    (function () {
        var form = document.getElementById('editForm');
        var btn  = document.getElementById('editSubmitBtn');
        var text = document.getElementById('editSubmitText');
        if (!form || !btn || !text) return;
        var originalText = text.textContent;

        form.addEventListener('submit', function () {
            if (btn.disabled) return;
            btn.disabled = true;
            text.textContent = 'Menyimpan...';
        });

        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                btn.disabled = false;
                text.textContent = originalText;
            }
        });
    })();
</script>
@endpush
