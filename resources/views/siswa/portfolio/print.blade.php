<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Portfolio — {{ $user->name }}</title>

{{-- ============================================================
     FONTS — Söhne-style display pairing untuk editorial book
     Fraunces  → display serif, karakter kuat untuk judul besar
     Inter     → body & UI, netral dan sangat terbaca di cetak kecil
     JetBrains Mono → angka indeks & metadata teknis (nomor halaman, tanggal)
============================================================ --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;0,9..144,900;1,9..144,400;1,9..144,500&family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          display: ['Fraunces', 'Georgia', 'serif'],
          sans: ['Inter', 'Helvetica Neue', 'Arial', 'sans-serif'],
          mono: ['"JetBrains Mono"', 'monospace'],
        },
        colors: {
          ink:   '#171310',
          paper: '#FAF7F2',
          clay:  '#B5462F',
          sand:  '#D8CFC0',
        },
      },
    },
  };
</script>

<style>
  /* ============================================================
     §00 — DESIGN TOKENS
     Palet "Studio Cetak" — kertas hangat, tinta hampir-hitam,
     dan satu aksen terracotta gelap (bukan oranye generik AI)
     yang direferensikan dari sampul buku desain klasik era 60-an.
  ============================================================ */
  :root{
    --ink:      #171310;
    --ink-soft: #4A4038;
    --paper:    #FAF7F2;
    --paper-2:  #F1EBE1;
    --clay:     #B5462F;
    --clay-dim: #8C3823;
    --sand:     #D8CFC0;
    --line:     #E4DCCC;
    --line-2:   #29241E;
  }

  *,*::before,*::after{ box-sizing:border-box; margin:0; padding:0; }

  html{ -webkit-text-size-adjust:100%; }

  body{
    font-family:'Inter',Helvetica,Arial,sans-serif;
    font-size:9.5pt;
    line-height:1.65;
    color:var(--ink);
    background:#DCD5C8;
  }

  /* ============================================================
     §01 — SCREEN CHROME (mobile-first control bar + book shadow)
     Di layar HP: sembunyikan hint cetak, tampilkan CTA unduh besar.
     Di layar desktop: tampilkan buku dengan bayangan halaman nyata.
  ============================================================ */
  .toolbar{
    position:sticky; top:0; z-index:50;
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    padding:10px 14px;
    background:rgba(23,19,16,0.92);
    backdrop-filter:blur(10px);
    color:var(--paper);
  }
  .toolbar-brand{
    display:flex; align-items:center; gap:8px;
    font-family:'JetBrains Mono',monospace;
    font-size:10px; letter-spacing:.08em; text-transform:uppercase;
    color:rgba(250,247,242,0.5);
  }
  .toolbar-actions{ display:flex; gap:8px; align-items:center; }
  .btn{
    display:inline-flex; align-items:center; gap:6px;
    font-family:'Inter',sans-serif; font-size:12px; font-weight:600;
    padding:8px 14px; border-radius:7px; border:none; cursor:pointer;
    text-decoration:none; white-space:nowrap;
  }
  .btn-ghost{ background:rgba(250,247,242,0.08); color:var(--paper); }
  .btn-clay{ background:var(--clay); color:#fff; box-shadow:0 6px 16px rgba(181,70,47,0.35); }

  .mobile-banner{ display:none; }

  @media (max-width: 640px){
    .toolbar-brand span:last-child{ display:none; }
    .btn-ghost{ display:none; }
    .mobile-banner{
      display:flex; flex-direction:column; gap:10px;
      margin:14px; padding:16px;
      background:var(--ink); color:var(--paper);
      border-radius:14px;
    }
    .mobile-banner .btn-clay{ justify-content:center; width:100%; padding:12px; font-size:14px; }
  }

  .stage{ padding:28px 0 60px; }
  @media (max-width:640px){ .stage{ padding:0 0 40px; } }

  /* ============================================================
     §02 — PAGE / SHEET SYSTEM
  ============================================================ */
  .sheet{
    width:210mm;
    height:297mm;
    margin:0 auto 10mm;
    background:var(--paper);
    position:relative;
    overflow:hidden;
  }
  @media screen{
    .sheet{
      box-shadow:
        0 1px 1px rgba(23,19,16,0.06),
        0 8px 24px rgba(23,19,16,0.10),
        0 30px 70px rgba(23,19,16,0.14);
    }
  }
  @media screen and (max-width:900px){
    .sheet{ width:100%; height:auto; min-height:0; margin-bottom:14px; border-radius:10px; }
  }
  @media print{
    .sheet{ width:100%; height:297mm; margin:0; box-shadow:none; border-radius:0; }
  }

  .sheet-pad{ padding:18mm 16mm; }
  @media screen and (max-width:900px){ .sheet-pad{ padding:8mm 7mm 10mm; } }

  /* Watermark — halus, terpasang di setiap sheet */
  .wm{
    position:absolute; inset:0;
    display:flex; align-items:center; justify-content:center;
    pointer-events:none; user-select:none; z-index:0;
    overflow:hidden;
  }
  .wm span{
    font-family:'Fraunces',serif; font-weight:600; font-style:italic;
    font-size:64pt; letter-spacing:.02em;
    color:var(--ink); opacity:0.025;
    transform:rotate(-6deg);
    white-space:nowrap;
  }
  @media screen and (max-width:900px){ .wm span{ font-size:34pt; } }

  .z1{ position:relative; z-index:1; }

  /* ============================================================
     §03 — TYPE SCALE
  ============================================================ */
  .idx{
    font-family:'JetBrains Mono',monospace;
    font-size:8.5px; font-weight:500; letter-spacing:.14em; text-transform:uppercase;
    color:var(--ink-soft);
  }
  .eyebrow{
    display:flex; align-items:center; gap:8px;
    font-family:'JetBrains Mono',monospace;
    font-size:8.5px; font-weight:500; letter-spacing:.16em; text-transform:uppercase;
    color:var(--clay);
    margin-bottom:9mm;
  }
  .eyebrow::before{ content:''; width:20px; height:1px; background:var(--clay); flex-shrink:0; }

  .h-display{
    font-family:'Fraunces',serif; font-weight:600; font-style:normal;
    letter-spacing:-.01em; line-height:0.96; color:var(--ink);
  }
  .h-cover{ font-size:58pt; }
  @media screen and (max-width:900px){ .h-cover{ font-size:15vw; } }
  .h-section{ font-size:27pt; }
  @media screen and (max-width:900px){ .h-section{ font-size:26px; } }
  .h-work{ font-size:19pt; letter-spacing:-.008em; }
  @media screen and (max-width:900px){ .h-work{ font-size:19px; } }

  .body-copy{ font-size:9pt; line-height:1.85; color:var(--ink-soft); }
  @media screen and (max-width:900px){ .body-copy{ font-size:13px; } }

  .cap{
    font-family:'JetBrains Mono',monospace;
    font-size:7.5px; font-weight:500; letter-spacing:.1em; text-transform:uppercase;
    color:#8B8175;
  }

  /* ============================================================
     §04 — RULES & GRID DEVICES
  ============================================================ */
  .rule{ width:100%; height:1px; background:var(--line); }
  .rule-strong{ width:100%; height:1px; background:var(--line-2); }
  .rule-clay{ width:26px; height:2px; background:var(--clay); }

  .kv{ display:flex; align-items:baseline; justify-content:space-between; gap:8px; padding:6px 0; border-bottom:1px solid var(--line); }
  .kv .k{ font-family:'JetBrains Mono',monospace; font-size:7.5px; letter-spacing:.1em; text-transform:uppercase; color:#8B8175; }
  .kv .v{ font-size:9pt; font-weight:600; color:var(--ink); text-align:right; }

  /* ============================================================
     §05 — TABLE OF CONTENTS (dot leaders, real print technique)
     Menggunakan flex + overflow text daripada border-dotted agar
     titik-titik tetap presisi baik di layar maupun saat dicetak.
  ============================================================ */
  .toc-row{ display:flex; align-items:baseline; gap:6px; padding:7px 0; }
  .toc-no{ font-family:'JetBrains Mono',monospace; font-size:8.5px; color:var(--clay); font-weight:600; flex-shrink:0; width:20px; }
  .toc-title{ font-size:9.5pt; font-weight:600; color:var(--ink); white-space:nowrap; flex-shrink:0; }
  .toc-leader{ flex:1; min-width:8px; border-bottom:1px dotted #C7BCA8; transform:translateY(-3px); }
  .toc-cat{ font-family:'JetBrains Mono',monospace; font-size:7.5px; letter-spacing:.06em; color:#8B8175; flex-shrink:0; text-align:right; }

  /* ============================================================
     §06 — IMAGE FRAME (object-fit: contain — karya tidak terpotong)
     Dipakai di halaman biodata / elemen lain yang butuh bingkai bebas tinggi.
  ============================================================ */
  .frame{
    width:100%; background:#F1EDE4; border:1px solid var(--line);
    display:flex; align-items:center; justify-content:center;
    overflow:hidden; min-height:70mm; max-height:148mm;
  }
  @media screen and (max-width:900px){ .frame{ max-height:none; min-height:0; } }
  .frame img{ display:block; width:100%; max-height:148mm; object-fit:contain; }
  @media screen and (max-width:900px){ .frame img{ max-height:none; } }

  /* ============================================================
     §06b — GRID KARYA (4 per halaman)
     Bingkai FIXED aspect-ratio 4:3 — seragam untuk semua karya,
     apapun orientasi aslinya, sehingga grid selalu rapi dan rata.
     object-fit:contain tetap dipakai di dalamnya agar tidak crop.
  ============================================================ */
  .work-card{
    border:1px solid var(--line);
    padding:5mm;
    background:#fff;
  }

  .frame-fixed{
    position:relative;
    width:100%;
    aspect-ratio:4/3;
    background:#F1EDE4;
    border:1px solid var(--line);
    overflow:hidden;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
  }
  .frame-fixed img{
    width:100%; height:100%;
    object-fit:contain;
    display:block;
  }
  .frame-fallback{
    display:none;
    position:absolute; inset:0;
    align-items:center; justify-content:center;
    flex-direction:column; gap:5px;
    color:#C7BCA8;
    background:#F1EDE4;
  }
  .frame-tag{
    position:absolute; top:4px; left:4px; z-index:2;
  }

  .card-desc{
    font-size:7.8pt;
    line-height:1.55;
    color:var(--ink-soft);
    text-align:left;
  }
  @media screen and (max-width:900px){ .card-desc{ font-size:12.5px; } }

  .page-qr-footer{
    display:flex; align-items:center; gap:6mm;
    margin-top:7mm; padding-top:6mm;
    border-top:1px solid var(--line);
  }
  .page-qr-footer img{ width:56px; height:56px; flex-shrink:0; border:1px solid var(--line); background:#fff; }
  .qr-link-text{
    font-family:'JetBrains Mono',monospace; font-size:7px; color:var(--clay);
    text-decoration:none; word-break:break-all; display:block;
  }
  @media screen and (max-width:900px){
    .work-card{ break-inside:avoid; }
  }

  /* ============================================================
     §07 — QR MODULES
     QR per-karya: kecil, sudut kanan-bawah sidebar, dof link jelas.
     Master QR: besar, panel dedicated di halaman biodata.
  ============================================================ */
  .qr-chip{
    display:flex; align-items:center; gap:8px;
    border:1px solid var(--line); background:#fff; padding:6px;
  }
  .qr-chip img{ width:44px; height:44px; display:block; flex-shrink:0; }
  .qr-chip .qr-text{ min-width:0; }
  .qr-chip .qr-text .cap{ margin-bottom:2px; }
  .qr-chip .qr-link{
    font-family:'JetBrains Mono',monospace; font-size:6.8px; color:var(--clay);
    word-break:break-all; text-decoration:none; line-height:1.4; display:block;
  }

  .qr-master{
    display:flex; gap:9mm; align-items:center;
    border:1px solid var(--line); border-top:2px solid var(--ink); padding:8mm;
    background:var(--paper-2);
  }
  @media screen and (max-width:900px){ .qr-master{ flex-direction:column; text-align:center; } }
  .qr-master img{ width:96px; height:96px; display:block; border:1px solid var(--line); background:#fff; padding:6px; flex-shrink:0; }

  /* ============================================================
     §08 — BADGES
  ============================================================ */
  .tag{
    display:inline-flex; align-items:center; gap:5px;
    font-family:'JetBrains Mono',monospace; font-size:7px; font-weight:600;
    letter-spacing:.1em; text-transform:uppercase;
    padding:4px 9px; border:1px solid var(--line-2); color:var(--ink);
  }
  .tag-fill{ background:var(--clay); border-color:var(--clay); color:#fff; }

  /* ============================================================
     §09 — DROP CAP (karya pertama — teknik editorial klasik)
  ============================================================ */
  .drop::first-letter{
    float:left; font-family:'Fraunces',serif; font-weight:600;
    font-size:4em; line-height:.72; padding-right:6px; color:var(--clay);
  }

  /* ============================================================
     §10 — BACKGROUND INDEX NUMBER
  ============================================================ */
  .bignum{
    position:absolute; top:14mm; right:16mm;
    font-family:'Fraunces',serif; font-style:italic; font-weight:500;
    font-size:70pt; color:rgba(23,19,16,0.05); line-height:1; z-index:0;
  }
  @media screen and (max-width:900px){ .bignum{ display:none; } }

  /* ============================================================
     §11 — DARK BOOKENDS (cover + closing — simetri editorial)
  ============================================================ */
  .dark{ background:var(--ink); color:var(--paper); }
  .dark .idx, .dark .cap{ color:rgba(250,247,242,0.35); }

  /* ============================================================
     §12 — PRINT PRECISION LAYER
  ============================================================ */
  .brk{ break-after:page; }
  .avoid{ break-inside:avoid; }

  @media print{
    @page{ size:A4 portrait; margin:14mm 13mm; }
    html,body{ background:#fff !important; }
    *{ -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important; color-adjust:exact !important; }
    .no-print{ display:none !important; }
    .stage{ padding:0 !important; }
    .toolbar{ display:none !important; }
    a{ color:inherit; text-decoration:none; }
    .toc-leader{ transform:none; position:relative; top:-3px; }
  }

  /* Reduced motion respected — no ambient animation used anywhere
     in this document by design; body of the piece is print-first. */
</style>
</head>
<body>

@php
  /**
   * qrImg — generator URL QR code (service eksternal, tanpa dependency baru).
   * fg/bg diberikan tanpa '#' sesuai format qrserver.
   */
  $qrImg = function (string $data, int $size = 200, string $fg = '171310', string $bg = 'FAF7F2') {
      return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size
          . '&color=' . $fg . '&bgcolor=' . $bg
          . '&qzone=1&data=' . urlencode($data);
  };

  /**
   * qrTargetFor — fallback aman: pakai slug bila tersedia,
   * jika tidak, gunakan ID agar link tetap valid (tidak pernah 404 karena null).
   */
  $qrTargetFor = function ($portfolio) {
      $slug = $portfolio->slug ?? $portfolio->id;
      return route('portfolio.public', $slug);
  };

  $totalKarya = $portfolios->count();
  $byKat      = $portfolios->groupBy(fn ($p) => $p->category?->name ?? 'Umum');
  $totalKat   = $byKat->count();
  $totalPdf   = $portfolios->whereNotNull('file_pdf_path')->count();

  $galleryUrl = \Illuminate\Support\Facades\Route::has('portfolio.gallery')
      ? route('portfolio.gallery', $user->username ?? $user->id)
      : route('siswa.dashboard');
@endphp

{{-- ================================================================
     TOOLBAR — screen only, mobile-first
================================================================ --}}
<div class="toolbar no-print">
  <div class="toolbar-brand">
    <span>DKV/SMEKDA</span>
    <span>— Portfolio Print View</span>
  </div>
  <div class="toolbar-actions">
    <a href="{{ route('siswa.dashboard') }}" class="btn btn-ghost">← Kembali</a>
    <button onclick="window.print()" class="btn btn-clay">Cetak / Simpan PDF</button>
  </div>
</div>

{{-- ================================================================
     MOBILE BANNER — tampil hanya di layar kecil (no-print)
     Alasan: di HP, dialog print browser kurang nyaman untuk buku
     multi-halaman. Arahkan pengguna ke unduhan langsung.
================================================================ --}}
<div class="mobile-banner no-print">
  <div class="cap" style="color:rgba(250,247,242,.5);">Tampilan Layar Kecil Terdeteksi</div>
  <div style="font-family:'Fraunces',serif; font-weight:600; font-size:17px; line-height:1.3;">
    Untuk hasil terbaik, unduh sebagai PDF
  </div>
  <div style="font-size:12.5px; line-height:1.6; color:rgba(250,247,242,.7);">
    Buku portofolio ini dirancang untuk kertas A4. Layar HP tetap bisa menampilkannya,
    tapi tombol di bawah akan membuka dialog simpan/cetak agar kamu mendapat file PDF utuh.
  </div>
  <button onclick="window.print()" class="btn btn-clay">Unduh PDF Sekarang</button>
</div>

<div class="stage">

{{-- ============================================================
     01 — COVER
     Konsep: sampul buku cetak fisik, bukan slide. Tipografi serif
     display raksasa dengan italic aksen mengambil bahasa visual
     katalog pameran seni (Behance Book / Issuu editorial cover).
============================================================ --}}
<div class="sheet brk dark">
  <div class="wm" aria-hidden="true"><span>SMKN 2 PADANG PANJANG</span></div>
  <div class="sheet-pad z1" style="height:100%; display:flex; flex-direction:column; justify-content:space-between;">

    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
      <div class="idx">SMK Negeri 2 Padang Panjang<br><span style="opacity:.5;">Kompetensi Keahlian DKV — {{ now()->format('Y') }}</span></div>
      <div class="rule-clay" style="width:20px;"></div>
    </div>

    <div>
      <div class="eyebrow" style="color:var(--clay);">Portfolio Design Book</div>
      <div class="h-display h-cover">Creative</div>
      <div class="h-display h-cover" style="font-style:italic; color:var(--clay);">Portfolio.</div>

      <div style="margin-top:14mm; display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:8mm;">
        <div>
          <div class="idx" style="margin-bottom:3mm;">Dipresentasikan oleh</div>
          <div style="font-family:'Fraunces',serif; font-weight:500; font-size:19pt; color:var(--paper);">{{ $user->name }}</div>
          <div class="idx" style="margin-top:2mm;">NIS/NIP {{ $user->nis_nip ?? '—' }} &nbsp;·&nbsp; Desain Komunikasi Visual</div>
        </div>
        <div style="display:flex; gap:8mm;">
          <div>
            <div style="font-family:'Fraunces',serif; font-size:26pt; font-weight:600; color:var(--paper);">{{ $totalKarya }}</div>
            <div class="idx">Karya</div>
          </div>
          <div>
            <div style="font-family:'Fraunces',serif; font-size:26pt; font-weight:600; color:var(--clay);">{{ $totalKat }}</div>
            <div class="idx">Kategori</div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- ============================================================
     02 — PROFIL DESAINER
============================================================ --}}
<div class="sheet brk">
  <div class="wm" aria-hidden="true"><span>SMKN 2 PADANG PANJANG</span></div>
  <div class="bignum z1">01</div>
  <div class="sheet-pad z1">

    <div class="eyebrow">01 — Profil Desainer</div>

    <div style="display:flex; gap:8mm; align-items:flex-start; margin-bottom:10mm; flex-wrap:wrap;">
      <div style="width:64px; height:64px; background:var(--ink); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <span style="font-family:'Fraunces',serif; font-size:24pt; font-weight:600; color:var(--clay);">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
      </div>
      <div style="flex:1; min-width:200px;">
        <div class="h-display h-section" style="margin-bottom:3mm;">{{ $user->name }}</div>
        <div class="idx" style="margin-bottom:5mm;">Siswa Kompetensi Keahlian Desain Komunikasi Visual</div>
        <div style="display:flex; gap:5px; flex-wrap:wrap;">
          <span class="tag tag-fill">DKV SMEKDA</span>
          <span class="tag">{{ now()->format('Y') }}</span>
          <span class="tag">{{ $totalKarya }} Karya</span>
        </div>
      </div>
    </div>

    <div class="rule" style="margin-bottom:8mm;"></div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0 10mm; margin-bottom:9mm;">
      <div class="kv"><span class="k">NIS/NIP</span><span class="v">{{ $user->nis_nip ?? '—' }}</span></div>
      <div class="kv"><span class="k">Email</span><span class="v" style="word-break:break-all;">{{ $user->email }}</span></div>
      <div class="kv"><span class="k">WhatsApp</span><span class="v">{{ $user->phone ?? '—' }}</span></div>
      <div class="kv"><span class="k">Institusi</span><span class="v">SMKN 2 Padang Panjang</span></div>
    </div>

    <div style="margin-bottom:9mm;">
      <div class="idx" style="margin-bottom:4mm;">Tentang Saya</div>
      <p class="body-copy" style="text-align:justify; max-width:135mm;">
        Saya adalah siswa Kompetensi Keahlian Desain Komunikasi Visual di SMK Negeri 2 Padang Panjang.
        Dokumen ini merupakan kompilasi karya desain yang dikerjakan sebagai bagian dari proses pembelajaran
        dan pengembangan kompetensi di bidang desain komunikasi visual. Setiap karya mencerminkan eksplorasi
        kreatif dan penerapan prinsip desain yang dipelajari selama masa studi.
      </p>
    </div>

    @if($totalKat > 0)
    <div style="margin-bottom:9mm;">
      <div class="idx" style="margin-bottom:4mm;">Bidang Karya</div>
      <div style="display:flex; flex-wrap:wrap; gap:5px;">
        @foreach($byKat as $katName => $items)
          <span class="tag">{{ $katName }} ({{ $items->count() }})</span>
        @endforeach
      </div>
    </div>
    @endif

    {{-- MASTER QR CODE — mengarah ke galeri profil online utama --}}
    <div class="rule" style="margin-bottom:8mm;"></div>
    <div class="qr-master avoid">
      <img src="{{ $qrImg($galleryUrl, 240) }}" alt="Master QR Code Galeri {{ $user->name }}"
           onerror="this.closest('.qr-master').querySelector('.qr-fallback').style.display='flex'; this.remove();">
      <div class="qr-fallback" style="display:none; width:96px; height:96px; border:2px solid var(--ink); align-items:center; justify-content:center; flex-direction:column; gap:3px; flex-shrink:0;">
        <div class="cap" style="color:var(--ink);">QR CODE</div>
        <div class="cap">Scan online</div>
      </div>
      <div style="flex:1; min-width:180px;">
        <div class="idx" style="margin-bottom:3mm; color:var(--clay);">Master Access — Galeri Profil</div>
        <div style="font-family:'Fraunces',serif; font-weight:600; font-size:13pt; margin-bottom:3mm;">Kunjungi Galeri Digital Lengkap</div>
        <p class="body-copy" style="margin-bottom:4mm;">
          Pindai kode ini untuk membuka seluruh koleksi karya {{ $user->name }} secara online,
          lengkap dengan versi resolusi penuh dan pembaruan terbaru.
        </p>
        <a href="{{ $galleryUrl }}" style="font-family:'JetBrains Mono',monospace; font-size:7.5px; color:var(--clay); word-break:break-all; text-decoration:none;">{{ $galleryUrl }}</a>
      </div>
    </div>

  </div>
</div>

{{-- ============================================================
     03 — DAFTAR ISI (dot leaders presisi)
============================================================ --}}
<div class="sheet brk">
  <div class="wm" aria-hidden="true"><span>SMKN 2 PADANG PANJANG</span></div>
  <div class="bignum z1">02</div>
  <div class="sheet-pad z1">

    <div class="eyebrow">02 — Daftar Isi</div>
    <div class="h-display h-section" style="margin-bottom:11mm;">Katalog<br>Karya Portofolio</div>

    <div>
      @forelse($portfolios as $i => $p)
        <div class="toc-row avoid">
          <div class="toc-no">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
          <div class="toc-title">{{ $p->title }}</div>
          <div class="toc-leader"></div>
          <div class="toc-cat">{{ $p->category?->name ?? 'Umum' }}</div>
        </div>
      @empty
        <p class="body-copy" style="text-align:center; padding:12mm 0;">Belum ada karya.</p>
      @endforelse
    </div>

    @if($portfolios->count() > 0)
    <div class="avoid" style="margin-top:11mm; padding:7mm; border:1px solid var(--line); border-left:3px solid var(--clay); background:var(--paper-2);">
      <div class="idx" style="margin-bottom:5mm;">Ringkasan Koleksi</div>
      <div style="display:flex; gap:11mm; flex-wrap:wrap;">
        <div>
          <div style="font-family:'Fraunces',serif; font-size:20pt; font-weight:600;">{{ $totalKarya }}</div>
          <div class="idx">Total Karya</div>
        </div>
        @foreach($byKat as $katName => $items)
        <div>
          <div style="font-family:'Fraunces',serif; font-size:20pt; font-weight:600;">{{ $items->count() }}</div>
          <div class="idx">{{ $katName }}</div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>
</div>

{{-- ============================================================
     04+ — HALAMAN KARYA — GRID 4 KARYA PER HALAMAN
     Alasan perubahan: 1-karya-per-halaman menyebabkan konten
     terpotong dan boros halaman. Grid 2×2 dengan bingkai gambar
     ukuran seragam (aspect-ratio tetap, object-fit:contain) jauh
     lebih rapi dan standar untuk portfolio book multi-karya.
     Deskripsi dipangkas jadi ringkasan pendek per kartu — detail
     lengkap cukup lewat QR "Lihat Galeri Online" di footer halaman,
     bukan QR per-karya yang membuat tiap kartu sempit.
============================================================ --}}
@php $chunks = $portfolios->chunk(4); @endphp

@forelse($chunks as $pageIndex => $chunk)
<div class="sheet brk">
  <div class="wm" aria-hidden="true"><span>SMKN 2 PADANG PANJANG</span></div>
  <div class="bignum z1">{{ str_pad($pageIndex + 1, 2, '0', STR_PAD_LEFT) }}</div>

  <div class="sheet-pad z1" style="display:flex; flex-direction:column; height:100%;">

    <div class="eyebrow" style="margin-bottom:7mm;">Katalog Karya — Halaman {{ $pageIndex + 1 }} dari {{ $chunks->count() }}</div>

    {{-- GRID 2×2 — bingkai seragam, tidak pernah terpotong --}}
    <div style="flex:1; display:grid; grid-template-columns:1fr 1fr; grid-template-rows:1fr 1fr; gap:7mm 8mm;">
      @foreach($chunk as $portfolio)
      <div class="work-card avoid" style="display:flex; flex-direction:column;">

        <div class="frame-fixed avoid">
          <img src="{{ asset('storage/' . $portfolio->image_path) }}" alt="{{ $portfolio->title }}" loading="eager"
               onerror="this.style.display='none'; this.closest('.frame-fixed').querySelector('.frame-fallback').style.display='flex';">
          <div class="frame-fallback">
            <div style="font-size:15pt; opacity:.4;">△</div>
            <div class="cap">Gambar Tidak Tersedia</div>
          </div>
          <div class="frame-tag">
            <span class="tag tag-fill" style="font-size:6px; padding:3px 7px;">{{ $portfolio->category?->name ?? 'Umum' }}</span>
          </div>
        </div>

        <div style="padding-top:3mm; flex:1; display:flex; flex-direction:column;">
          <div style="font-family:'Fraunces',serif; font-weight:600; font-size:11pt; letter-spacing:-.005em; line-height:1.2; margin-bottom:2mm;">
            {{ $portfolio->title }}
          </div>
          <p class="card-desc">
            {{ \Illuminate\Support\Str::limit($portfolio->description, 105) }}
          </p>
          <div style="margin-top:auto; padding-top:2mm; display:flex; justify-content:space-between; align-items:baseline;">
            <span class="cap">{{ $portfolio->created_at->format('d/m/Y') }}</span>
            @if($portfolio->file_pdf_path)
              <span class="cap" style="color:var(--clay);">PDF ↗</span>
            @endif
          </div>
        </div>

      </div>
      @endforeach

      {{-- Isi slot kosong bila karya di halaman terakhir < 4, agar grid tetap simetris --}}
      @if($chunk->count() < 4)
        @for($e = 0; $e < 4 - $chunk->count(); $e++)
          <div></div>
        @endfor
      @endif
    </div>

    {{-- FOOTER HALAMAN — satu QR galeri online, bukan per-karya --}}
    <div class="page-qr-footer avoid">
      <img src="{{ $qrImg($galleryUrl, 120) }}" alt="QR Galeri Online {{ $user->name }}"
           onerror="this.style.display='none';">
      <div style="flex:1; min-width:0;">
        <div class="cap" style="color:var(--clay); margin-bottom:1.5mm;">Karya lebih lengkap? Scan untuk galeri online</div>
        <div style="font-size:8pt; font-weight:600; color:var(--ink); margin-bottom:1mm;">
          Detail resolusi penuh, proses desain, dan karya terbaru tersedia live di galeri digital.
        </div>
        <a href="{{ $galleryUrl }}" class="qr-link-text">{{ \Illuminate\Support\Str::limit($galleryUrl, 60) }}</a>
      </div>
    </div>

  </div>
</div>
@empty
<div class="sheet brk">
  <div class="sheet-pad z1" style="height:100%; display:flex; align-items:center; justify-content:center;">
    <div style="text-align:center; color:#C7BCA8;">
      <div style="font-size:24pt; opacity:.3; margin-bottom:6mm;">△</div>
      <div class="idx">Belum ada karya yang tersedia</div>
    </div>
  </div>
</div>
@endforelse

{{-- ============================================================
     05 — REKAP STATISTIK
============================================================ --}}
@if($portfolios->count() > 0)
<div class="sheet brk">
  <div class="wm" aria-hidden="true"><span>SMKN 2 PADANG PANJANG</span></div>
  <div class="sheet-pad z1">

    <div class="eyebrow">Rekap — Statistik Portofolio</div>
    <div class="h-display h-section" style="margin-bottom:12mm;">Statistik &<br>Rekap Karya</div>

    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:8mm; margin-bottom:11mm;">
      <div style="border-top:2px solid var(--ink); padding-top:5mm;">
        <div style="font-family:'Fraunces',serif; font-size:38pt; font-weight:600;">{{ $totalKarya }}</div>
        <div class="idx" style="margin-top:2mm;">Total Karya</div>
      </div>
      <div style="border-top:2px solid var(--clay); padding-top:5mm;">
        <div style="font-family:'Fraunces',serif; font-size:38pt; font-weight:600; color:var(--clay);">{{ $totalKat }}</div>
        <div class="idx" style="margin-top:2mm;">Kategori</div>
      </div>
      <div style="border-top:2px solid var(--line); padding-top:5mm;">
        <div style="font-family:'Fraunces',serif; font-size:38pt; font-weight:600; color:#8B8175;">{{ $totalPdf }}</div>
        <div class="idx" style="margin-top:2mm;">Dokumen PDF</div>
      </div>
    </div>

    <div class="rule" style="margin-bottom:8mm;"></div>

    <div style="margin-bottom:9mm;">
      <div class="idx" style="margin-bottom:6mm;">Distribusi per Kategori</div>
      @foreach($byKat as $katName => $items)
        @php $pct = $totalKarya > 0 ? round(($items->count() / $totalKarya) * 100) : 0; @endphp
        <div class="avoid" style="margin-bottom:5mm;">
          <div style="display:flex; justify-content:space-between; margin-bottom:2mm;">
            <span style="font-size:9pt; font-weight:700;">{{ $katName }}</span>
            <span class="cap">{{ $items->count() }} karya · {{ $pct }}%</span>
          </div>
          <div style="width:100%; height:3px; background:var(--paper-2);">
            <div style="height:100%; width:{{ $pct }}%; background:var(--ink);"></div>
          </div>
        </div>
      @endforeach
    </div>

  </div>
</div>
@endif

{{-- ============================================================
     06 — PENUTUP (bookend gelap simetris dengan cover)
============================================================ --}}
<div class="sheet dark" style="border-top:3px solid var(--clay);">
  <div class="sheet-pad z1" style="height:100%; display:flex; flex-direction:column; justify-content:space-between;">

    <div>
      <div class="eyebrow" style="color:rgba(250,247,242,.4);">Penutup</div>
      <div class="h-display h-cover" style="font-size:44pt;">Terima</div>
      <div class="h-display h-cover" style="font-size:44pt; font-style:italic; color:var(--clay);">Kasih.</div>
      <div class="rule-clay" style="margin:7mm 0 8mm;"></div>
      <p class="body-copy" style="color:rgba(250,247,242,.55); max-width:120mm;">
        Dokumen portofolio ini merupakan representasi karya dan kompetensi yang telah dikembangkan
        selama masa pembelajaran di Kompetensi Keahlian Desain Komunikasi Visual, SMK Negeri 2
        Padang Panjang. Setiap karya adalah hasil eksplorasi dan proses kreatif berkelanjutan.
      </p>
    </div>

    <div style="border-top:1px solid rgba(250,247,242,.08); border-bottom:1px solid rgba(250,247,242,.08); padding:8mm 0; display:grid; grid-template-columns:repeat(3,1fr); gap:6mm;">
      <div><div style="font-family:'Fraunces',serif; font-size:22pt; font-weight:600;">{{ $totalKarya }}</div><div class="idx">Karya Terdokumentasi</div></div>
      <div><div style="font-family:'Fraunces',serif; font-size:22pt; font-weight:600; color:var(--clay);">{{ $totalKat }}</div><div class="idx">Bidang Kategori</div></div>
      <div><div style="font-family:'Fraunces',serif; font-size:22pt; font-weight:600; opacity:.5;">{{ now()->format('Y') }}</div><div class="idx">Tahun Akademik</div></div>
    </div>

    <div>
      <div style="font-family:'Fraunces',serif; font-weight:600; font-size:13pt; margin-bottom:2mm;">{{ $user->name }}</div>
      <div class="idx" style="margin-bottom:6mm;">{{ $user->nis_nip ?? '—' }} · DKV · SMKN 2 Padang Panjang · {{ now()->format('Y') }}</div>
      <div class="idx" style="opacity:.5;">SISTEM MANAJEMEN PORTFOLIO DIGITAL — DKV SMEKDA</div>
      <div class="idx" style="opacity:.3;">Developed by Rafli Ahmad Zulkarnain — {{ now()->format('Y') }}</div>
    </div>

  </div>
</div>

</div>{{-- /.stage --}}

{{-- ================================================================
     AUTO-PRINT — menunggu gambar & font selesai dimuat
================================================================ --}}
<script>
(function () {
  var fired = false;
  function go() {
    if (fired) return;
    fired = true;
    setTimeout(function () { window.print(); }, 350);
  }

  var imgs = Array.prototype.slice.call(document.querySelectorAll('img'));
  var done = 0;

  function tick() { done++; if (done >= imgs.length) go(); }

  if (imgs.length === 0) { setTimeout(go, 900); return; }

  imgs.forEach(function (img) {
    if (img.complete) tick();
    else {
      img.addEventListener('load', tick);
      img.addEventListener('error', tick);
    }
  });

  // Pastikan font kustom (Fraunces/Inter/Mono) sudah ter-render
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(function () { /* no-op: gate tetap di gambar */ });
  }

  setTimeout(go, 4500); // fallback keras jika ada aset gagal
})();
</script>

</body>
</html>