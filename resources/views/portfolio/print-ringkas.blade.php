<!-- resources/views/portfolio/print-ringkas.blade.php -->
{{-- ================================================================
     PDF RINGKAS — versi cetak singkat (maks. 2 halaman A4), dipakai
     dari 2 tempat:
       1) Privat  : PortfolioController@printView       (siswa.portfolio.print)
       2) Publik  : PublicPortfolioController@print     (portfolio.public.print)
     Kedua rute mengirim variabel yang sama: $user, $portfolios.
     Palet warna & font disamakan dengan tema web (gelap-merah, Inter),
     BUKAN gaya hangat/editorial print.blade.php lama.
================================================================ --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Portfolio {{ $user->name }} — Cetak PDF</title>

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
        background: #121212;
        color: #f5f5f5;
    }

    /* ══════════════════════ TOOLBAR (layar saja) ══════════════════════ */
    .toolbar {
        position: sticky; top: 0; z-index: 50;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        padding: 12px 16px;
        background: rgba(8,8,8,0.92);
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border);
    }
    .toolbar-brand {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.68rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;
        color: rgba(255,255,255,0.4); white-space: nowrap;
    }
    .toolbar-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .tbtn {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.74rem; font-weight: 700;
        padding: 8px 14px; border-radius: 8px; border: none; cursor: pointer;
        text-decoration: none; white-space: nowrap; transition: all 0.2s ease;
    }
    .tbtn-ghost { background: rgba(255,255,255,0.06); color: #f5f5f5; border: 1px solid var(--border); }
    .tbtn-ghost:hover { background: rgba(255,255,255,0.1); }
    .tbtn-red { background: var(--red); color: #fff; box-shadow: 0 4px 16px rgba(220,38,38,0.35); }
    .tbtn-red:hover { background: #b91c1c; }
    .tbtn svg { width: 14px; height: 14px; flex-shrink: 0; }

    @media (max-width: 640px) {
        .toolbar-brand span:last-child { display: none; }
        .tbtn-ghost span { display: none; }
    }

    /* ══════════════════════ HINT MOBILE (layar kecil saja) ══════════════════════ */
    .mobile-hint { display: none; }
    @media screen and (max-width: 640px) {
        .mobile-hint {
            display: block; margin: 12px 16px 0; padding: 12px 14px;
            background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.2);
            border-radius: 10px; font-size: 0.72rem; line-height: 1.5;
            color: rgba(255,255,255,0.6); text-align: center;
        }
    }

    /* ══════════════════════ SHEET (halaman A4) ══════════════════════ */
    .stage { padding: 24px 0 48px; }
    .sheet {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        background: #080808;
        padding: 14mm 13mm;
        position: relative;
    }
    @media screen {
        .sheet {
            box-shadow: 0 1px 1px rgba(0,0,0,0.25), 0 8px 24px rgba(0,0,0,0.35), 0 30px 70px rgba(0,0,0,0.45);
        }
    }
    @media screen and (max-width: 900px) {
        .sheet { width: 100%; min-height: 0; padding: 8mm 6mm 10mm; }
    }
    @media print {
        html, body { background: #080808; }
        .sheet { width: 100%; min-height: 0; margin: 0; padding: 0; box-shadow: none; }
        .stage { padding: 0; }
        .toolbar, .mobile-hint, .no-print { display: none !important; }
        @page { size: A4 portrait; margin: 14mm 13mm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
        .avoid { break-inside: avoid; }
    }

    /* ══════════════════════ HEADER IDENTITAS ══════════════════════ */
    .id-header { display: flex; align-items: center; gap: 14pt; }
    .id-avatar {
        width: 60pt; height: 60pt; border-radius: 14pt;
        background: linear-gradient(135deg, #dc2626, #7c3aed);
        display: flex; align-items: center; justify-content: center;
        font-size: 21pt; font-weight: 900; color: #fff;
        overflow: hidden; flex-shrink: 0;
    }
    .id-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .id-name { font-size: 18.5pt; font-weight: 900; letter-spacing: -0.3pt; line-height: 1.15; word-break: break-word; }
    .id-tagline { font-size: 9.5pt; font-weight: 600; color: #fca5a5; margin-top: 3pt; }
    .id-meta { font-size: 7.8pt; color: rgba(255,255,255,0.4); margin-top: 4pt; line-height: 1.5; }

    .rule { height: 1px; background: var(--border); margin: 11pt 0; }

    .bio-text { font-size: 8.6pt; line-height: 1.65; color: rgba(255,255,255,0.65); }

    .tag-row { display: flex; flex-wrap: wrap; gap: 5pt; margin-top: 11pt; }
    .tag {
        font-size: 7.3pt; font-weight: 700; color: rgba(255,255,255,0.7);
        background: rgba(255,255,255,0.04); border: 1px solid var(--border);
        border-radius: 5pt; padding: 3pt 8pt;
    }

    .section-idx {
        font-size: 7pt; font-weight: 800; letter-spacing: 2pt; text-transform: uppercase;
        color: rgba(255,255,255,0.3); margin-bottom: 9pt; display: flex; align-items: center; gap: 6pt;
    }
    .section-idx::before { content: ''; width: 10pt; height: 1.4pt; background: var(--red); display: block; flex-shrink: 0; }

    .karya-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8pt; }
    .karya-card { border: 1px solid var(--border); border-radius: 6pt; overflow: hidden; }
    .karya-thumb { width: 100%; height: 60pt; object-fit: cover; display: block; background: #151515; }
    .karya-info { padding: 6pt 7pt; }
    .karya-cat { font-size: 5.8pt; font-weight: 800; letter-spacing: 0.6pt; text-transform: uppercase; color: #fca5a5; margin-bottom: 2pt; }
    .karya-title {
        font-size: 7.6pt; font-weight: 700; color: #f5f5f5; line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }

    .more-hint { text-align: center; font-size: 7.6pt; color: rgba(255,255,255,0.35); margin-top: 9pt; }

    .empty-hint { font-size: 8.6pt; color: rgba(255,255,255,0.35); }

    .qr-block { display: flex; align-items: center; gap: 14pt; }
    .qr-img-wrap { flex-shrink: 0; width: 88px; height: 88px; }
    .qr-img-wrap img { width: 100%; height: 100%; border-radius: 6pt; display: block; }
    .qr-fallback {
        width: 88px; height: 88px; border: 1.5px solid rgba(255,255,255,0.2);
        border-radius: 6pt; display: none; align-items: center; justify-content: center;
        flex-direction: column; gap: 3pt;
    }
    .qr-fallback span:first-child { font-size: 6.5pt; font-weight: 800; color: rgba(255,255,255,0.5); }
    .qr-fallback span:last-child { font-size: 6pt; color: rgba(255,255,255,0.3); }
    .qr-title { font-size: 9pt; font-weight: 800; color: var(--red); margin-bottom: 4pt; }
    .qr-desc { font-size: 7.6pt; line-height: 1.55; color: rgba(255,255,255,0.5); margin-bottom: 4pt; }
    .qr-url { font-size: 6.4pt; color: rgba(255,255,255,0.3); word-break: break-all; }

    .print-footer { margin-top: 11pt; text-align: center; font-size: 6.4pt; color: rgba(255,255,255,0.16); }
</style>
</head>
<body>

@php
    /**
     * Siapkan data ringkas untuk PDF: total karya, maksimal 6 karya
     * terbaru untuk ditampilkan pada grid, dan sisa karya (jika ada)
     * untuk ditulis sebagai teks singkat + arahan pindai QR.
     */
    $totalKarya  = $portfolios->count();
    $karyaTampil = $portfolios->take(6);
    $sisaKarya   = max(0, $totalKarya - $karyaTampil->count());

    /**
     * Ambil maksimal 3 kalimat pertama dari bio agar isi PDF tetap
     * ringkas dan halaman tidak melebar melebihi batas 1-2 halaman A4.
     */
    $bioSingkat = null;
    if ($user->bio) {
        $kalimatBio = preg_split('/(?<=[.!?])\s+/', trim($user->bio), -1, PREG_SPLIT_NO_EMPTY);
        $bioSingkat = implode(' ', array_slice($kalimatBio, 0, 3));
    }

    /**
     * URL galeri Live Portfolio online. Fallback aman ke halaman utama
     * jika portfolio_slug belum tersedia, supaya QR/tautan tidak pernah rusak.
     */
    $galleryUrl = $user->portfolio_slug
        ? route('portfolio.profile', $user->portfolio_slug)
        : url('/');

    /**
     * qrImg — generator URL QR code lewat layanan eksternal qrserver.com
     * (tanpa dependency baru), warna disamakan dengan tema gelap-merah web.
     */
    $qrImg = function (string $data, int $size = 220) {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size
            . '&color=f5f5f5&bgcolor=0a0a0a&qzone=1&data=' . urlencode($data);
    };
@endphp

{{-- ================================================================
     TOOLBAR — hanya tampil di layar, otomatis hilang saat dicetak
================================================================ --}}
<div class="toolbar no-print">
    <div class="toolbar-brand">
        <span>DKV/SMEKDA</span>
        <span>&mdash; Portfolio PDF Ringkas</span>
    </div>
    <div class="toolbar-actions">
        @if(auth()->check() && auth()->id() === $user->id)
        <a href="{{ route('siswa.dashboard') }}" class="tbtn tbtn-ghost">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Dashboard</span>
        </a>
        @endif
        <a href="{{ $galleryUrl }}" target="_blank" rel="noopener" class="tbtn tbtn-ghost">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            <span>Lihat Profil Online</span>
        </a>
        <button onclick="window.print()" class="tbtn tbtn-red">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Cetak / Simpan PDF
        </button>
    </div>
</div>

<div class="mobile-hint no-print">
    Untuk hasil terbaik, gunakan tombol <strong>Cetak / Simpan PDF</strong> di atas untuk mengunduh dokumen ini.
</div>

<div class="stage">
<div class="sheet">

    {{-- ══ HEADER IDENTITAS ══ --}}
    <div class="id-header avoid">
        <div class="id-avatar">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <div>
            <div class="id-name">{{ $user->name }}</div>
            <div class="id-tagline">Siswa Desain Komunikasi Visual</div>
            <div class="id-meta">
                SMK Negeri 2 Padang Panjang
                @if($user->nis_nip) &bull; NIS/NIP {{ $user->nis_nip }} @endif
                @if($user->contact) &bull; WA {{ $user->contact }} @endif
            </div>
        </div>
    </div>

    <div class="rule"></div>

    @if($bioSingkat)
    <p class="bio-text">{{ $bioSingkat }}</p>
    @endif

    @if(!empty($user->skills))
    <div class="tag-row">
        @foreach($user->skills as $skill)
            <span class="tag">{{ $skill }}</span>
        @endforeach
    </div>
    @endif

    <div class="rule"></div>

    {{-- ══ KARYA ══ --}}
    <div class="section-idx">
        Karya Terbaru
        @if($totalKarya > 0)
            ({{ $totalKarya }})
        @endif
    </div>

    @if($karyaTampil->count() > 0)
    <div class="karya-grid">
        @foreach($karyaTampil as $portfolio)
        <div class="karya-card avoid">
            <img
                src="{{ asset('storage/' . $portfolio->image_path) }}"
                alt="{{ $portfolio->title }}"
                class="karya-thumb"
                onerror="this.style.background='#1a1a1a'"
            >
            <div class="karya-info">
                <div class="karya-cat">{{ $portfolio->category?->name ?? 'Umum' }}</div>
                <div class="karya-title">{{ $portfolio->title }}</div>
            </div>
        </div>
        @endforeach
    </div>
    @if($sisaKarya > 0)
    <div class="more-hint">+{{ $sisaKarya }} karya lainnya &mdash; pindai QR di bawah untuk lihat semua</div>
    @endif
    @else
    <p class="empty-hint">Siswa ini belum mengunggah karya.</p>
    @endif

    <div class="rule"></div>

    {{-- ══ QR PENUTUP — satu-satunya QR, mengarah ke galeri profil online ══ --}}
    <div class="qr-block avoid">
        <div class="qr-img-wrap">
            <img
                src="{{ $qrImg($galleryUrl, 220) }}"
                alt="QR Code Galeri {{ $user->name }}"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div class="qr-fallback">
                <span>QR CODE</span>
                <span>Scan online</span>
            </div>
        </div>
        <div>
            <div class="qr-title">Lihat Semua Karya Secara Online</div>
            <div class="qr-desc">Pindai kode ini untuk membuka galeri digital lengkap {{ $user->name }}, lengkap dengan deskripsi tiap karya dan pembaruan terbaru.</div>
            <div class="qr-url">{{ $galleryUrl }}</div>
        </div>
    </div>

    <div class="print-footer">
        Dibuat otomatis oleh Sistem Portofolio Digital DKV &bull; SMK Negeri 2 Padang Panjang &bull; {{ now()->translatedFormat('d F Y') }}
    </div>

</div>
</div>

</body>
</html>
