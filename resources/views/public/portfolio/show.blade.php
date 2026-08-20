{{-- resources/views/public/portfolio/show.blade.php --}}
{{-- Halaman publik detail satu karya — tidak memerlukan login, dibuka lewat link/QR (/p/{slug}). --}}
@extends('layouts.app')

@section('title', $portfolio->title . ' — Portfolio ' . $portfolio->user->name)

@push('meta')
<meta name="description" content="{{ Str::limit(strip_tags($portfolio->description), 160) }}">
<link rel="canonical" href="{{ url('/p/' . $portfolio->slug) }}">

<meta property="og:site_name"    content="DKV SMEKDA">
<meta property="og:type"         content="article">
<meta property="og:locale"       content="id_ID">
<meta property="og:title"        content="{{ $portfolio->title }}">
<meta property="og:description"  content="{{ Str::limit(strip_tags($portfolio->description), 160) }}">
<meta property="og:image"        content="{{ asset('storage/' . $portfolio->image_path) }}">
<meta property="og:url"          content="{{ url('/p/' . $portfolio->slug) }}">

<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $portfolio->title }}">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($portfolio->description), 160) }}">
<meta name="twitter:image"       content="{{ asset('storage/' . $portfolio->image_path) }}">

{{-- Structured data — dibangun lewat json_encode (bukan interpolasi Blade langsung)
     supaya karakter kutip/HTML di judul & deskripsi tidak merusak sintaks JSON. --}}
