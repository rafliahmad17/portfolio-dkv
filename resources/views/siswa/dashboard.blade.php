<!-- resources/views/siswa/dashboard.blade.php-->

@extends('layouts.app')

@section('title', 'Dashboard — DKV SMEKDA Portal')

{{-- Dashboard siswa sudah punya sidebar + topbar sendiri sebagai navigasi,
     jadi navbar/footer default dari layout tidak dipakai di halaman ini
     (pola yang sama dengan guru/dashboard.blade.php). --}}
@section('navbar')@endsection
@section('footer')@endsection

{{--
    ARAH DESAIN — "Curatorial Command Desk"
    Palet   : Warm ivory canvas (#FAF7F2), surface putih, aksen oxblood (#7A2E2E)
    Tipografi: Fraunces (editorial serif — judul & angka besar), Public Sans (sans — UI/body),
               JetBrains Mono (label arsip, meta-data, angka statistik)
    Motif   : Sidebar sebagai "Studio Index" bernomor, kartu karya berlabel nomor katalog,
              blok statistik berbentuk ledger/buku arsip. Ikon tetap inline SVG bawaan
              (bukan CDN) supaya halaman ini tidak bergantung pada layanan luar untuk
              fungsinya — konsisten dengan pola file lain di portal ini.
--}}

@push('styles')
<style>
    :root {

        --canvas-deep:    #F3EEE3;

        --surface-sunk:   #F6F1E7;

        --ink-faint:      #756F65;
        --hairline:       rgba(25,24,22,0.10);
        --hairline-strong:rgba(25,24,22,0.18);
        --oxblood:        #7A2E2E;
        --oxblood-deep:   #5C2222;
        --oxblood-soft:   rgba(122,46,46,0.08);
        --oxblood-border: rgba(122,46,46,0.26);
        --oxblood-ink:    #6E2A2A;
        --shadow-paper:   0 1px 2px rgba(25,24,22,0.04), 0 16px 34px -20px rgba(25,24,22,0.16);
        --shadow-lift:    0 1px 2px rgba(25,24,22,0.05), 0 22px 44px -20px rgba(122,46,46,0.22);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        height: 100%;
        font-family: var(--font-sans);
        background-color: var(--color-paper);
        color: var(--color-ink);
        overflow-x: hidden;
    }

    /* Tekstur kertas — sangat halus, meniru permukaan katalog cetak */
    body::before {
        content: '';
        position: fixed; inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.028'/%3E%3C/svg%3E");
        pointer-events: none; z-index: 0;
        mix-blend-mode: multiply;
    }

    /* Cahaya galeri — satu titik hangat, sangat lambat, sangat redup */
    .gallery-spotlight {
        position: fixed; top: -280px; left: 50%;
        width: 900px; height: 900px;
        transform: translateX(-40%);
        border-radius: 50%;
        background: radial-gradient(circle, rgba(122,46,46,0.055) 0%, transparent 62%);
        pointer-events: none; z-index: 0;
        animation: spotlightDrift 26s ease-in-out infinite alternate;
    }
    @keyframes spotlightDrift {
        0%   { transform: translateX(-42%) translateY(0); }
        100% { transform: translateX(-34%) translateY(26px); }
    }

    /* ── SCROLLBAR ── */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--hairline-strong); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--oxblood-border); }

    /* ── FOKUS KEYBOARD (AKSESIBILITAS) ── */
    a:focus-visible,
    button:focus-visible,
    [tabindex]:focus-visible {
        outline: 2px solid var(--oxblood);
        outline-offset: 3px;
        border-radius: 6px;
    }

    .skip-link {
        position: fixed; top: -100px; left: 16px; z-index: 100;
        background: var(--color-ink); color: var(--color-paper);
        padding: 10px 18px; border-radius: 8px;
        font-family: var(--font-sans);
        font-size: 0.8rem; font-weight: 600;
        text-decoration: none;
        transition: top 0.2s ease;
    }
    .skip-link:focus { top: 16px; }

    /* ── SIDEBAR — "STUDIO INDEX" ── */
    .sidebar {
        position: fixed; top: 0; left: 0;
        width: 280px; height: 100vh;
        background: var(--color-paper-elevated);
        border-right: 1px solid var(--hairline);
        display: flex; flex-direction: column;
        z-index: 50;
        overflow-y: auto;
    }

    .sidebar-logo {
        padding: 30px 26px 22px;
        border-bottom: 1px solid var(--hairline);
    }

    .logo-wordmark {
        font-family: var(--font-sans);
        font-size: 0.82rem;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--color-ink);
        display: flex; align-items: center; gap: 10px;
    }

    .logo-wordmark .dot { color: var(--oxblood); }

    .logo-mark {
        width: 34px; height: 34px;
        border-radius: 50%;
        border: 1px solid var(--hairline-strong);
        background: var(--surface-sunk);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
    }
    .logo-mark::after {
        content: '';
        position: absolute; inset: 3px;
        border: 1px solid var(--hairline);
        border-radius: 50%;
        pointer-events: none;
    }

    .logo-mark img { width: 100%; height: 100%; object-fit: contain; padding: 3px; }

    .logo-sub {
        font-family: var(--font-mono);
        font-size: 0.62rem;
        color: var(--ink-faint);
        margin-top: 6px;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        padding-left: 40px;
    }

    .sidebar-profile {
        padding: 22px 26px;
        border-bottom: 1px solid var(--hairline);
    }

    .profile-avatar {
        width: 44px; height: 44px;
        border-radius: 14px;
        background: var(--oxblood);
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-serif);
        font-size: 1.05rem; font-weight: 700; color: var(--color-paper);
        flex-shrink: 0;
        overflow: hidden;
    }

    .profile-name {
        font-family: var(--font-sans);
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--color-ink);
        line-height: 1.3;
        margin-bottom: 2px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .profile-nis {
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--ink-faint);
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }

    .badge-role {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--oxblood-soft);
        border: 1px solid var(--oxblood-border);
        color: var(--oxblood-ink);
        padding: 3px 10px;
        border-radius: 20px;
        font-family: var(--font-mono);
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .badge-role-dot {
        width: 5px; height: 5px;
        background: var(--oxblood);
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Nav — daftar terindeks, bukan grid ikon generik */
    .sidebar-nav { flex: 1; padding: 22px 16px; }

    .nav-label {
        font-family: var(--font-mono);
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ink-faint);
        padding: 0 10px;
        margin-bottom: 10px;
        margin-top: 4px;
    }

    .nav-item {
        display: flex; align-items: center; gap: 14px;
        padding: 11px 12px;
        min-height: 44px;
        border-radius: 10px;
        font-family: var(--font-sans);
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--color-ink-muted);
        text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        border: 1px solid transparent;
        margin-bottom: 2px;
        position: relative;
    }

    .nav-index {
        font-family: var(--font-mono);
        font-size: 0.68rem;
        font-weight: 500;
        color: var(--ink-faint);
        flex-shrink: 0;
        width: 16px;
    }

    .nav-item:hover { background: var(--surface-sunk); color: var(--color-ink); }

    .nav-item.active {
        color: var(--oxblood-ink);
        background: var(--oxblood-soft);
        border-color: var(--oxblood-border);
    }

    .nav-item.active .nav-index { color: var(--oxblood); }

    .nav-item.active::before {
        content: '';
        position: absolute; left: 0; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 20px;
        background: var(--oxblood);
        border-radius: 0 3px 3px 0;
    }

    /* Logout */
    .sidebar-footer { padding: 16px; border-top: 1px solid var(--hairline); }

    .btn-logout {
        width: 100%;
        display: flex; align-items: center; gap: 12px;
        padding: 11px 12px;
        min-height: 44px;
        border-radius: 10px;
        background: none; border: 1px solid transparent;
        color: var(--color-ink-muted);
        font-family: var(--font-sans);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-logout:hover {
        color: var(--oxblood-ink);
        background: var(--oxblood-soft);
        border-color: var(--oxblood-border);
    }

    .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

    .main-content {
        margin-left: 280px;
        min-height: 100vh;
        position: relative; z-index: 1;
    }

    .topbar {
        position: sticky; top: 0; z-index: 30;
        background: rgba(250,247,242,0.86);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid var(--hairline);
        padding: 18px 40px;
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px;
    }

    .topbar-title {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 500;
        color: var(--ink-faint);
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .topbar-crumb-sep { margin-left: 8px; color: var(--ink-faint); }
    .topbar-crumb-current { margin-left: 8px; color: var(--color-ink-muted); }

    .date-stamp {
        display: inline-flex; align-items: center; gap: 8px;
        border: 1px dashed var(--hairline-strong);
        border-radius: 8px;
        padding: 6px 14px;
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--color-ink-muted);
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    /* ── INNER PAGE ── */
    .page-inner { padding: 44px 40px 64px; max-width: 1440px; }

    /* Catatan / flash message */
    .flash-note {
        display: flex; align-items: flex-start; gap: 14px;
        background: var(--color-paper-elevated);
        border: 1px solid var(--hairline);
        border-left: 3px solid var(--oxblood);
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 32px;
        font-family: var(--font-sans);
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--color-ink);
        box-shadow: var(--shadow-paper);
    }
    .flash-note svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--oxblood); margin-top: 2px; }

    /* ── STUDIO HEADER ── */
    .studio-header {
        display: flex; align-items: flex-end; justify-content: space-between;
        flex-wrap: wrap; gap: 24px;
        margin-bottom: 48px;
        padding-bottom: 36px;
        border-bottom: 1px solid var(--hairline);
        position: relative;
    }

    /* Angka arsip raksasa, samar — latar editorial di belakang sambutan */
    .header-watermark {
        position: absolute;
        top: -22px; right: 0;
        font-family: var(--font-serif);
        font-style: italic;
        font-weight: 300;
        font-size: clamp(6rem, 13vw, 11rem);
        line-height: 1;
        letter-spacing: -4px;
        color: rgba(122,46,46,0.045);
        -webkit-text-stroke: 1px rgba(122,46,46,0.16);
        pointer-events: none;
        user-select: none;
        z-index: 0;
        white-space: nowrap;
    }
    @media (max-width: 860px) { .header-watermark { display: none; } }

    .studio-eyebrow-row {
        display: flex; flex-wrap: wrap; align-items: center; gap: 10px;
        margin-bottom: 16px;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: var(--ink-faint);
    }
    .studio-eyebrow-row .sep { color: var(--hairline-strong); }
    .studio-eyebrow-row a {
        color: var(--oxblood);
        text-decoration: none;
        border-bottom: 1px solid var(--oxblood-border);
        padding-bottom: 1px;
        transition: border-color 0.2s ease;
    }
    .studio-eyebrow-row a:hover { border-color: var(--oxblood); }

    .studio-headline {
        font-family: var(--font-serif);
        font-size: clamp(1.9rem, 3vw, 2.6rem);
        font-weight: 600;
        letter-spacing: -0.5px;
        line-height: 1.14;
        color: var(--color-ink);
        margin-bottom: 12px;
    }
    .studio-headline em {
        font-style: italic;
        font-weight: 500;
        color: var(--oxblood);
    }

    .studio-sub {
        font-family: var(--font-sans);
        font-size: 0.92rem;
        color: var(--color-ink-muted);
        max-width: 52ch;
        line-height: 1.65;
    }

    /* ── TOMBOL ── */
    .btn-primary {
        display: inline-flex; align-items: center; gap: 12px;
        background: var(--oxblood);
        border: 1px solid var(--oxblood);
        color: var(--color-paper);
        padding: 8px 8px 8px 22px;
        min-height: 44px;
        border-radius: 9px;
        font-family: var(--font-sans);
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.22s ease, transform 0.22s ease, box-shadow 0.22s ease;
    }
    .btn-primary:hover { background: var(--oxblood-deep); transform: translateY(-1px); box-shadow: var(--shadow-lift); }
    .btn-primary:active { transform: translateY(0) scale(0.98); }

    /* Ikon bersarang dalam chip bulat — detail tactile, bergerak halus saat hover */
    .btn-icon-chip {
        width: 27px; height: 27px;
        border-radius: 50%;
        background: rgba(250,247,242,0.18);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: transform 0.35s cubic-bezier(0.2,0.8,0.2,1), background 0.25s ease;
    }
    .btn-icon-chip svg { width: 13px; height: 13px; }
    .btn-primary:hover .btn-icon-chip { transform: translate(2px,-2px) rotate(8deg); }

    /* Varian terang — dipakai di atas panel gelap (Hero Spotlight) */
    .btn-primary.on-dark { background: var(--color-paper); border-color: var(--color-paper); color: var(--color-ink); }
    .btn-primary.on-dark:hover { background: #FFFFFF; box-shadow: 0 24px 48px -22px rgba(0,0,0,0.5); }
    .btn-primary.on-dark .btn-icon-chip { background: rgba(25,24,22,0.08); }

    .btn-ghost {
        display: inline-flex; align-items: center; gap: 9px;
        background: var(--color-paper-elevated);
        border: 1px solid var(--hairline-strong);
        color: var(--color-ink-muted);
        padding: 10px 20px;
        min-height: 44px;
        border-radius: 9px;
        font-family: var(--font-sans);
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.22s ease;
    }
    .btn-ghost:hover { border-color: var(--oxblood-border); color: var(--oxblood-ink); background: var(--oxblood-soft); }
    .btn-ghost svg { width: 15px; height: 15px; flex-shrink: 0; }

    .btn-ghost.on-dark { background: transparent; border-color: rgba(250,247,242,0.22); color: rgba(250,247,242,0.82); }
    .btn-ghost.on-dark:hover { background: rgba(250,247,242,0.08); border-color: rgba(250,247,242,0.42); color: var(--color-paper); }

    /* ── HERO SPOTLIGHT — karya terbaru, dipajang seperti di dinding galeri ── */
    .hero-spotlight {
        background: var(--color-ink);
        border-radius: 24px;
        padding: 34px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 34px 74px -30px rgba(25,24,22,0.5);
    }
    .hero-spotlight::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(560px circle at 88% -12%, rgba(122,46,46,0.32), transparent 60%),
            radial-gradient(420px circle at -6% 112%, rgba(122,46,46,0.18), transparent 55%);
        pointer-events: none;
    }
    .hero-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 40px;
        align-items: center;
        position: relative; z-index: 1;
    }
    @media (max-width: 900px) { .hero-grid { grid-template-columns: 1fr; gap: 28px; } }

    /* Bingkai: dinding gelap → mat → karya — metafora karya dipajang di galeri */
    .hero-frame {
        background: rgba(250,247,242,0.06);
        border: 1px solid rgba(250,247,242,0.12);
        border-radius: 18px;
        padding: 14px;
    }
    .hero-frame-inner {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        aspect-ratio: 4 / 3;
        background: var(--color-paper);
        display: block;
    }
    .hero-image {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s cubic-bezier(0.2,0.7,0.3,1);
    }
    .hero-frame-inner:hover .hero-image { transform: scale(1.03); }

    .hero-eyebrow {
        font-family: var(--font-mono);
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: rgba(250,247,242,0.5);
        margin-bottom: 16px;
    }
    .hero-category {
        display: inline-flex; align-items: center;
        background: rgba(250,247,242,0.08);
        border: 1px solid rgba(250,247,242,0.18);
        color: #E3A79C;
        font-family: var(--font-mono);
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 16px;
    }
    .hero-title {
        font-family: var(--font-serif);
        font-size: clamp(1.7rem, 2.6vw, 2.5rem);
        font-weight: 600;
        letter-spacing: -0.5px;
        line-height: 1.16;
        color: var(--color-paper);
        margin-bottom: 14px;
    }
    .hero-desc {
        font-family: var(--font-sans);
        font-size: 0.9rem;
        line-height: 1.7;
        color: rgba(250,247,242,0.62);
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 46ch;
    }
    .hero-meta {
        display: flex; align-items: center; gap: 7px;
        font-family: var(--font-mono);
        font-size: 0.7rem;
        color: rgba(250,247,242,0.42);
        margin-bottom: 26px;
    }
    .hero-meta svg { width: 13px; height: 13px; flex-shrink: 0; }
    .hero-actions { display: flex; flex-wrap: wrap; gap: 10px; }

    /* ── SOROTAN KURSOR — border menyala mengikuti kursor (progressive enhancement) ── */
    [data-glow] { position: relative; }
    [data-glow]::after {
        content: '';
        position: absolute; inset: 0;
        z-index: 2;
        border-radius: inherit;
        padding: 1px;
        background: radial-gradient(220px circle at var(--mx, 50%) var(--my, 50%), rgba(122,46,46,0.55), transparent 72%);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.35s ease;
        pointer-events: none;
    }
    [data-glow]:hover::after { opacity: 1; }
    @supports not (mask-composite: exclude) {
        [data-glow]::after { display: none; }
    }

    /* ── PIL KURSOR "LIHAT KARYA" — mengikuti kursor di atas gambar ── */
    [data-cursor-target] { cursor: none; }
    .cursor-follow {
        position: fixed; top: 0; left: 0;
        z-index: 70; pointer-events: none;
        transform: translate(var(--fx, -999px), var(--fy, -999px)) translate(-50%, -50%) scale(0.6);
        opacity: 0;
        background: var(--color-ink);
        color: var(--color-paper);
        font-family: var(--font-mono);
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        padding: 11px 20px;
        border-radius: 30px;
        white-space: nowrap;
        transition: transform 0.18s ease-out, opacity 0.25s ease;
        will-change: transform;
    }
    .cursor-follow.is-active {
        opacity: 1;
        transform: translate(var(--fx, -999px), var(--fy, -999px)) translate(-50%, -50%) scale(1);
    }
    @media (hover: none) {
        [data-cursor-target] { cursor: pointer; }
        .cursor-follow { display: none; }
    }

    /* ── STATISTIK — LEDGER ARSIP (bukan kartu ikon berwarna) ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--hairline);
        border: 1px solid var(--hairline);
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 56px;
    }

    .stat-block {
        background: var(--color-paper-elevated);
        padding: 28px 26px;
        transition: background 0.25s ease;
    }
    .stat-block:hover { background: var(--surface-sunk); }

    .stat-label {
        display: flex; align-items: baseline; gap: 8px;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--ink-faint);
        margin-bottom: 20px;
    }
    .stat-label .idx { color: var(--oxblood); }

    .stat-number {
        font-family: var(--font-mono);
        font-variant-numeric: tabular-nums;
        font-size: 2.7rem;
        font-weight: 600;
        letter-spacing: -1px;
        color: var(--color-ink);
        line-height: 1;
        margin-bottom: 18px;
    }

    .stat-bar-wrap {
        height: 2px;
        background: var(--hairline);
        border-radius: 2px;
        overflow: hidden;
    }
    .stat-bar { height: 100%; background: var(--oxblood); }

    /* ── PANEL ETALASE KARYA ── */
    .showcase-panel {
        background: var(--color-paper-elevated);
        border: 1px solid var(--hairline);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-paper);
    }

    .section-header-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 26px 28px;
        border-bottom: 1px solid var(--hairline);
        gap: 16px; flex-wrap: wrap;
    }

    .section-title {
        font-family: var(--font-serif);
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--color-ink);
        letter-spacing: -0.2px;
    }

    .section-sub {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--ink-faint);
        margin-top: 5px;
        letter-spacing: 0.3px;
    }

    /* Grid karya — mobile-first: 1 kolom → 2 kolom → 3 kolom */
    .portfolio-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        padding: 28px;
    }
    @media (min-width: 641px) {
        .portfolio-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
        .portfolio-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; }
        /* Ritme bento — setiap kartu ke-4n+3 tampil lebih lebar & panoramik,
           supaya grid tidak terasa monoton seperti tabel generik. */
        .portfolio-card:nth-child(4n+3) { grid-column: span 2; }
        .portfolio-card:nth-child(4n+3) .thumb-wrapper { aspect-ratio: 2.3 / 1; }
    }

    .portfolio-card {
        background: var(--color-paper-elevated);
        border: 1px solid var(--hairline);
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.2,0.7,0.3,1), border-color 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }
    .portfolio-card:hover {
        transform: translateY(-4px);
        border-color: var(--oxblood-border);
        box-shadow: var(--shadow-lift);
    }

    .thumb-wrapper {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        background: var(--surface-sunk);
        display: block;
    }

    .portfolio-thumb {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s cubic-bezier(0.2,0.7,0.3,1), opacity 0.3s ease;
        background: var(--surface-sunk);
    }
    .portfolio-card:hover .portfolio-thumb { transform: scale(1.035); }

    /* Fallback halus kalau gambar gagal dimuat/rusak */
    .thumb-broken {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect width='400' height='300' fill='%23F6F1E7'/%3E%3Cg stroke='%23C9C1B3' stroke-width='7' stroke-linecap='round' stroke-linejoin='round' fill='none'%3E%3Crect x='60' y='60' width='280' height='180' rx='12'/%3E%3Ccircle cx='140' cy='120' r='16'/%3E%3Cpath d='M60 200l70-60 50 40 60-50 100 90'/%3E%3C/g%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 92px 69px;
    }
    .thumb-broken .portfolio-thumb,
    .thumb-broken .hero-image { opacity: 0; }

    .catalog-tag {
        position: absolute; top: 10px; right: 10px;
        background: rgba(250,247,242,0.92);
        backdrop-filter: blur(6px);
        border: 1px solid var(--hairline-strong);
        color: var(--color-ink-muted);
        font-family: var(--font-mono);
        font-size: 0.64rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .pdf-pill {
        position: absolute; top: 10px; left: 10px;
        background: var(--color-ink);
        color: var(--color-paper);
        font-family: var(--font-mono);
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .portfolio-body { padding: 18px; }

    .portfolio-category {
        display: inline-flex; align-items: center;
        background: var(--oxblood-soft);
        border: 1px solid var(--oxblood-border);
        color: var(--oxblood-ink);
        font-family: var(--font-mono);
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 10px;
    }

    .portfolio-title {
        font-family: var(--font-serif);
        font-size: 1rem;
        font-weight: 600;
        color: var(--color-ink);
        letter-spacing: -0.2px;
        line-height: 1.35;
        margin-bottom: 6px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .portfolio-desc {
        font-family: var(--font-sans);
        font-size: 0.8rem;
        color: var(--color-ink-muted);
        line-height: 1.55;
        margin-bottom: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-clamp: 2;
        overflow: hidden;
    }

    .portfolio-meta {
        display: flex; align-items: center; gap: 6px;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--ink-faint);
        margin-bottom: 16px;
    }
    .portfolio-meta svg { width: 12px; height: 12px; flex-shrink: 0; }

    .portfolio-actions {
        display: flex; gap: 8px;
        padding-top: 14px;
        border-top: 1px solid var(--hairline);
    }

    .btn-action {
        flex: 1;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        padding: 9px;
        min-height: 42px;
        border-radius: 8px;
        background: var(--color-paper-elevated);
        border: 1px solid var(--hairline-strong);
        color: var(--color-ink-muted);
        font-family: var(--font-sans);
        font-size: 0.74rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-action:hover { border-color: var(--color-ink); color: var(--color-ink); background: var(--surface-sunk); }
    .btn-action svg { width: 13px; height: 13px; flex-shrink: 0; }

    .btn-action-delete {
        flex: 1;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        padding: 9px;
        min-height: 42px;
        border-radius: 8px;
        background: var(--color-paper-elevated);
        border: 1px solid var(--oxblood-border);
        color: var(--oxblood-ink);
        font-family: var(--font-sans);
        font-size: 0.74rem;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        transition: all 0.2s ease;
    }
    .btn-action-delete:hover { background: var(--oxblood); border-color: var(--oxblood); color: var(--color-paper); }
    .btn-action-delete svg { width: 13px; height: 13px; flex-shrink: 0; }

    .empty-wrap {
        padding: 88px 40px;
        min-height: 420px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .empty-icon {
        width: 64px; height: 64px;
        border: 1px solid var(--hairline-strong);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 26px;
    }
    .empty-icon svg { width: 26px; height: 26px; color: var(--ink-faint); }

    .empty-title {
        font-family: var(--font-serif);
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--color-ink);
        margin-bottom: 10px;
    }

    .empty-sub {
        font-family: var(--font-sans);
        font-size: 0.85rem;
        color: var(--color-ink-muted);
        margin-bottom: 30px;
        line-height: 1.7;
        max-width: 38ch;
    }

    .add-card {
        border: 1.5px dashed var(--hairline-strong);
        border-radius: 16px;
        min-height: 300px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        text-decoration: none;
        transition: all 0.25s ease;
        gap: 14px;
    }
    .add-card:hover { border-color: var(--oxblood-border); background: var(--oxblood-soft); }

    .add-card-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        border: 1px solid var(--hairline-strong);
        display: flex; align-items: center; justify-content: center;
        transition: all 0.25s ease;
    }
    .add-card:hover .add-card-icon { border-color: var(--oxblood-border); background: var(--color-paper-elevated); }
    .add-card-icon svg { width: 17px; height: 17px; color: var(--ink-faint); transition: color 0.25s ease; }
    .add-card:hover .add-card-icon svg { color: var(--oxblood); }

    .add-card-text {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: var(--ink-faint);
        transition: color 0.25s ease;
    }
    .add-card:hover .add-card-text { color: var(--oxblood-ink); }

    .dashboard-footer-strip {
        margin-top: 56px;
        padding-top: 26px;
        border-top: 1px solid var(--hairline);
        display: flex;
        align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--ink-faint);
        letter-spacing: 0.3px;
    }
    .dashboard-footer-strip strong { color: var(--color-ink-muted); font-weight: 600; }

    /* ================================================================
       OFF-CANVAS DRAWER (SIDEBAR MOBILE) & TOMBOL HAMBURGER
    ================================================================ */
    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(25,24,22,0.35);
        -webkit-backdrop-filter: blur(2px);
        backdrop-filter: blur(2px);
        z-index: 45;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .sidebar-overlay.active { opacity: 1; visibility: visible; }

    .hamburger-btn {
        display: none;
        align-items: center; justify-content: center;
        width: 44px; height: 44px;
        border-radius: 10px;
        background: var(--color-paper-elevated);
        border: 1px solid var(--hairline-strong);
        color: var(--color-ink-muted);
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.22s ease;
    }
    .hamburger-btn:hover { border-color: var(--oxblood-border); color: var(--oxblood-ink); background: var(--oxblood-soft); }
    .hamburger-btn svg { width: 19px; height: 19px; }

    .sidebar-close-btn {
        display: none;
        align-items: center; justify-content: center;
        width: 40px; height: 40px;
        border-radius: 9px;
        background: var(--surface-sunk);
        border: 1px solid var(--hairline);
        color: var(--color-ink-muted);
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.22s ease;
    }
    .sidebar-close-btn:hover { border-color: var(--oxblood-border); color: var(--oxblood-ink); }
    .sidebar-close-btn svg { width: 16px; height: 16px; }

    /* ================================================================
       SCROLL REVEAL — HALUS, DIHORMATI reduced-motion
    ================================================================ */
    [data-reveal] {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.6s cubic-bezier(0.16,1,0.3,1), transform 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    [data-reveal].is-visible { opacity: 1; transform: translateY(0); }

    /* ================================================================
       RESPONSIVE — LAYAR MOBILE (≤860px)
    ================================================================ */
    @media (max-width: 860px) {

        .sidebar {
            transform: translateX(-100%);
            width: min(300px, 86vw);
            box-shadow: 20px 0 60px rgba(25,24,22,0.18);
            transition: transform 0.34s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar.sidebar-open { transform: translateX(0); }

        .sidebar-close-btn { display: flex; }
        .hamburger-btn { display: inline-flex; }

        .main-content { margin-left: 0; }

        .topbar { padding: 16px 20px; gap: 12px; }
        .page-inner { padding: 26px 18px 50px; }

        .topbar-crumb-brand,
        .topbar-crumb-sep { display: none; }

        .studio-header { margin-bottom: 36px; padding-bottom: 28px; }

        .hero-spotlight { padding: 20px; border-radius: 20px; margin-bottom: 32px; }

        .stats-grid { grid-template-columns: 1fr; margin-bottom: 40px; }

        .empty-wrap { padding: 56px 22px; min-height: 320px; }

        .dashboard-footer-strip { justify-content: center; text-align: center; }
    }

    /* ================================================================
       HORMATI PREFERENSI REDUCED MOTION
    ================================================================ */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
        [data-reveal] { opacity: 1; transform: none; }
    }
</style>
@endpush

@section('content')
<a href="#konten-utama" class="skip-link">Lompat ke konten utama</a>
<div class="gallery-spotlight" aria-hidden="true"></div>
<div class="sidebar-overlay" id="siswaSidebarOverlay" aria-hidden="true"></div>
<div class="cursor-follow" id="cursorFollow" aria-hidden="true"><span>Lihat Karya</span></div>

{{-- ================================================================
     SIDEBAR — STUDIO INDEX
================================================================ --}}
<aside class="sidebar" id="siswaSidebar" aria-label="Navigasi utama siswa">

    {{-- Logo --}}
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

            {{-- Tombol tutup drawer — hanya tampil di layar mobile --}}
            <button type="button" class="sidebar-close-btn" id="siswaSidebarClose" aria-label="Tutup menu navigasi">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
            <div class="profile-avatar">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
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

    {{-- Nav — bernomor seperti daftar isi katalog --}}
    <nav class="sidebar-nav" aria-label="Menu utama">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('siswa.dashboard') }}" class="nav-item active" aria-current="page">
            <span class="nav-index">01</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('siswa.portfolio.create') }}" class="nav-item">
            <span class="nav-index">02</span>
            <span>Tambah Karya</span>
        </a>

        <a href="{{ route('siswa.portfolio.print') }}" class="nav-item">
            <span class="nav-index">03</span>
            <span>Cetak Portfolio</span>
        </a>

        <a href="{{ route('siswa.achievement.index') }}" class="nav-item">
            <span class="nav-index">04</span>
            <span>Prestasi &amp; Sertifikat</span>
        </a>

        <div class="nav-label" style="margin-top:22px;">Akun</div>

        <a href="{{ route('siswa.profile.edit') }}" class="nav-item">
            <span class="nav-index">05</span>
            <span>Profil Saya</span>
        </a>
    </nav>

    {{-- Logout --}}
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

    {{-- Top Bar --}}
    <header class="topbar">
        <div style="display:flex; align-items:center; gap:14px; min-width:0;">
            {{-- Tombol hamburger — hanya tampil di layar mobile (≤860px) --}}
            <button type="button" class="hamburger-btn" id="siswaSidebarOpen"
                    aria-label="Buka menu navigasi" aria-controls="siswaSidebar" aria-expanded="false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
            <div class="topbar-title">
                <span class="topbar-crumb-brand">Portal DKV SMEKDA</span>
                <span class="topbar-crumb-sep">/</span>
                <span class="topbar-crumb-current">Dashboard Siswa</span>
            </div>
        </div>
        <div class="date-stamp">{{ now()->translatedFormat('d F Y') }}</div>
    </header>

    <div class="page-inner">

        {{-- Flash Success --}}
        @if(session('success'))
            <div class="flash-note" role="status">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ── STUDIO HEADER ── --}}
        <div class="studio-header">
            <div class="header-watermark" aria-hidden="true">{{ str_pad($totalKarya, 2, '0', STR_PAD_LEFT) }}</div>
            <div style="position:relative; z-index:1;">
                <div class="studio-eyebrow-row">
                    <span>Arsip Kreator &middot; NIS {{ auth()->user()->nis_nip ?? '—' }}</span>
                    <span class="sep">&middot;</span>
                    <span>Status Aktif</span>
                    @if($portfolios->isNotEmpty())
                        <span class="sep">&middot;</span>
                        <a href="{{ route('portfolio.public', $portfolios->first()->slug) }}" target="_blank" rel="noopener noreferrer">Lihat Etalase Publik &#8599;</a>
                    @endif
                </div>
                <h1 class="studio-headline">
                    Selamat datang kembali, <em>{{ explode(' ', auth()->user()->name)[0] }}</em>.
                </h1>
                <p class="studio-sub">Kelola arsip karya, susun kategori, dan siapkan portofolio yang siap dipamerkan ke luar kelas.</p>
            </div>

            {{-- CTA utama halaman: Tambah Karya --}}
            <a href="{{ route('siswa.portfolio.create') }}" class="btn-primary" style="flex-shrink:0; position:relative; z-index:1;">
                <span>Tambah Karya</span>
                <span class="btn-icon-chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
            </a>
        </div>

        {{-- ── HERO SPOTLIGHT — karya terbaru dipajang besar ── --}}
        @if($portfolios->isNotEmpty())
        <section class="hero-spotlight" aria-labelledby="hero-heading">
            <div class="hero-grid">
                <div class="hero-frame">
                    <a href="{{ route('portfolio.public', $portfolios->first()->slug) }}" target="_blank" rel="noopener noreferrer" class="hero-frame-inner" data-glow data-cursor-target>
                        <img
                            src="{{ asset('storage/' . $portfolios->first()->image_path) }}"
                            alt="{{ $portfolios->first()->title }}"
                            class="hero-image"
                            loading="lazy"
                            decoding="async"
                            onerror="this.onerror=null; this.closest('.hero-frame-inner').classList.add('thumb-broken');"
                        >
                        @if($portfolios->first()->file_pdf_path)
                            <div class="pdf-pill">PDF</div>
                        @endif
                    </a>
                </div>
                <div class="hero-content">
                    <div class="hero-eyebrow">Karya Terbaru &middot; No. 001</div>
                    <div class="hero-category">{{ $portfolios->first()->category?->name ?? 'Umum' }}</div>
                    <h2 class="hero-title" id="hero-heading">{{ $portfolios->first()->title }}</h2>
                    <p class="hero-desc">{{ $portfolios->first()->description }}</p>
                    <div class="hero-meta">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $portfolios->first()->created_at->diffForHumans() }}
                    </div>
                    <div class="hero-actions">
                        <a href="{{ route('portfolio.public', $portfolios->first()->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-primary on-dark">
                            <span>Lihat Karya</span>
                            <span class="btn-icon-chip">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 17L17 7M9 7h8v8"/>
                                </svg>
                            </span>
                        </a>
                        <a href="{{ route('siswa.portfolio.edit', $portfolios->first()) }}" class="btn-ghost on-dark">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Karya
                        </a>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- ── STATISTIK — LEDGER ARSIP ── --}}
        <section class="stats-grid" aria-label="Ringkasan statistik karya">

            <div class="stat-block">
                <div class="stat-label"><span class="idx">01</span>Total Karya</div>
                <div class="stat-number" data-count="{{ $totalKarya }}">0</div>
                <div class="stat-bar-wrap">
                    <div class="stat-bar" style="width:{{ min(100, $totalKarya * 8) }}%;"></div>
                </div>
            </div>

            <div class="stat-block">
                <div class="stat-label"><span class="idx">02</span>Karya Poster</div>
                <div class="stat-number" data-count="{{ $totalPoster }}">0</div>
                <div class="stat-bar-wrap">
                    <div class="stat-bar" style="width:{{ $totalKarya > 0 ? min(100, ($totalPoster / $totalKarya) * 100) : 0 }}%;"></div>
                </div>
            </div>

            <div class="stat-block">
                <div class="stat-label"><span class="idx">03</span>Karya UI/UX</div>
                <div class="stat-number" data-count="{{ $totalUIUX }}">0</div>
                <div class="stat-bar-wrap">
                    <div class="stat-bar" style="width:{{ $totalKarya > 0 ? min(100, ($totalUIUX / $totalKarya) * 100) : 0 }}%;"></div>
                </div>
            </div>

        </section>

        {{-- ── ETALASE KARYA ── --}}
        <section aria-labelledby="portfolio-heading" class="showcase-panel">

            <div class="section-header-bar">
                <div>
                    <h2 class="section-title" id="portfolio-heading">Arsip Karya Lengkap</h2>
                    <div class="section-sub">
                        {{ $portfolios->count() }} karya tersimpan &middot; karya terbaru tampil di atas
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <a href="{{ route('siswa.portfolio.print') }}" class="btn-ghost">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Cetak Katalog
                    </a>
                    <a href="{{ route('siswa.portfolio.create') }}" class="btn-primary">
                        <span>Tambah Karya</span>
                        <span class="btn-icon-chip">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            @if($portfolios->isEmpty())

                {{-- Empty State --}}
                <div class="empty-wrap">
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="empty-title">Belum ada karya di arsip ini.</h3>
                    <div class="empty-sub">
                        Unggah karya pertamamu dan mulai membangun katalog portofolio yang siap dibagikan.
                    </div>
                    <a href="{{ route('siswa.portfolio.create') }}" class="btn-primary" style="margin:0 auto;">
                        <span>Unggah Karya Pertama</span>
                        <span class="btn-icon-chip">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                            </svg>
                        </span>
                    </a>
                </div>

            @else

                <div class="portfolio-grid">

                    @foreach($portfolios->skip(1) as $portfolio)
                    <div class="portfolio-card" data-reveal data-glow>
                        <a href="{{ route('portfolio.public', $portfolio->slug) }}" target="_blank" rel="noopener noreferrer" class="thumb-wrapper" data-cursor-target>
                            <img
                                src="{{ asset('storage/' . $portfolio->image_path) }}"
                                alt="{{ $portfolio->title }}"
                                class="portfolio-thumb"
                                loading="lazy"
                                decoding="async"
                                onerror="this.onerror=null; this.closest('.thumb-wrapper').classList.add('thumb-broken');"
                            >
                            <div class="catalog-tag">No. {{ str_pad($loop->iteration + 1, 3, '0', STR_PAD_LEFT) }}</div>
                            @if($portfolio->file_pdf_path)
                                <div class="pdf-pill">PDF</div>
                            @endif
                        </a>
                        <div class="portfolio-body">
                            <div class="portfolio-category">
                                {{ $portfolio->category?->name ?? 'Umum' }}
                            </div>
                            <h3 class="portfolio-title">{{ $portfolio->title }}</h3>
                            <div class="portfolio-desc">{{ $portfolio->description }}</div>
                            <div class="portfolio-meta">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $portfolio->created_at->diffForHumans() }}
                                &nbsp;&middot;&nbsp;
                                {{ $portfolio->created_at->format('d M Y') }}
                            </div>
                            <div class="portfolio-actions">
                                {{-- 1. Tombol Lihat Publik --}}
                                <a href="{{ route('portfolio.public', $portfolio->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-action">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat
                                </a>

                                {{-- 2. Tombol Edit --}}
                                <a href="{{ route('siswa.portfolio.edit', $portfolio) }}" class="btn-action">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                {{-- 3. Tombol Hapus --}}
                                <form
                                    method="POST"
                                    action="{{ route('siswa.portfolio.destroy', $portfolio) }}"
                                    style="flex:1; display:flex;"
                                    onsubmit="return confirm('Hapus karya ini? Tindakan tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Add Placeholder --}}
                    <a href="{{ route('siswa.portfolio.create') }}" class="add-card">
                        <div class="add-card-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="add-card-text">Tambah Karya</div>
                    </a>

                </div>

            @endif

        </section>

        {{-- Footer Strip --}}
        <div class="dashboard-footer-strip">
            <span>
                &copy; {{ date('Y') }} <strong>DKV SMEKDA</strong>
                &nbsp;&middot;&nbsp; SMK Negeri 2 Padang Panjang
            </span>
            <span>
                Dikembangkan untuk Skripsi oleh <strong>Rafli</strong> &mdash; 2026
            </span>
        </div>

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

        function isMobile() {
            return window.innerWidth <= 860;
        }

        // Sinkronkan aria-hidden dengan status drawer supaya screen reader
        // tidak "melihat" menu yang sedang disembunyikan secara visual.
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

        // Perangkap fokus sederhana: Tab/Shift+Tab berputar di dalam drawer
        // selama drawer terbuka di layar mobile.
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

        // Tutup drawer otomatis begitu salah satu menu/logout ditekan di mobile
        document.querySelectorAll('.sidebar-nav .nav-item, .sidebar-footer .btn-logout').forEach(function (el) {
            el.addEventListener('click', function () { closeSidebar(false); });
        });

        // Reset state drawer kalau layar dibesarkan melewati breakpoint mobile
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
<script>
    // Reveal halus untuk kartu karya saat masuk viewport — dihormati prefers-reduced-motion
    (function () {
        var items = document.querySelectorAll('[data-reveal]');
        if (!items.length) return;

        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduceMotion || !('IntersectionObserver' in window)) {
            items.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        items.forEach(function (el, i) {
            el.style.transitionDelay = Math.min(i * 55, 330) + 'ms';
            observer.observe(el);
        });
    })();
