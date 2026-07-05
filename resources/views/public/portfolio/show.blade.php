{{-- resources/views/public/show.blade.php --}}
{{-- Halaman publik — tidak memerlukan login, bisa dibuka siapa saja via link/QR --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO & Open Graph (untuk preview saat dishare di WhatsApp/Instagram) --}}
    <title>{{ $portfolio->title }} — Portfolio {{ $portfolio->user->name }}</title>
    <meta name="description" content="{{ Str::limit($portfolio->description, 160) }}">
    <meta property="og:title"       content="{{ $portfolio->title }}">
    <meta property="og:description" content="{{ Str::limit($portfolio->description, 160) }}">
    <meta property="og:image"       content="{{ asset('storage/' . $portfolio->image_path) }}">
    <meta property="og:type"        content="article">
    <meta name="twitter:card"       content="summary_large_image">

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

        body {
            font-family: 'Inter', sans-serif;
            background-color: #080808;
            color: #f5f5f5;
            min-height: 100vh;
        }

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

        .blob {
            position: fixed; border-radius: 50%;
            pointer-events: none; z-index: 0;
        }

        .blob-1 {
            top: -200px; right: -100px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, transparent 65%);
            animation: blobF 12s ease-in-out infinite alternate;
        }

        .blob-2 {
            bottom: -150px; left: -100px;
            width: 500px; height: 500px;
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

        /* ── NAVBAR PUBLIK ── */
        .public-nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            padding: 14px 0;
        }

        .nav-inner {
            max-width: 900px; margin: 0 auto;
            padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 9px;
            text-decoration: none;
        }

        .nav-logo-icon {
            width: 28px; height: 28px; border-radius: 8px;
            background: var(--red);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px var(--red-glow);
        }

        .nav-logo-icon svg { width: 14px; height: 14px; }

        .nav-brand-text {
            font-size: 0.8rem; font-weight: 900;
            letter-spacing: 3px; text-transform: uppercase;
            color: rgba(255,255,255,0.85);
        }

        .nav-brand-text span { color: var(--red); }

        .nav-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.2);
            border-radius: 30px; padding: 4px 12px;
            font-size: 0.65rem; font-weight: 700;
            color: #fca5a5; letter-spacing: 0.5px; text-transform: uppercase;
        }

        .live-dot {
            width: 5px; height: 5px; background: var(--red);
            border-radius: 50%; box-shadow: 0 0 6px var(--red-glow);
            animation: livePulse 1.5s ease-in-out infinite;
        }

        @keyframes livePulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.3; transform: scale(0.6); }
        }

        /* ── MAIN CONTAINER ── */
        .main-wrap {
            position: relative; z-index: 1;
            max-width: 900px; margin: 0 auto;
            padding: 48px 24px 80px;
        }

        /* ── HERO IMAGE ── */
        .hero-image-wrap {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: #111;
            margin-bottom: 36px;
            position: relative;
        }

        .hero-image-wrap img {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            display: block;
            background: #0f0f0f;
        }

        .hero-image-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, transparent 60%, rgba(8,8,8,0.4));
            pointer-events: none;
        }

        /* ── CONTENT GRID ── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 24px;
            align-items: start;
        }

        /* ── MAIN CONTENT ── */
        .content-main {
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .content-main::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }

        .content-main-body { padding: 32px; }

        .category-badge {
            display: inline-flex; align-items: center;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.2);
            color: #fca5a5;
            font-size: 0.65rem; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase;
            padding: 4px 12px; border-radius: 20px;
            margin-bottom: 16px;
        }

        .portfolio-title {
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 900; letter-spacing: -1px; line-height: 1.15;
            color: #f5f5f5; margin-bottom: 24px;
        }

        .divider-red { width: 32px; height: 2px; background: var(--red); margin-bottom: 20px; }

        .description-text {
            font-size: 0.9rem; color: rgba(255,255,255,0.55);
            line-height: 1.85; text-align: justify;
        }

        /* ── SIDEBAR ── */
        .sidebar-card {
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: 20px; overflow: hidden;
            position: relative;
        }

        .sidebar-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }

        .sidebar-section { padding: 20px; }

        .sidebar-section + .sidebar-section {
            border-top: 1px solid var(--border);
        }

        .sidebar-label {
            font-size: 0.62rem; font-weight: 800;
            letter-spacing: 2.5px; text-transform: uppercase;
            color: rgba(255,255,255,0.2); margin-bottom: 10px;
        }

        /* Author block */
        .author-avatar {
            width: 44px; height: 44px; border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; font-weight: 900; color: white;
            flex-shrink: 0; box-shadow: 0 0 16px rgba(220,38,38,0.25);
        }

        .author-name {
            font-size: 0.88rem; font-weight: 800; color: #f5f5f5;
            line-height: 1.3;
        }

        .author-role {
            font-size: 0.68rem; color: rgba(255,255,255,0.28);
            margin-top: 2px;
        }

        /* Meta rows */
        .meta-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.04);
        }

        .meta-row:last-child { border-bottom: none; }

        .meta-key {
            font-size: 0.7rem; color: rgba(255,255,255,0.28); font-weight: 600;
        }

        .meta-val {
            font-size: 0.75rem; color: #f5f5f5; font-weight: 700; text-align: right;
        }

        /* Action buttons */
        .btn-red {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%;
            background: var(--red); color: white; border: none;
            border-radius: 11px; padding: 12px 20px;
            font-size: 0.8rem; font-weight: 800;
            font-family: 'Inter', sans-serif; cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(220,38,38,0.28);
            position: relative; overflow: hidden;
        }

        .btn-red::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, #b91c1c, #ef4444);
            opacity: 0; transition: opacity 0.3s ease;
        }

        .btn-red:hover { transform: translateY(-1px); box-shadow: 0 8px 28px var(--red-glow); }
        .btn-red:hover::before { opacity: 1; }
        .btn-red span, .btn-red svg { position: relative; z-index: 1; }
        .btn-red svg { width: 15px; height: 15px; }

        .btn-outline {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%;
            background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.5);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 11px; padding: 11px 20px;
            font-size: 0.8rem; font-weight: 700;
            font-family: 'Inter', sans-serif; cursor: pointer;
            text-decoration: none; margin-top: 8px;
            transition: all 0.22s ease;
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.16);
            color: #f5f5f5;
        }

        .btn-outline svg { width: 15px; height: 15px; }

        /* Share URL box */
        .share-url-box {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 10px; padding: 10px 12px;
            cursor: pointer; transition: all 0.22s ease;
        }

        .share-url-box:hover { border-color: rgba(220,38,38,0.3); }

        .share-url-text {
            flex: 1; font-size: 0.7rem; color: rgba(255,255,255,0.3);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            font-family: monospace;
        }

        .share-copy-btn {
            flex-shrink: 0; font-size: 0.62rem; font-weight: 800;
            color: var(--red); letter-spacing: 0.5px; text-transform: uppercase;
            cursor: pointer; transition: opacity 0.2s;
        }

        /* QR area */
        .qr-wrap {
            text-align: center; padding: 4px 0;
        }

        .qr-wrap img {
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.06);
        }

        /* ── RELATED WORKS ── */
        .related-section { margin-top: 36px; }

        .related-header {
            display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        }

        .related-header::before {
            content: '';
            display: block; width: 16px; height: 2px; background: var(--red);
        }

        .related-label {
            font-size: 0.7rem; font-weight: 800;
            letter-spacing: 3px; text-transform: uppercase;
            color: rgba(255,255,255,0.35);
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .related-card {
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: 14px; overflow: hidden;
            text-decoration: none;
            transition: all 0.25s ease;
            display: block;
        }

        .related-card:hover {
            transform: translateY(-3px);
            border-color: rgba(220,38,38,0.2);
            box-shadow: 0 12px 32px rgba(0,0,0,0.35), 0 0 24px rgba(220,38,38,0.06);
        }

        .related-thumb {
            width: 100%; height: 100px; object-fit: cover;
            display: block; background: #111;
        }

        .related-info { padding: 10px; }

        .related-cat {
            font-size: 0.55rem; font-weight: 800; letter-spacing: 1.5px;
            text-transform: uppercase; color: #fca5a5; margin-bottom: 4px;
        }

        .related-title {
            font-size: 0.75rem; font-weight: 700; color: #f5f5f5;
            line-height: 1.3;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* ── FOOTER PUBLIK ── */
        .public-footer {
            border-top: 1px solid var(--border);
            padding: 24px;
            text-align: center;
            margin-top: 0;
        }

        .footer-text { font-size: 0.72rem; color: rgba(255,255,255,0.14); }
        .footer-text strong { color: rgba(255,255,255,0.26); }
        .footer-text span { color: var(--red); }

        /* ── TOAST ── */
        .toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            background: rgba(22,22,22,0.96);
            border: 1px solid rgba(34,197,94,0.3);
            border-radius: 12px; padding: 12px 20px;
            display: flex; align-items: center; gap: 10px;
            font-size: 0.8rem; font-weight: 700; color: #86efac;
            opacity: 0; transform: translateY(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .toast.show { opacity: 1; transform: translateY(0); }
        .toast svg  { width: 16px; height: 16px; flex-shrink: 0; color: #4ade80; }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

{{-- ── NAVBAR PUBLIK ── --}}
<nav class="public-nav">
    <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-brand">
            <div class="nav-logo-icon">
                <svg fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="nav-brand-text">DKV<span>.</span>SMEKDA</span>
        </a>

        <div class="nav-badge">
            <div class="live-dot"></div>
            Portfolio Publik
        </div>
    </div>
</nav>

{{-- ── MAIN ── --}}
<div class="main-wrap">

    {{-- Hero Image --}}
    <div class="hero-image-wrap">
        <img
            src="{{ asset('storage/' . $portfolio->image_path) }}"
            alt="{{ $portfolio->title }}"
            onerror="this.style.minHeight='200px'; this.style.display='flex';"
        >
        <div class="hero-image-overlay"></div>
    </div>

    {{-- 2-Column Content --}}
    <div class="content-grid">

        {{-- ── LEFT: DETAIL KARYA ── --}}
        <div class="content-main">
            <div class="content-main-body">

                <div class="category-badge">
                    {{ $portfolio->category?->name ?? 'Umum' }}
                </div>

                <h1 class="portfolio-title">{{ $portfolio->title }}</h1>

                <div class="divider-red"></div>

                <p class="description-text">{{ $portfolio->description }}</p>

                {{-- PDF Download --}}
                @if($portfolio->file_pdf_path)
                <div style="margin-top:28px; padding-top:20px; border-top:1px solid var(--border);">
                    <div style="font-size:0.7rem; font-weight:800; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,0.2); margin-bottom:12px;">
                        Dokumen Pendukung
                    </div>
                    
                        href="{{ asset('storage/' . $portfolio->file_pdf_path) }}"
                        target="_blank"
                        style="display:inline-flex; align-items:center; gap:8px; background:rgba(220,38,38,0.08); border:1px solid rgba(220,38,38,0.2); color:#fca5a5; padding:10px 16px; border-radius:10px; font-size:0.78rem; font-weight:700; text-decoration:none; transition:all 0.22s ease;"
                        onmouseover="this.style.background='rgba(220,38,38,0.14)'"
                        onmouseout="this.style.background='rgba(220,38,38,0.08)'"
                    >
                        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Lihat Dokumen PDF
                        <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
                @endif

            </div>
        </div>

        {{-- ── RIGHT: SIDEBAR INFO ── --}}
        <div>
            <div class="sidebar-card">

                {{-- Author --}}
                <div class="sidebar-section">
                    <div class="sidebar-label">Dibuat Oleh</div>
                    <div style="display:flex; align-items:center; gap:12px;">
                       <div class="author-avatar" style="overflow: hidden;">
                            @if($portfolio->user->photo)
                                <img src="{{ asset('storage/' . $portfolio->user->photo) }}" alt="{{ $portfolio->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                {{ strtoupper(substr($portfolio->user->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="author-name">{{ $portfolio->user->name }}</div>
                            <div class="author-role">Siswa DKV — SMKN 2 Padang Panjang</div>
                            @if($portfolio->user->nis_nip)
                            <div class="author-role" style="margin-top:2px;">NIS: {{ $portfolio->user->nis_nip }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Meta info --}}
                <div class="sidebar-section">
                    <div class="sidebar-label">Detail Karya</div>
                    <div class="meta-row">
                        <span class="meta-key">Kategori</span>
                        <span class="meta-val">{{ $portfolio->category?->name ?? 'Umum' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Diunggah</span>
                        <span class="meta-val">{{ $portfolio->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Terakhir Update</span>
                        <span class="meta-val">{{ $portfolio->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Dokumen PDF</span>
                        <span class="meta-val">
                            @if($portfolio->file_pdf_path)
                                <span style="color:#4ade80;">&#10003; Tersedia</span>
                            @else
                                <span style="color:rgba(255,255,255,0.2);">—</span>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Share URL --}}
                <div class="sidebar-section">
                    <div class="sidebar-label">Bagikan Karya</div>

                    {{-- URL Copy --}}
                    <div class="share-url-box" onclick="copyLink()" title="Klik untuk copy link">
                        <svg style="width:13px;height:13px;color:rgba(255,255,255,0.2);flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <span class="share-url-text" id="shareUrl">{{ url('/p/' . $portfolio->slug) }}</span>
                        <span class="share-copy-btn">Copy</span>
                    </div>

                    {{-- QR Code --}}
                    <div class="qr-wrap" style="margin-top:14px;">
                        <div style="font-size:0.62rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:rgba(255,255,255,0.18); margin-bottom:10px;">
                            QR Code
                        </div>
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&color=f5f5f5&bgcolor=0a0a0a&data={{ urlencode(url('/p/' . $portfolio->slug)) }}"
                            alt="QR Code"
                            width="150"
                            height="150"
                            style="border-radius:10px; border:1px solid rgba(255,255,255,0.06);"
                            onerror="this.parentElement.innerHTML='<div style=\'font-size:0.7rem;color:rgba(255,255,255,0.18);padding:12px;\'>QR Code tidak tersedia saat offline</div>'"
                        >
                        <div style="font-size:0.62rem; color:rgba(255,255,255,0.18); margin-top:8px; line-height:1.5;">
                            Scan untuk buka di perangkat lain
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="sidebar-section">
                    <a href="{{ url('/p/' . $portfolio->slug) }}"
                       onclick="copyLink(); return false;"
                       class="btn-red">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        <span>Salin Link Portofolio</span>
                    </a>

                    @if($portfolio->file_pdf_path)
                    <a href="{{ asset('storage/' . $portfolio->file_pdf_path) }}" target="_blank" class="btn-outline">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Unduh PDF
                    </a>
                    @endif

                    <a href="{{ url('/') }}" class="btn-outline" style="margin-top:8px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Lihat Semua Portfolio
                    </a>
                </div>

            </div>
        </div>

    </div>

    {{-- ── KARYA LAINNYA ── --}}
    @if($relatedPortfolios->count() > 0)
    <div class="related-section">
        <div class="related-header">
            <span class="related-label">Karya Lainnya dari {{ explode(' ', $portfolio->user->name)[0] }}</span>
        </div>
        <div class="related-grid">
            @foreach($relatedPortfolios as $related)
            <a href="{{ route('portfolio.public', $related->slug) }}" class="related-card">
                <img
                    src="{{ asset('storage/' . $related->image_path) }}"
                    alt="{{ $related->title }}"
                    class="related-thumb"
                    onerror="this.style.background='#1a1a1a'"
                >
                <div class="related-info">
                    <div class="related-cat">{{ $related->category?->name ?? 'Umum' }}</div>
                    <div class="related-title">{{ $related->title }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- Footer --}}
<div class="public-footer">
    <p class="footer-text">
        &copy; {{ date('Y') }}
        &nbsp;<strong>DKV SMEKDA</strong>&nbsp;
        &bull; SMK Negeri 2 Padang Panjang
        &bull; Sistem Portfolio Digital
        &bull; Dikembangkan oleh <strong>Rafli</strong> &mdash; <span>2026</span>
    </p>
</div>

{{-- Toast Notifikasi --}}
<div class="toast" id="toast">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    </svg>
    Link berhasil disalin ke clipboard!
</div>

<script>
    /* ── COPY LINK ── */
    function copyLink() {
        const url = document.getElementById('shareUrl').textContent.trim();
        navigator.clipboard.writeText(url).then(() => {
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2800);
        }).catch(() => {
            // Fallback untuk browser lama
            const el = document.createElement('textarea');
            el.value = url;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2800);
        });
    }
</script>

</body>
</html>