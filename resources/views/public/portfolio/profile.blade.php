@extends('layouts.app')

@section('title', 'Portfolio ' . $user->name . ' — DKV SMKN 2 Padang Panjang')

@push('meta')
<meta name="description" content="{{ Str::limit($user->bio ?? 'Kumpulan karya desain komunikasi visual '.$user->name, 160) }}">
<meta property="og:title" content="Portfolio {{ $user->name }}">
<meta property="og:description" content="{{ Str::limit($user->bio ?? 'Kumpulan karya desain komunikasi visual', 160) }}">
@if($portfolios->first())
<meta property="og:image" content="{{ asset('storage/' . $portfolios->first()->image_path) }}">
@endif
<meta property="og:type" content="profile">
<meta name="twitter:card" content="summary_large_image">
@endpush

{{-- Halaman publik ini punya navbar & footer sendiri (nav anchor-link +
     footer kontak/CTA), jadi navbar/footer bawaan layout tidak dipakai —
     sama seperti pola di guru/dashboard.blade.php dan auth/login.blade.php. --}}
@section('navbar')@endsection
@section('footer')@endsection

@push('styles')
<style>

        /* ══════════════════════════════════════════════════════════════
           DESIGN TOKENS — "Editorial Light Museum"
           Kertas museum hangat + tinta charcoal + aksen oxblood tunggal.
           Motif berulang: tanda register/crop-mark khas pracetak (DKV),
           dipakai sebagai signature visual — bukan dekorasi generik.
           ══════════════════════════════════════════════════════════════ */
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,380;0,9..144,460;0,9..144,560;1,9..144,440;1,9..144,520&family=Public+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=IBM+Plex+Mono:wght@400;500&display=swap');

        :root {
            --paper:        #FAF7F2;
            --paper-raised: #FFFEFB;
            --ink:          #191816;
            --ink-soft:     #3A3733;
            --muted:        #57534E;
            --faint:        #8C857D;
            --line:         rgba(120,113,108,0.24);
            --line-soft:    rgba(120,113,108,0.14);
            --oxblood:      #7A2E2E;
            --oxblood-ink:  #5E2222;
            --oxblood-04:   rgba(122,46,46,0.04);
            --oxblood-08:   rgba(122,46,46,0.08);
            --oxblood-14:   rgba(122,46,46,0.14);
            --oxblood-24:   rgba(122,46,46,0.26);
            --shadow-ambient: 0 1px 2px rgba(58,46,36,0.05), 0 14px 34px -16px rgba(58,46,36,0.14);
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Public Sans', sans-serif;
            background-color: var(--paper);
            color: var(--ink);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Serat kertas halus — pengganti noise neon versi lama, tetap dipakai
           agar permukaan tidak terasa flat/steril (lihat catatan redesign). */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
            opacity: 0.5;
            mix-blend-mode: multiply;
            pointer-events: none; z-index: 0;
        }

        .ambient-light { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
        .ambient-light-1 {
            top: -200px; right: -140px; width: 620px; height: 620px;
            background: radial-gradient(circle, rgba(122,46,46,0.055) 0%, transparent 68%);
        }
        .ambient-light-2 {
            bottom: -180px; left: -160px; width: 520px; height: 520px;
            background: radial-gradient(circle, rgba(122,46,46,0.035) 0%, transparent 68%);
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--paper); }
        ::-webkit-scrollbar-thumb { background: var(--oxblood); border-radius: 10px; }

        a:focus-visible, .share-copy-btn:focus-visible, button:focus-visible {
            outline: 2px solid var(--oxblood); outline-offset: 3px; border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
        }

        /* Reveal-on-scroll (tambahan; tidak mengganggu copyLink()/scroll-spy) */
        .reveal { opacity: 0; transform: translateY(16px); transition: opacity .7s var(--ease), transform .7s var(--ease); }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }

        /* Tanda register/crop-mark — motif berulang dari dunia pracetak DKV */
        .corner { position: absolute; width: 12px; height: 12px; border-color: var(--oxblood); pointer-events: none; }
        .corner-tl { border-top: 1.4px solid; border-left: 1.4px solid; }
        .corner-tr { border-top: 1.4px solid; border-right: 1.4px solid; }
        .corner-bl { border-bottom: 1.4px solid; border-left: 1.4px solid; }
        .corner-br { border-bottom: 1.4px solid; border-right: 1.4px solid; }

        /* ══════════════════════ NAVBAR — floating curatorial island ══════════════════════ */
        .public-nav-wrap { position: sticky; top: 0; z-index: 50; padding: 14px 20px 0; }
        .public-nav {
            max-width: 900px; margin: 0 auto;
            background: rgba(250,247,242,0.86);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow-ambient);
            padding: 8px 8px 8px 16px;
        }
        .nav-inner { display: flex; align-items: center; gap: 16px; }
        .nav-brand { display: flex; align-items: center; gap: 9px; text-decoration: none; flex-shrink: 0; }
        .nav-logo-icon {
            width: 26px; height: 26px; border-radius: 50%;
            border: 1.4px solid var(--oxblood);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .nav-logo-icon svg { width: 13px; height: 13px; stroke: var(--oxblood); }
        .nav-brand-text {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.68rem; font-weight: 500; letter-spacing: 2.5px;
            text-transform: uppercase; color: var(--ink);
            white-space: nowrap;
        }
        .nav-brand-text span { color: var(--oxblood); }

        .nav-links {
            display: flex; align-items: center; gap: 2px;
            margin: 0 auto 0 4px;
            overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;
        }
        .nav-links::-webkit-scrollbar { display: none; }
        .nav-link {
            flex-shrink: 0;
            font-size: 0.78rem; font-weight: 600; letter-spacing: 0.1px;
            color: var(--muted); text-decoration: none;
            padding: 8px 14px; border-radius: 10px;
            white-space: nowrap; transition: all 0.25s var(--ease);
        }
        .nav-link:hover { color: var(--ink); background: rgba(120,113,108,0.08); }
        .nav-link.active { color: var(--oxblood-ink); background: var(--oxblood-08); }

        .nav-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--oxblood-08); border: 1px solid var(--oxblood-14);
            border-radius: 20px; padding: 6px 12px 6px 10px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.6rem; font-weight: 500; color: var(--oxblood-ink);
            letter-spacing: 1.2px; text-transform: uppercase;
            flex-shrink: 0; white-space: nowrap;
        }
        .live-dot {
            width: 5px; height: 5px; background: var(--oxblood); border-radius: 50%;
            animation: livePulse 1.8s ease-in-out infinite;
            flex-shrink: 0; display: inline-block;
        }
        @keyframes livePulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.35; transform: scale(0.6); }
        }

        @media (max-width: 768px) {
            .nav-badge { display: none; }
            .public-nav-wrap { padding: 10px 14px 0; }
            /* Target sentuh: pill nav dinaikkan ke minimal 44px khusus di mobile/tablet. */
            .nav-link {
                display: inline-flex;
                align-items: center;
                min-height: 44px;
                padding: 8px 14px;
            }
        }

        .main-wrap { position: relative; z-index: 1; max-width: 1040px; margin: 0 auto; padding: 0 24px; }

        section[id] { scroll-margin-top: 96px; }

        /* ══════════════════════ HERO — Artist Stage ══════════════════════ */
        .hero {
            padding: 76px 0 48px;
            display: flex; flex-direction: column; align-items: center;
            text-align: center;
        }

        /* Bingkai matting foto — signature element: bidang kertas outer
           menirukan mat-board galeri, dengan tanda register di keempat sudut. */
        .hero-frame {
            position: relative;
            width: 148px; height: 148px;
            padding: 10px;
            background: var(--paper-raised);
            border: 1px solid var(--line);
            box-shadow: var(--shadow-ambient);
            margin-bottom: 26px;
        }
        .hero-frame-inner {
            width: 100%; height: 100%; overflow: hidden;
            background: linear-gradient(155deg, #E4DCCB, #C9BEA8);
            display: flex; align-items: center; justify-content: center;
        }
        .hero-frame-inner img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .hero-frame-initial { font-family: 'Fraunces', serif; font-weight: 500; font-size: 2.5rem; color: var(--paper-raised); }
        .hero-frame .corner-tl { top: -6px; left: -6px; }
        .hero-frame .corner-tr { top: -6px; right: -6px; }
        .hero-frame .corner-bl { bottom: -6px; left: -6px; }
        .hero-frame .corner-br { bottom: -6px; right: -6px; }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.68rem; font-weight: 500; letter-spacing: 2px;
            text-transform: uppercase; color: var(--muted);
            margin-bottom: 18px;
        }

        .hero-name {
            font-family: 'Fraunces', serif; font-weight: 560;
            font-size: clamp(2.3rem, 7vw, 4.4rem);
            letter-spacing: -0.02em; line-height: 1.05; color: var(--ink);
            margin-bottom: 14px; word-break: break-word;
        }
        .hero-tagline {
            font-family: 'Fraunces', serif; font-style: italic; font-weight: 440;
            font-size: clamp(1rem, 2vw, 1.25rem);
            color: var(--oxblood-ink); margin-bottom: 8px;
        }
        .hero-institution {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.72rem; letter-spacing: 1.2px; text-transform: uppercase;
            color: var(--faint); margin-bottom: 36px;
        }

        .hero-cta-row { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; width: 100%; }
        .btn-primary, .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.85rem; font-weight: 600;
            padding: 13px 22px; border-radius: 10px;
            text-decoration: none; transition: all 0.3s var(--ease);
            border: 1px solid transparent;
        }
        .btn-primary { background: var(--ink); color: var(--paper); }
        .btn-primary:hover { background: var(--oxblood-ink); transform: translateY(-2px); box-shadow: 0 12px 26px -10px rgba(122,46,46,0.4); }
        .btn-outline { background: transparent; color: var(--ink); border-color: var(--line); }
        .btn-outline:hover { border-color: var(--ink); background: rgba(25,24,22,0.03); transform: translateY(-2px); }
        .btn-primary svg, .btn-outline svg { width: 15px; height: 15px; flex-shrink: 0; }

        @media (max-width: 480px) {
            .hero-cta-row { flex-direction: column; }
            .btn-primary, .btn-outline { justify-content: center; width: 100%; min-height: 48px; }
        }

        /* ══════════════════════ SHARE BAR ══════════════════════ */
        .share-bar {
            display: flex; align-items: center; gap: 10px;
            background: var(--paper-raised); border: 1px solid var(--line);
            border-radius: 12px; padding: 11px 14px; margin-bottom: 64px;
            flex-wrap: wrap;
        }
        .share-label { font-family: 'IBM Plex Mono', monospace; font-size: 0.6rem; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: var(--faint); }
        .share-url-text { flex: 1; font-family: 'IBM Plex Mono', monospace; font-size: 0.76rem; color: var(--muted); min-width: 160px; word-break: break-all; }
        .share-copy-btn {
            font-size: 0.68rem; font-weight: 700; color: var(--oxblood-ink);
            letter-spacing: 0.4px; text-transform: uppercase; cursor: pointer;
            background: var(--oxblood-08); border: 1px solid var(--oxblood-14);
            padding: 7px 14px; border-radius: 8px; transition: all 0.25s var(--ease);
            flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .share-copy-btn:hover { background: var(--oxblood-14); }
        @media (max-width: 768px) {
            .share-copy-btn { min-height: 44px; padding: 7px 16px; }
        }

        /* ══════════════════════ LABEL SECTION (reusable) ══════════════════════ */
        .section-block { padding-bottom: 68px; }
        .section-header { display: flex; align-items: baseline; gap: 10px; margin-bottom: 26px; }
        .section-index { font-family: 'IBM Plex Mono', monospace; font-size: 0.68rem; color: var(--oxblood); font-weight: 500; flex-shrink: 0; }
        .section-label { font-family: 'IBM Plex Mono', monospace; font-size: 0.68rem; font-weight: 500; letter-spacing: 3px; text-transform: uppercase; color: var(--muted); flex-shrink: 0; }
        .section-rule { flex: 1; height: 1px; background: var(--line); }

        /* ══════════════════════ TENTANG ══════════════════════ */
        .about-card {
            background: var(--paper-raised); border: 1px solid var(--line);
            border-radius: 16px; padding: 36px; box-shadow: var(--shadow-ambient);
        }
        .about-bio {
            font-family: 'Fraunces', serif; font-weight: 400;
            font-size: 1.05rem; line-height: 1.72; color: var(--ink-soft);
            max-width: 640px; margin-bottom: 30px;
        }
        .info-grid {
            display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px 30px;
            padding-top: 26px; border-top: 1px solid var(--line-soft);
            margin-bottom: 26px;
        }
        .info-item-label { font-family: 'IBM Plex Mono', monospace; font-size: 0.6rem; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: var(--faint); margin-bottom: 7px; }
        .info-item-value { font-size: 0.92rem; font-weight: 600; color: var(--ink); word-break: break-word; }

        .stat-row { display: flex; gap: 36px; padding-top: 26px; border-top: 1px solid var(--line-soft); }
        .stat-box { text-align: left; }
        .stat-num { font-family: 'Fraunces', serif; font-weight: 560; font-size: 2rem; color: var(--ink); line-height: 1; }
        .stat-label {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.6rem; font-weight: 500; letter-spacing: 1.5px;
            text-transform: uppercase; color: var(--faint); margin-top: 7px;
        }

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
            .about-card { padding: 24px; }
        }

        /* ══════════════════════ KEAHLIAN ══════════════════════ */
        .skills-wrap { display: flex; flex-wrap: wrap; gap: 10px; }
        .skill-tag {
            display: inline-flex; align-items: center;
            background: var(--paper-raised); border: 1px solid var(--line);
            border-radius: 8px; padding: 9px 16px;
            font-size: 0.82rem; font-weight: 600; color: var(--ink-soft);
            transition: all 0.25s var(--ease);
        }
        .skill-tag:hover { border-color: var(--oxblood-24); color: var(--oxblood-ink); background: var(--oxblood-04); }

        /* ══════════════════════ KARYA — Exhibition Works Showcase (bento) ══════════════════════ */
        .works-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px 20px; }
        .work-card { text-decoration: none; display: block; color: inherit; }

        .work-figure {
            position: relative; overflow: hidden;
            background: #EDE7DA; border: 1px solid var(--line); border-radius: 4px;
            margin-bottom: 13px;
            transition: border-color 0.35s var(--ease), box-shadow 0.35s var(--ease);
        }
        /* Rasio natural bergantian — bukan crop pipih 160px seragam */
        .work-card:nth-child(6n+1) .work-figure, .work-card:nth-child(6n+4) .work-figure { aspect-ratio: 4 / 3; }
        .work-card:nth-child(6n+2) .work-figure { aspect-ratio: 3 / 4; }
        .work-card:nth-child(6n+3) .work-figure, .work-card:nth-child(6n+6) .work-figure { aspect-ratio: 16 / 9; }
        .work-card:nth-child(6n+5) .work-figure { aspect-ratio: 1 / 1; }
        .work-card:nth-child(5n) { grid-column: span 2; }
        .work-card:nth-child(5n) .work-figure { aspect-ratio: 16 / 9; }

        .work-thumb { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.7s var(--ease); }
        .work-card:hover .work-thumb { transform: scale(1.045); }
        .work-card:hover .work-figure { border-color: var(--oxblood-24); box-shadow: var(--shadow-ambient); }

        .work-figure .corner { opacity: 0; transition: opacity 0.3s var(--ease); z-index: 2; }
        .work-figure .corner-tl { top: 7px; left: 7px; }
        .work-figure .corner-br { bottom: 7px; right: 7px; }
        .work-card:hover .corner { opacity: 1; }

        /* Label dinding galeri — nomor plat + judul + kategori */
        .work-plate { display: flex; align-items: baseline; gap: 10px; }
        .work-index { font-family: 'IBM Plex Mono', monospace; font-size: 0.72rem; color: var(--oxblood); flex-shrink: 0; padding-top: 2px; }
        .work-meta { min-width: 0; }
        .work-cat { font-family: 'IBM Plex Mono', monospace; font-size: 0.6rem; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: var(--faint); margin-bottom: 4px; }
        .work-title { font-family: 'Fraunces', serif; font-weight: 500; font-style: italic; font-size: 1.02rem; color: var(--ink); line-height: 1.3; }

        .empty-state {
            text-align: center; padding: 64px 24px; color: var(--faint);
            border: 1px dashed var(--line); border-radius: 14px; font-size: 0.85rem;
        }

        /* ══════════════════════ PRESTASI & SERTIFIKAT — Exhibition Accolades ══════════════════════ */
        .achv-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .achv-card {
            background: var(--paper-raised); border: 1px solid var(--line);
            border-radius: 14px; overflow: hidden; transition: all 0.35s var(--ease);
        }
        .achv-card:hover { border-color: var(--oxblood-24); box-shadow: var(--shadow-ambient); transform: translateY(-3px); }
        .achv-thumb-wrap { position: relative; aspect-ratio: 3 / 4; overflow: hidden; background: #EDE7DA; border-bottom: 1px solid var(--line); }
        .achv-thumb { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.6s var(--ease); }
        .achv-card:hover .achv-thumb { transform: scale(1.045); }
        .achv-thumb-placeholder {
            width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(160deg, #F1ECE0, #E1D8C4);
        }
        .achv-thumb-placeholder svg { width: 34px; height: 34px; color: var(--faint); }
        .achv-body { padding: 18px; }
        .achv-type {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--oxblood-08); border: 1px solid var(--oxblood-14);
            color: var(--oxblood-ink); font-family: 'IBM Plex Mono', monospace;
            font-size: 0.58rem; font-weight: 500; letter-spacing: 1.4px;
            text-transform: uppercase; padding: 4px 9px; border-radius: 20px; margin-bottom: 11px;
        }
        .achv-type svg { width: 10px; height: 10px; }
        .achv-title { font-family: 'Fraunces', serif; font-weight: 500; font-size: 0.98rem; color: var(--ink); line-height: 1.35; margin-bottom: 9px; }
        .achv-issuer {
            display: flex; align-items: center; gap: 6px; font-size: 0.78rem;
            color: var(--muted); margin-bottom: 9px; font-weight: 500;
        }
        .achv-issuer svg { width: 12px; height: 12px; flex-shrink: 0; color: var(--faint); }
        .achv-meta {
            display: flex; align-items: center; gap: 6px;
            font-family: 'IBM Plex Mono', monospace; font-size: 0.66rem; color: var(--faint); margin-bottom: 12px;
        }
        .achv-meta svg { width: 11px; height: 11px; }
        .achv-link {
            display: inline-flex; align-items: center; gap: 6px; font-size: 0.74rem; font-weight: 700;
            color: var(--oxblood-ink); text-decoration: none; padding-top: 12px;
            border-top: 1px solid var(--line-soft); width: 100%; transition: color 0.2s var(--ease);
        }
        .achv-link:hover { color: var(--ink); }
        .achv-link svg { width: 12px; height: 12px; }

        @media (max-width: 1023px) {
            .works-grid { grid-template-columns: repeat(2, 1fr); }
            .achv-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 639px) {
            .works-grid { grid-template-columns: repeat(1, 1fr); gap: 30px; }
            .work-card:nth-child(5n) { grid-column: span 1; }
            .work-card .work-figure { aspect-ratio: 4 / 3 !important; }
            .achv-grid { grid-template-columns: repeat(1, 1fr); gap: 16px; }
            .hero { padding: 56px 0 36px; }
        }

        /* ══════════════════════ FOOTER / KONTAK ══════════════════════ */
        .public-footer {
            border-top: 1px solid var(--line); padding: 60px 24px 36px; text-align: center;
            position: relative; z-index: 1;
        }
        .footer-eyebrow { font-family: 'IBM Plex Mono', monospace; font-size: 0.62rem; letter-spacing: 2px; text-transform: uppercase; color: var(--faint); margin-bottom: 14px; }
        .footer-cta-title { font-family: 'Fraunces', serif; font-weight: 560; font-size: 1.6rem; color: var(--ink); margin-bottom: 10px; }
        .footer-cta-sub { font-size: 0.86rem; color: var(--muted); margin-bottom: 30px; }
        .footer-contact-row {
            display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;
            margin-bottom: 34px;
        }
        .footer-pill {
            display: inline-flex; align-items: center; gap: 9px;
            background: var(--paper-raised); border: 1px solid var(--line);
            border-radius: 10px; padding: 11px 20px; font-size: 0.84rem; font-weight: 600;
            color: var(--ink-soft); text-decoration: none; transition: all 0.25s var(--ease);
        }
        @media (max-width: 768px) {
            .footer-pill { min-height: 44px; }
        }
        .footer-pill:hover { border-color: var(--oxblood-24); color: var(--oxblood-ink); background: var(--oxblood-04); }
        .footer-pill svg { width: 15px; height: 15px; color: var(--oxblood); flex-shrink: 0; }

        .footer-text { font-family: 'IBM Plex Mono', monospace; font-size: 0.66rem; color: var(--faint); margin-top: 10px; line-height: 2; }
        .footer-text strong { color: var(--muted); font-weight: 500; }
        .footer-text span { color: var(--oxblood); }

        .toast {
            position: fixed; bottom: 24px; right: 24px; left: 24px; z-index: 9999;
            max-width: 320px; margin-left: auto;
            background: var(--ink); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px; padding: 13px 18px; display: flex; align-items: center; gap: 10px;
            font-size: 0.8rem; font-weight: 600; color: var(--paper);
            opacity: 0; transform: translateY(10px); transition: all 0.35s var(--ease); pointer-events: none;
        }
        .toast.show { opacity: 1; transform: translateY(0); }

    </style>
@endpush

@section('content')
<div class="ambient-light ambient-light-1"></div>
<div class="ambient-light ambient-light-2"></div>

<div class="public-nav-wrap">
<nav class="public-nav">
    <div class="nav-inner">
        <a href="#top" class="nav-brand">
            <div class="nav-logo-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                    <circle cx="12" cy="12" r="4.5" stroke-linecap="round"/>
                    <path stroke-linecap="round" d="M12 2v3.5M12 18.5V22M2 12h3.5M18.5 12H22"/>
                </svg>
            </div>
            <span class="nav-brand-text">DKV<span>.</span>SMEKDA</span>
        </a>

        <div class="nav-links" id="navLinks">
            <a href="#tentang" class="nav-link" data-section="tentang">Profil</a>
            @if(!empty($user->skills))
            <a href="#keahlian" class="nav-link" data-section="keahlian">Keahlian</a>
            @endif
            <a href="#karya" class="nav-link" data-section="karya">Karya</a>
            @if($achievements->isNotEmpty())
            <a href="#prestasi" class="nav-link" data-section="prestasi">Prestasi</a>
            @endif
            <a href="#kontak" class="nav-link" data-section="kontak">Kontak</a>
        </div>

        <div class="nav-badge">
            <span class="live-dot"></span>
            Live Exhibition
        </div>
    </div>
</nav>
</div>

<div id="top"></div>

@php
    /**
     * Tagline otomatis berdasarkan kategori karya terbanyak siswa.
     * Murni logika tampilan (tanpa kolom baru di database) agar
     * setiap profil terasa personal sesuai karya yang sudah diunggah.
     */
    $tagline = 'Siswa Desain Komunikasi Visual';
    $kategoriDominan = $portfolios
        ->pluck('category.name')
        ->filter()
        ->countBy()
        ->sortDesc()
        ->keys()
        ->first();
    if ($kategoriDominan) {
        $tagline = 'Spesialis ' . $kategoriDominan;
    }

    /**
     * Ubah nomor WA ($user->contact) menjadi format internasional (62xxxxxxxxxx)
     * agar tautan wa.me di footer selalu valid, baik nomor ditulis diawali "0"
     * ataupun "+62".
     */
    $waNumber = null;
    if ($user->contact) {
        $waDigits = preg_replace('/\D/', '', $user->contact);
        if (str_starts_with($waDigits, '0')) {
            $waDigits = '62' . substr($waDigits, 1);
        }
        $waNumber = $waDigits;
    }
@endphp

<div class="main-wrap">

    {{-- ══════════════ HERO / ARTIST STAGE ══════════════ --}}
    <div class="hero">
        <div class="hero-frame">
            <span class="corner corner-tl"></span>
            <span class="corner corner-tr"></span>
            <span class="corner corner-bl"></span>
            <span class="corner corner-br"></span>
            <div class="hero-frame-inner">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
                @else
                    <span class="hero-frame-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>
        </div>

        <div class="hero-eyebrow">
            <span class="live-dot"></span> Pameran Aktif
        </div>

        <h1 class="hero-name">{{ $user->name }}</h1>
        <p class="hero-tagline">{{ $tagline }}</p>
        <p class="hero-institution">Siswa DKV — SMKN 2 Padang Panjang</p>

        <div class="hero-cta-row">
            <a href="#karya" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><rect x="3.5" y="3.5" width="7" height="7" rx="1"/><rect x="13.5" y="3.5" width="7" height="7" rx="1"/><rect x="3.5" y="13.5" width="7" height="7" rx="1"/><rect x="13.5" y="13.5" width="7" height="7" rx="1"/></svg>
                Lihat Karya
            </a>
            <a href="{{ route('portfolio.public.print', $user->portfolio_slug) }}" target="_blank" rel="noopener" class="btn-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
                Unduh Portofolio PDF
            </a>
        </div>
    </div>

    {{-- ══════════════ SHARE URL ══════════════ --}}
    <div class="share-bar">
        <span class="share-label">Live URL</span>
        <span class="share-url-text" id="shareUrl">{{ url('/u/' . $user->portfolio_slug) }}</span>
        <span class="share-copy-btn" onclick="copyLink()">Copy Link</span>
    </div>

    {{-- ══════════════ TENTANG ══════════════ --}}
    <section id="tentang" class="section-block">
        <div class="section-header">
            <span class="section-index">§01</span>
            <span class="section-label">Tentang</span>
            <span class="section-rule"></span>
        </div>
        <div class="about-card reveal">
            @if($user->bio)
            <p class="about-bio">{{ $user->bio }}</p>
            @endif

            <div class="info-grid">
                @if($user->nis_nip)
                <div>
                    <div class="info-item-label">NIS / NIP</div>
                    <div class="info-item-value">{{ $user->nis_nip }}</div>
                </div>
                @endif
                <div>
                    <div class="info-item-label">Institusi</div>
                    <div class="info-item-value">SMK Negeri 2 Padang Panjang</div>
                </div>
                @if($user->contact)
                <div>
                    <div class="info-item-label">Kontak (WhatsApp)</div>
                    <div class="info-item-value">{{ $user->contact }}</div>
                </div>
                @endif
                <div>
                    <div class="info-item-label">Program Keahlian</div>
                    <div class="info-item-value">Desain Komunikasi Visual</div>
                </div>
            </div>

            <div class="stat-row">
                <div class="stat-box">
                    <div class="stat-num">{{ $totalKarya }}</div>
                    <div class="stat-label">Karya</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $totalKategori }}</div>
                    <div class="stat-label">Kategori</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ KEAHLIAN ══════════════ --}}
    @if(!empty($user->skills))
    <section id="keahlian" class="section-block">
        <div class="section-header">
            <span class="section-index">§02</span>
            <span class="section-label">Keahlian</span>
            <span class="section-rule"></span>
        </div>
        <div class="skills-wrap reveal">
            @foreach($user->skills as $skill)
                <span class="skill-tag">{{ $skill }}</span>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ══════════════ KARYA ══════════════ --}}
    <section id="karya" class="section-block">
        <div class="section-header">
            <span class="section-index">§03</span>
            <span class="section-label">Semua Karya</span>
            <span class="section-rule"></span>
        </div>

        @if($portfolios->count() > 0)
        <div class="works-grid">
            @foreach($portfolios as $portfolio)
            <a href="{{ route('portfolio.public', $portfolio->slug) }}" class="work-card reveal">
                <div class="work-figure">
                    <span class="corner corner-tl"></span>
                    <span class="corner corner-br"></span>
                    <img
                        src="{{ asset('storage/' . $portfolio->image_path) }}"
                        alt="{{ $portfolio->title }}"
                        class="work-thumb"
                        loading="lazy"
                        onerror="this.style.background='#EDE7DA'"
                    >
                </div>
                <div class="work-plate">
                    <span class="work-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="work-meta">
                        <div class="work-cat">{{ $portfolio->category?->name ?? 'Umum' }}</div>
                        <div class="work-title">{{ $portfolio->title }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state">Siswa ini belum mengunggah karya.</div>
        @endif
    </section>

    {{-- ══════════════ PRESTASI & SERTIFIKAT ══════════════ --}}
    @if($achievements->isNotEmpty())
    <section id="prestasi" class="section-block">
        <div class="section-header">
            <span class="section-index">§04</span>
            <span class="section-label">Prestasi &amp; Sertifikat</span>
            <span class="section-rule"></span>
        </div>

        <div class="achv-grid">
            @foreach($achievements as $achievement)
            <div class="achv-card reveal">
                <div class="achv-thumb-wrap">
                    @if($achievement->image_path)
                        <img
                            src="{{ asset('storage/' . $achievement->image_path) }}"
                            alt="{{ $achievement->title }}"
                            class="achv-thumb"
                            loading="lazy"
                            onerror="this.style.background='#EDE7DA'"
                        >
                    @else
                        <div class="achv-thumb-placeholder">
                            @if($achievement->type === 'prestasi')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
                                </svg>
                            @else
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="achv-body">
                    <div class="achv-type">
                        @if($achievement->type === 'prestasi')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
                            </svg>
                            Prestasi
                        @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Sertifikat
                        @endif
                    </div>
                    <div class="achv-title">{{ $achievement->title }}</div>

                    @if($achievement->issuer)
                        <div class="achv-issuer">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m4-14h6m-6 4h6m-6 4h6M9 21v-4a1 1 0 011-1h4a1 1 0 011 1v4"/>
                            </svg>
                            {{ $achievement->issuer }}
                        </div>
                    @endif

                    @if($achievement->achieved_at)
                        <div class="achv-meta">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $achievement->achieved_at->translatedFormat('d M Y') }}
                        </div>
                    @endif

                    @if($achievement->file_path)
                        <a href="{{ asset('storage/' . $achievement->file_path) }}" target="_blank" rel="noopener" class="achv-link">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Lihat Dokumen
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>

