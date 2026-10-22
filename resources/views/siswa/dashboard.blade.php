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
@vite('resources/css/pages/siswa-dashboard.css')
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
                        <x-school-logo alt="Logo SMK" />
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
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" width="64" height="64" decoding="async" style="width: 100%; height: 100%; object-fit: cover;">
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
                    @if($featured)
                        <span class="sep">&middot;</span>
                        <a href="{{ route('portfolio.public', $featured->slug) }}" target="_blank" rel="noopener noreferrer">Lihat Etalase Publik &#8599;</a>
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
        @if($featured)
        <section class="hero-spotlight" aria-labelledby="hero-heading">
            <div class="hero-grid">
                <div class="hero-frame">
                    <a href="{{ route('portfolio.public', $featured->slug) }}" target="_blank" rel="noopener noreferrer" class="hero-frame-inner" data-glow data-cursor-target>
                        <img
                            src="{{ $featured->image_url }}"
                            {!! $featured->imageSizeAttributes() !!}
                            alt="{{ $featured->title }}"
                            class="hero-image"
                            fetchpriority="high"
                            decoding="async"
                            onerror="this.onerror=null; this.closest('.hero-frame-inner').classList.add('thumb-broken');"
                        >
                        @if($featured->file_pdf_path)
                            <div class="pdf-pill">PDF</div>
                        @endif
                    </a>
                </div>
                <div class="hero-content">
                    <div class="hero-eyebrow">Karya Terbaru &middot; No. 001</div>
                    <div class="hero-category">{{ $featured->category?->name ?? 'Umum' }}</div>
                    <h2 class="hero-title" id="hero-heading">{{ $featured->title }}</h2>
                    <p class="hero-desc">{{ $featured->description }}</p>
                    <div class="hero-meta">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $featured->created_at->diffForHumans() }}
                    </div>
                    <div class="hero-actions">
                        <a href="{{ route('portfolio.public', $featured->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-primary on-dark">
                            <span>Lihat Karya</span>
                            <span class="btn-icon-chip">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 17L17 7M9 7h8v8"/>
                                </svg>
                            </span>
                        </a>
                        <a href="{{ route('siswa.portfolio.edit', $featured) }}" class="btn-ghost on-dark">
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

            {{-- Dua kartu kategori mengikuti kategori yang paling banyak dipakai siswa
                 (dihitung lewat category_id di DashboardController), sehingga nama
                 kartu ikut berubah jika guru mengganti nama kategori. --}}
            @foreach($kategoriTeratas as $kategori)
            <div class="stat-block">
                <div class="stat-label">
                    <span class="idx">{{ str_pad($loop->iteration + 1, 2, '0', STR_PAD_LEFT) }}</span>{{ $kategori['name'] ? 'Karya ' . $kategori['name'] : 'Kategori Lainnya' }}
                </div>
                <div class="stat-number" data-count="{{ $kategori['total'] }}">0</div>
                <div class="stat-bar-wrap">
                    <div class="stat-bar" style="width:{{ $totalKarya > 0 ? min(100, ($kategori['total'] / $totalKarya) * 100) : 0 }}%;"></div>
                </div>
            </div>
            @endforeach

        </section>

        {{-- ── ETALASE KARYA ── --}}
        <section aria-labelledby="portfolio-heading" class="showcase-panel">

            <div class="section-header-bar">
                <div>
                    <h2 class="section-title" id="portfolio-heading">Arsip Karya Lengkap</h2>
                    <div class="section-sub">
                        {{ $totalKarya }} karya tersimpan &middot; karya terbaru tampil di atas
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

            @if(! $featured)

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

                    @foreach($portfolios as $portfolio)
                    <div class="portfolio-card" data-reveal data-glow>
                        <a href="{{ route('portfolio.public', $portfolio->slug) }}" target="_blank" rel="noopener noreferrer" class="thumb-wrapper" data-cursor-target>
                            <img
                                src="{{ $portfolio->thumbnail_url }}"
                                {!! $portfolio->imageSizeAttributes(true) !!}
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

                @include('partials.pagination', ['paginator' => $portfolios])

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
@endpush