<script type="application/ld+json">{!! json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => 'CreativeWork',
    'name'          => $portfolio->title,
    'description'   => Str::limit(strip_tags($portfolio->description), 300),
    'image'         => asset('storage/' . $portfolio->image_path),
    'url'           => url('/p/' . $portfolio->slug),
    'datePublished' => $portfolio->created_at->toIso8601String(),
    'dateModified'  => $portfolio->updated_at->toIso8601String(),
    'creator'       => [
        '@type' => 'Person',
        'name'  => $portfolio->user->name,
    ],
    'about'         => $portfolio->category?->name,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

{{-- Halaman publik ini punya navbar & footer editorial sendiri (identitas
     berbeda dari dashboard internal), jadi navbar/footer bawaan layout
     dikosongkan — pola yang sama dipakai di public/portfolio/profile.blade.php
     dan guru/dashboard.blade.php. --}}
@section('navbar')@endsection
@section('footer')@endsection

@push('styles')
<style>

    /* ══════════════════════════════════════════════════════════════
       DESIGN TOKENS
       Art direction: galeri editorial — kertas gading hangat, tinta
       charcoal, dan satu aksen oxblood yang merupakan evolusi dari
       warna merah identitas sekolah (bukan penghapusan, tapi
       pematangan). Dipakai sangat sedikit & sengaja.
       ══════════════════════════════════════════════════════════════ */
    :root {
        --pw-paper:       #FAF7EF;  /* latar halaman — kertas gading hangat */
        --pw-surface:     #FFFFFF;  /* permukaan kartu/mat di atas kertas */
        --pw-surface-2:   #F2ECDD;  /* permukaan sekunder — mat foto, chip */
        --pw-ink:         #1C1A16;  /* teks utama — charcoal hangat, bukan hitam pekat */
        --pw-ink-soft:    #4A453B;  /* teks sekunder / paragraf panjang */
        --pw-stone:       #726C5A;  /* label, metadata, caption kecil */
        --pw-stone-2:     #A39C86; /* teks tersier / placeholder halus */
        --pw-hairline:    #E4DCC6; /* garis pemisah & border halus */
        --pw-hairline-2:  #EFE9D8; /* border lebih halus lagi, untuk kartu */
        --pw-accent:      #8B3123; /* oxblood — evolusi merah identitas sekolah */
        --pw-accent-ink:  #6E2519; /* oxblood gelap — untuk teks/hover di atas terang */
        --pw-accent-tint: #F4E6DF; /* oxblood sangat pudar — latar chip/badge */

        --pw-font-body:    'Inter', sans-serif;
        --pw-font-display: 'Fraunces', serif;

        --pw-radius-sm: 8px;
        --pw-radius-md: 14px;
        --pw-radius-lg: 22px;

        --pw-shadow-card: 0 1px 2px rgba(28,26,22,0.04), 0 12px 32px -16px rgba(28,26,22,0.18);
        --pw-ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    .pw { color-scheme: light; }
    .pw, .pw *, .pw *::before, .pw *::after { box-sizing: border-box; }

    body:has(.pw) {
        background: var(--pw-paper);
        color: var(--pw-ink);
        font-family: var(--pw-font-body);
    }

    .pw {
        background: var(--pw-paper);
        color: var(--pw-ink);
        font-family: var(--pw-font-body);
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
    }

    .pw a { color: inherit; }
    .pw img { max-width: 100%; }

    .pw :focus-visible {
        outline: 2px solid var(--pw-accent);
        outline-offset: 3px;
        border-radius: 4px;
    }

    @media (prefers-reduced-motion: reduce) {
        .pw *, .pw *::before, .pw *::after {
            animation-duration: 0.001ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.001ms !important;
            scroll-behavior: auto !important;
        }
    }

    /* Reveal halus saat halaman dimuat — hanya untuk pengguna yang
       tidak meminta reduced motion. Progressive enhancement: tanpa ini
       konten tetap tampil penuh sejak awal. */
    @media (prefers-reduced-motion: no-preference) {
        .pw-reveal {
            opacity: 0;
            animation: pwFadeUp 0.9s var(--pw-ease) forwards;
        }
        .pw-reveal-1 { animation-delay: 0.05s; }
        .pw-reveal-2 { animation-delay: 0.16s; }
        .pw-reveal-3 { animation-delay: 0.26s; }
    }

    @keyframes pwFadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ══════════════════════════════════════════════════════════════
       NAVIGASI — minimal, dua aksi saja selain brand
       ══════════════════════════════════════════════════════════════ */
    .pw-nav {
        position: sticky; top: 0; z-index: 50;
        background: rgba(250,247,239,0.86);
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid var(--pw-hairline);
    }

    .pw-nav-inner {
        max-width: 1120px; margin: 0 auto;
        padding: 14px 20px;
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px;
    }

    .pw-brand {
        display: flex; align-items: center; gap: 9px;
        text-decoration: none; flex-shrink: 0;
        min-width: 0;
    }

    .pw-brand img {
        width: 26px; height: 26px; object-fit: contain; flex-shrink: 0;
    }

    .pw-brand-text {
        font-size: 0.72rem; font-weight: 700;
        letter-spacing: 0.16em; text-transform: uppercase;
        color: var(--pw-ink);
        white-space: nowrap;
    }

    .pw-brand-text em {
        font-style: normal; color: var(--pw-accent);
    }

    .pw-nav-actions {
        display: flex; align-items: center; gap: 6px;
        flex-shrink: 0;
    }

    .pw-nav-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 12px;
        font-family: var(--pw-font-body);
        font-size: 0.76rem; font-weight: 600;
        color: var(--pw-ink-soft);
        text-decoration: none;
        border: 1px solid transparent;
        border-radius: var(--pw-radius-sm);
        background: transparent;
        cursor: pointer;
        transition: background 0.2s var(--pw-ease), border-color 0.2s var(--pw-ease), color 0.2s var(--pw-ease);
        white-space: nowrap;
    }

    .pw-nav-btn:hover {
        background: var(--pw-surface);
        border-color: var(--pw-hairline);
        color: var(--pw-ink);
    }

    .pw-nav-btn svg { width: 15px; height: 15px; flex-shrink: 0; }

    .pw-nav-btn .pw-nav-btn-label { display: inline; }

    @media (max-width: 420px) {
        /* Cukup ruang sempit: sisakan ikon saja supaya navbar tidak overflow. */
        .pw-nav-btn .pw-nav-btn-label { display: none; }
        .pw-nav-btn { padding: 8px; }
    }

    /* ══════════════════════════════════════════════════════════════
       MAIN / ARTICLE WRAP
       ══════════════════════════════════════════════════════════════ */
    .pw-main {
        max-width: 1120px; margin: 0 auto;
        padding: 40px 20px 96px;
    }

    .pw-col {
        max-width: 720px; margin: 0 auto;
    }

    /* ══════════════════════════════════════════════════════════════
       HERO ARTWORK — karya sebagai bintang utama
       ══════════════════════════════════════════════════════════════ */
    .pw-frame {
        background: var(--pw-surface-2);
        border: 1px solid var(--pw-hairline);
        border-radius: var(--pw-radius-lg);
        padding: clamp(14px, 3.4vw, 40px);
        margin-bottom: 28px;
    }

    .pw-frame-inner {
        background: var(--pw-surface);
        border: 1px solid var(--pw-hairline-2);
        border-radius: var(--pw-radius-md);
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        min-height: 220px;
    }

    .pw-frame-inner img {
        display: block;
        width: 100%;
        max-height: 74vh;
        object-fit: contain;
        background: var(--pw-surface);
    }

    .pw-plate {
        max-width: 720px; margin: 0 auto 44px;
        padding: 0 4px;
    }

    .pw-eyebrow {
        display: flex; align-items: center; gap: 8px;
        font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.14em; text-transform: uppercase;
        color: var(--pw-stone);
        margin-bottom: 10px;
    }

    .pw-eyebrow-dot {
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--pw-accent); flex-shrink: 0;
    }

    .pw-title {
        font-family: var(--pw-font-display);
        font-weight: 700;
        font-size: clamp(1.9rem, 4.6vw, 3.1rem);
        line-height: 1.08;
        letter-spacing: -0.01em;
        color: var(--pw-ink);
    }

    /* ══════════════════════════════════════════════════════════════
       BYLINE — pembuat karya, tautan ke profil publiknya
       ══════════════════════════════════════════════════════════════ */
    .pw-byline {
        display: flex; align-items: center; gap: 13px;
        text-decoration: none;
        padding: 16px 4px;
        margin-bottom: 8px;
        border-top: 1px solid var(--pw-hairline);
        border-bottom: 1px solid var(--pw-hairline);
    }

    .pw-avatar {
        width: 42px; height: 42px; border-radius: var(--pw-radius-sm);
        flex-shrink: 0; overflow: hidden;
        background: var(--pw-surface-2);
        border: 1px solid var(--pw-hairline);
        display: flex; align-items: center; justify-content: center;
        font-family: var(--pw-font-display);
        font-weight: 700; font-size: 1rem; color: var(--pw-ink-soft);
    }

    .pw-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .pw-byline-text { min-width: 0; flex: 1; }

    .pw-byline-name {
        font-size: 0.9rem; font-weight: 700; color: var(--pw-ink);
        display: flex; align-items: center; gap: 6px;
    }

    .pw-byline-name svg {
        width: 13px; height: 13px; color: var(--pw-stone-2);
        transition: transform 0.25s var(--pw-ease), color 0.25s var(--pw-ease);
        flex-shrink: 0;
    }

    .pw-byline:hover .pw-byline-name svg {
        transform: translateX(3px);
        color: var(--pw-accent);
    }

    .pw-byline-role {
        font-size: 0.76rem; color: var(--pw-stone);
        margin-top: 1px;
    }

    /* ══════════════════════════════════════════════════════════════
       DESKRIPSI
       ══════════════════════════════════════════════════════════════ */
    .pw-section-label {
        font-size: 0.68rem; font-weight: 700;
        letter-spacing: 0.14em; text-transform: uppercase;
        color: var(--pw-stone-2);
        margin: 30px 0 12px;
    }

    .pw-desc {
        font-size: 1rem; color: var(--pw-ink-soft);
        line-height: 1.85;
        white-space: pre-line;
    }

    /* ══════════════════════════════════════════════════════════════
       META STRIP — panel keterangan gaya label galeri
       ══════════════════════════════════════════════════════════════ */
    .pw-meta {
        display: flex; flex-wrap: wrap;
        gap: 22px 34px;
        margin-top: 30px;
        padding: 20px 0;
        border-top: 1px solid var(--pw-hairline);
    }

    .pw-meta-item { min-width: 96px; }

    .pw-meta-label {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--pw-stone-2);
        margin-bottom: 5px;
    }

    .pw-meta-value {
        font-size: 0.86rem; font-weight: 600; color: var(--pw-ink);
    }

    .pw-meta-value.is-muted { color: var(--pw-stone-2); font-weight: 500; }
    .pw-meta-value.is-ok { color: #3F6B3F; }

    /* ══════════════════════════════════════════════════════════════
       CTA PDF
       ══════════════════════════════════════════════════════════════ */
    .pw-pdf-cta {
        display: inline-flex; align-items: center; gap: 9px;
        margin-top: 22px;
        padding: 10px 16px;
        border: 1px solid var(--pw-hairline);
        border-radius: 999px;
        background: var(--pw-surface);
        color: var(--pw-ink);
        text-decoration: none;
        font-size: 0.82rem; font-weight: 600;
        transition: border-color 0.2s var(--pw-ease), background 0.2s var(--pw-ease), transform 0.2s var(--pw-ease);
    }

    .pw-pdf-cta:hover {
        border-color: var(--pw-accent);
        background: var(--pw-accent-tint);
        transform: translateY(-1px);
    }

    .pw-pdf-cta svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--pw-accent); }

    /* ══════════════════════════════════════════════════════════════
       SHARE BLOCK — link, copy, native share, QR
       ══════════════════════════════════════════════════════════════ */
    .pw-share {
        margin-top: 40px;
        border: 1px solid var(--pw-hairline);
        border-radius: var(--pw-radius-md);
        background: var(--pw-surface);
        padding: 22px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 22px;
        align-items: center;
    }

    .pw-share-main { min-width: 0; }

    .pw-share-title {
        font-size: 0.82rem; font-weight: 700; color: var(--pw-ink);
        margin-bottom: 12px;
    }

    .pw-share-row {
        display: flex; align-items: stretch; gap: 8px;
        flex-wrap: wrap;
    }

    .pw-url-box {
        flex: 1 1 200px; min-width: 0;
        display: flex; align-items: center; gap: 8px;
        background: var(--pw-paper);
        border: 1px solid var(--pw-hairline);
        border-radius: var(--pw-radius-sm);
        padding: 10px 12px;
        cursor: pointer;
        transition: border-color 0.2s var(--pw-ease);
    }

    .pw-url-box:hover { border-color: var(--pw-stone-2); }

    .pw-url-box svg { width: 14px; height: 14px; color: var(--pw-stone-2); flex-shrink: 0; }

    .pw-url-text {
        flex: 1; min-width: 0;
        font-family: 'SF Mono', 'Menlo', monospace;
        font-size: 0.74rem; color: var(--pw-ink-soft);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .pw-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 10px 16px;
        border-radius: var(--pw-radius-sm);
        font-family: var(--pw-font-body);
        font-size: 0.8rem; font-weight: 700;
        border: 1px solid var(--pw-accent);
        background: var(--pw-accent);
        color: #FBF3EF;
        cursor: pointer;
        transition: background 0.2s var(--pw-ease), transform 0.2s var(--pw-ease), box-shadow 0.2s var(--pw-ease);
        flex-shrink: 0;
        white-space: nowrap;
    }

    .pw-btn:hover {
        background: var(--pw-accent-ink);
        border-color: var(--pw-accent-ink);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -8px rgba(139,49,35,0.55);
    }

    .pw-btn svg { width: 14px; height: 14px; }

    .pw-btn.is-copied {
        background: #3F6B3F; border-color: #3F6B3F;
    }

    .pw-qr {
        text-align: center; flex-shrink: 0;
        padding-left: 22px;
        border-left: 1px solid var(--pw-hairline);
    }

    .pw-qr img {
        width: 84px; height: 84px;
        border-radius: 8px;
        border: 1px solid var(--pw-hairline);
        display: block; margin: 0 auto 8px;
    }

    .pw-qr-caption {
        font-size: 0.64rem; color: var(--pw-stone);
        max-width: 100px; line-height: 1.4;
    }

    @media (max-width: 560px) {
        .pw-share { grid-template-columns: 1fr; }
        .pw-qr {
            border-left: none; border-top: 1px solid var(--pw-hairline);
            padding-left: 0; padding-top: 18px;
            display: flex; align-items: center; gap: 14px; text-align: left;
        }
        .pw-qr img { margin: 0; }
        .pw-qr-caption { max-width: none; }
    }

    /* ══════════════════════════════════════════════════════════════
       VIEW ALL PROFILE LINK
       ══════════════════════════════════════════════════════════════ */
    .pw-profile-link {
        display: inline-flex; align-items: center; gap: 7px;
        margin-top: 26px;
        font-size: 0.82rem; font-weight: 600; color: var(--pw-ink-soft);
        text-decoration: none;
        border-bottom: 1px solid var(--pw-hairline);
        padding-bottom: 2px;
        transition: color 0.2s var(--pw-ease), border-color 0.2s var(--pw-ease);
    }

    .pw-profile-link:hover { color: var(--pw-accent); border-color: var(--pw-accent); }
    .pw-profile-link svg { width: 14px; height: 14px; }

    /* ══════════════════════════════════════════════════════════════
       RELATED WORKS — galeri kurasi
       ══════════════════════════════════════════════════════════════ */
    .pw-related {
        max-width: 1120px; margin: 76px auto 0;
        padding: 0 20px;
    }

    .pw-related-head {
        max-width: 720px; margin: 0 auto 22px;
        padding: 0 4px;
        display: flex; align-items: baseline; gap: 10px;
    }

    .pw-related-head h2 {
        font-family: var(--pw-font-display);
        font-weight: 700;
        font-size: 1.3rem;
        color: var(--pw-ink);
    }

    .pw-related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 18px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .pw-related-card {
        text-decoration: none;
        display: block;
        border-radius: var(--pw-radius-md);
        overflow: hidden;
        border: 1px solid var(--pw-hairline-2);
        background: var(--pw-surface);
        transition: border-color 0.25s var(--pw-ease), box-shadow 0.25s var(--pw-ease), transform 0.25s var(--pw-ease);
    }

    .pw-related-card:hover {
        border-color: var(--pw-hairline);
        box-shadow: var(--pw-shadow-card);
        transform: translateY(-2px);
    }

    .pw-related-thumb-wrap {
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: var(--pw-surface-2);
    }

    .pw-related-thumb-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        display: block;
        transition: transform 0.6s var(--pw-ease);
    }

    .pw-related-card:hover .pw-related-thumb-wrap img {
        transform: scale(1.045);
    }

    .pw-related-info { padding: 12px 14px 15px; }

    .pw-related-cat {
        font-size: 0.62rem; font-weight: 700; letter-spacing: 0.1em;
        text-transform: uppercase; color: var(--pw-stone-2);
        margin-bottom: 4px;
    }

    .pw-related-title {
        font-size: 0.85rem; font-weight: 600; color: var(--pw-ink);
        line-height: 1.35;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ══════════════════════════════════════════════════════════════
       FOOTER
       ══════════════════════════════════════════════════════════════ */
    .pw-footer {
        margin-top: 88px;
        border-top: 1px solid var(--pw-hairline);
        padding: 26px 20px;
        text-align: center;
    }

    .pw-footer-text {
        font-size: 0.72rem; color: var(--pw-stone-2);
        line-height: 1.7;
    }

    .pw-footer-text strong { color: var(--pw-stone); font-weight: 600; }

    /* ══════════════════════════════════════════════════════════════
       TOAST
       ══════════════════════════════════════════════════════════════ */
    .pw-toast {
        position: fixed; bottom: 22px; left: 50%;
        transform: translate(-50%, 12px);
        z-index: 9999;
        background: var(--pw-ink);
        color: var(--pw-paper);
        border-radius: 999px;
        padding: 11px 20px;
        display: flex; align-items: center; gap: 9px;
        font-size: 0.78rem; font-weight: 600;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s var(--pw-ease), transform 0.3s var(--pw-ease);
        box-shadow: 0 12px 28px -10px rgba(28,26,22,0.4);
    }

    .pw-toast.show { opacity: 1; transform: translate(-50%, 0); }
    .pw-toast svg { width: 15px; height: 15px; color: #9BD69B; flex-shrink: 0; }

    @media (min-width: 768px) {
        .pw-main { padding-top: 52px; }
        .pw-title { letter-spacing: -0.015em; }
    }

</style>
@endpush

@section('content')
<div class="pw">

    {{-- ── NAVIGASI ── --}}
    <nav class="pw-nav" aria-label="Navigasi halaman publik">
        <div class="pw-nav-inner">
            <a href="{{ url('/') }}" class="pw-brand">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Lambang SMK Negeri 2 Padang Panjang">
                <span class="pw-brand-text">DKV<em>.</em>SMEKDA</span>
            </a>

            <div class="pw-nav-actions">
                <a href="{{ route('portfolio.profile', $portfolio->user->portfolio_slug) }}" class="pw-nav-btn" aria-label="Kembali ke portofolio {{ explode(' ', $portfolio->user->name)[0] }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    <span class="pw-nav-btn-label" aria-hidden="true">Portofolio {{ explode(' ', $portfolio->user->name)[0] }}</span>
                </a>
                <button type="button" class="pw-nav-btn" onclick="pwShare()" aria-label="Bagikan karya ini">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                    </svg>
                    <span class="pw-nav-btn-label" aria-hidden="true">Bagikan</span>
                </button>
            </div>
        </div>
    </nav>

    {{-- ── MAIN ── --}}
    <main class="pw-main">

        {{-- Artwork hero — bingkai bermat, gambar tidak pernah dipotong paksa --}}
        <div class="pw-frame pw-reveal pw-reveal-1">
            <div class="pw-frame-inner">
                <img
                    src="{{ asset('storage/' . $portfolio->image_path) }}"
                    alt="{{ $portfolio->title }}"
                    onerror="this.closest('.pw-frame-inner').innerHTML='<div style=&quot;padding:48px 20px;text-align:center;color:var(--pw-stone-2);font-size:0.82rem;&quot;>Gambar karya tidak dapat dimuat</div>'"
                >
            </div>
        </div>

        {{-- Wall label — eyebrow + judul, persis di bawah karya --}}
        <div class="pw-plate pw-reveal pw-reveal-2">
            <div class="pw-eyebrow">
                <span class="pw-eyebrow-dot"></span>
                <span>{{ $portfolio->category?->name ?? 'Umum' }} &middot; {{ $portfolio->created_at->format('Y') }}</span>
            </div>
            <h1 class="pw-title">{{ $portfolio->title }}</h1>
        </div>

        <div class="pw-col pw-reveal pw-reveal-3">

            {{-- Byline pembuat karya — tautan ke profil publiknya --}}
            <a href="{{ route('portfolio.profile', $portfolio->user->portfolio_slug) }}" class="pw-byline">
                <div class="pw-avatar">
                    @if($portfolio->user->photo)
                        <img src="{{ asset('storage/' . $portfolio->user->photo) }}" alt="{{ $portfolio->user->name }}">
                    @else
                        {{ strtoupper(substr($portfolio->user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="pw-byline-text">
                    <div class="pw-byline-name">
                        {{ $portfolio->user->name }}
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                    <div class="pw-byline-role">
                        Siswa DKV &middot; SMK Negeri 2 Padang Panjang{{ $portfolio->user->nis_nip ? ' · NIS ' . $portfolio->user->nis_nip : '' }}
                    </div>
                </div>
            </a>

            {{-- Deskripsi karya --}}
            <div class="pw-section-label">Tentang Karya</div>
            <p class="pw-desc">{{ $portfolio->description }}</p>

            {{-- Panel metadata gaya label galeri --}}
            <div class="pw-meta">
                <div class="pw-meta-item">
                    <div class="pw-meta-label">Kategori</div>
                    <div class="pw-meta-value">{{ $portfolio->category?->name ?? 'Umum' }}</div>
                </div>
                <div class="pw-meta-item">
                    <div class="pw-meta-label">Diunggah</div>
                    <div class="pw-meta-value">{{ $portfolio->created_at->format('d M Y') }}</div>
                </div>
                <div class="pw-meta-item">
                    <div class="pw-meta-label">Diperbarui</div>
                    <div class="pw-meta-value">{{ $portfolio->updated_at->diffForHumans() }}</div>
                </div>
                <div class="pw-meta-item">
                    <div class="pw-meta-label">Dokumen</div>
                    @if($portfolio->file_pdf_path)
                        <div class="pw-meta-value is-ok">Tersedia</div>
                    @else
                        <div class="pw-meta-value is-muted">Tidak ada</div>
                    @endif
                </div>
            </div>

            {{-- CTA PDF — hanya tampil jika dokumen tersedia --}}
            @if($portfolio->file_pdf_path)
            <a href="{{ asset('storage/' . $portfolio->file_pdf_path) }}" target="_blank" rel="noopener" class="pw-pdf-cta">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h4m1-16H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                </svg>
                <span>Lihat dokumen PDF</span>
            </a>
            @endif

            {{-- Bagikan karya --}}
            <div class="pw-share">
                <div class="pw-share-main">
                    <div class="pw-share-title">Bagikan karya ini</div>
                    <div class="pw-share-row">
                        <div class="pw-url-box" onclick="copyLink()" role="button" tabindex="0" onkeydown="if(event.key==='Enter')copyLink()" title="Klik untuk menyalin tautan">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span class="pw-url-text" id="shareUrl">{{ url('/p/' . $portfolio->slug) }}</span>
                        </div>
                        <button type="button" class="pw-btn" id="copyBtn" onclick="copyLink()">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span id="copyBtnLabel">Salin</span>
                        </button>
                    </div>
                </div>

                <div class="pw-qr">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=8&color=1C1A16&bgcolor=FFFFFF&data={{ urlencode(url('/p/' . $portfolio->slug)) }}"
                        alt="Kode QR menuju karya {{ $portfolio->title }}"
                        width="84" height="84" loading="lazy"
                        onerror="this.closest('.pw-qr').style.display='none'"
                    >
                    <div class="pw-qr-caption">Pindai untuk membuka karya ini</div>
                </div>
            </div>

            <a href="{{ route('portfolio.profile', $portfolio->user->portfolio_slug) }}" class="pw-profile-link">
                Lihat semua portofolio {{ explode(' ', $portfolio->user->name)[0] }}
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>

        </div>
    </main>

    {{-- ── KARYA LAINNYA ── --}}
    @if($relatedPortfolios->count() > 0)
    <section class="pw-related" aria-labelledby="pw-related-heading">
        <div class="pw-related-head">
            <h2 id="pw-related-heading">Karya lainnya dari {{ explode(' ', $portfolio->user->name)[0] }}</h2>
        </div>
        <div class="pw-related-grid">
            @foreach($relatedPortfolios as $related)
            <a href="{{ route('portfolio.public', $related->slug) }}" class="pw-related-card">
                <div class="pw-related-thumb-wrap">
                    <img
                        src="{{ asset('storage/' . $related->image_path) }}"
                        alt="{{ $related->title }}"
                        loading="lazy"
                        onerror="this.parentElement.style.background='var(--pw-surface-2)'; this.remove();"
                    >
                </div>
                <div class="pw-related-info">
                    <div class="pw-related-cat">{{ $related->category?->name ?? 'Umum' }}</div>
                    <div class="pw-related-title">{{ $related->title }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── FOOTER ── --}}
    <footer class="pw-footer">
        <p class="pw-footer-text">
            <strong>DKV SMEKDA</strong> &middot; SMK Negeri 2 Padang Panjang<br>
            Sistem Portofolio Digital &middot; Dikembangkan oleh Rafli, {{ date('Y') }}
        </p>
    </footer>

    {{-- Toast notifikasi --}}
    <div class="pw-toast" id="toast" role="status" aria-live="polite">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span>Tautan berhasil disalin</span>
    </div>

</div>
@endsection

@push('scripts')
<script>
    /* ── COPY LINK ──
       Nama fungsi & perilaku dipertahankan agar kompatibel dengan
       markup lain yang mungkin masih memanggilnya. */
    function copyLink() {
        const urlEl = document.getElementById('shareUrl');
        const url = urlEl ? urlEl.textContent.trim() : window.location.href;

        const showToast = () => {
            const toast = document.getElementById('toast');
            if (!toast) return;
            toast.classList.add('show');
            clearTimeout(window.__pwToastTimer);
            window.__pwToastTimer = setTimeout(() => toast.classList.remove('show'), 2600);

            const btn = document.getElementById('copyBtn');
            const label = document.getElementById('copyBtnLabel');
            if (btn && label) {
                const original = label.textContent;
                btn.classList.add('is-copied');
                label.textContent = 'Tersalin';
                clearTimeout(window.__pwBtnTimer);
                window.__pwBtnTimer = setTimeout(() => {
                    btn.classList.remove('is-copied');
                    label.textContent = original;
                }, 2000);
            }
        };

        const legacyCopy = () => {
            const el = document.createElement('textarea');
            el.value = url;
            el.style.position = 'fixed';
            el.style.opacity = '0';
            document.body.appendChild(el);
            el.select();
            try { document.execCommand('copy'); } catch (e) { /* diamkan */ }
            document.body.removeChild(el);
            showToast();
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(showToast).catch(legacyCopy);
        } else {
            legacyCopy();
        }
    }

    /* ── WEB SHARE API ──
       Dipakai oleh tombol "Bagikan" di navbar. Memakai native share
       sheet bila didukung perangkat, jika tidak tersedia jatuh ke
       copyLink() sehingga fungsinya tetap selalu bekerja. */
    function pwShare() {
        const url = document.getElementById('shareUrl')?.textContent.trim() || window.location.href;
        const title = @js($portfolio->title);

        if (navigator.share) {
            navigator.share({ title: title, url: url }).catch(() => { /* dibatalkan pengguna, diamkan */ });
        } else {
            copyLink();
        }
    }
</script>
@endpush