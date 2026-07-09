<!-- resources/views/public/portfolio/profile.blade.php -->
{{-- Halaman profil publik siswa — Live URL Portfolio, tidak perlu login --}}
{{-- Didesain ulang: navbar sticky dengan scroll-spy, hero premium, section Tentang/Keahlian/Karya,
     serta tautan ke PDF ringkas (lihat resources/views/portfolio/print-ringkas.blade.php) --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Portfolio {{ $user->name }} — DKV SMKN 2 Padang Panjang</title>
    <meta name="description" content="{{ Str::limit($user->bio ?? 'Kumpulan karya desain komunikasi visual '.$user->name, 160) }}">
    <meta property="og:title" content="Portfolio {{ $user->name }}">
    <meta property="og:description" content="{{ Str::limit($user->bio ?? 'Kumpulan karya desain komunikasi visual', 160) }}">
    @if($portfolios->first())
    <meta property="og:image" content="{{ asset('storage/' . $portfolios->first()->image_path) }}">
    @endif
    <meta property="og:type" content="profile">
    <meta name="twitter:card" content="summary_large_image">

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
            --red:      #dc2626;
            --red-glow: rgba(220,38,38,0.4);
            --border:   rgba(255,255,255,0.07);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #080808;
            color: #f5f5f5;
            min-height: 100vh;
        }

        /* Noise halus — konsisten dengan tema halaman detail karya (/p/{slug}) */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }

        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(220,38,38,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220,38,38,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none; z-index: 0;
        }

        .blob { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
        .blob-1 {
            top: -200px; right: -100px; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, transparent 65%);
            animation: blobF 12s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -150px; left: -100px; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(220,38,38,0.05) 0%, transparent 65%);
            animation: blobF 15s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobF {
            0%   { transform: scale(1)    translate(0,0); }
            100% { transform: scale(1.12) translate(20px,15px); }
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #080808; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        @media (prefers-reduced-motion: reduce) {
            .blob-1, .blob-2 { animation: none; }
            html { scroll-behavior: auto; }
        }

        /* ══════════════════════ NAVBAR ══════════════════════ */
        .public-nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            padding: 14px 0;
        }
        .nav-inner {
            max-width: 1080px; margin: 0 auto; padding: 0 24px;
            display: flex; align-items: center; gap: 18px;
        }
        .nav-brand { display: flex; align-items: center; gap: 9px; text-decoration: none; flex-shrink: 0; }
        .nav-logo-icon {
            width: 28px; height: 28px; border-radius: 8px; background: var(--red);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px var(--red-glow);
        }
        .nav-logo-icon svg { width: 14px; height: 14px; }
        .nav-brand-text {
            font-size: 0.8rem; font-weight: 900; letter-spacing: 3px;
            text-transform: uppercase; color: rgba(255,255,255,0.85);
            white-space: nowrap;
        }
        .nav-brand-text span { color: var(--red); }

        .nav-links {
            display: flex; align-items: center; gap: 2px;
            margin: 0 auto 0 8px;
            overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;
        }
        .nav-links::-webkit-scrollbar { display: none; }
        .nav-link {
            flex-shrink: 0;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;
            color: rgba(255,255,255,0.4); text-decoration: none;
            padding: 7px 14px; border-radius: 20px;
            white-space: nowrap; transition: all 0.2s ease;
        }
        .nav-link:hover { color: #f5f5f5; background: rgba(255,255,255,0.05); }
        .nav-link.active { color: #fca5a5; background: rgba(220,38,38,0.12); }

        .nav-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.2);
            border-radius: 30px; padding: 4px 12px;
            font-size: 0.65rem; font-weight: 700; color: #fca5a5;
            letter-spacing: 0.5px; text-transform: uppercase;
            flex-shrink: 0; white-space: nowrap;
        }
        .live-dot {
            width: 5px; height: 5px; background: var(--red); border-radius: 50%;
            box-shadow: 0 0 6px var(--red-glow); animation: livePulse 1.5s ease-in-out infinite;
            flex-shrink: 0; display: inline-block;
        }
        @keyframes livePulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.3; transform: scale(0.6); }
        }

        @media (max-width: 768px) {
            .nav-badge { display: none; }
        }

        .main-wrap { position: relative; z-index: 1; max-width: 1080px; margin: 0 auto; padding: 0 24px; }

        section[id] { scroll-margin-top: 88px; }

        /* ══════════════════════ HERO ══════════════════════ */
        .hero {
            padding: 64px 0 44px;
            display: flex; flex-direction: column; align-items: center;
            text-align: center;
        }
        .hero-avatar {
            width: 116px; height: 116px; border-radius: 28px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.6rem; font-weight: 900; color: white;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.08), 0 0 50px rgba(220,38,38,0.35), 0 20px 50px rgba(0,0,0,0.5);
            overflow: hidden; margin-bottom: 22px; flex-shrink: 0;
        }
        .hero-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .hero-status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.04); border: 1px solid var(--border);
            border-radius: 30px; padding: 5px 14px; margin-bottom: 18px;
            font-size: 0.66rem; font-weight: 700; letter-spacing: 0.5px;
            color: rgba(255,255,255,0.55); text-transform: uppercase;
        }

        .hero-name {
            font-size: clamp(1.9rem, 6vw, 3.4rem); font-weight: 900;
            letter-spacing: -1.5px; line-height: 1.08; margin-bottom: 12px;
            word-break: break-word;
        }
        .hero-tagline {
            font-size: clamp(0.92rem, 2vw, 1.1rem); font-weight: 500;
            color: #fca5a5; margin-bottom: 6px;
        }
        .hero-institution {
            font-size: 0.8rem; color: rgba(255,255,255,0.35);
            margin-bottom: 30px;
        }

        .hero-cta-row { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; width: 100%; }
        .btn-hero-primary, .btn-hero-outline {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.85rem; font-weight: 700;
            padding: 13px 24px; border-radius: 14px;
            text-decoration: none; transition: all 0.25s ease;
        }
        .btn-hero-primary {
            background: var(--red); color: white;
            box-shadow: 0 8px 30px rgba(220,38,38,0.35);
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 36px rgba(220,38,38,0.5); }
        .btn-hero-outline {
            background: rgba(255,255,255,0.03); color: #f5f5f5;
            border: 1px solid var(--border);
        }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.18); transform: translateY(-2px); }
        .btn-hero-primary svg, .btn-hero-outline svg { width: 16px; height: 16px; flex-shrink: 0; }

        @media (max-width: 480px) {
            .hero-cta-row { flex-direction: column; }
            .btn-hero-primary, .btn-hero-outline { justify-content: center; width: 100%; }
        }

        /* ══════════════════════ SHARE BAR ══════════════════════ */
        .share-bar {
            display: flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 14px; padding: 12px 16px; margin-bottom: 56px;
            flex-wrap: wrap;
        }
        .share-label { font-size: 0.65rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.25); }
        .share-url-text { flex: 1; font-size: 0.78rem; color: rgba(255,255,255,0.5); font-family: monospace; min-width: 160px; word-break: break-all; }
        .share-copy-btn {
            font-size: 0.68rem; font-weight: 800; color: var(--red);
            letter-spacing: 0.5px; text-transform: uppercase; cursor: pointer;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.2);
            padding: 6px 14px; border-radius: 20px; transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .share-copy-btn:hover { background: rgba(220,38,38,0.18); }

        /* ══════════════════════ LABEL SECTION (reusable) ══════════════════════ */
        .section-block { padding-bottom: 60px; }
        .section-header { display: flex; align-items: center; gap: 8px; margin-bottom: 22px; }
        .section-header::before { content: ''; display: block; width: 16px; height: 2px; background: var(--red); flex-shrink: 0; }
        .section-label { font-size: 0.7rem; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; color: rgba(255,255,255,0.35); }

        /* ══════════════════════ TENTANG ══════════════════════ */
        .about-card {
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 20px; padding: 32px;
        }
        .about-bio {
            font-size: 0.92rem; line-height: 1.75; color: rgba(255,255,255,0.6);
            max-width: 640px; margin-bottom: 28px;
        }
        .info-grid {
            display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 28px;
            padding-top: 24px; border-top: 1px solid var(--border);
            margin-bottom: 24px;
        }
        .info-item-label { font-size: 0.62rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.25); margin-bottom: 6px; }
        .info-item-value { font-size: 0.88rem; font-weight: 600; color: #f5f5f5; word-break: break-word; }

        .stat-row { display: flex; gap: 32px; padding-top: 24px; border-top: 1px solid var(--border); }
        .stat-box { text-align: left; }
        .stat-num { font-size: 1.8rem; font-weight: 900; color: #f5f5f5; line-height: 1; }
        .stat-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; color: rgba(255,255,255,0.25); margin-top: 6px;
        }

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
            .about-card { padding: 22px; }
        }

        /* ══════════════════════ KEAHLIAN ══════════════════════ */
        .skills-wrap { display: flex; flex-wrap: wrap; gap: 10px; }
        .skill-tag {
            display: inline-flex; align-items: center;
            background: rgba(255,255,255,0.03); border: 1px solid var(--border);
            border-radius: 10px; padding: 9px 16px;
            font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.7);
            transition: all 0.2s ease;
        }
        .skill-tag:hover { border-color: rgba(220,38,38,0.3); color: #f5f5f5; background: rgba(220,38,38,0.06); }

        /* ══════════════════════ KARYA GRID ══════════════════════ */
        .works-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .work-card {
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden; text-decoration: none; display: block;
            transition: all 0.25s ease;
        }
        .work-card:hover {
            transform: translateY(-3px); border-color: rgba(220,38,38,0.25);
            box-shadow: 0 12px 32px rgba(0,0,0,0.35), 0 0 24px rgba(220,38,38,0.08);
        }
        .work-thumb-wrap { position: relative; overflow: hidden; background: #111; }
        .work-thumb {
            width: 100%; height: 160px; object-fit: cover; display: block;
            transition: transform 0.5s ease;
        }
        .work-card:hover .work-thumb { transform: scale(1.08); }
        .work-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(0deg, rgba(8,8,8,0.85) 0%, rgba(8,8,8,0.12) 55%, transparent 100%);
            opacity: 0; transition: opacity 0.3s ease;
            display: flex; align-items: flex-end; justify-content: flex-end; padding: 12px;
        }
        .work-card:hover .work-overlay { opacity: 1; }
        .work-overlay-icon {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--red); display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 16px var(--red-glow);
            transform: translateY(6px); transition: transform 0.3s ease;
        }
        .work-card:hover .work-overlay-icon { transform: translateY(0); }
        .work-overlay-icon svg { width: 14px; height: 14px; }
        .work-info { padding: 14px; }
        .work-cat { font-size: 0.6rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #fca5a5; margin-bottom: 6px; }
        .work-title { font-size: 0.88rem; font-weight: 700; color: #f5f5f5; line-height: 1.3; }

        .empty-state {
            text-align: center; padding: 60px 24px; color: rgba(255,255,255,0.3);
            border: 1px dashed var(--border); border-radius: 16px; font-size: 0.85rem;
        }

        @media (max-width: 640px) {
            .works-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .work-thumb { height: 128px; }
            .hero { padding: 48px 0 34px; }
        }

        /* ══════════════════════ FOOTER / KONTAK ══════════════════════ */
        .public-footer {
            border-top: 1px solid var(--border); padding: 48px 24px 32px; text-align: center;
        }
        .footer-cta-title { font-size: 1.3rem; font-weight: 900; margin-bottom: 8px; }
        .footer-cta-sub { font-size: 0.85rem; color: rgba(255,255,255,0.4); margin-bottom: 26px; }
        .footer-contact-row {
            display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;
            margin-bottom: 28px;
        }
        .footer-contact-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.03); border: 1px solid var(--border);
            border-radius: 30px; padding: 9px 18px; font-size: 0.82rem; font-weight: 600;
            color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s ease;
        }
        .footer-contact-pill:hover { border-color: rgba(220,38,38,0.3); color: #f5f5f5; }
        .footer-contact-pill svg { width: 15px; height: 15px; color: var(--red); flex-shrink: 0; }

        .footer-text { font-size: 0.72rem; color: rgba(255,255,255,0.14); margin-top: 8px; line-height: 1.8; }
        .footer-text strong { color: rgba(255,255,255,0.26); }
        .footer-text span { color: var(--red); }

        .toast {
            position: fixed; bottom: 24px; right: 24px; left: 24px; z-index: 9999;
            max-width: 320px; margin-left: auto;
            background: rgba(22,22,22,0.96); border: 1px solid rgba(34,197,94,0.3);
            border-radius: 12px; padding: 12px 20px; display: flex; align-items: center; gap: 10px;
            font-size: 0.8rem; font-weight: 700; color: #86efac;
            opacity: 0; transform: translateY(10px); transition: all 0.3s ease; pointer-events: none;
        }
        .toast.show { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<nav class="public-nav">
    <div class="nav-inner">
        <a href="#top" class="nav-brand">
            <div class="nav-logo-icon">
                <svg fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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
            <a href="#kontak" class="nav-link" data-section="kontak">Kontak</a>
        </div>

        <div class="nav-badge">
            <span class="live-dot"></span>
            Live Portfolio
        </div>
    </div>
</nav>

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

    {{-- ══════════════ HERO ══════════════ --}}
    <div class="hero">
        <div class="hero-avatar">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>

        <div class="hero-status-badge">
            <span class="live-dot"></span> Portofolio Aktif
        </div>

        <h1 class="hero-name">{{ $user->name }}</h1>
        <p class="hero-tagline">{{ $tagline }}</p>
        <p class="hero-institution">Siswa DKV — SMKN 2 Padang Panjang</p>

        <div class="hero-cta-row">
            <a href="#karya" class="btn-hero-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Lihat Karya
            </a>
            <a href="{{ route('portfolio.public.print', $user->portfolio_slug) }}" target="_blank" rel="noopener" class="btn-hero-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
        <div class="section-header"><span class="section-label">Tentang</span></div>
        <div class="about-card">
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
        <div class="section-header"><span class="section-label">Keahlian</span></div>
        <div class="skills-wrap">
            @foreach($user->skills as $skill)
                <span class="skill-tag">{{ $skill }}</span>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ══════════════ KARYA ══════════════ --}}
    <section id="karya" class="section-block">
        <div class="section-header"><span class="section-label">Semua Karya</span></div>

        @if($portfolios->count() > 0)
        <div class="works-grid">
            @foreach($portfolios as $portfolio)
            <a href="{{ route('portfolio.public', $portfolio->slug) }}" class="work-card">
                <div class="work-thumb-wrap">
                    <img
                        src="{{ asset('storage/' . $portfolio->image_path) }}"
                        alt="{{ $portfolio->title }}"
                        class="work-thumb"
                        onerror="this.style.background='#1a1a1a'"
                    >
                    <div class="work-overlay">
                        <div class="work-overlay-icon">
                            <svg fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>
                </div>
                <div class="work-info">
                    <div class="work-cat">{{ $portfolio->category?->name ?? 'Umum' }}</div>
                    <div class="work-title">{{ $portfolio->title }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state">Siswa ini belum mengunggah karya.</div>
        @endif
    </section>

</div>

{{-- ══════════════ FOOTER / KONTAK ══════════════ --}}
<footer id="kontak" class="public-footer">
    <div class="footer-cta-title">Tertarik Berkolaborasi?</div>
    <p class="footer-cta-sub">Hubungi langsung atau unduh portofolio lengkap dalam format PDF.</p>

    <div class="footer-contact-row">
        @if($user->contact)
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="footer-contact-pill">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            {{ $user->contact }}
        </a>
        @endif
        <a href="{{ route('portfolio.public.print', $user->portfolio_slug) }}" target="_blank" rel="noopener" class="footer-contact-pill">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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

<div class="toast" id="toast">Link berhasil disalin ke clipboard!</div>

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
</script>

</body>
</html>
