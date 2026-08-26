{{-- resources/views/siswa/portfolio/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Karya')

@push('styles')
<style>
    /* ── DESIGN TOKENS (migrasi Fase 2.2: --tk-* → token editorial resmi) ── */
    /* --tk-border-3 sudah selesai dimigrasikan & dihapus di Batch 2 (dipakai
       langsung sebagai var(--color-paper-border) di .tk-card::before).
       --tk-red, --tk-red-bright, --tk-red-glow-1/2/3 masih dipertahankan
       namanya karena masih dipakai rule dropzone/PDF di bawah — akan
       dimigrasikan & dihapus pada Batch 5. Nilainya sudah menunjuk ke token
       resmi/pola oxblood editorial. */
    :root {
        --tk-red-bright:      var(--color-accent-500);
        --tk-red-glow-2:      rgba(122,46,46,0.18);
    }

    body {
        background-color: var(--color-paper);
        color: var(--color-ink);
    }
    
    .tk-page {
        position: relative;
        min-height: 100vh;
    }
    .tk-page::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        opacity: 0.025;
        mix-blend-mode: overlay;
        background-repeat: repeat;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    }

    /* ── CARD SURFACE / DEPTH ── */
    .tk-card { position: relative; }
    .tk-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--color-paper-border), transparent);
        pointer-events: none;
    }

    /* ── IMAGE DROPZONE ── */
    .tk-dropzone {
        transition: border-color .3s ease, background-color .3s ease, box-shadow .3s ease;
    }
    .tk-dropzone:hover,
    .tk-dropzone:focus-visible {
        border-color: var(--color-accent-600);
        background-color: color-mix(in srgb, var(--color-accent-600) 4.5%, transparent);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 12%, transparent);
        outline: none;
    }
    .tk-dropzone.has-error {
        border-color: color-mix(in srgb, var(--color-accent-500) 60%, transparent) !important;
        background-color: color-mix(in srgb, var(--color-accent-500) 5%, transparent) !important;
    }
    .tk-dropzone.has-preview {
        border-style: solid;
        border-color: color-mix(in srgb, var(--color-accent-600) 40%, transparent);
    }
    .tk-dropzone.is-dragover {
        animation: tk-border-pulse 1.4s ease-in-out infinite;
    }
    @keyframes tk-border-pulse {
        0%, 100% { border-color: var(--color-accent-600); box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 12%, transparent); }
        50%      { border-color: var(--color-accent-500); box-shadow: 0 0 0 4px color-mix(in srgb, var(--color-accent-600) 18%, transparent); }
    }

    .tk-drop-icon-box { transition: background-color .3s ease, border-color .3s ease; }
    .tk-drop-icon-box svg { transition: color .3s ease; }
    .tk-drop-title { transition: color .3s ease; }
    .tk-dropzone:hover .tk-drop-icon-box,
    .tk-dropzone:focus-visible .tk-drop-icon-box {
        background-color: color-mix(in srgb, var(--color-accent-600) 12%, transparent);
        border-color: color-mix(in srgb, var(--color-accent-600) 25%, transparent);
    }
    .tk-dropzone:hover .tk-drop-icon-box svg,
    .tk-dropzone:focus-visible .tk-drop-icon-box svg { color: var(--color-accent-600); }
    .tk-dropzone:hover .tk-drop-title,
    .tk-dropzone:focus-visible .tk-drop-title { color: var(--color-ink); }

    .tk-preview-overlay {
        opacity: 0;
        background-color: color-mix(in srgb, var(--color-ink) 55%, transparent);
        transition: opacity .3s ease;
    }
    .tk-dropzone:hover .tk-preview-overlay,
    .tk-dropzone:focus-within .tk-preview-overlay { opacity: 1; }

    /* ── IMAGE PREVIEW CONTROLS: tombol Hapus (Batch 5.1d-2 — migrasi token editorial) ── */
    #tkRemoveImageBtn {
        background-color: color-mix(in srgb, var(--color-ink) 70%, transparent);
        border-color: color-mix(in srgb, var(--color-paper) 15%, transparent);
        color: color-mix(in srgb, var(--color-paper) 60%, transparent);
    }
    #tkRemoveImageBtn:hover {
        color: var(--color-accent-500);
        border-color: color-mix(in srgb, var(--color-accent-500) 40%, transparent);
    }
    #tkRemoveImageBtn:focus-visible {
        outline: none;
        box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-accent-600) 40%, transparent);
    }

    /* ── IMAGE PREVIEW CONTROLS: tombol Ganti Gambar (Batch 5.1d-4 ── migrasi token editorial) ── */
    #tkChangeImageBtn {
        background-color: color-mix(in srgb, var(--color-accent-600) 90%, transparent);
        color: var(--color-paper);
        box-shadow: 0 4px 20px color-mix(in srgb, var(--color-accent-600) 40%, transparent);
    }
    #tkChangeImageBtn:focus-visible {
        outline: none;
        box-shadow: 0 4px 20px color-mix(in srgb, var(--color-accent-600) 40%, transparent), 0 0 0 2px color-mix(in srgb, var(--color-accent-600) 40%, transparent);
    }

    /* ── PREVIEW REVEAL ANIMATION ── */
    @keyframes tk-fade-in {
        from { opacity: 0; transform: scale(0.97); }
        to   { opacity: 1; transform: scale(1); }
    }
    .tk-fade-in { animation: tk-fade-in 0.35s ease forwards; }

    /* ── ERROR SHAKE ── */
    @keyframes tk-shake {
        10%, 90% { transform: translateX(-1px); }
        20%, 80% { transform: translateX(2px); }
        30%, 50%, 70% { transform: translateX(-3px); }
        40%, 60% { transform: translateX(3px); }
    }
    .tk-shake { animation: tk-shake 0.4s ease-in-out; }

    /* ── PDF AREA ── */
    .tk-pdf-area { transition: border-color .25s ease, background-color .25s ease; }
    .tk-pdf-area:hover,
    .tk-pdf-area:focus-visible {
        border-color: color-mix(in srgb, var(--color-accent-600) 30%, transparent);
        background-color: color-mix(in srgb, var(--color-accent-600) 3.5%, transparent);
        outline: none;
    }
    .tk-pdf-area.has-error {
        border-color: color-mix(in srgb, var(--color-accent-500) 55%, transparent) !important;
        background-color: color-mix(in srgb, var(--color-accent-500) 5%, transparent) !important;
    }
    .tk-pdf-area.has-file {
        border-style: solid;
        border-color: color-mix(in srgb, var(--color-accent-600) 35%, transparent);
        background-color: color-mix(in srgb, var(--color-accent-600) 5%, transparent);
    }
    .tk-pdf-area.has-file .tk-pdf-text { color: var(--color-accent-500); }
    .tk-pdf-area.has-file .tk-pdf-icon-box {
        color: var(--color-accent-600);
        border-color: color-mix(in srgb, var(--color-accent-600) 25%, transparent);
        background-color: color-mix(in srgb, var(--color-accent-600) 12%, transparent);
    }
    .tk-pdf-area.is-dragover {
        border-color: var(--color-accent-600) !important;
        background-color: color-mix(in srgb, var(--color-accent-600) 12%, transparent) !important;
    }

    /* ── PDF PREVIEW CONTROLS: tombol Hapus PDF (Batch 5.2f — migrasi token editorial) ── */
    #tkRemovePdfBtn {
        background-color: color-mix(in srgb, var(--color-ink) 5%, transparent);
        border-color: color-mix(in srgb, var(--color-ink) 10%, transparent);
        color: var(--color-ink-faint);
    }
    #tkRemovePdfBtn:hover {
        color: var(--color-accent-500);
        border-color: color-mix(in srgb, var(--color-accent-500) 30%, transparent);
    }
    #tkRemovePdfBtn:focus-visible {
        outline: none;
        box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-accent-600) 40%, transparent);
    }

    /* ── FIELD: JUDUL KARYA (Batch 4.1 — migrasi token editorial) ── */
    .tk-title-icon {
        color: var(--color-ink-faint);
        transition: color .2s ease;
    }
    .group:focus-within .tk-title-icon {
        color: var(--color-accent-600);
    }
    .tk-title-input {
        color: var(--color-ink);
        background-color: var(--color-paper-elevated);
        border-color: var(--color-paper-border);
        transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
    }
    .tk-title-input::placeholder {
        color: var(--color-ink-faint);
    }
    .tk-title-input:hover {
        border-color: var(--color-accent-700);
    }
    .tk-title-input:focus {
        outline: none;
        border-color: var(--color-accent-600);
        background-color: var(--color-paper-elevated);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 20%, transparent);
    }
    .tk-title-input.has-error {
        border-color: var(--color-accent-500);
        background-color: color-mix(in srgb, var(--color-accent-500) 8%, transparent);
    }

    /* ── FIELD: KATEGORI (Batch 4.2 — migrasi token editorial) ── */
    .tk-category-icon {
        color: var(--color-ink-faint);
        transition: color .2s ease;
    }
    .group:focus-within .tk-category-icon {
        color: var(--color-accent-600);
    }
    .tk-category-chevron {
        color: var(--color-ink-faint);
    }
    .tk-category-select {
        color: var(--color-ink);
        background-color: var(--color-paper-elevated);
        border-color: var(--color-paper-border);
        transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
    }
    .tk-category-select:invalid {
        color: var(--color-ink-faint);
    }
    .tk-category-select:hover:not(:disabled) {
        border-color: var(--color-accent-700);
    }
    .tk-category-select:focus:not(:disabled) {
        outline: none;
        border-color: var(--color-accent-600);
        background-color: var(--color-paper-elevated);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 20%, transparent);
    }
    .tk-category-select.has-error {
        border-color: var(--color-accent-500);
        background-color: color-mix(in srgb, var(--color-accent-500) 8%, transparent);
    }
    .tk-category-select:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .tk-category-select option {
        background-color: var(--color-paper-elevated);
        color: var(--color-ink);
    }

    /* ── FIELD: DESKRIPSI (Batch 4.3 — migrasi token editorial) ── */
    .tk-description-textarea {
        color: var(--color-ink);
        background-color: var(--color-paper-elevated);
        border-color: var(--color-paper-border);
        transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
    }
    .tk-description-textarea::placeholder {
        color: var(--color-ink-faint);
    }
    .tk-description-textarea:hover {
        border-color: var(--color-accent-700);
    }
    .tk-description-textarea:focus {
        outline: none;
        border-color: var(--color-accent-600);
        background-color: var(--color-paper-elevated);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 20%, transparent);
    }
    .tk-description-textarea.has-error {
        border-color: var(--color-accent-500);
        background-color: color-mix(in srgb, var(--color-accent-500) 8%, transparent);
    }

    /* ── BREADCRUMB (Batch 5.3b — migrasi token editorial) ── */
    .tk-breadcrumb-link {
        color: inherit;
    }
    .tk-breadcrumb-link:hover {
        color: var(--color-accent-600);
    }
    .tk-breadcrumb-link:focus-visible {
        outline: none;
        box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-accent-600) 40%, transparent);
    }

    /* ── PAGE HEADER (Batch 5.3c — migrasi token editorial) ── */
    .tk-header-blur {
        background-color: color-mix(in srgb, var(--color-accent-600) 10%, transparent);
    }
    .tk-header-back-link {
        border-color: var(--color-paper-border);
        background-color: var(--color-paper-elevated);
        color: var(--color-ink-muted);
        transition: color .2s ease, border-color .2s ease, background-color .2s ease;
    }
    .tk-header-back-link:hover {
        color: var(--color-accent-500);
        border-color: color-mix(in srgb, var(--color-accent-600) 30%, transparent);
        background-color: color-mix(in srgb, var(--color-accent-600) 6%, transparent);
    }
    .tk-header-back-link:focus-visible {
        outline: none;
        box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-accent-600) 40%, transparent);
    }
    .tk-header-eyebrow {
        color: color-mix(in srgb, var(--color-accent-600) 70%, transparent);
    }
    .tk-header-title {
        color: var(--color-ink);
    }
    .tk-header-title-accent {
        color: var(--color-accent-600);
        text-shadow: 0 0 26px color-mix(in srgb, var(--color-accent-600) 35%, transparent);
    }
    .tk-header-subtitle {
        color: var(--color-ink-faint);
    }

    /* ── SUCCESS FLASH BANNER (Batch 5.3e — migrasi token editorial) ── */
    .tk-success-banner {
        border-color: color-mix(in srgb, var(--color-accent-600) 25%, transparent);
        border-left-color: var(--color-accent-600);
        background-color: color-mix(in srgb, var(--color-accent-600) 6%, transparent);
    }
    .tk-success-icon-box {
        background-color: color-mix(in srgb, var(--color-accent-600) 15%, transparent);
        border-color: color-mix(in srgb, var(--color-accent-600) 30%, transparent);
    }
    .tk-success-icon {
        color: var(--color-accent-500);
    }
    .tk-success-text {
        color: var(--color-ink);
    }

    @media (prefers-reduced-motion: reduce) {
        .tk-page *, .tk-page *::before, .tk-page *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>
@endpush

@section('content')
<div class="tk-page w-full max-w-6xl mx-auto pb-16" style="font-family:var(--font-sans);">

    {{-- BREADCRUMB --}}
    <nav aria-label="Breadcrumb" class="mb-6 flex items-center gap-2 text-xs font-semibold" style="color: var(--color-ink-faint);">
        <a href="{{ route('siswa.dashboard') }}" class="tk-breadcrumb-link transition-colors rounded">Dashboard</a>
        <svg class="w-3 h-3" style="color: var(--color-ink-faint);" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span style="color: var(--color-ink-muted);" aria-current="page">Tambah Karya</span>
    </nav>

    {{-- PAGE HEADER --}}
    <div class="relative mb-8 sm:mb-10">
        <div class="tk-header-blur pointer-events-none absolute -top-16 -right-10 w-72 h-72 rounded-full blur-3xl" aria-hidden="true"></div>

        <div class="relative z-10">
            <a href="{{ route('siswa.dashboard') }}"
               class="tk-header-back-link group inline-flex items-center gap-2 mb-6 px-3.5 py-2 rounded-lg border text-xs font-bold">
                <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Dashboard
            </a>

            <div class="tk-header-eyebrow text-[0.68rem] font-bold tracking-[3px] uppercase mb-2.5">
                <span aria-hidden="true">&#9654;</span> Portofolio Digital
            </div>
            <h1 class="tk-header-title text-2xl sm:text-3xl lg:text-[2rem] font-black tracking-tight leading-tight">
                Tambah <span class="tk-header-title-accent">Karya</span>
            </h1>
            <p class="tk-header-subtitle mt-2 text-sm max-w-xl">
                Unggah karya terbarumu dan lengkapi detailnya untuk ditampilkan di portofolio digital.
            </p>
        </div>
    </div>

    {{-- SUCCESS FLASH (jika backend menyediakan session flash, tampilkan secara elegan) --}}
    @if(session('success'))
        <div role="status" class="tk-success-banner mb-7 flex items-start gap-3 rounded-2xl border border-l-[3px] px-5 py-4">
            <div class="tk-success-icon-box w-7 h-7 rounded-full border flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="tk-success-icon w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="tk-success-text text-[0.82rem] font-semibold leading-relaxed">{{ session('success') }}</p>
        </div>
    @endif

    {{-- GLOBAL ERROR ALERT --}}
    @if ($errors->any())
        <div id="tk-error-alert" role="alert" class="mb-7 rounded-2xl border border-red-600/[0.3] border-l-[3px] border-l-red-600 bg-red-600/[0.08] px-5 py-4">
            <div class="flex items-center gap-2 text-[0.8rem] font-extrabold text-red-300 mb-2.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Form gagal disimpan &mdash; {{ $errors->count() }} kesalahan perlu diperbaiki:
            </div>
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="flex items-start gap-2 text-[0.75rem] text-red-200/[0.8]">
                        <span class="text-red-600 font-black text-[0.65rem] mt-0.5" aria-hidden="true">&#10005;</span>
                        <span>{{ $error }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- EMPTY CATEGORY WARNING --}}
    @php $categoriesEmpty = isset($categories) && $categories->isEmpty(); @endphp
    @if($categoriesEmpty)
        <div class="mb-5 flex items-start gap-2.5 rounded-xl border border-yellow-500/[0.25] border-l-[3px] border-l-yellow-500 bg-yellow-500/[0.08] px-4 py-3.5">
            <svg class="w-4 h-4 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-[0.75rem] leading-relaxed font-semibold text-yellow-500/[0.8]">
                <strong class="text-yellow-500">Kategori belum tersedia.</strong>
                Minta admin untuk menjalankan
                <code class="bg-white/[0.08] px-1.5 py-0.5 rounded text-[0.7rem]">php artisan db:seed --class=CategorySeeder</code>
                &mdash; formulir tidak dapat disimpan sampai kategori tersedia.
            </p>
        </div>
    @endif

    {{-- Live region untuk pengumuman status ke pembaca layar (screen reader) --}}
    <div id="tkSrAnnouncer" class="sr-only" role="status" aria-live="polite"></div>

    {{-- FORM --}}
    <form
        method="POST"
        action="{{ route('siswa.portfolio.store') }}"
        enctype="multipart/form-data"
        id="tkForm"
        novalidate
        class="space-y-6"
    >
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

            {{-- ============ MEDIA ============ --}}
            <section aria-labelledby="tk-media-heading" class="tk-card relative rounded-[20px] border border-white/[0.07] bg-white/[0.03] overflow-hidden shadow-[0_25px_60px_-35px_rgba(0,0,0,0.8)]">
                <div class="flex items-center gap-3 px-6 py-[18px] border-b border-white/[0.07]">
                    <div class="w-9 h-9 rounded-[10px] bg-red-600/[0.1] border border-red-600/[0.2] flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 id="tk-media-heading" class="text-[0.85rem] font-extrabold text-[#f5f5f5]">Media Karya</h2>
                        <p class="text-[0.7rem] text-white/[0.25] mt-0.5">Thumbnail dan dokumen pendukung</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    {{-- IMAGE DROPZONE --}}
                    <div>
                        <label for="image" class="block text-[0.7rem] font-bold tracking-wider uppercase mb-2" style="color: var(--color-ink-muted);">
                            Thumbnail Gambar <span class="ml-0.5" style="color: var(--color-accent-600);">*</span>
                        </label>

                        <div
                            id="tkImageDropzone"
                            class="tk-dropzone {{ $errors->has('image') ? 'has-error' : '' }} relative w-full max-w-full min-h-[260px] sm:min-h-[300px] rounded-2xl border-2 border-dashed border-[color:var(--color-paper-border)] bg-[color:var(--color-paper-elevated)] flex flex-col items-center justify-center cursor-pointer overflow-hidden"
                            role="button"
                            tabindex="0"
                            aria-required="true"
                            aria-label="Pilih atau seret gambar thumbnail karya untuk diunggah. Format JPG, JPEG, atau PNG, maksimal 2MB."
                        >
                            <div id="tkImagePrompt" class="flex flex-col items-center justify-center gap-3.5 px-6 py-9 text-center pointer-events-none">
                                <div class="tk-drop-icon-box w-16 h-16 rounded-2xl bg-[color:var(--color-paper-elevated)] border border-[color:var(--color-paper-border)] flex items-center justify-center">
                                    <svg class="w-7 h-7 text-[color:var(--color-ink-faint)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="tk-drop-title text-[0.88rem] font-extrabold text-[color:var(--color-ink-muted)]">Pilih atau letakkan gambar di sini</div>
                                    <div class="text-[0.72rem] text-[color:var(--color-ink-faint)] mt-1.5 leading-relaxed">Klik area ini atau seret file untuk mengunggah</div>
                                </div>
                                <div id="tkImageFormatHint" class="flex gap-1.5 flex-wrap justify-center">
                                    <span class="text-[0.62rem] font-extrabold tracking-wider uppercase px-2.5 py-1 rounded-full bg-[color:var(--color-paper-elevated)] border border-[color:var(--color-paper-border)] text-[color:var(--color-ink-faint)]">JPG</span>
                                    <span class="text-[0.62rem] font-extrabold tracking-wider uppercase px-2.5 py-1 rounded-full bg-[color:var(--color-paper-elevated)] border border-[color:var(--color-paper-border)] text-[color:var(--color-ink-faint)]">PNG</span>
                                    <span class="text-[0.62rem] font-extrabold tracking-wider uppercase px-2.5 py-1 rounded-full bg-[color:var(--color-paper-elevated)] border border-[color:var(--color-paper-border)] text-[color:var(--color-ink-faint)]">Maks 2MB</span>
                                </div>
                            </div>

                            {{-- Processing state (saat FileReader membaca gambar) --}}
                            <div id="tkImageProcessing" class="hidden absolute inset-0 z-20 flex flex-col items-center justify-center gap-3" style="background-color: color-mix(in srgb, var(--color-ink) 70%, transparent);">
                                <svg class="w-8 h-8 animate-spin" style="color: var(--color-accent-500);" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span role="status" class="text-[0.75rem] font-semibold" style="color: color-mix(in srgb, var(--color-paper) 60%, transparent);">Memproses gambar&hellip;</span>
                            </div>

                            <div id="tkImagePreviewWrap" class="absolute inset-0 hidden">
                                <img src="#" alt="Pratinjau gambar karya" id="tkImagePreviewImg" class="w-full h-full object-cover">

                                <button type="button" id="tkRemoveImageBtn"
                                        class="hidden absolute top-3 right-3 z-10 w-7 h-7 rounded-full border items-center justify-center transition-colors duration-200 focus-visible:outline-none"
                                        aria-label="Hapus gambar yang dipilih">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                <div class="tk-preview-overlay absolute inset-0 flex flex-col items-center justify-center gap-2.5">
                                    <button type="button" id="tkChangeImageBtn"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-[10px] text-[0.78rem] font-extrabold transition-transform duration-150 active:scale-95 focus-visible:outline-none">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Ganti Gambar
                                    </button>
                                    <div class="flex items-center gap-1.5 px-4 max-w-full">
                                        <div id="tkImageFileName" class="text-[0.72rem] font-semibold truncate" style="color: color-mix(in srgb, var(--color-paper) 60%, transparent);"></div>
                                        <span id="tkImageFileSize" class="text-[0.68rem] flex-shrink-0" style="color: color-mix(in srgb, var(--color-paper) 35%, transparent);"></span>
                                    </div>
                                </div>

                                <div class="absolute top-3 left-3 backdrop-blur px-2.5 py-1 rounded-full text-[0.65rem] font-extrabold tracking-wider uppercase" style="background-color: color-mix(in srgb, var(--color-ink) 75%, transparent); border: 1px solid color-mix(in srgb, var(--color-accent-500) 40%, transparent); color: var(--color-accent-500);" aria-hidden="true">
                                    &#10003; Terpilih
                                </div>
                            </div>
                        </div>

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept=".jpg,.jpeg,.png"
                            class="sr-only"
                            required
                            aria-required="true"
                            aria-describedby="tkImageFormatHint{{ $errors->has('image') ? ' image-error' : '' }}"
                            @if ($errors->has('image')) aria-invalid="true" @endif
                        >

                        <p id="tkImageClientError" role="alert" class="hidden mt-2.5 flex items-center gap-1.5 text-[0.73rem] font-semibold" style="color: var(--color-accent-500);">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="tkImageClientErrorText"></span>
                        </p>

                        @error('image')
                            <p id="image-error" class="mt-2.5 flex items-center gap-1.5 text-[0.73rem] font-semibold" style="color: var(--color-accent-500);">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- PDF UPLOAD --}}
                    <div>
                        <label for="file_pdf" class="block text-[0.7rem] font-bold tracking-wider uppercase mb-2" style="color: var(--color-ink-muted);">
                            Dokumen PDF
                            <span class="font-medium normal-case tracking-normal text-[0.65rem] ml-1" style="color: var(--color-ink-faint);">(Opsional)</span>
                        </label>

                        <div
                            id="tkPdfArea"
                            class="tk-pdf-area flex items-center gap-3.5 rounded-xl border border-dashed border-[color:var(--color-paper-border)] bg-[color:var(--color-paper-elevated)] px-4 py-4 cursor-pointer min-h-[44px]"
                            role="button"
                            tabindex="0"
                            aria-label="Pilih atau seret file PDF dokumen pendukung, opsional. Maksimal 5MB."
                        >
                            <div class="tk-pdf-icon-box w-10 h-10 rounded-[10px] bg-[color:var(--color-paper-elevated)] border border-[color:var(--color-paper-border)] flex items-center justify-center flex-shrink-0">
                                <svg class="w-[18px] h-[18px] text-[color:var(--color-ink-faint)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div id="tkPdfText" class="tk-pdf-text text-[0.8rem] font-bold text-[color:var(--color-ink-muted)] truncate">Klik atau seret file PDF ke sini</div>
                                <div id="tkPdfSub" class="text-[0.68rem] text-[color:var(--color-ink-faint)] mt-0.5">Format PDF &bull; Maksimal 5MB</div>
                            </div>
                            <span id="tkPdfBrowsePill" class="flex-shrink-0 text-[0.65rem] font-extrabold tracking-wider uppercase px-2.5 py-1 rounded-md bg-[color:var(--color-paper-elevated)] border border-[color:var(--color-paper-border)] text-[color:var(--color-ink-faint)]">Browse</span>
                            <button type="button" id="tkRemovePdfBtn"
                                    class="hidden flex-shrink-0 w-7 h-7 rounded-full border items-center justify-center transition-colors duration-200 focus-visible:outline-none"
                                    aria-label="Hapus file PDF">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <input
                            type="file"
                            id="file_pdf"
                            name="file_pdf"
                            accept=".pdf"
                            class="sr-only"
                            aria-describedby="tkPdfSub{{ $errors->has('file_pdf') ? ' pdf-error' : '' }}"
                            @if ($errors->has('file_pdf')) aria-invalid="true" @endif
                        >

                        <p id="tkPdfClientError" role="alert" class="hidden mt-2 flex items-center gap-1.5 text-[0.73rem] font-semibold" style="color: var(--color-accent-500);">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="tkPdfClientErrorText"></span>
                        </p>

                        @error('file_pdf')
                            <p id="pdf-error" class="mt-2 flex items-center gap-1.5 text-[0.73rem] font-semibold" style="color: var(--color-accent-500);">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </section>

            {{-- ============ INFORMASI KARYA ============ --}}
            <section aria-labelledby="tk-info-heading" class="tk-card relative rounded-[20px] border border-white/[0.07] bg-white/[0.03] overflow-hidden shadow-[0_25px_60px_-35px_rgba(0,0,0,0.8)]">
                <div class="flex items-center gap-3 px-6 py-[18px] border-b border-white/[0.07]">
                    <div class="w-9 h-9 rounded-[10px] bg-red-600/[0.1] border border-red-600/[0.2] flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 id="tk-info-heading" class="text-[0.85rem] font-extrabold text-[#f5f5f5]">Informasi Karya</h2>
                        <p class="text-[0.7rem] text-white/[0.25] mt-0.5">Lengkapi detail karya kamu</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- JUDUL --}}
                        <div>
                            <label for="title" class="block text-[0.7rem] font-bold tracking-wider uppercase mb-2" style="color: var(--color-ink-muted);">
                                Judul Karya <span class="ml-0.5" style="color: var(--color-accent-600);">*</span>
                            </label>
                            <div class="relative group">
                                <svg class="tk-title-icon pointer-events-none absolute left-[14px] top-1/2 -translate-y-1/2 w-[15px] h-[15px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    placeholder="Contoh: Poster Hari Kemerdekaan 2025"
                                    autofocus
                                    required
                                    maxlength="255"
                                    aria-required="true"
                                    @if ($errors->has('title')) aria-invalid="true" aria-describedby="title-error" @endif
                                    class="tk-title-input {{ $errors->has('title') ? 'has-error' : '' }} w-full min-h-[44px] rounded-[11px] border-[1.5px] pl-[42px] pr-3.5 py-3 text-[0.85rem] font-medium outline-none"
                                >
                            </div>
                            @error('title')
                                <p id="title-error" class="mt-2 flex items-center gap-1.5 text-[0.73rem] font-semibold" style="color: var(--color-accent-500);">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- KATEGORI --}}
                                {{-- KATEGORI --}}
        <div>
            <label for="category_id" class="block text-[0.7rem] font-bold tracking-wider uppercase mb-2" style="color: var(--color-ink-muted);">
                Kategori <span class="ml-0.5" style="color: var(--color-accent-600);">*</span>
            </label>
            <div class="relative group">
                <svg class="tk-category-icon pointer-events-none absolute left-[14px] top-1/2 -translate-y-1/2 w-[15px] h-[15px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <select
                    id="category_id"
                    name="category_id"
                    required
                    aria-required="true"
                    @if ($categoriesEmpty) disabled @endif
                    @if ($errors->has('category_id')) aria-invalid="true" aria-describedby="category-error" @endif
                    class="tk-category-select {{ $errors->has('category_id') ? 'has-error' : '' }} w-full min-h-[44px] appearance-none rounded-[11px] border-[1.5px] pl-[42px] pr-10 py-3 text-[0.85rem] font-medium outline-none cursor-pointer"
                >
                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>
                        @if($categoriesEmpty)
                            &#9888; Belum ada kategori
                        @else
                            &mdash; Pilih Kategori &mdash;
                        @endif
                    </option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <svg class="tk-category-chevron pointer-events-none absolute right-[13px] top-1/2 -translate-y-1/2 w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            @error('category_id')
                <p id="category-error" class="mt-2 flex items-center gap-1.5 text-[0.73rem] font-semibold" style="color: var(--color-accent-500);">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
                    </div>

                    {{-- DESKRIPSI --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="description" class="block text-[0.7rem] font-bold tracking-wider uppercase" style="color: var(--color-ink-muted);">
                                Deskripsi <span class="ml-0.5" style="color: var(--color-accent-600);">*</span>
                            </label>
                            <span id="tkDescCount" class="text-[0.65rem] tabular-nums" style="color: var(--color-ink-faint);" aria-hidden="true">0 karakter</span>
                        </div>
                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            placeholder="Ceritakan konsep, proses kreatif, tools yang digunakan, dan pesan di balik karya ini..."
                            required
                            aria-required="true"
                            @if ($errors->has('description')) aria-invalid="true" aria-describedby="description-error" @endif
                            class="tk-description-textarea {{ $errors->has('description') ? 'has-error' : '' }} w-full min-h-[140px] rounded-[11px] border-[1.5px] px-3.5 py-3 text-[0.85rem] font-medium leading-relaxed outline-none resize-y"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p id="description-error" class="mt-2 flex items-center gap-1.5 text-[0.73rem] font-semibold" style="color: var(--color-accent-500);">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </section>

        </div>

        {{-- TIPS --}}
        <div class="rounded-[14px] border border-red-600/[0.15] bg-red-600/[0.05] px-[18px] py-[18px]">
            <div class="flex items-center gap-1.5 text-[0.7rem] font-extrabold tracking-[1.5px] uppercase text-red-500/[0.7] mb-3">
                <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tips Upload
            </div>
            <ul class="space-y-2">
                <li class="flex items-start gap-2 text-[0.75rem] text-white/[0.3] leading-relaxed">
                    <span class="mt-[7px] w-1 h-1 rounded-full bg-red-600 flex-shrink-0" aria-hidden="true"></span>
                    Gunakan resolusi minimal <strong class="text-white/[0.45] font-semibold">800&times;600px</strong> untuk tampilan terbaik.
                </li>
                <li class="flex items-start gap-2 text-[0.75rem] text-white/[0.3] leading-relaxed">
                    <span class="mt-[7px] w-1 h-1 rounded-full bg-red-600 flex-shrink-0" aria-hidden="true"></span>
                    Format yang diterima: <strong class="text-white/[0.45] font-semibold">JPG, JPEG, PNG</strong> &mdash; maksimal 2MB.
                </li>
                <li class="flex items-start gap-2 text-[0.75rem] text-white/[0.3] leading-relaxed">
                    <span class="mt-[7px] w-1 h-1 rounded-full bg-red-600 flex-shrink-0" aria-hidden="true"></span>
                    Isi semua field bertanda <span class="text-red-600 font-bold">*</span> agar karya berhasil disimpan.
                </li>
            </ul>
        </div>

        {{-- ACTIONS --}}
        <div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button
                    type="submit"
                    id="tkSubmitBtn"
                    @if ($categoriesEmpty) disabled @endif
                    class="order-1 sm:order-2 sm:flex-1 min-h-[44px] inline-flex items-center justify-center gap-2.5 rounded-xl bg-red-600 px-6 py-[15px] text-[0.9rem] font-extrabold text-white tracking-wide shadow-[0_4px_20px_rgba(220,38,38,0.3)] transition-all duration-300 hover:bg-red-500 hover:-translate-y-0.5 hover:shadow-[0_10px_40px_rgba(220,38,38,0.45)] active:translate-y-0 active:scale-[0.99] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600/[0.4] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-600 disabled:hover:translate-y-0 disabled:hover:shadow-[0_4px_20px_rgba(220,38,38,0.3)]"
                >
                    <svg id="tkSubmitIcon" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span id="tkSubmitText">Simpan Karya</span>
                </button>

                <a href="{{ route('siswa.dashboard') }}"
                   class="order-2 sm:order-1 min-h-[44px] inline-flex items-center justify-center rounded-xl border border-white/[0.08] bg-white/[0.03] px-6 py-[15px] text-[0.85rem] font-bold text-white/[0.4] transition-colors duration-200 hover:text-white/[0.7] hover:border-white/[0.15] hover:bg-white/[0.05] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600/[0.4]">
                    Batal
                </a>
            </div>
            @if($categoriesEmpty)
                <p class="mt-2.5 text-center sm:text-right text-[0.7rem] text-white/[0.25]">
                    Tombol simpan nonaktif sampai kategori tersedia.
                </p>
            @endif
        </div>

    </form>

    <div class="pointer-events-none absolute -z-10 bottom-0 -left-16 w-80 h-80 bg-red-600/[0.05] rounded-full blur-3xl" aria-hidden="true"></div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var MAX_IMAGE_SIZE = 2 * 1024 * 1024; // 2MB
    var MAX_PDF_SIZE   = 5 * 1024 * 1024; // 5MB
    var ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png'];

    function formatBytes(bytes) {
        if (!bytes) return '0 KB';
        var kb = bytes / 1024;
        if (kb < 1024) return Math.round(kb) + ' KB';
        return (kb / 1024).toFixed(1) + ' MB';
    }

    function shake(el) {
        el.classList.remove('tk-shake');
        // force reflow so the animation can restart
        void el.offsetWidth;
        el.classList.add('tk-shake');
    }

    // Live region untuk pengumuman non-error ke pembaca layar (mis. file berhasil dipilih/dihapus).
    // Reset dulu ke string kosong lalu isi lagi sesaat kemudian, supaya pesan yang identik
    // berturut-turut tetap diumumkan ulang oleh screen reader.
    var srAnnouncer = document.getElementById('tkSrAnnouncer');
    function announce(msg) {
        if (!srAnnouncer) return;
        srAnnouncer.textContent = '';
        window.setTimeout(function () {
            srAnnouncer.textContent = msg;
        }, 50);
    }

    /* ================= IMAGE ================= */
    var imageInput        = document.getElementById('image');
    var imageDropzone      = document.getElementById('tkImageDropzone');
    var imagePrompt        = document.getElementById('tkImagePrompt');
    var imageProcessing    = document.getElementById('tkImageProcessing');
    var imagePreviewWrap   = document.getElementById('tkImagePreviewWrap');
    var imagePreviewImg    = document.getElementById('tkImagePreviewImg');
    var imageFileName      = document.getElementById('tkImageFileName');
    var imageFileSize      = document.getElementById('tkImageFileSize');
    var changeImageBtn     = document.getElementById('tkChangeImageBtn');
    var removeImageBtn     = document.getElementById('tkRemoveImageBtn');
    var imageClientError   = document.getElementById('tkImageClientError');
    var imageClientErrorTx = document.getElementById('tkImageClientErrorText');
    var imageDropzoneDefaultLabel = imageDropzone.getAttribute('aria-label');
    var imageDragCounter   = 0;

    function clearImageClientError() {
        imageClientError.classList.add('hidden');
        imageClientErrorTx.textContent = '';
        imageDropzone.classList.remove('has-error');
    }

    function showImageClientError(msg) {
        imageClientErrorTx.textContent = msg;
        imageClientError.classList.remove('hidden');
        imageDropzone.classList.add('has-error');
        shake(imageDropzone);
    }

    function validateImageFile(file) {
        if (ALLOWED_IMAGE_TYPES.indexOf(file.type) === -1) {
            return 'Format file harus JPG, JPEG, atau PNG.';
        }
        if (file.size > MAX_IMAGE_SIZE) {
            return 'Ukuran file maksimal 2MB.';
        }
        return null;
    }

    function resetImage() {
        imageInput.value = '';
        imagePreviewWrap.classList.add('hidden');
        imagePreviewWrap.classList.remove('tk-fade-in');
        imagePrompt.classList.remove('hidden');
        removeImageBtn.classList.add('hidden');
        removeImageBtn.classList.remove('flex');
        imageDropzone.classList.remove('has-preview');
        imageDropzone.setAttribute('aria-label', imageDropzoneDefaultLabel);
        clearImageClientError();
    }

    function showImagePreview(file) {
        imageProcessing.classList.remove('hidden');
        var reader = new FileReader();
        reader.onload = function (e) {
            imagePreviewImg.src = e.target.result;
            imagePreviewImg.alt = 'Pratinjau: ' + file.name;
            imagePrompt.classList.add('hidden');
            imageProcessing.classList.add('hidden');
            imagePreviewWrap.classList.remove('hidden');
            imagePreviewWrap.classList.add('tk-fade-in');
            imageFileName.textContent = file.name;
            imageFileSize.textContent = '\u2022 ' + formatBytes(file.size);
            removeImageBtn.classList.remove('hidden');
            removeImageBtn.classList.add('flex');
            imageDropzone.classList.add('has-preview');
            imageDropzone.setAttribute('aria-label', 'Gambar terpilih: ' + file.name + ', ' + formatBytes(file.size) + '. Klik untuk mengganti gambar, atau gunakan tombol hapus.');
            announce('Gambar dipilih: ' + file.name + ', ' + formatBytes(file.size) + '.');
        };
        reader.onerror = function () {
            imageProcessing.classList.add('hidden');
            showImageClientError('Gagal membaca file. Silakan coba lagi.');
        };
        reader.readAsDataURL(file);
    }

    function handleImageFile(file) {
        if (!file) return;
        clearImageClientError();
        var error = validateImageFile(file);
        if (error) {
            showImageClientError(error);
            imageInput.value = '';
            return;
        }
        showImagePreview(file);
    }

    imageInput.addEventListener('change', function () {
        if (imageInput.files && imageInput.files[0]) handleImageFile(imageInput.files[0]);
    });

    // Guard: abaikan klik yang berasal dari tombol asli di dalamnya (Ganti/Hapus)
    // supaya dialog file tidak terbuka dobel akibat event yang menggelembung (bubbling).
    imageDropzone.addEventListener('click', function (e) {
        if (e.target.closest('button')) return;
        imageInput.click();
    });

    imageDropzone.addEventListener('keydown', function (e) {
        if (e.target.closest('button')) return;
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            imageInput.click();
        }
    });

    if (changeImageBtn) {
        changeImageBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            imageInput.click();
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            resetImage();
            announce('Gambar dihapus.');
            imageDropzone.focus();
        });
    }

    // Drag counter mencegah state "is-dragover" berkedip saat pointer melintasi
    // elemen anak (dragenter/dragleave saling menimpa pada elemen bersarang).
    imageDropzone.addEventListener('dragenter', function (e) {
        e.preventDefault();
        e.stopPropagation();
        imageDragCounter++;
        imageDropzone.classList.add('is-dragover');
    });

    imageDropzone.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    imageDropzone.addEventListener('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        imageDragCounter = Math.max(0, imageDragCounter - 1);
        if (imageDragCounter === 0) {
            imageDropzone.classList.remove('is-dragover');
        }
    });

    imageDropzone.addEventListener('dragend', function () {
        imageDragCounter = 0;
        imageDropzone.classList.remove('is-dragover');
    });

    imageDropzone.addEventListener('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        imageDragCounter = 0;
        imageDropzone.classList.remove('is-dragover');
        var file = e.dataTransfer.files[0];
        if (file) {
            var dt = new DataTransfer();
            dt.items.add(file);
            imageInput.files = dt.files;
            handleImageFile(file);
        }
    });

    /* ================= PDF ================= */
    var pdfInput        = document.getElementById('file_pdf');
    var pdfArea          = document.getElementById('tkPdfArea');
    var pdfText          = document.getElementById('tkPdfText');
    var pdfSub           = document.getElementById('tkPdfSub');
    var pdfBrowsePill    = document.getElementById('tkPdfBrowsePill');
    var removePdfBtn     = document.getElementById('tkRemovePdfBtn');
    var pdfClientError   = document.getElementById('tkPdfClientError');
    var pdfClientErrorTx = document.getElementById('tkPdfClientErrorText');
    var pdfDefaultText   = pdfText.textContent;
    var pdfDefaultSub    = pdfSub.textContent;
    var pdfAreaDefaultLabel = pdfArea.getAttribute('aria-label');
    var pdfDragCounter   = 0;

    function clearPdfClientError() {
        pdfClientError.classList.add('hidden');
        pdfClientErrorTx.textContent = '';
        pdfArea.classList.remove('has-error');
    }

    function showPdfClientError(msg) {
        pdfClientErrorTx.textContent = msg;
        pdfClientError.classList.remove('hidden');
        pdfArea.classList.add('has-error');
        shake(pdfArea);
    }

    function validatePdfFile(file) {
        if (file.type !== 'application/pdf') {
            return 'File harus berformat PDF.';
        }
        if (file.size > MAX_PDF_SIZE) {
            return 'Ukuran file maksimal 5MB.';
        }
        return null;
    }

    function resetPdf() {
        pdfInput.value = '';
        pdfText.textContent = pdfDefaultText;
        pdfSub.textContent = pdfDefaultSub;
        pdfArea.classList.remove('has-file');
        pdfArea.setAttribute('aria-label', pdfAreaDefaultLabel);
        pdfBrowsePill.classList.remove('hidden');
        removePdfBtn.classList.add('hidden');
        removePdfBtn.classList.remove('flex');
        clearPdfClientError();
    }

    function handlePdfFile(file) {
        if (!file) return;
        clearPdfClientError();
        var error = validatePdfFile(file);
        if (error) {
            showPdfClientError(error);
            pdfInput.value = '';
            return;
        }
        pdfText.textContent = file.name;
        pdfSub.textContent = formatBytes(file.size);
        pdfArea.classList.add('has-file');
        pdfArea.setAttribute('aria-label', 'PDF terpilih: ' + file.name + ', ' + formatBytes(file.size) + '. Klik untuk mengganti file, atau gunakan tombol hapus.');
        pdfBrowsePill.classList.add('hidden');
        removePdfBtn.classList.remove('hidden');
        removePdfBtn.classList.add('flex');
        announce('File PDF dipilih: ' + file.name + ', ' + formatBytes(file.size) + '.');
    }

    // Guard yang sama seperti dropzone gambar: klik/keydown dari tombol Hapus
    // yang bersarang tidak boleh ikut membuka dialog file lagi.
    pdfArea.addEventListener('click', function (e) {
        if (e.target.closest('button')) return;
        pdfInput.click();
    });

    pdfArea.addEventListener('keydown', function (e) {
        if (e.target.closest('button')) return;
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            pdfInput.click();
        }
    });

    pdfInput.addEventListener('change', function () {
        if (pdfInput.files && pdfInput.files[0]) handlePdfFile(pdfInput.files[0]);
    });

    if (removePdfBtn) {
        removePdfBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            resetPdf();
            announce('File PDF dihapus.');
            pdfArea.focus();
        });
    }

    // Drag & drop untuk PDF (konsisten dengan dropzone gambar), memakai pola
    // counter yang sama supaya state hover tidak berkedip.
    pdfArea.addEventListener('dragenter', function (e) {
        e.preventDefault();
        e.stopPropagation();
        pdfDragCounter++;
        pdfArea.classList.add('is-dragover');
    });

    pdfArea.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    pdfArea.addEventListener('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        pdfDragCounter = Math.max(0, pdfDragCounter - 1);
        if (pdfDragCounter === 0) {
            pdfArea.classList.remove('is-dragover');
        }
    });

    pdfArea.addEventListener('dragend', function () {
        pdfDragCounter = 0;
        pdfArea.classList.remove('is-dragover');
    });

    pdfArea.addEventListener('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        pdfDragCounter = 0;
        pdfArea.classList.remove('is-dragover');
        var file = e.dataTransfer.files[0];
        if (file) {
            var dt = new DataTransfer();
            dt.items.add(file);
            pdfInput.files = dt.files;
            handlePdfFile(file);
        }
    });

    /* ================= DESCRIPTION COUNTER ================= */
    var descriptionEl = document.getElementById('description');
    var descCount      = document.getElementById('tkDescCount');

    function updateDescCount() {
        var len = descriptionEl.value.length;
        descCount.textContent = len + ' karakter';
    }
    if (descriptionEl && descCount) {
        descriptionEl.addEventListener('input', updateDescCount);
        updateDescCount();
    }

    /* ================= ERROR ALERT SCROLL ================= */
    // Script ini dirender di akhir <body> (lihat @stack('scripts') pada layout),
    // jadi DOM sudah siap saat baris ini dieksekusi — tidak perlu menunggu
    // event 'load' (yang baru terjadi setelah semua gambar/font selesai dimuat
    // dan bisa membuat auto-scroll terasa lambat/tersendat).
    var errorAlert = document.getElementById('tk-error-alert');
    if (errorAlert) {
        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /* ================= SUBMIT ================= */
    var form        = document.getElementById('tkForm');
    var submitBtn   = document.getElementById('tkSubmitBtn');
    var submitText  = document.getElementById('tkSubmitText');
    var submitIcon  = document.getElementById('tkSubmitIcon');
    var submitBtnInitiallyDisabled = submitBtn.disabled;

    form.addEventListener('submit', function (e) {
        if (!imageInput.files || imageInput.files.length === 0) {
            e.preventDefault();
            showImageClientError('Gambar thumbnail wajib diunggah sebelum menyimpan.');
            imageDropzone.scrollIntoView({ behavior: 'smooth', block: 'center' });
            imageDropzone.focus();
            return;
        }

        if (submitBtn.disabled) {
            e.preventDefault();
            return;
        }

        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
        submitIcon.classList.add('animate-spin');
    });

    // Fix bfcache: kalau user menekan tombol Back browser setelah submit gagal
    // (mis. balik dari halaman lain), event 'pageshow' dengan persisted=true
    // mengembalikan tombol simpan ke kondisi semula alih-alih terjebak di
    // teks "Menyimpan..." selamanya.
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            submitBtn.disabled = submitBtnInitiallyDisabled;
            submitText.textContent = 'Simpan Karya';
            submitIcon.classList.remove('animate-spin');
        }
    });
})();
</script>
@endpush