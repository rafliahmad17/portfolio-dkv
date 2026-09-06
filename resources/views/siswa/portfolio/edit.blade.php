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
<style>
    :root {
        --hairline:        rgba(25,24,22,0.10);
        --hairline-strong: rgba(25,24,22,0.18);
        --surface-sunk:    #F6F1E7;
        --oxblood-soft:    rgba(122,46,46,0.08);
        --oxblood-border:  rgba(122,46,46,0.26);
        --oxblood-ink:     #6E2A2A;
        --shadow-paper:    0 1px 2px rgba(25,24,22,0.04), 0 16px 34px -20px rgba(25,24,22,0.16);
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

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--hairline-strong); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--oxblood-border); }

    a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible, select:focus-visible, [tabindex]:focus-visible {
        outline: 2px solid var(--color-accent-600);
        outline-offset: 3px;
        border-radius: 6px;
    }

    .skip-link {
        position: fixed; top: -100px; left: 16px; z-index: 100;
        background: var(--color-ink); color: var(--color-paper);
        padding: 10px 18px; border-radius: 8px;
        font-family: var(--font-sans); font-size: 0.8rem; font-weight: 600;
        text-decoration: none; transition: top 0.2s ease;
    }
    .skip-link:focus { top: 16px; }

    /* ── SIDEBAR + TOPBAR + MAIN-CONTENT ──
       Dipakai dari resources/css/components/dashboard-shell-siswa.css
       (identik dengan siswa/dashboard.blade.php & siswa/profile/edit.blade.php).
       Yang tetap di sini: .badge-pill (elemen topbar kanan khusus halaman
       ini — menampilkan tanggal, bukan NIS/role seperti di profile/edit). */
    .badge-pill {
        display: inline-flex; align-items: center; gap: 8px;
        border: 1px solid var(--hairline-strong); border-radius: 30px; padding: 6px 14px;
        font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; color: var(--color-ink-muted); letter-spacing: 0.5px; white-space: nowrap;
    }

    /* ── BACK BUTTON ── */
    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        color: var(--color-ink-faint); font-family: var(--font-sans); font-size: 0.78rem; font-weight: 700;
        text-decoration: none; padding: 9px 14px; min-height: 40px;
        border: 1px solid var(--hairline-strong); border-radius: 10px;
        background: var(--color-paper-elevated);
        transition: all 0.22s ease; margin-bottom: 26px;
        width: fit-content;
    }
    .btn-back:hover { color: var(--oxblood-ink); border-color: var(--oxblood-border); background: var(--oxblood-soft); }
    .btn-back svg { width: 14px; height: 14px; transition: transform 0.22s ease; flex-shrink: 0; }
    .btn-back:hover svg { transform: translateX(-3px); }

    /* ── PAGE HEADER ── */
    .page-header-block { margin-bottom: 30px; }
    .edit-eyebrow {
        display: flex; align-items: center; gap: 6px;
        font-family: var(--font-mono); font-size: 0.68rem; font-weight: 700;
        letter-spacing: 3px; text-transform: uppercase; color: var(--color-accent-600);
        margin-bottom: 12px;
    }
    .form-headline {
        font-family: var(--font-serif); font-size: clamp(1.6rem, 2.6vw, 2.15rem); font-weight: 600;
        letter-spacing: -0.4px; line-height: 1.16; color: var(--color-ink); margin-bottom: 8px;
    }
    .form-headline .hl { color: var(--color-accent-600); }
    .form-sub { font-family: var(--font-sans); font-size: 0.92rem; color: var(--color-ink-muted); line-height: 1.6; max-width: 62ch; }

    /* ── ERROR SUMMARY ── */
    .error-alert {
        background: color-mix(in srgb, var(--color-accent-600) 8%, transparent);
        border: 1px solid color-mix(in srgb, var(--color-accent-600) 30%, transparent);
        border-left: 3px solid var(--color-accent-600); border-radius: 14px;
        padding: 16px 20px; margin-bottom: 24px;
    }
    .error-alert-title { font-family: var(--font-sans); font-size: 0.8rem; font-weight: 800; color: var(--color-accent-700); display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
    .error-alert-title svg { width: 16px; height: 16px; flex-shrink: 0; }
    .error-alert-list { list-style: none; }
    .error-alert-list li { font-family: var(--font-sans); font-size: 0.75rem; color: var(--color-ink-muted); padding: 3px 0; display: flex; align-items: flex-start; gap: 7px; }
    .error-alert-list li::before { content: '✕'; color: var(--color-accent-600); font-weight: 900; font-size: 0.65rem; margin-top: 2px; flex-shrink: 0; }

    /* ── FORM GRID ── */
    .edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; align-items: start; }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--color-paper-elevated); border: 1px solid var(--hairline);
        border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-paper);
        position: relative;
    }
    .form-card-header { padding: 18px 24px; border-bottom: 1px solid var(--hairline); display: flex; align-items: center; gap: 12px; }
    .card-header-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--oxblood-soft); border: 1px solid var(--oxblood-border);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .card-header-icon svg { width: 16px; height: 16px; color: var(--color-accent-600); }
    .card-header-title { font-family: var(--font-serif); font-size: 0.95rem; font-weight: 600; color: var(--color-ink); }
    .card-header-sub { font-family: var(--font-mono); font-size: 0.68rem; color: var(--color-ink-faint); margin-top: 2px; }
    .form-card-body { padding: 24px; }

    /* ── FIELD ── */
    .field-wrap { margin-bottom: 20px; }
    .field-wrap:last-child { margin-bottom: 0; }
    .field-label {
        display: block; font-family: var(--font-mono); font-size: 0.68rem; font-weight: 500;
        letter-spacing: 0.1em; text-transform: uppercase; color: var(--color-ink-muted); margin-bottom: 9px;
    }
    .field-label .req { color: var(--color-accent-600); margin-left: 3px; }
    .field-label .opt-hint { color: var(--color-ink-faint); font-weight: 400; text-transform: none; letter-spacing: 0; margin-left: 6px; font-size: 0.65rem; }

    .input-wrap { position: relative; }
    .input-icon {
        position: absolute; top: 50%; left: 14px; transform: translateY(-50%);
        width: 15px; height: 15px; color: var(--color-ink-faint);
        pointer-events: none; transition: color 0.22s ease;
    }
    .input-wrap:focus-within .input-icon { color: var(--color-accent-600); }

    .form-input, .form-textarea, .form-select {
        width: 100%; font-family: var(--font-sans); font-size: 0.88rem; font-weight: 500;
        color: var(--color-ink); background: var(--color-paper);
        border: 1.5px solid var(--color-paper-border); border-radius: var(--radius-sm, 8px);
        padding: 12px 14px 12px 42px; outline: none; min-height: 46px;
        transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-textarea { padding-left: 16px; resize: vertical; min-height: 130px; line-height: 1.65; }
    .form-input::placeholder, .form-textarea::placeholder { color: var(--color-ink-faint); font-weight: 400; }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        border-color: var(--color-accent-600);
        background: color-mix(in srgb, var(--color-accent-600) 4%, var(--color-paper));
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 16%, transparent);
    }
    .form-input.is-error, .form-textarea.is-error, .form-select.is-error {
        border-color: var(--color-accent-500) !important;
        background: color-mix(in srgb, var(--color-accent-500) 6%, transparent) !important;
    }

    .form-select { appearance: none; -webkit-appearance: none; cursor: pointer; padding-right: 40px; color: var(--color-ink-muted); }
    .form-select:focus { color: var(--color-ink); }
    .form-select option { background: var(--color-paper-elevated); color: var(--color-ink); }
    .select-arrow {
        position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
        width: 14px; height: 14px; color: var(--color-ink-faint); pointer-events: none;
    }

    .field-error {
        margin-top: 7px; font-family: var(--font-sans); font-size: 0.76rem; font-weight: 600;
        color: var(--color-accent-700); display: flex; align-items: center; gap: 6px;
    }
    .field-error svg { width: 12px; height: 12px; flex-shrink: 0; }

    /* ── IMAGE DROP ZONE ── */
    .drop-zone {
        position: relative;
        border: 2px dashed var(--color-paper-border);
        border-radius: 16px;
        min-height: 320px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        cursor: pointer; overflow: hidden;
        transition: all 0.3s ease;
        background: var(--surface-sunk);
    }
    .drop-zone:hover, .drop-zone.drag-over {
        border-color: var(--color-accent-600);
        background: color-mix(in srgb, var(--color-accent-600) 4.5%, transparent);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 12%, transparent);
    }
    .drop-zone.has-preview { border-color: color-mix(in srgb, var(--color-accent-600) 40%, transparent); border-style: solid; }

    @keyframes borderPulse {
        0%, 100% { border-color: var(--color-accent-600); box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-accent-600) 12%, transparent); }
        50%      { border-color: var(--color-accent-500); box-shadow: 0 0 0 5px color-mix(in srgb, var(--color-accent-600) 18%, transparent); }
    }
    .drop-zone.drag-over { animation: borderPulse 1.1s ease-in-out infinite; }

    .drop-zone-prompt {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 14px; padding: 36px 24px; text-align: center;
    }
    .drop-icon-wrap {
        width: 68px; height: 68px; border-radius: 20px;
        background: var(--color-paper-elevated); border: 1px solid var(--color-paper-border);
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }
    .drop-zone:hover .drop-icon-wrap, .drop-zone.drag-over .drop-icon-wrap {
        background: var(--color-accent-50); border-color: var(--color-accent-200);
    }
    .drop-icon-wrap svg { width: 30px; height: 30px; color: var(--color-ink-faint); transition: color 0.3s ease; }
    .drop-zone:hover .drop-icon-wrap svg, .drop-zone.drag-over .drop-icon-wrap svg { color: var(--color-accent-600); }
    .drop-title { font-family: var(--font-sans); font-size: 0.87rem; font-weight: 800; color: var(--color-ink-muted); transition: color 0.3s ease; }
    .drop-zone:hover .drop-title, .drop-zone.drag-over .drop-title { color: var(--color-ink); }
    .drop-sub { font-family: var(--font-sans); font-size: 0.72rem; color: var(--color-ink-faint); line-height: 1.6; }
    .drop-types { display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; }
    .drop-type-pill {
        font-family: var(--font-mono); font-size: 0.62rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
        padding: 3px 9px; border-radius: 20px;
        background: var(--color-paper-elevated); border: 1px solid var(--color-paper-border); color: var(--color-ink-faint);
    }

    /* ── IMAGE PREVIEW ── */
    .preview-wrap { position: absolute; inset: 0; }
    .preview-img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .preview-overlay {
        position: absolute; inset: 0;
        background: color-mix(in srgb, var(--color-ink) 58%, transparent);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 10px; opacity: 0; transition: opacity 0.3s ease;
    }
    .drop-zone:hover .preview-overlay, .drop-zone:focus-within .preview-overlay { opacity: 1; }
    .preview-change-btn {
        display: inline-flex; align-items: center; gap: 7px;
        background: color-mix(in srgb, var(--color-accent-600) 92%, transparent);
        color: var(--color-paper); padding: 10px 20px; border-radius: 10px;
        font-family: var(--font-sans); font-size: 0.8rem; font-weight: 800;
        cursor: pointer; border: none;
        box-shadow: 0 4px 22px color-mix(in srgb, var(--color-accent-600) 40%, transparent);
        transition: all 0.22s ease;
    }
    .preview-change-btn:hover { background: var(--color-accent-700); }
    .preview-change-btn svg { width: 14px; height: 14px; }
    .preview-name {
        font-family: var(--font-sans); font-size: 0.72rem; color: color-mix(in srgb, var(--color-paper) 75%, transparent);
        font-weight: 600; max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .preview-badge {
        position: absolute; top: 12px; left: 12px;
        background: color-mix(in srgb, var(--color-ink) 78%, transparent); backdrop-filter: blur(10px);
        border: 1px solid color-mix(in srgb, var(--color-accent-500) 45%, transparent);
        color: var(--color-accent-200); padding: 4px 10px; border-radius: 20px;
        font-family: var(--font-mono); font-size: 0.63rem; font-weight: 700;
        letter-spacing: 0.8px; text-transform: uppercase;
    }
    .existing-badge {
        position: absolute; top: 12px; right: 12px;
        background: color-mix(in srgb, var(--color-ink) 68%, transparent); backdrop-filter: blur(10px);
        border: 1px solid color-mix(in srgb, var(--color-paper) 22%, transparent);
        color: color-mix(in srgb, var(--color-paper) 78%, transparent); padding: 4px 10px; border-radius: 20px;
        font-family: var(--font-mono); font-size: 0.6rem; font-weight: 600;
        letter-spacing: 0.5px; text-transform: uppercase;
    }

    /* ── EXISTING FILE BOX (dipakai untuk indikator PDF lama) ── */
    .current-file-box {
        display: flex; align-items: center; gap: 12px;
        background: var(--surface-sunk); border: 1px solid var(--hairline);
        border-radius: 12px; padding: 12px 14px; margin-bottom: 12px;
    }
    .current-file-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--oxblood-soft); border: 1px solid var(--oxblood-border);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .current-file-icon svg { width: 17px; height: 17px; color: var(--color-accent-600); }
    .current-file-text { font-family: var(--font-sans); font-size: 0.78rem; font-weight: 700; color: var(--color-ink); }
    .current-file-sub { font-family: var(--font-sans); font-size: 0.7rem; color: var(--color-ink-faint); margin-top: 1px; line-height: 1.5; }

    /* ── PDF UPLOAD CONTROL ── */
    .pdf-upload-area {
        border: 1.5px dashed var(--color-paper-border); border-radius: 12px; padding: 15px;
        display: flex; align-items: center; gap: 14px;
        cursor: pointer; transition: all 0.25s ease;
        background: var(--surface-sunk); min-height: 44px;
    }
    .pdf-upload-area:hover, .pdf-upload-area:focus-within {
        border-color: color-mix(in srgb, var(--color-accent-600) 45%, transparent);
        background: color-mix(in srgb, var(--color-accent-600) 4%, transparent);
    }
    .pdf-upload-area.has-file {
        border-style: solid; border-color: color-mix(in srgb, var(--color-accent-600) 40%, transparent);
        background: color-mix(in srgb, var(--color-accent-600) 5%, transparent);
    }
    .pdf-icon-box {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--color-paper-elevated); border: 1px solid var(--color-paper-border);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        transition: all 0.25s ease;
    }
    .pdf-upload-area:hover .pdf-icon-box, .pdf-upload-area.has-file .pdf-icon-box {
        background: var(--oxblood-soft); border-color: var(--oxblood-border);
    }
    .pdf-icon-box svg { width: 18px; height: 18px; color: var(--color-ink-faint); transition: color 0.25s ease; }
    .pdf-upload-area:hover .pdf-icon-box svg, .pdf-upload-area.has-file .pdf-icon-box svg { color: var(--color-accent-600); }
    .pdf-text-main { font-family: var(--font-sans); font-size: 0.8rem; font-weight: 700; color: var(--color-ink-muted); transition: color 0.25s ease; }
    .pdf-upload-area:hover .pdf-text-main { color: var(--color-ink); }
    .pdf-upload-area.has-file .pdf-text-main { color: var(--oxblood-ink); }
    .pdf-text-sub { font-family: var(--font-sans); font-size: 0.68rem; color: var(--color-ink-faint); margin-top: 2px; }
    .pdf-browse-pill {
        font-family: var(--font-mono); font-size: 0.62rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;
        color: var(--color-ink-faint); background: var(--color-paper-elevated); border: 1px solid var(--color-paper-border);
        padding: 3px 9px; border-radius: 6px; flex-shrink: 0;
    }

    /* ── TIPS ── */
    .tips-card {
        background: var(--oxblood-soft); border: 1px solid var(--oxblood-border);
        border-radius: 14px; padding: 18px; margin-top: 16px;
    }
    .tips-title {
        font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700;
        letter-spacing: 1.5px; text-transform: uppercase; color: var(--oxblood-ink);
        margin-bottom: 12px; display: flex; align-items: center; gap: 7px;
    }
    .tips-title svg { width: 13px; height: 13px; }
    .tips-item {
        display: flex; align-items: flex-start; gap: 8px;
        font-family: var(--font-sans); font-size: 0.76rem; color: var(--color-ink-muted);
        line-height: 1.6; margin-bottom: 8px; font-weight: 500;
    }
    .tips-item:last-child { margin-bottom: 0; }
    .tips-item strong { color: var(--color-ink); }
    .tips-bullet { width: 4px; height: 4px; border-radius: 50%; background: var(--color-accent-600); margin-top: 7px; flex-shrink: 0; }

    /* ── INFO BANNER (dipakai .flash-note dari shell bersama) ── */
    .flash-note strong { color: var(--color-ink); }

    /* ── ACTIONS ── */
    .btn-submit {
        width: 100%; background: var(--color-accent-600); color: var(--color-paper); border: none;
        border-radius: 12px; padding: 15px 24px; min-height: 46px;
        font-family: var(--font-sans); font-size: 0.9rem; font-weight: 800;
        letter-spacing: 0.3px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        position: relative; overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px color-mix(in srgb, var(--color-accent-600) 30%, transparent);
    }
    .btn-submit::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, var(--color-accent-700), var(--color-accent-500));
        opacity: 0; transition: opacity 0.3s ease;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 40px color-mix(in srgb, var(--color-accent-600) 45%, transparent), 0 0 0 4px color-mix(in srgb, var(--color-accent-600) 15%, transparent); }
    .btn-submit:hover::before { opacity: 1; }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit span, .btn-submit svg { position: relative; z-index: 1; }
    .btn-submit svg { width: 18px; height: 18px; }
    .btn-submit:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

    .btn-plain-cancel {
        display: block; text-align: center; margin-top: 12px; min-height: 20px;
        font-family: var(--font-sans); font-size: 0.78rem; font-weight: 700;
        color: var(--color-ink-faint); text-decoration: none; transition: color 0.22s ease;
    }
    .btn-plain-cancel:hover { color: var(--oxblood-ink); }

    .form-actions-wrap { padding-top: 8px; border-top: 1px solid var(--hairline); margin-top: 4px; }

    /* ── PAGE FOOTER NOTE ── */
    .page-footer-note {
        margin-top: 48px; padding-top: 24px; border-top: 1px solid var(--hairline);
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
    }
    .page-footer-note span { font-family: var(--font-sans); font-size: 0.7rem; color: var(--color-ink-faint); }
    .page-footer-note strong { color: var(--color-ink-muted); }

    /* ── RESPONSIVE ── */
    @media (max-width: 980px) {
        .edit-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 860px) {
        .page-inner { padding: 26px 18px 50px; }
        .form-card-header { padding: 16px 18px; }
        .form-card-body { padding: 18px; }
        .drop-zone { min-height: 260px; }
    }
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>
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
                        <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK">
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
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" style="width:100%; height:100%; object-fit:cover;">
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
                                        src="{{ asset('storage/' . $portfolio->image_path) }}"
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