</script>
<script>
    // Sorotan kursor pada kartu karya & bingkai Hero — murni progresif,
    // tidak memengaruhi fungsi apa pun jika browser tidak mendukungnya.
    (function () {
        if (!window.matchMedia('(hover: hover)').matches) return;

        document.addEventListener('pointermove', function (e) {
            var target = e.target.closest && e.target.closest('[data-glow]');
            if (!target) return;
            var r = target.getBoundingClientRect();
            target.style.setProperty('--mx', (e.clientX - r.left) + 'px');
            target.style.setProperty('--my', (e.clientY - r.top) + 'px');
        }, { passive: true });
    })();
</script>
<script>
    // Pil "Lihat Karya" yang mengikuti kursor di atas thumbnail —
    // hanya aktif di perangkat yang benar-benar punya kursor (hover: hover).
    (function () {
        var follower = document.getElementById('cursorFollow');
        var targets = document.querySelectorAll('[data-cursor-target]');
        if (!follower || !targets.length || !window.matchMedia('(hover: hover)').matches) return;

        var ticking = false, mx = -999, my = -999;
        function apply() {
            follower.style.setProperty('--fx', mx + 'px');
            follower.style.setProperty('--fy', my + 'px');
            ticking = false;
        }

        targets.forEach(function (t) {
            t.addEventListener('mouseenter', function () { follower.classList.add('is-active'); });
            t.addEventListener('mouseleave', function () { follower.classList.remove('is-active'); });
            t.addEventListener('mousemove', function (e) {
                mx = e.clientX; my = e.clientY;
                if (!ticking) { ticking = true; requestAnimationFrame(apply); }
            });
        });
    })();
</script>
<script>
    // Hitung-naik pada angka statistik saat blok masuk viewport —
    // langsung ke nilai akhir bila prefers-reduced-motion aktif.
    (function () {
        var nums = document.querySelectorAll('.stat-number[data-count]');
        if (!nums.length) return;

        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function animate(el) {
            var target = parseInt(el.getAttribute('data-count'), 10) || 0;
            if (reduceMotion || !target) { el.textContent = target; return; }
            var start = null, duration = 900;
            function step(ts) {
                if (!start) start = ts;
                var progress = Math.min((ts - start) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target);
                if (progress < 1) { requestAnimationFrame(step); }
                else { el.textContent = target; }
            }
            requestAnimationFrame(step);
        }

        if (!('IntersectionObserver' in window)) {
            nums.forEach(function (el) { el.textContent = el.getAttribute('data-count'); });
            return;
        }

        var statObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animate(entry.target);
                    statObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        nums.forEach(function (el) { statObserver.observe(el); });
    })();
</script>
@endpushJSVO  