{{-- ══════════════ FOOTER / KONTAK ══════════════ --}}
<footer id="kontak" class="public-footer">
    <div class="footer-eyebrow">Ruang Kurasi</div>
    <div class="footer-cta-title reveal">Tertarik Berkolaborasi?</div>
    <p class="footer-cta-sub">Hubungi langsung atau unduh portofolio lengkap dalam format PDF.</p>

    <div class="footer-contact-row">
        @if($user->contact)
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="footer-pill">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            {{ $user->contact }}
        </a>
        @endif
        <a href="{{ route('portfolio.public.print', $user->portfolio_slug) }}" target="_blank" rel="noopener" class="footer-pill">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
            Unduh Portofolio (PDF)
        </a>
    </div>

    <p class="footer-text">
        &copy; {{ date('Y') }}
        &nbsp;<strong>DKV SMEKDA</strong>&nbsp;
        &bull; SMK Negeri 2 Padang Panjang
        &bull; Sistem Portfolio Digital
        &bull; Dikembangkan oleh <strong>Rafli</strong> &mdash; <span>2026</span>
    </p>
</footer>

<div class="toast" id="toast">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    Link berhasil disalin ke clipboard!
</div>
@endsection

@push('scripts')
<script>
    // Salin tautan Live URL ke clipboard, dengan fallback untuk browser lama/tanpa izin clipboard API
    function copyLink() {
        const url = document.getElementById('shareUrl').textContent.trim();
        const showToast = () => {
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2800);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(showToast).catch(() => {
                const el = document.createElement('textarea');
                el.value = url;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                showToast();
            });
        } else {
            const el = document.createElement('textarea');
            el.value = url;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            showToast();
        }
    }

    // Navbar scroll-spy: menandai section yang sedang dilihat memakai IntersectionObserver
    // bawaan browser (tanpa library tambahan), lalu memberi class "active" pada nav-link terkait.
    (function () {
        const navLinks = Array.from(document.querySelectorAll('.nav-link'));
        const linkBySection = new Map(navLinks.map((link) => [link.dataset.section, link]));

        const sections = Array.from(linkBySection.keys())
            .map((id) => document.getElementById(id))
            .filter(Boolean);

        if (!('IntersectionObserver' in window) || sections.length === 0) return;

        const setActive = (id) => {
            navLinks.forEach((l) => l.classList.remove('active'));
            const active = linkBySection.get(id);
            if (active) active.classList.add('active');
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) setActive(entry.target.id);
            });
        }, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });

        sections.forEach((section) => observer.observe(section));
    })();

    // Reveal-on-scroll: fade-up halus untuk kartu bio, chip keahlian, karya,
    // prestasi, dan CTA footer. Terpisah dari scroll-spy di atas agar tidak
    // saling mengganggu; menghormati prefers-reduced-motion.
    (function () {
        const revealEls = document.querySelectorAll('.reveal');
        if (!revealEls.length) return;

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduceMotion || !('IntersectionObserver' in window)) {
            revealEls.forEach((el) => el.classList.add('is-visible'));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

        revealEls.forEach((el) => observer.observe(el));
    })();
</script>
@endpush