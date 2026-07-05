<!-- resources/views/public/portfolio/profile.blade.php -->
{{-- Halaman profil publik siswa — Live URL Portfolio, tidak perlu login --}}
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

        body {
            font-family: 'Inter', sans-serif;
            background-color: #080808;
            color: #f5f5f5;
            min-height: 100vh;
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

        /* ── NAVBAR ── */
        .public-nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            padding: 14px 0;
        }
        .nav-inner {
            max-width: 1000px; margin: 0 auto; padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-brand { display: flex; align-items: center; gap: 9px; text-decoration: none; }
        .nav-logo-icon {
            width: 28px; height: 28px; border-radius: 8px; background: var(--red);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px var(--red-glow);
        }
        .nav-logo-icon svg { width: 14px; height: 14px; }
        .nav-brand-text {
            font-size: 0.8rem; font-weight: 900; letter-spacing: 3px;
            text-transform: uppercase; color: rgba(255,255,255,0.85);
        }
        .nav-brand-text span { color: var(--red); }
        .nav-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.2);
            border-radius: 30px; padding: 4px 12px;
            font-size: 0.65rem; font-weight: 700; color: #fca5a5;
            letter-spacing: 0.5px; text-transform: uppercase;
        }
        .live-dot {
            width: 5px; height: 5px; background: var(--red); border-radius: 50%;
            box-shadow: 0 0 6px var(--red-glow); animation: livePulse 1.5s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.3; transform: scale(0.6); }
        }

        .main-wrap { position: relative; z-index: 1; max-width: 1000px; margin: 0 auto; padding: 48px 24px 80px; }

        /* ── PROFILE HERO ── */
        .profile-hero {
            display: flex; align-items: center; gap: 24px;
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border); border-radius: 20px;
            padding: 32px; margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .profile-avatar {
            width: 84px; height: 84px; border-radius: 18px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 900; color: white;
            flex-shrink: 0; box-shadow: 0 0 22px rgba(220,38,38,0.3);
            overflow: hidden;
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-name { font-size: 1.5rem; font-weight: 900; letter-spacing: -0.5px; }
        .profile-role { font-size: 0.78rem; color: rgba(255,255,255,0.4); margin-top: 4px; }
        .profile-bio {
            font-size: 0.85rem; color: rgba(255,255,255,0.55);
            line-height: 1.7; margin-top: 12px; max-width: 560px;
        }
        .profile-stats { display: flex; gap: 20px; margin-left: auto; }
        .stat-box { text-align: center; }
        .stat-num { font-size: 1.4rem; font-weight: 900; color: #f5f5f5; }
        .stat-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; color: rgba(255,255,255,0.25); margin-top: 2px;
        }

        /* ── SHARE URL ── */
        .share-bar {
            display: flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 14px; padding: 12px 16px; margin-bottom: 36px;
            flex-wrap: wrap;
        }
        .share-label { font-size: 0.65rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.25); }
        .share-url-text { flex: 1; font-size: 0.78rem; color: rgba(255,255,255,0.5); font-family: monospace; min-width: 200px; }
        .share-copy-btn {
            font-size: 0.68rem; font-weight: 800; color: var(--red);
            letter-spacing: 0.5px; text-transform: uppercase; cursor: pointer;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.2);
            padding: 6px 14px; border-radius: 20px; transition: all 0.2s ease;
        }
        .share-copy-btn:hover { background: rgba(220,38,38,0.18); }

        /* ── WORKS GRID ── */
        .works-header { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .works-header::before { content: ''; display: block; width: 16px; height: 2px; background: var(--red); }
        .works-label { font-size: 0.7rem; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; color: rgba(255,255,255,0.35); }

        .works-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .work-card {
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden; text-decoration: none; display: block;
            transition: all 0.25s ease;
        }
        .work-card:hover {
            transform: translateY(-3px); border-color: rgba(220,38,38,0.2);
            box-shadow: 0 12px 32px rgba(0,0,0,0.35), 0 0 24px rgba(220,38,38,0.06);
        }
        .work-thumb { width: 100%; height: 160px; object-fit: cover; display: block; background: #111; }
        .work-info { padding: 14px; }
        .work-cat { font-size: 0.6rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #fca5a5; margin-bottom: 6px; }
        .work-title { font-size: 0.88rem; font-weight: 700; color: #f5f5f5; line-height: 1.3; }

        .empty-state {
            text-align: center; padding: 60px 24px; color: rgba(255,255,255,0.3);
            border: 1px dashed var(--border); border-radius: 16px; font-size: 0.85rem;
        }

        .public-footer { border-top: 1px solid var(--border); padding: 24px; text-align: center; }
        .footer-text { font-size: 0.72rem; color: rgba(255,255,255,0.14); }
        .footer-text strong { color: rgba(255,255,255,0.26); }
        .footer-text span { color: var(--red); }

        .toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            background: rgba(22,22,22,0.96); border: 1px solid rgba(34,197,94,0.3);
            border-radius: 12px; padding: 12px 20px; display: flex; align-items: center; gap: 10px;
            font-size: 0.8rem; font-weight: 700; color: #86efac;
            opacity: 0; transform: translateY(10px); transition: all 0.3s ease; pointer-events: none;
        }
        .toast.show { opacity: 1; transform: translateY(0); }

        @media (max-width: 640px) {
            .works-grid { grid-template-columns: repeat(2, 1fr); }
            .profile-stats { margin-left: 0; width: 100%; justify-content: flex-start; }
        }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

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
            Live Portfolio
        </div>
    </div>
</nav>

<div class="main-wrap">

    {{-- ── HERO PROFIL ── --}}
    <div class="profile-hero">
        <div class="profile-avatar">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <div>
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-role">
                Siswa DKV — SMKN 2 Padang Panjang
                @if($user->nis_nip) &bull; NIS {{ $user->nis_nip }} @endif
            </div>
            @if($user->bio)
            <div class="profile-bio">{{ $user->bio }}</div>
            @endif
        </div>
        <div class="profile-stats">
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

    {{-- ── SHARE LIVE URL ── --}}
    <div class="share-bar">
        <span class="share-label">Live URL</span>
        <span class="share-url-text" id="shareUrl">{{ url('/u/' . $user->portfolio_slug) }}</span>
        <span class="share-copy-btn" onclick="copyLink()">Copy Link</span>
    </div>

    {{-- ── GRID KARYA ── --}}
    <div class="works-header">
        <span class="works-label">Semua Karya</span>
    </div>

    @if($portfolios->count() > 0)
    <div class="works-grid">
        @foreach($portfolios as $portfolio)
        <a href="{{ route('portfolio.public', $portfolio->slug) }}" class="work-card">
            <img
                src="{{ asset('storage/' . $portfolio->image_path) }}"
                alt="{{ $portfolio->title }}"
                class="work-thumb"
                onerror="this.style.background='#1a1a1a'"
            >
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

</div>

<div class="public-footer">
    <p class="footer-text">
        &copy; {{ date('Y') }}
        &nbsp;<strong>DKV SMEKDA</strong>&nbsp;
        &bull; SMK Negeri 2 Padang Panjang
        &bull; Sistem Portfolio Digital
        &bull; Dikembangkan oleh <strong>Rafli</strong> &mdash; <span>2026</span>
    </p>
</div>

<div class="toast" id="toast">Link berhasil disalin ke clipboard!</div>

<script>
    function copyLink() {
        const url = document.getElementById('shareUrl').textContent.trim();
        navigator.clipboard.writeText(url).then(() => {
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2800);
        }).catch(() => {
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