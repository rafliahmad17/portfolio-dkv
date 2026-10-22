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
@vite('resources/css/pages/public-portfolio-show.css')
@endpush

@section('content')
<div class="pw">

    {{-- ── NAVIGASI ── --}}
    <nav class="pw-nav" aria-label="Navigasi halaman publik">
        <div class="pw-nav-inner">
            <a href="{{ url('/') }}" class="pw-brand">
                <x-school-logo alt="Lambang SMK Negeri 2 Padang Panjang" />
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
                    src="{{ $portfolio->image_url }}"
                    {!! $portfolio->imageSizeAttributes() !!}
                    fetchpriority="high" decoding="async"
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
                        <img src="{{ asset('storage/' . $portfolio->user->photo) }}" alt="{{ $portfolio->user->name }}" width="96" height="96" decoding="async">
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
                        {{-- NIS sengaja TIDAK ditampilkan di halaman publik (data pribadi siswa). --}}
                        Siswa DKV &middot; SMK Negeri 2 Padang Panjang
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
                        src="{{ $related->thumbnail_url }}"
                        {!! $related->imageSizeAttributes(true) !!}
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