{{-- resources/views/siswa/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya — DKV SMEKDA Portal')

{{-- Halaman ini punya sidebar + topbar sendiri (pola yang sama dengan
     siswa/dashboard.blade.php), jadi navbar/footer bawaan layout tidak
     dipakai di sini. --}}
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

    a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible, [tabindex]:focus-visible {
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

    /* ── SIDEBAR — sama seperti siswa/dashboard.blade.php, agar terasa
       satu sistem navigasi di seluruh Portal Siswa. ── */
    .sidebar {
        position: fixed; top: 0; left: 0; width: 280px; height: 100vh;
        background: var(--color-paper-elevated);
        border-right: 1px solid var(--hairline);
        display: flex; flex-direction: column;
        z-index: 50; overflow-y: auto;
    }
    .sidebar-logo { padding: 30px 26px 22px; border-bottom: 1px solid var(--hairline); }
    .logo-wordmark {
        font-family: var(--font-sans); font-size: 0.82rem; font-weight: 800;
        letter-spacing: 2.5px; text-transform: uppercase; color: var(--color-ink);
        display: flex; align-items: center; gap: 10px;
    }
    .logo-wordmark .dot { color: var(--color-accent-600); }
    .logo-mark {
        width: 34px; height: 34px; border-radius: 50%;
        border: 1px solid var(--hairline-strong); background: var(--surface-sunk);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; overflow: hidden; position: relative;
    }
    .logo-mark img { width: 100%; height: 100%; object-fit: contain; padding: 3px; }
    .logo-sub {
        font-family: var(--font-mono); font-size: 0.62rem; color: var(--color-ink-faint);
        margin-top: 6px; letter-spacing: 1.6px; text-transform: uppercase; padding-left: 40px;
    }

    .sidebar-profile { padding: 22px 26px; border-bottom: 1px solid var(--hairline); }
    .profile-avatar {
        width: 44px; height: 44px; border-radius: 14px;
        background: var(--color-accent-600);
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-serif); font-size: 1.05rem; font-weight: 700; color: var(--color-paper);
        flex-shrink: 0; overflow: hidden;
    }
    .profile-name {
        font-family: var(--font-sans); font-size: 0.85rem; font-weight: 700; color: var(--color-ink);
        line-height: 1.3; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .profile-nis { font-family: var(--font-mono); font-size: 0.68rem; color: var(--color-ink-faint); margin-bottom: 8px; letter-spacing: 0.3px; }
    .badge-role {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--oxblood-soft); border: 1px solid var(--oxblood-border);
        color: var(--oxblood-ink); padding: 3px 10px; border-radius: 20px;
        font-family: var(--font-mono); font-size: 0.62rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;
    }
    .badge-role-dot { width: 5px; height: 5px; background: var(--color-accent-600); border-radius: 50%; flex-shrink: 0; }

    .sidebar-nav { flex: 1; padding: 22px 16px; }
    .nav-label {
        font-family: var(--font-mono); font-size: 0.62rem; font-weight: 600; letter-spacing: 2px;
        text-transform: uppercase; color: var(--color-ink-faint); padding: 0 10px; margin-bottom: 10px; margin-top: 4px;
    }
    .nav-item {
        display: flex; align-items: center; gap: 14px; padding: 11px 12px; min-height: 44px;
        border-radius: 10px; font-family: var(--font-sans); font-size: 0.85rem; font-weight: 600;
        color: var(--color-ink-muted); text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        border: 1px solid transparent; margin-bottom: 2px; position: relative;
    }
    .nav-index { font-family: var(--font-mono); font-size: 0.68rem; font-weight: 500; color: var(--color-ink-faint); flex-shrink: 0; width: 16px; }
    .nav-item:hover { background: var(--surface-sunk); color: var(--color-ink); }
    .nav-item.active { color: var(--oxblood-ink); background: var(--oxblood-soft); border-color: var(--oxblood-border); }
    .nav-item.active .nav-index { color: var(--color-accent-600); }
    .nav-item.active::before {
        content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
        width: 3px; height: 20px; background: var(--color-accent-600); border-radius: 0 3px 3px 0;
    }

    .sidebar-footer { padding: 16px; border-top: 1px solid var(--hairline); }
    .btn-logout {
        width: 100%; display: flex; align-items: center; gap: 12px; padding: 11px 12px; min-height: 44px;
        border-radius: 10px; background: none; border: 1px solid transparent; color: var(--color-ink-muted);
        font-family: var(--font-sans); font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-logout:hover { color: var(--oxblood-ink); background: var(--oxblood-soft); border-color: var(--oxblood-border); }
    .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

    .main-content { margin-left: 280px; min-height: 100vh; position: relative; z-index: 1; }

    .topbar {
        position: sticky; top: 0; z-index: 30;
        background: rgba(250,247,242,0.86); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid var(--hairline); padding: 18px 40px;
        display: flex; align-items: center; justify-content: space-between; gap: 16px;
    }
    .topbar-title { font-family: var(--font-mono); font-size: 0.72rem; font-weight: 500; color: var(--color-ink-faint); letter-spacing: 1px; text-transform: uppercase; }
    .topbar-crumb-sep { margin-left: 8px; color: var(--color-ink-faint); }
    .topbar-crumb-current { margin-left: 8px; color: var(--color-ink-muted); }
    .badge-pill {
        display: inline-flex; align-items: center; gap: 8px;
        border: 1px solid var(--hairline-strong); border-radius: 30px; padding: 6px 14px;
        font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; color: var(--color-ink-muted); letter-spacing: 0.5px; white-space: nowrap;
    }

    .page-inner { padding: 44px 40px 64px; max-width: 780px; }

    .flash-note {
        display: flex; align-items: flex-start; gap: 14px;
        background: var(--color-paper-elevated); border: 1px solid var(--hairline); border-left: 3px solid var(--color-accent-600);
        border-radius: 10px; padding: 16px 20px; margin-bottom: 32px;
        font-family: var(--font-sans); font-size: 0.85rem; font-weight: 500; color: var(--color-ink);
        box-shadow: var(--shadow-paper);
    }
    .flash-note svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--color-accent-600); margin-top: 2px; }

    /* ── HEADER ── */
    .profile-header { margin-bottom: 40px; }
    .profile-eyebrow-row {
        display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-bottom: 14px;
        font-family: var(--font-mono); font-size: 0.68rem; letter-spacing: 1.4px; text-transform: uppercase; color: var(--color-ink-faint);
    }
    .profile-eyebrow-row .sep { color: var(--hairline-strong); }
    .profile-headline {
        font-family: var(--font-serif); font-size: clamp(1.7rem, 3vw, 2.3rem); font-weight: 600;
        letter-spacing: -0.4px; line-height: 1.16; color: var(--color-ink); margin-bottom: 10px;
    }
    .profile-headline em { font-style: italic; font-weight: 500; color: var(--color-accent-600); }
    .profile-sub { font-family: var(--font-sans); font-size: 0.92rem; color: var(--color-ink-muted); line-height: 1.65; max-width: 52ch; }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--color-paper-elevated); border: 1px solid var(--hairline);
        border-radius: 18px; overflow: hidden; margin-bottom: 24px; box-shadow: var(--shadow-paper);
    }
    .form-card-header { padding: 20px 26px; border-bottom: 1px solid var(--hairline); display: flex; align-items: center; gap: 14px; }
    .card-icon-chip {
        width: 38px; height: 38px; border-radius: 11px; background: var(--oxblood-soft); border: 1px solid var(--oxblood-border);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .card-icon-chip svg { width: 17px; height: 17px; color: var(--color-accent-600); }
    .card-title { font-family: var(--font-serif); font-size: 1.05rem; font-weight: 600; color: var(--color-ink); letter-spacing: -0.1px; }
    .card-sub { font-family: var(--font-mono); font-size: 0.68rem; color: var(--color-ink-faint); margin-top: 3px; letter-spacing: 0.2px; }
    .form-card-body { padding: 26px; }

    /* ── FIELD ── */
    .field-wrap { margin-bottom: 22px; }
    .field-wrap:last-child { margin-bottom: 0; }
    .field-label {
        display: block; font-family: var(--font-mono); font-size: 0.66rem; font-weight: 500;
        letter-spacing: 0.12em; text-transform: uppercase; color: var(--color-ink-muted); margin-bottom: 9px;
    }
    .field-label .req { color: var(--color-accent-600); margin-left: 3px; }
    .field-hint { font-family: var(--font-sans); font-size: 0.76rem; color: var(--color-ink-faint); margin-top: 7px; line-height: 1.5; }

    .input-icon-wrap { position: relative; }
    .input-icon {
        position: absolute; top: 50%; left: 15px; transform: translateY(-50%);
        width: 16px; height: 16px; color: var(--color-ink-faint); pointer-events: none; transition: color 0.2s ease;
    }
    .input-icon-wrap:focus-within .input-icon { color: var(--color-accent-600); }

    .input-field, .textarea-field {
        width: 100%; font-family: var(--font-sans); font-size: 0.92rem; font-weight: 500; color: var(--color-ink);
        background: var(--color-paper); border: 1.5px solid var(--color-paper-border); border-radius: var(--radius-sm);
        padding: 13px 16px 13px 43px; outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .input-field.no-icon, .textarea-field { padding-left: 16px; }
    .input-field::placeholder, .textarea-field::placeholder { color: var(--color-ink-faint); font-weight: 400; }
    .input-field:focus, .textarea-field:focus { border-color: var(--color-accent-600); box-shadow: 0 0 0 3px var(--color-accent-200); }
    .input-field.is-error, .textarea-field.is-error { border-color: var(--color-accent-600); background: var(--color-accent-50); }
    .textarea-field { resize: vertical; min-height: 104px; line-height: 1.65; }

    .pw-toggle {
        position: absolute; top: 50%; right: 6px; transform: translateY(-50%);
        width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;
        background: none; border: none; cursor: pointer; color: var(--color-ink-faint);
        border-radius: var(--radius-xs); transition: color 0.2s ease;
    }
    .pw-toggle:hover { color: var(--color-accent-600); }
    .pw-toggle svg { width: 16px; height: 16px; }

    .field-error {
        display: flex; align-items: center; gap: 6px; margin-top: 8px;
        font-family: var(--font-sans); font-size: 0.78rem; font-weight: 600; color: var(--color-accent-700);
    }
    .field-error svg { width: 14px; height: 14px; flex-shrink: 0; }

    /* ── PHOTO UPLOAD ── */
    .photo-row { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; margin-bottom: 22px; }
    .photo-preview {
        width: 88px; height: 88px; border-radius: 18px; flex-shrink: 0;
        background: var(--color-accent-600); border: 1px solid var(--hairline-strong);
        display: flex; align-items: center; justify-content: center; overflow: hidden;
        font-family: var(--font-serif); font-size: 1.9rem; font-weight: 700; color: var(--color-paper);
    }
    .photo-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .photo-actions { display: flex; flex-direction: column; gap: 8px; }
    .btn-photo-upload {
        display: inline-flex; align-items: center; gap: 9px; width: fit-content;
        background: var(--color-paper-elevated); border: 1px solid var(--hairline-strong); color: var(--color-ink-muted);
        font-family: var(--font-sans); font-size: 0.8rem; font-weight: 700;
        padding: 10px 18px; min-height: 40px; border-radius: 9px; cursor: pointer; transition: all 0.22s ease;
    }
    .btn-photo-upload:hover { border-color: var(--oxblood-border); color: var(--oxblood-ink); background: var(--oxblood-soft); }
    .btn-photo-upload svg { width: 15px; height: 15px; flex-shrink: 0; }
    .photo-hint { font-family: var(--font-mono); font-size: 0.68rem; color: var(--color-ink-faint); }
    .sr-only-input { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }

    /* ── SKILL ── */
    .skill-group { margin-bottom: 26px; }
    .skill-group:last-of-type { margin-bottom: 0; }
    .skill-group-title {
        font-family: var(--font-mono); font-size: 0.66rem; font-weight: 600; letter-spacing: 0.12em;
        text-transform: uppercase; color: var(--color-ink-muted); margin-bottom: 14px;
    }
    .skill-rows { display: flex; flex-direction: column; gap: 4px; }
    .skill-row {
        display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.3fr);
        align-items: center; gap: 18px; padding: 12px 10px; border-radius: 10px; transition: background 0.2s ease, opacity 0.2s ease;
    }
    .skill-row:hover { background: var(--surface-sunk); }
    .skill-row.is-inactive .skill-slider-wrap { opacity: 0.4; pointer-events: none; }
    .skill-check { display: flex; align-items: center; gap: 11px; cursor: pointer; user-select: none; min-width: 0; }
    .skill-checkbox {
        width: 17px; height: 17px; flex-shrink: 0; border: 1.5px solid var(--color-paper-border); border-radius: 5px;
        background: var(--color-paper); appearance: none; -webkit-appearance: none; cursor: pointer; position: relative; transition: all 0.2s ease;
    }
    .skill-checkbox:checked { background: var(--color-accent-600); border-color: var(--color-accent-600); }
    .skill-checkbox:checked::after {
        content: ''; position: absolute; top: 2px; left: 5px; width: 4px; height: 8px;
        border: 2px solid var(--color-paper); border-top: none; border-left: none; transform: rotate(45deg);
    }
    .skill-name { font-family: var(--font-sans); font-size: 0.87rem; font-weight: 600; color: var(--color-ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .skill-slider-wrap { display: flex; align-items: center; gap: 12px; min-width: 0; transition: opacity 0.2s ease; }
    .skill-slider {
        flex: 1; -webkit-appearance: none; appearance: none; height: 4px; border-radius: 4px;
        background: var(--color-paper-border); outline: none; cursor: pointer;
    }
    .skill-slider::-webkit-slider-thumb {
        -webkit-appearance: none; width: 16px; height: 16px; border-radius: 50%;
        background: var(--color-accent-600); border: 2px solid var(--color-paper-elevated);
        box-shadow: 0 0 0 1px var(--color-accent-600); cursor: pointer;
    }
    .skill-slider::-moz-range-thumb {
        width: 16px; height: 16px; border-radius: 50%; background: var(--color-accent-600);
        border: 2px solid var(--color-paper-elevated); box-shadow: 0 0 0 1px var(--color-accent-600); cursor: pointer;
    }
    .skill-slider-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 1px; flex-shrink: 0; width: 84px; }
    .skill-level-label { font-family: var(--font-mono); font-size: 0.66rem; font-weight: 600; color: var(--oxblood-ink); white-space: nowrap; }
    .skill-percent { font-family: var(--font-mono); font-size: 0.66rem; color: var(--color-ink-faint); }

    .custom-skill-list { display: flex; flex-direction: column; gap: 12px; }
    .custom-skill-row {
        display: grid; grid-template-columns: minmax(0,0.9fr) minmax(0,1.3fr) auto;
        align-items: center; gap: 14px; padding: 4px 0;
    }
    .custom-skill-name { padding: 10px 14px; font-size: 0.85rem; }
    .btn-remove-skill {
        width: 34px; height: 34px; flex-shrink: 0; border-radius: 9px; border: 1px solid var(--hairline-strong);
        background: var(--color-paper-elevated); color: var(--color-ink-faint); cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;
    }
    .btn-remove-skill:hover { border-color: var(--oxblood-border); color: var(--color-accent-600); background: var(--oxblood-soft); }
    .btn-remove-skill svg { width: 14px; height: 14px; }

    .btn-add-skill {
        display: inline-flex; align-items: center; gap: 8px; margin-top: 16px;
        background: none; border: 1px dashed var(--hairline-strong); color: var(--color-ink-muted);
        font-family: var(--font-sans); font-size: 0.8rem; font-weight: 700;
        padding: 10px 18px; border-radius: 9px; cursor: pointer; transition: all 0.22s ease;
    }
    .btn-add-skill:hover { border-color: var(--oxblood-border); color: var(--oxblood-ink); background: var(--oxblood-soft); }
    .btn-add-skill svg { width: 14px; height: 14px; }

    /* ── ACTIONS ── */
    .form-actions { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 8px; }
    .btn-primary {
        display: inline-flex; align-items: center; gap: 12px; background: var(--color-accent-600);
        border: 1px solid var(--color-accent-600); color: var(--color-paper); padding: 8px 8px 8px 22px;
        min-height: 46px; border-radius: 10px; font-family: var(--font-sans); font-size: 0.86rem; font-weight: 700;
        text-decoration: none; cursor: pointer; transition: background 0.22s ease, transform 0.22s ease, box-shadow 0.22s ease;
    }
    .btn-primary:hover { background: var(--color-accent-700); transform: translateY(-1px); box-shadow: 0 22px 44px -20px rgba(122,46,46,0.22); }
    .btn-primary:active { transform: translateY(0) scale(0.98); }
    .btn-icon-chip {
        width: 28px; height: 28px; border-radius: 50%; background: rgba(250,247,242,0.2);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .btn-icon-chip svg { width: 14px; height: 14px; }

    .btn-ghost {
        display: inline-flex; align-items: center; gap: 9px; background: var(--color-paper-elevated);
        border: 1px solid var(--hairline-strong); color: var(--color-ink-muted); padding: 10px 20px;
        min-height: 46px; border-radius: 10px; font-family: var(--font-sans); font-size: 0.82rem; font-weight: 700;
        text-decoration: none; cursor: pointer; transition: all 0.22s ease;
    }
    .btn-ghost:hover { border-color: var(--oxblood-border); color: var(--oxblood-ink); background: var(--oxblood-soft); }
    .btn-ghost svg { width: 15px; height: 15px; flex-shrink: 0; }

    /* ── OFF-CANVAS DRAWER (MOBILE) ── */
    .sidebar-overlay {
        position: fixed; inset: 0; background: rgba(25,24,22,0.35);
        -webkit-backdrop-filter: blur(2px); backdrop-filter: blur(2px); z-index: 45;
        opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .sidebar-overlay.active { opacity: 1; visibility: visible; }
    .hamburger-btn {
        display: none; align-items: center; justify-content: center; width: 44px; height: 44px;
        border-radius: 10px; background: var(--color-paper-elevated); border: 1px solid var(--hairline-strong);
        color: var(--color-ink-muted); cursor: pointer; flex-shrink: 0; transition: all 0.22s ease;
    }
    .hamburger-btn:hover { border-color: var(--oxblood-border); color: var(--oxblood-ink); background: var(--oxblood-soft); }
    .hamburger-btn svg { width: 19px; height: 19px; }
    .sidebar-close-btn {
        display: none; align-items: center; justify-content: center; width: 40px; height: 40px;
        border-radius: 9px; background: var(--surface-sunk); border: 1px solid var(--hairline);
        color: var(--color-ink-muted); cursor: pointer; flex-shrink: 0; transition: all 0.22s ease;
    }
    .sidebar-close-btn:hover { border-color: var(--oxblood-border); color: var(--oxblood-ink); }
    .sidebar-close-btn svg { width: 16px; height: 16px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 860px) {
        .sidebar {
            transform: translateX(-100%); width: min(300px, 86vw);
            box-shadow: 20px 0 60px rgba(25,24,22,0.18);
            transition: transform 0.34s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar.sidebar-open { transform: translateX(0); }
        .sidebar-close-btn { display: flex; }
        .hamburger-btn { display: inline-flex; }
        .main-content { margin-left: 0; }
        .topbar { padding: 16px 20px; gap: 12px; }
        .topbar-crumb-sep, .badge-pill { display: none; }
        .page-inner { padding: 26px 18px 50px; }
        .form-card-header { padding: 18px 20px; }
        .form-card-body { padding: 20px; }
        .photo-row { gap: 16px; }
    }

    @media (max-width: 560px) {
        .skill-row { grid-template-columns: 1fr; gap: 10px; align-items: flex-start; }
        .skill-slider-wrap { width: 100%; }
        .custom-skill-row { grid-template-columns: 1fr; gap: 10px; }
        .btn-remove-skill { justify-self: flex-end; }
        .form-actions { flex-direction: column; align-items: stretch; }
        .form-actions .btn-primary, .form-actions .btn-ghost { justify-content: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endpush

@section('content')
<a href="#konten-utama" class="skip-link">Lompat ke konten utama</a>
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
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
            <div class="profile-avatar" id="sidebarAvatar">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1; min-width:0;">
                <div class="profile-name">{{ $user->name }}</div>
                <div class="profile-nis">NIS {{ $user->nis_nip ?? '—' }}</div>
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

        <div class="nav-label" style="margin-top:22px;">Akun</div>

        <a href="{{ route('siswa.profile.edit') }}" class="nav-item active" aria-current="page">
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
<main class="main-content" id="konten-utama">

    <header class="topbar">
        <div style="display:flex; align-items:center; gap:14px; min-width:0;">
            <button type="button" class="hamburger-btn" id="siswaSidebarOpen"
                    aria-label="Buka menu navigasi" aria-controls="siswaSidebar" aria-expanded="false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
            <div class="topbar-title">
                <span>Akun</span>
                <span class="topbar-crumb-sep">/</span>
                <span class="topbar-crumb-current">Profil Saya</span>
            </div>
        </div>
        <div class="badge-pill">
            <span class="badge-role-dot" aria-hidden="true"></span>
            Siswa DKV
        </div>
    </header>

    <div class="page-inner">

        @if(session('success'))
            <div class="flash-note" role="status">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="profile-header">
            <div class="profile-eyebrow-row">
                <span>Akun &middot; NIS {{ $user->nis_nip ?? '—' }}</span>
                <span class="sep">&middot;</span>
                <span>Siswa DKV</span>
            </div>
            <h1 class="profile-headline">Profil <em>Saya</em></h1>
            <p class="profile-sub">Kelola informasi pribadi dan tampilan profil portofolio publik Anda.</p>
        </div>

        <form method="POST" action="{{ route('siswa.profile.update') }}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            {{-- ══════════════ IDENTITAS & PROFIL ══════════════ --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon-chip">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="card-title">Identitas &amp; Profil</div>
                        <div class="card-sub">Tampil di halaman portofolio publik Anda</div>
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
                        <div class="photo-actions">
                            <label for="photo" class="btn-photo-upload">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Ganti Foto
                            </label>
                            <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png" class="sr-only-input">
                            <div class="photo-hint">JPG atau PNG, maksimal 2MB</div>
                        </div>
                    </div>
                    @error('photo')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="field-wrap" style="margin-top:24px;">
                        <label for="name" class="field-label">Nama Lengkap <span class="req">*</span></label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input type="text" id="name" name="name" required
                                   value="{{ old('name', $user->name) }}"
                                   class="input-field {{ $errors->has('name') ? 'is-error' : '' }}">
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
                        <div class="input-icon-wrap">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                            <input type="text" id="nis_nip" name="nis_nip"
                                   value="{{ old('nis_nip', $user->nis_nip) }}"
                                   class="input-field {{ $errors->has('nis_nip') ? 'is-error' : '' }}">
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
                        <textarea id="bio" name="bio" rows="4"
                                  placeholder="Ceritakan singkat tentang minat desain Anda (opsional)"
                                  class="textarea-field {{ $errors->has('bio') ? 'is-error' : '' }}">{{ old('bio', $user->bio) }}</textarea>
                        <div class="field-hint">Akan tampil di halaman portofolio publik Anda. Maksimal 500 karakter.</div>
                        @error('bio')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ══════════════ KONTAK ══════════════ --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon-chip">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="card-title">Kontak</div>
                        <div class="card-sub">Memudahkan pihak industri menghubungi Anda</div>
                    </div>
                </div>
                <div class="form-card-body">

                    <div class="field-wrap">
                        <label for="contact" class="field-label">Kontak (WA / Email)</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <input type="text" id="contact" name="contact"
                                   placeholder="Contoh: 0812xxxxxxx atau nama@email.com"
                                   value="{{ old('contact', $user->contact) }}"
                                   class="input-field {{ $errors->has('contact') ? 'is-error' : '' }}">
                        </div>
                        <div class="field-hint">Opsional — ditampilkan sebagai tautan WhatsApp di halaman portofolio publik.</div>
                        @error('contact')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-wrap">
                        <label for="instagram" class="field-label">Instagram</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <rect x="3.5" y="3.5" width="17" height="17" rx="5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 8.5h.01"/>
                                <circle cx="12" cy="12" r="4"/>
                            </svg>
                            <input type="text" id="instagram" name="instagram"
                                   placeholder="@username atau tautan profil"
                                   value="{{ old('instagram', $user->instagram) }}"
                                   class="input-field {{ $errors->has('instagram') ? 'is-error' : '' }}">
                        </div>
                        <div class="field-hint">Username atau tautan Instagram. Opsional.</div>
                        @error('instagram')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ══════════════ KEAHLIAN ══════════════ --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon-chip">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="card-title">Keahlian &amp; Kompetensi</div>
                        <div class="card-sub">Geser slider untuk menandai tingkat penguasaan Anda</div>
                    </div>
                </div>
                <div class="form-card-body">

                    @foreach($skillOptions as $group => $options)
                        @php
                            $existingByName = collect(old('skills_active') ? [] : ($user->skills ?? []))->keyBy('name');
                        @endphp
                        <div class="skill-group">
                            <div class="skill-group-title">{{ $group }}</div>
                            <div class="skill-rows">
                                @foreach($options as $skillName)
                                    @php
                                        $isChecked = old('skills_active')
                                            ? in_array($skillName, old('skills_active', []))
                                            : $existingByName->has($skillName);
                                        $level = (int) old("skills_level.$skillName", $existingByName->get($skillName)['level'] ?? 50);
                                        $levelLabelText = $level <= 20 ? 'Pemula' : ($level <= 40 ? 'Dasar' : ($level <= 60 ? 'Menengah' : ($level <= 80 ? 'Mahir' : 'Sangat Mahir')));
                                    @endphp
                                    <div class="skill-row {{ $isChecked ? '' : 'is-inactive' }}" data-skill-row>
                                        <label class="skill-check">
                                            <input type="checkbox"
                                                   name="skills_active[]"
                                                   value="{{ $skillName }}"
                                                   class="skill-checkbox"
                                                   {{ $isChecked ? 'checked' : '' }}>
                                            <span class="skill-name">{{ $skillName }}</span>
                                        </label>
                                        <div class="skill-slider-wrap">
                                            <input type="range" min="0" max="100" step="5"
                                                   name="skills_level[{{ $skillName }}]"
                                                   value="{{ $level }}"
                                                   class="skill-slider"
                                                   oninput="handleSkillSliderInput(this)"
                                                   aria-label="Tingkat penguasaan {{ $skillName }}">
                                            <div class="skill-slider-meta">
                                                <span class="skill-level-label">{{ $levelLabelText }}</span>
                                                <span class="skill-percent">{{ $level }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Skill Custom --}}
                    <div class="skill-group">
                        <div class="skill-group-title">Skill Lainnya (Custom)</div>
                        <div class="custom-skill-list" id="customSkillList">
                            @php $existingCustom = collect($user->skills ?? [])->where('type', 'Custom')->values(); @endphp
                            @forelse($existingCustom as $custom)
                                @php
                                    $customLevel = (int) $custom['level'];
                                    $customLevelLabel = $customLevel <= 20 ? 'Pemula' : ($customLevel <= 40 ? 'Dasar' : ($customLevel <= 60 ? 'Menengah' : ($customLevel <= 80 ? 'Mahir' : 'Sangat Mahir')));
                                @endphp
                                <div class="custom-skill-row" data-custom-row>
                                    <input type="text" name="custom_skill_name[]" value="{{ $custom['name'] }}"
                                           placeholder="Nama skill" class="input-field custom-skill-name no-icon">
                                    <div class="skill-slider-wrap">
                                        <input type="range" min="0" max="100" step="5" name="custom_skill_level[]"
                                               value="{{ $customLevel }}" class="skill-slider"
                                               oninput="handleSkillSliderInput(this)" aria-label="Tingkat penguasaan skill custom">
                                        <div class="skill-slider-meta">
                                            <span class="skill-level-label">{{ $customLevelLabel }}</span>
                                            <span class="skill-percent">{{ $customLevel }}%</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-remove-skill" onclick="this.closest('.custom-skill-row').remove()" aria-label="Hapus skill custom">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        <button type="button" onclick="addCustomSkillRow()" class="btn-add-skill">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Tambah Skill Custom
                        </button>
                        <div class="field-hint">Contoh: Blender 3D, Adobe Premiere, Sablon Manual, dsb.</div>
                    </div>

                </div>
            </div>

            {{-- ══════════════ KEAMANAN ══════════════ --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon-chip">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="card-title">Keamanan</div>
                        <div class="card-sub">Kosongkan jika tidak ingin mengganti password</div>
                    </div>
                </div>
                <div class="form-card-body">

                    <div class="field-wrap">
                        <label for="password" class="field-label">Password Baru</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="password" name="password"
                                   placeholder="Minimal 8 karakter" autocomplete="new-password"
                                   class="input-field {{ $errors->has('password') ? 'is-error' : '' }}"
                                   style="padding-right:44px;">
                            <button type="button" class="pw-toggle" id="pwTogglePassword" tabindex="-1" aria-label="Tampilkan atau sembunyikan password">
                                <svg class="eye-show" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="eye-hide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-wrap">
                        <label for="password_confirmation" class="field-label">Konfirmasi Password Baru</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   placeholder="Ulangi password baru" autocomplete="new-password"
                                   class="input-field" style="padding-right:44px;">
                            <button type="button" class="pw-toggle" id="pwToggleConfirm" tabindex="-1" aria-label="Tampilkan atau sembunyikan password">
                                <svg class="eye-show" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="eye-hide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <span>Simpan Perubahan</span>
                    <span class="btn-icon-chip">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </button>
                <a href="{{ route('siswa.dashboard') }}" class="btn-ghost">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Dashboard
                </a>
            </div>

        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
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
</script>
<script>
    // Live-preview foto profil sebelum di-upload (sidebar & kartu identitas ikut diperbarui)
    (function () {
        var photoInput   = document.getElementById('photo');
        var photoPreview = document.getElementById('photoPreview');
        var sidebarAvatar = document.getElementById('sidebarAvatar');
        if (!photoInput || !photoPreview) return;

        photoInput.addEventListener('change', function () {
            var file = this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                var markup = '<img src="' + e.target.result + '" alt="Preview">';
                photoPreview.innerHTML = markup;
                if (sidebarAvatar) sidebarAvatar.innerHTML = markup;
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
<script>
    // Slider tingkat penguasaan skill: memperbarui label & persentase,
    // TANPA mengubah mekanisme nilai 0–100 yang sudah dipakai controller.
    function levelLabelText(v) {
        if (v <= 20) return 'Pemula';
        if (v <= 40) return 'Dasar';
        if (v <= 60) return 'Menengah';
        if (v <= 80) return 'Mahir';
        return 'Sangat Mahir';
    }

    function handleSkillSliderInput(el) {
        var val = parseInt(el.value, 10) || 0;
        var wrap = el.closest('.skill-slider-wrap');
        if (!wrap) return;
        var percentEl = wrap.querySelector('.skill-percent');
        var labelEl = wrap.querySelector('.skill-level-label');
        if (percentEl) percentEl.textContent = val + '%';
        if (labelEl) labelEl.textContent = levelLabelText(val);
    }

    // Redupkan slider ketika skill bawaan tidak dicentang (murni visual,
    // tidak mengubah field yang dikirim ke server).
    document.querySelectorAll('.skill-checkbox').forEach(function (cb) {
        function sync() {
            var row = cb.closest('[data-skill-row]');
            if (!row) return;
            row.classList.toggle('is-inactive', !cb.checked);
        }
        cb.addEventListener('change', sync);
    });

    // Tambah baris skill custom secara dinamis
    function addCustomSkillRow() {
        var wrap = document.createElement('div');
        wrap.className = 'custom-skill-row';
        wrap.setAttribute('data-custom-row', '');
        wrap.innerHTML =
            '<input type="text" name="custom_skill_name[]" placeholder="Nama skill" class="input-field custom-skill-name no-icon">' +
            '<div class="skill-slider-wrap">' +
                '<input type="range" min="0" max="100" step="5" name="custom_skill_level[]" value="50" class="skill-slider" oninput="handleSkillSliderInput(this)" aria-label="Tingkat penguasaan skill custom">' +
                '<div class="skill-slider-meta">' +
                    '<span class="skill-level-label">Menengah</span>' +
                    '<span class="skill-percent">50%</span>' +
                '</div>' +
            '</div>' +
            '<button type="button" class="btn-remove-skill" onclick="this.closest(\'.custom-skill-row\').remove()" aria-label="Hapus skill custom">' +
                '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>' +
            '</button>';
        document.getElementById('customSkillList').appendChild(wrap);
    }
</script>
<script>
    // Toggle tampil/sembunyi password — pola sama seperti auth/login.blade.php
    function setupPasswordToggle(toggleId, inputId) {
        var toggle = document.getElementById(toggleId);
        var input  = document.getElementById(inputId);
        if (!toggle || !input) return;
        var eyeShow = toggle.querySelector('.eye-show');
        var eyeHide = toggle.querySelector('.eye-hide');
        toggle.addEventListener('click', function () {
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            if (eyeShow) eyeShow.style.display = isHidden ? 'none' : 'block';
            if (eyeHide) eyeHide.style.display = isHidden ? 'block' : 'none';
        });
    }
    setupPasswordToggle('pwTogglePassword', 'password');
    setupPasswordToggle('pwToggleConfirm', 'password_confirmation');
</script>
@endpush
