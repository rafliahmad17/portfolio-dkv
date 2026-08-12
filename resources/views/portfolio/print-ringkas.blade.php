{{-- ================================================================
     PDF RINGKAS — PREMIUM EDITORIAL (Swiss / minimal, light mode)
     Dipakai dari 2 tempat:
       1) Privat  : PortfolioController@printView       (siswa.portfolio.print)
       2) Publik  : PublicPortfolioController@print     (portfolio.public.print)
     Kedua rute mengirim variabel yang sama: $user, $portfolios.
     Fokus dokumen: identitas desainer, bio, keahlian, kontak, dan QR
     menuju Live Portfolio online — BUKAN grid thumbnail karya.
     Gaya: putih bersih, tipografi dominan, aksen merah tipis, 1 halaman A4.
================================================================ --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Portfolio {{ $user->name }} — Cetak PDF</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    :root {
        --paper:     #FFFFFF;
        --ink:       #111827;
        --ink-soft:  #6B7280;
        --ink-faint: #9CA3AF;
        --border:    #E5E7EB;
        --red:       #DC2626;
        --red-tint:  rgba(220,38,38,0.06);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Inter', sans-serif;
        background: #F5F5F4;
        color: var(--ink);
        -webkit-font-smoothing: antialiased;
    }

    /* ══════════════════════ TOOLBAR (layar saja) ══════════════════════ */
    .toolbar {
        position: sticky; top: 0; z-index: 50;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        padding: 12px 16px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border);
    }
    .toolbar-brand {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.68rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;
        color: var(--ink-faint); white-space: nowrap;
    }
    .toolbar-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .tbtn {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.74rem; font-weight: 700;
        padding: 8px 14px; border-radius: 8px; border: none; cursor: pointer;
        text-decoration: none; white-space: nowrap; transition: all 0.15s ease;
    }
    .tbtn-ghost { background: #F3F4F6; color: var(--ink); border: 1px solid var(--border); }
    .tbtn-ghost:hover { background: #E5E7EB; }
    .tbtn-red { background: var(--red); color: #fff; box-shadow: 0 3px 10px rgba(220,38,38,0.25); }
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
            background: var(--red-tint); border: 1px solid rgba(220,38,38,0.18);
            border-radius: 10px; font-size: 0.72rem; line-height: 1.5;
            color: var(--ink-soft); text-align: center;
        }
    }

    /* ══════════════════════ SHEET (halaman A4) ══════════════════════ */
    .stage { padding: 40px 0 64px; }
    .sheet {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        background: var(--paper);
        padding: 16mm;
        position: relative;
    }
    @media screen {
        .sheet {
            box-shadow: 0 1px 2px rgba(0,0,0,0.04), 0 16px 40px rgba(0,0,0,0.06);
        }
    }
    @media screen and (max-width: 900px) {
        .sheet { width: 100%; min-height: 0; padding: 8mm 6mm 10mm; }
    }
    @media print {
        html, body { background: #FFFFFF; }
        .sheet { width: 100%; min-height: 0; margin: 0; padding: 0; box-shadow: none; }
        .stage { padding: 0; }
        .toolbar, .mobile-hint, .no-print { display: none !important; }
        @page { size: A4 portrait; margin: 16mm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
        .avoid { break-inside: avoid; }
    }

    /* ══════════════════════ HEADER IDENTITAS ══════════════════════ */
    .id-header { display: flex; align-items: center; gap: 22px; padding-bottom: 26px; }
    .id-avatar {
        width: 88px; height: 88px; border-radius: 8px;
        background: #F3F4F6; border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; font-weight: 800; color: var(--red);
        overflow: hidden; flex-shrink: 0;
    }
    .id-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .id-name { font-size: 30px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.12; color: var(--ink); word-break: break-word; }
    .id-tagline { font-size: 11.5px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--red); margin-top: 7px; }
    .id-meta { font-size: 10.5px; color: var(--ink-soft); margin-top: 9px; line-height: 1.6; }

    .header-rule { height: 1px; background: var(--border); margin-bottom: 30px; }

    .section { margin-bottom: 32px; }
    .section-title {
        font-size: 10.5px; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase;
        color: var(--ink-soft); padding-left: 12px; border-left: 3px solid var(--red);
        margin-bottom: 14px; line-height: 1;
    }

    .bio-text { font-size: 12.5px; line-height: 1.9; color: #374151; max-width: 148mm; }

    /* ══════════════════════ KEAHLIAN ══════════════════════ */
    .skill-row { display: flex; flex-wrap: wrap; gap: 8px; }
    .skill-badge {
        font-size: 10.5px; font-weight: 600; color: var(--ink);
        background: #F9FAFB; border: 1px solid var(--border);
        border-radius: 4px; padding: 6px 14px;
    }

    /* ══════════════════════ KONTAK ══════════════════════ */
    .contact-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
    }
    .contact-item {
        display: flex; align-items: center; gap: 11px;
        padding: 16px 18px; border-left: 1px solid var(--border);
    }
    .contact-item:first-child { border-left: none; padding-left: 0; }
    .contact-icon {
        width: 32px; height: 32px; border-radius: 7px; background: var(--red-tint);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .contact-icon svg { width: 15px; height: 15px; stroke: var(--red); }
    .contact-label { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--ink-faint); margin-bottom: 3px; }
    .contact-value { font-size: 11px; font-weight: 600; color: var(--ink); line-height: 1.35; word-break: break-word; }

    @media screen and (max-width: 640px) {
        .contact-grid { grid-template-columns: 1fr !important; }
        .contact-item { border-left: none !important; border-top: 1px solid var(--border); padding: 14px 0; }
        .contact-item:first-child { border-top: none; }
    }

    /* ══════════════════════ QR HERO ══════════════════════ */
    .qr-hero { text-align: center; padding: 40px 0 6px; }
    .qr-hero img { width: 190px; height: 190px; display: block; margin: 0 auto 22px; }
    .qr-fallback {
        width: 190px; height: 190px; margin: 0 auto 22px;
        border: 1.5px solid var(--border); border-radius: 8px;
        display: none; align-items: center; justify-content: center;
        flex-direction: column; gap: 4px;
    }
    .qr-fallback span:first-child { font-size: 8px; font-weight: 800; letter-spacing: 1px; color: var(--ink-soft); }
    .qr-fallback span:last-child { font-size: 7.5px; color: var(--ink-faint); }
    .qr-hero-title { font-size: 14px; font-weight: 800; color: var(--ink); letter-spacing: -0.2px; margin-bottom: 8px; }
    .qr-hero-desc { font-size: 11px; line-height: 1.75; color: var(--ink-soft); max-width: 340px; margin: 0 auto 10px; }
    .qr-hero-url { font-size: 9.5px; color: var(--ink-faint); word-break: break-all; letter-spacing: 0.2px; }

    .print-footer { margin-top: 30px; text-align: center; font-size: 10px; color: #D1D5DB; letter-spacing: 0.3px; }
</style>
</head>
<body>

@php
    /**
     * Ambil maksimal 3 kalimat pertama dari bio agar isi PDF tetap
     * ringkas dan halaman tidak melebar melebihi 1 halaman A4.
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
     * (tanpa dependency baru). Modul gelap di atas kertas putih, tanpa
     * background gelap, agar konsisten dengan tema cetak premium/light.
     */
    $qrImg = function (string $data, int $size = 220) {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size
            . '&color=111827&bgcolor=ffffff&qzone=1&data=' . urlencode($data);
    };

    /**
     * Kumpulan kontak yang tersedia — hanya ditampilkan jika datanya ada,
     * agar layout kolom Kontak tetap rapi meski sebagian data kosong.
     */
    $kontakItems = collect([
        $user->contact ? ['label' => 'WhatsApp', 'value' => $user->contact, 'icon' => 'phone'] : null,
        $user->email ? ['label' => 'Email', 'value' => $user->email, 'icon' => 'mail'] : null,
        (!empty($user->instagram)) ? ['label' => 'Instagram', 'value' => $user->instagram, 'icon' => 'camera'] : null,
    ])->filter()->values();
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
            <div class="id-tagline">Desain Komunikasi Visual</div>
            <div class="id-meta">
                SMK Negeri 2 Padang Panjang
                @if($user->nis_nip) &bull; NIS/NIP {{ $user->nis_nip }} @endif
            </div>
        </div>
    </div>
    <div class="header-rule"></div>

    {{-- ══ TENTANG SAYA ══ --}}
    @if($bioSingkat)
    <div class="section avoid">
        <div class="section-title">Tentang Saya</div>
        <p class="bio-text">{{ $bioSingkat }}</p>
    </div>
    @endif

    {{-- ══ KEAHLIAN ══ --}}
    @if(!empty($user->skills))
    <div class="section avoid">
        <div class="section-title">Keahlian</div>
        <div class="skill-row">
            @foreach($user->skills as $skill)
                <span class="skill-badge">{{ $skill }}</span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══ KONTAK ══ --}}
    @if($kontakItems->isNotEmpty())
    <div class="section avoid">
        <div class="section-title">Kontak</div>
        <div class="contact-grid" style="grid-template-columns: repeat({{ $kontakItems->count() }}, 1fr);">
            @foreach($kontakItems as $item)
            <div class="contact-item">
                <div class="contact-icon">
                    @if($item['icon'] === 'phone')
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.5 8.5 0 01-8.5 8.5c-1.35 0-2.62-.32-3.74-.9L3 21l1.9-5.76A8.5 8.5 0 1121 11.5z"/></svg>
                    @elseif($item['icon'] === 'mail')
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8a2 2 0 012-2h1.2a1 1 0 00.86-.5l.9-1.5a1 1 0 01.86-.5h4.36a1 1 0 01.86.5l.9 1.5a1 1 0 00.86.5H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V8z"/><circle cx="12" cy="12.5" r="3.5"/></svg>
                    @endif
                </div>
                <div>
                    <div class="contact-label">{{ $item['label'] }}</div>
                    <div class="contact-value">{{ $item['value'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══ QR HERO — satu-satunya QR, mengarah ke galeri profil online ══ --}}
    <div class="qr-hero avoid">
        <img
            src="{{ $qrImg($galleryUrl, 460) }}"
            alt="QR Code Galeri {{ $user->name }}"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        >
        <div class="qr-fallback">
            <span>QR CODE</span>
            <span>Scan online</span>
        </div>
        <div class="qr-hero-title">Scan untuk membuka live portfolio</div>
        <p class="qr-hero-desc">Galeri karya, deskripsi proyek, proses desain, dan pembaruan terbaru tersedia secara online.</p>
        <div class="qr-hero-url">{{ $galleryUrl }}</div>
    </div>

    <div class="print-footer">
        Dibuat otomatis oleh Sistem Portofolio Digital DKV &bull; SMK Negeri 2 Padang Panjang &bull; {{ now()->translatedFormat('d F Y') }}
    </div>

</div>
</div>

</body>
</html>