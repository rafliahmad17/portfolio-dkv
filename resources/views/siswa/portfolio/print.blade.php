{{--
============================================================
📄 resources/views/siswa/portfolio/print.blade.php
============================================================
PORTFOLIO DIGITAL DKV — Premium Editorial Print View
Versi   : 3.0 Final Professional Edition
Target  : Browser Print → Chrome/Edge → A4 PDF
Standar : Portfolio Book Profesional / Creative Agency

ROUTE yang dibutuhkan (tambahkan di routes/web.php):
────────────────────────────────────────────────────
Route::get('/portfolio/print', [\App\Http\Controllers\PortfolioController::class, 'printView'])->name('portfolio.print');

CONTROLLER METHOD yang dibutuhkan (di PortfolioController.php):
────────────────────────────────────────────────────────────────
public function printView(): \Illuminate\View\View {
    $user = Auth::user();
    $portfolios = Portfolio::with('category')
        ->where('user_id', $user->id)
        ->latest()->get();
    return view('siswa.portfolio.print', compact('portfolios', 'user'));
}
============================================================
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio — {{ $user->name }}</title>

    {{-- [CDN] Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- [FONT] Inter — Professional sans-serif, optimal untuk cetak --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;0,14..32,900;1,14..32,400&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'Helvetica Neue', 'Arial', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
      
        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm 14mm 14mm 14mm;
            }


            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            body {
                background: #ffffff !important;
                counter-reset: portfolio-page;
            }

            /* Elemen layar tersembunyi saat cetak */
            .screen-only { display: none !important; }

            /* Counter halaman untuk elemen .page-num */
            .page-num::before {
                counter-increment: portfolio-page;
                content: counter(portfolio-page);
            }
        }

        /* ============================================================
           §02 — GLOBAL BASE STYLES
           Alasan: Reset bersih + font system konsisten di seluruh dokumen
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #0a0a0a;
            background: #e8e8e8;
        }

        @media print { body { background: white; } }

       
        .wm {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 52pt;
            font-weight: 900;
            letter-spacing: 0.18em;
            color: #000000;
            opacity: 0.022;
            pointer-events: none;
            z-index: 9999;
            white-space: nowrap;
            user-select: none;
        }

        
        .a4 {
            width: 210mm;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
        }

        @media screen {
            .a4 {
                box-shadow:
                    0 0 0 0.5px rgba(0,0,0,0.05),
                    0 4px 8px rgba(0,0,0,0.08),
                    0 20px 60px rgba(0,0,0,0.12);
                margin: 24px auto;
                margin-bottom: 0;
            }
        }

        @media print {
            .a4 {
                width: 100%;
                margin: 0;
                box-shadow: none;
            }
        }

       
        .pb-before { break-before: page; }
        .pb-avoid  { break-inside: avoid; }

    
        .cover {
            background-color: #0a0a0a;
            min-height: 297mm;
            position: relative;
            overflow: hidden;
        }

       
        .cover-panel {
            position: absolute;
            top: 0; right: 0; bottom: 0;
            width: 38%;
            background-color: #111111;
        }

   
        .cover-diagonal {
            position: absolute;
            top: 0; bottom: 0;
            left: 58%;
            width: 4mm;
            background-color: #dc2626;
            transform: skewX(-1.5deg);
        }

      
        .cover-foot-red {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 5mm;
            background-color: #dc2626;
        }

        
        .t-cover {
            font-size: 64pt;
            font-weight: 900;
            letter-spacing: -4px;
            line-height: 0.88;
            color: #ffffff;
        }


        .t-section {
            font-size: 28pt;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.1;
            color: #0a0a0a;
        }

       
        .t-portfolio {
            font-size: 20pt;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.15;
            color: #0a0a0a;
        }

      
        .t-body {
            font-size: 8.5pt;
            font-weight: 400;
            line-height: 1.8;
            color: #374151;
        }

      
        .t-label {
            font-size: 6pt;
            font-weight: 700;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: #9ca3af;
        }

       
        .t-micro {
            font-size: 6.5pt;
            font-weight: 500;
            color: #9ca3af;
        }

        
        .section-marker {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12mm;
        }

        .section-marker::before {
            content: '';
            display: block;
            width: 18px;
            height: 2px;
            background-color: #dc2626;
            flex-shrink: 0;
        }

        /* ============================================================
           §09 — BADGES & PILLS
        ============================================================ */
        .badge-red {
            display: inline-block;
            background-color: #dc2626;
            color: #ffffff;
            font-size: 6pt;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            padding: 3px 10px;
        }

        .badge-outline {
            display: inline-block;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 6pt;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            padding: 3px 10px;
        }

        /* ============================================================
           §10 — RULES & DIVIDERS
           Alasan: Garis tipis elegan memisahkan konten tanpa dominan
        ============================================================ */
        .rule      { width: 100%; height: 0.5px; background: #e5e7eb; margin: 6mm 0; }
        .rule-bold { width: 100%; height: 2px;   background: #0a0a0a; margin: 0 0 6mm; }
        .rule-red  { width: 28px; height: 2px;   background: #dc2626; margin: 4mm 0; }

        /* ============================================================
           §11 — PORTFOLIO IMAGE CONTAINER
           Alasan: object-contain memastikan SELURUH karya tampil
           tanpa terpotong. Background gray netral untuk konteks.
           max-height terkontrol agar tidak overflow ke halaman berikut.
        ============================================================ */
        .img-frame {
            width: 100%;
            background-color: #f8f8f8;
            border: 0.5px solid #e5e7eb;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 150mm;
            min-height: 80mm;
        }

        .img-frame img {
            display: block;
            max-width: 100%;
            max-height: 150mm;
            object-fit: contain;
            width: 100%;
        }

        /* ============================================================
           §12 — PROFILE AVATAR
        ============================================================ */
        .avatar {
            width: 70px;
            height: 70px;
            background-color: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ============================================================
           §13 — TOC (TABLE OF CONTENTS)
           Alasan: Dot leader dari CSS menciptakan tampilan daftar isi
           buku profesional yang rapi dan konsisten
        ============================================================ */
        .toc-item {
            display: flex;
            align-items: baseline;
            padding: 5px 0;
            border-bottom: 0.5px solid #f5f5f5;
        }

        .toc-num {
            font-size: 6.5pt;
            font-weight: 700;
            color: #dc2626;
            min-width: 22px;
            flex-shrink: 0;
        }

        .toc-name {
            flex: 1;
            font-size: 8.5pt;
            font-weight: 600;
            color: #0a0a0a;
            padding-right: 6mm;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .toc-cat {
            font-size: 6.5pt;
            font-weight: 700;
            color: #9ca3af;
            text-align: right;
            flex-shrink: 0;
            padding-left: 4mm;
        }

        /* ============================================================
           §14 — STATISTICS BAR
           Alasan: Progress bar sebagai infografis sederhana dan efektif
        ============================================================ */
        .stat-bar-bg {
            width: 100%;
            height: 3px;
            background-color: #f3f4f6;
        }

        .stat-bar-fill {
            height: 100%;
            background-color: #0a0a0a;
        }

        /* ============================================================
           §15 — DROP CAP (untuk karya pertama)
           Alasan: Drop cap adalah teknik tipografi editorial klasik
           yang membedakan portfolio book dari laporan biasa
        ============================================================ */
        .drop-cap::first-letter {
            float: left;
            font-size: 4.2em;
            font-weight: 900;
            line-height: 0.7;
            padding-right: 5px;
            padding-top: 3px;
            color: #dc2626;
        }

        /* ============================================================
           §16 — DECORATIVE BIG NUMBER (Background)
           Alasan: Angka besar transparan sebagai elemen desain
           yang memberikan kedalaman visual tanpa mengganggu konten
        ============================================================ */
        .bg-number {
            position: absolute;
            top: 12mm;
            right: 14mm;
            font-size: 80pt;
            font-weight: 900;
            color: rgba(0, 0, 0, 0.04);
            letter-spacing: -5px;
            line-height: 1;
            pointer-events: none;
            user-select: none;
        }
    </style>
</head>
<body>

{{-- ================================================================
     WATERMARK — Sangat halus, tercetak di semua halaman
================================================================ --}}
<div class="wm" aria-hidden="true">SMKN 2 PADANG PANJANG</div>

{{-- ================================================================
     SCREEN-ONLY NAVIGATION
     Alasan: Tombol navigasi hanya di layar, tidak ikut tercetak
================================================================ --}}
<div class="screen-only" style="position: fixed; top: 16px; right: 16px; z-index: 9999; display: flex; gap: 8px; align-items: center;">
    <a href="{{ route('siswa.dashboard') }}"
       style="display: inline-flex; align-items: center; gap: 7px; background: white; border: 1px solid #d1d5db; color: #374151; font-size: 11px; font-weight: 700; padding: 9px 16px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-decoration: none; font-family: Inter, sans-serif;">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <button onclick="window.print()"
            style="display: inline-flex; align-items: center; gap: 7px; background: #dc2626; color: white; font-size: 11px; font-weight: 700; padding: 9px 18px; border-radius: 8px; box-shadow: 0 4px 14px rgba(220,38,38,0.4); border: none; cursor: pointer; font-family: Inter, sans-serif;">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Cetak / Simpan PDF
    </button>
</div>

{{-- ================================================================
     A4 PAPER CONTAINER
================================================================ --}}
<div class="a4">

{{-- ============================================================
     HALAMAN 1 — COVER
     Alasan desain: Cover hitam total dengan tipografi putih raksasa
     adalah standar visual portfolio agensi desain internasional.
     Asimetri geometris merah memberi energi tanpa kehilangan
     keeleganan. White text on black = high impact, zero noise.
============================================================ --}}
<div class="cover pb-avoid" style="break-after: page; position: relative; overflow: hidden; background-color: #0a0a0a; min-height: 297mm;">

    {{-- Panel geometris kanan --}}
    <div class="cover-panel" style="position: absolute; top: 0; right: 0; bottom: 0; width: 38%; background-color: #111111;"></div>

    {{-- Garis diagonal merah --}}
    <div class="cover-diagonal" style="position: absolute; top: 0; bottom: 0; left: 58.5%; width: 3mm; background-color: #dc2626; transform: skewX(-1deg);"></div>

    {{-- Strip merah bawah --}}
    <div class="cover-foot-red" style="position: absolute; bottom: 0; left: 0; right: 0; height: 5mm; background-color: #dc2626;"></div>

    {{-- Content layer --}}
    <div style="position: relative; z-index: 10; padding: 16mm 14mm 20mm;">

        {{-- Top bar --}}
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.07); padding-bottom: 6mm; margin-bottom: 18mm;">
            <div>
                <div class="t-label" style="color: rgba(255,255,255,0.4); margin-bottom: 2mm;">SMK Negeri 2 Padang Panjang</div>
                <div class="t-label" style="color: rgba(255,255,255,0.18);">Desain Komunikasi Visual &bull; {{ now()->format('Y') }}</div>
            </div>
            <div style="display: flex; gap: 3px;">
                <div style="width: 7mm; height: 7mm; background-color: #dc2626;"></div>
                <div style="width: 7mm; height: 7mm; background-color: rgba(255,255,255,0.06);"></div>
            </div>
        </div>

        {{-- Eyebrow --}}
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6mm;">
            <div style="width: 16px; height: 1.5px; background-color: #dc2626;"></div>
            <div class="t-label" style="color: rgba(255,255,255,0.35);">Portfolio Design Book</div>
        </div>

        {{-- Giant Title --}}
        <div class="t-cover" style="margin-bottom: 4mm;">CREATIVE</div>
        <div class="t-cover" style="color: #dc2626; margin-bottom: 8mm;">PORTFOLIO</div>

        {{-- Bold rule --}}
        <div style="width: 100%; height: 1px; background: rgba(255,255,255,0.06); margin-bottom: 10mm;"></div>

        {{-- Student Name --}}
        <div class="t-label" style="color: rgba(255,255,255,0.3); margin-bottom: 4mm;">Dipresentasikan oleh</div>
        <div style="font-size: 24pt; font-weight: 800; color: #ffffff; letter-spacing: -0.8px; line-height: 1.15; margin-bottom: 3mm;">
            {{ $user->name }}
        </div>
        <div class="t-label" style="color: rgba(255,255,255,0.3);">
            NIS / NIP: {{ $user->nis_nip ?? '—' }}
            &nbsp;&bull;&nbsp;
            Kompetensi Keahlian Desain Komunikasi Visual
        </div>

        {{-- Stats --}}
        @php
            $totalKarya = $portfolios->count();
            $byKat      = $portfolios->groupBy(fn($p) => $p->category?->name ?? 'Umum');
            $totalKat   = $byKat->count();
            $totalPdf   = $portfolios->whereNotNull('file_pdf_path')->count();
        @endphp

        <div style="margin-top: 16mm; border-top: 1px solid rgba(255,255,255,0.07); padding-top: 8mm; display: flex; gap: 0;">
            <div style="flex: 1; padding-right: 8mm; border-right: 1px solid rgba(255,255,255,0.06);">
                <div style="font-size: 32pt; font-weight: 900; color: #ffffff; letter-spacing: -2px; line-height: 1;">{{ $totalKarya }}</div>
                <div class="t-label" style="color: rgba(255,255,255,0.28); margin-top: 2mm;">Karya</div>
            </div>
            <div style="flex: 1; padding: 0 8mm; border-right: 1px solid rgba(255,255,255,0.06);">
                <div style="font-size: 32pt; font-weight: 900; color: #dc2626; letter-spacing: -2px; line-height: 1;">{{ $totalKat }}</div>
                <div class="t-label" style="color: rgba(255,255,255,0.28); margin-top: 2mm;">Kategori</div>
            </div>
            <div style="flex: 1; padding-left: 8mm;">
                <div style="font-size: 32pt; font-weight: 900; color: rgba(255,255,255,0.5); letter-spacing: -2px; line-height: 1;">{{ $totalPdf }}</div>
                <div class="t-label" style="color: rgba(255,255,255,0.28); margin-top: 2mm;">Dokumen PDF</div>
            </div>
        </div>

    </div>
</div>

{{-- ============================================================
     HALAMAN 2 — PROFIL DESAINER
     Alasan: Memperkenalkan persona desainer sebelum karya tampil.
     Grid 2-kolom: avatar besar kiri, info kanan — standar CV kreatif.
============================================================ --}}
<div class="pb-avoid" style="break-after: page; padding: 16mm 14mm; min-height: 267mm; position: relative;">

    <div class="bg-number" aria-hidden="true">01</div>

    <div class="section-marker">
        <span class="t-label" style="color: #dc2626;">01 — Profil Desainer</span>
    </div>

    {{-- Profile header --}}
    <div style="display: flex; gap: 8mm; align-items: flex-start; margin-bottom: 8mm;">

        <div class="avatar" style="background-color: #0a0a0a;">
            <span style="font-size: 28pt; font-weight: 900; color: #dc2626; line-height: 1;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
        </div>

        <div style="flex: 1;">
            <div class="t-section" style="margin-bottom: 2mm;">{{ $user->name }}</div>
            <div class="t-label" style="margin-bottom: 5mm;">
                Siswa Kompetensi Keahlian Desain Komunikasi Visual
            </div>
            <div style="display: flex; gap: 4px;">
                <span class="badge-red">DKV SMEKDA</span>
                <span class="badge-outline">{{ now()->format('Y') }}</span>
                @if($portfolios->count() > 0)
                <span class="badge-outline">{{ $totalKarya }} Karya</span>
                @endif
            </div>
        </div>
    </div>

    <div class="rule"></div>

    {{-- Info grid --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6mm 10mm; margin-bottom: 8mm;">

        <div>
            <div class="t-label" style="margin-bottom: 2mm;">NIS / NIP</div>
            <div style="font-size: 11pt; font-weight: 700; color: #0a0a0a;">{{ $user->nis_nip ?? '—' }}</div>
        </div>

        <div>
            <div class="t-label" style="margin-bottom: 2mm;">Email</div>
            <div style="font-size: 9pt; font-weight: 500; color: #0a0a0a; word-break: break-all;">{{ $user->email }}</div>
        </div>

        <div>
            <div class="t-label" style="margin-bottom: 2mm;">Nomor WhatsApp</div>
            <div style="font-size: 9pt; font-weight: 500; color: #0a0a0a;">{{ $user->phone ?? '—' }}</div>
        </div>

        <div>
            <div class="t-label" style="margin-bottom: 2mm;">Institusi</div>
            <div style="font-size: 9pt; font-weight: 500; color: #0a0a0a;">SMK Negeri 2 Padang Panjang</div>
        </div>

        <div>
            <div class="t-label" style="margin-bottom: 2mm;">Total Karya</div>
            <div style="font-size: 24pt; font-weight: 900; color: #dc2626; letter-spacing: -1px; line-height: 1;">{{ $totalKarya }}</div>
        </div>

        <div>
            <div class="t-label" style="margin-bottom: 2mm;">Bidang Keahlian</div>
            <div style="font-size: 9pt; font-weight: 500; color: #0a0a0a;">Desain Komunikasi Visual</div>
        </div>
    </div>

    <div class="rule"></div>

    {{-- Bio --}}
    <div style="margin-top: 6mm;">
        <div class="t-label" style="margin-bottom: 4mm;">Tentang Saya</div>
        <div class="t-body" style="text-align: justify; max-width: 140mm; line-height: 1.85;">
            Saya adalah siswa Kompetensi Keahlian Desain Komunikasi Visual di SMK Negeri 2 Padang Panjang.
            Dokumen ini merupakan kompilasi karya desain yang telah dikerjakan sebagai bagian dari proses
            pembelajaran dan pengembangan kompetensi di bidang desain komunikasi visual. Setiap karya
            mencerminkan eksplorasi kreatif dan penerapan prinsip desain yang telah dipelajari selama
            masa studi.
        </div>
    </div>

    {{-- Category list --}}
    @if($totalKat > 0)
    <div style="margin-top: 8mm;">
        <div class="t-label" style="margin-bottom: 4mm;">Bidang Karya</div>
        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
            @foreach($byKat as $katName => $items)
            <span class="badge-outline">{{ $katName }} ({{ $items->count() }})</span>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- ============================================================
     HALAMAN 3 — DAFTAR ISI
     Alasan: Daftar isi profesional membantu pembaca (guru, juri,
     rekruter) menavigasi buku portofolio dengan efisien.
============================================================ --}}
<div class="pb-avoid" style="break-after: page; padding: 16mm 14mm; min-height: 267mm; position: relative;">

    <div class="bg-number" aria-hidden="true">02</div>

    <div class="section-marker">
        <span class="t-label" style="color: #dc2626;">02 — Daftar Isi</span>
    </div>

    <div class="t-section" style="margin-bottom: 10mm;">
        Katalog<br>Karya Portofolio
    </div>

    {{-- TOC entries --}}
    <div>
        @forelse($portfolios as $i => $p)
        <div class="toc-item pb-avoid">
            <div class="toc-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
            <div class="toc-name">{{ $p->title }}</div>
            <div class="toc-cat">{{ $p->category?->name ?? 'Umum' }}</div>
        </div>
        @empty
        <div style="padding: 10mm 0; text-align: center; color: #9ca3af;" class="t-body">
            Belum ada karya.
        </div>
        @endforelse
    </div>

    {{-- Summary box --}}
    @if($portfolios->count() > 0)
    <div style="margin-top: 10mm; padding: 6mm; border: 0.5px solid #e5e7eb; border-left: 3px solid #dc2626; background-color: #fafafa;">
        <div class="t-label" style="margin-bottom: 5mm;">Ringkasan Koleksi</div>
        <div style="display: flex; gap: 10mm;">
            <div>
                <div style="font-size: 20pt; font-weight: 900; color: #0a0a0a; letter-spacing: -1px; line-height: 1;">{{ $totalKarya }}</div>
                <div class="t-label">Total Karya</div>
            </div>
            <div style="width: 0.5px; background: #e5e7eb;"></div>
            @foreach($byKat as $katName => $items)
            <div>
                <div style="font-size: 20pt; font-weight: 900; color: #0a0a0a; letter-spacing: -1px; line-height: 1;">{{ $items->count() }}</div>
                <div class="t-label">{{ $katName }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- ============================================================
     HALAMAN 4+ — PORTFOLIO ITEMS
     Alasan: Satu karya per halaman memaksimalkan fokus visual
     dan memberi ruang napas pada setiap karya desain.
     break-inside: avoid mencegah gambar terpotong antar halaman.
============================================================ --}}
@forelse($portfolios as $index => $portfolio)

<div class="pb-avoid" style="break-after: page; padding: 14mm 14mm 8mm; min-height: 267mm; position: relative;">

    {{-- Decorative background number --}}
    <div class="bg-number" aria-hidden="true">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>

    {{-- Item meta header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5mm;">
        <div style="display: flex; align-items: center; gap: 4mm;">
            <span class="badge-red">{{ $portfolio->category?->name ?? 'Umum' }}</span>
            @if($portfolio->file_pdf_path)
            <span class="badge-outline" style="border-color: #fca5a5; color: #dc2626;">&#128196; PDF</span>
            @endif
        </div>
        <div class="t-micro">
            {{ $portfolio->created_at->format('d F Y') }}
        </div>
    </div>

    {{-- IMAGE — Full width, primary focus --}}
    <div class="img-frame pb-avoid" style="margin-bottom: 6mm;">
        <img
            src="{{ asset('storage/' . $portfolio->image_path) }}"
            alt="{{ $portfolio->title }}"
            loading="eager"
            onerror="
                this.style.display='none';
                this.nextElementSibling.style.display='flex';
            "
        >
        <div style="display: none; width: 100%; height: 80mm; align-items: center; justify-content: center; color: #d1d5db; font-size: 7pt; font-weight: 700; letter-spacing: 0.3em; text-transform: uppercase; flex-direction: column; gap: 4px;">
            <div style="font-size: 20pt; opacity: 0.3;">&#9651;</div>
            Gambar Tidak Tersedia
        </div>
    </div>

    {{-- Content area — 2-column grid --}}
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 8mm; align-items: start;">

        {{-- LEFT: Title + Description --}}
        <div>
            {{-- Portfolio title --}}
            <div class="t-portfolio" style="margin-bottom: 3mm;">{{ $portfolio->title }}</div>

            {{-- Red rule --}}
            <div class="rule-red"></div>

            {{-- Description --}}
            <div class="{{ $index === 0 ? 'drop-cap' : '' }} t-body" style="text-align: justify;">
                {{ $portfolio->description }}
            </div>
        </div>

        {{-- RIGHT: Metadata sidebar --}}
        <div style="border-left: 0.5px solid #e5e7eb; padding-left: 7mm;">

            <div style="margin-bottom: 5mm;">
                <div class="t-label" style="margin-bottom: 2mm;">Kategori</div>
                <div style="font-size: 8.5pt; font-weight: 700; color: #0a0a0a;">{{ $portfolio->category?->name ?? 'Umum' }}</div>
            </div>

            <div class="rule"></div>

            <div style="margin-bottom: 5mm;">
                <div class="t-label" style="margin-bottom: 2mm;">Tanggal Upload</div>
                <div style="font-size: 8.5pt; font-weight: 700; color: #0a0a0a;">{{ $portfolio->created_at->format('d F Y') }}</div>
                <div class="t-micro" style="margin-top: 1mm;">{{ $portfolio->created_at->format('H:i') }} WIB</div>
            </div>

            <div class="rule"></div>

            <div style="margin-bottom: 5mm;">
                <div class="t-label" style="margin-bottom: 2mm;">File Pendukung</div>
                @if($portfolio->file_pdf_path)
                    <span style="display: inline-block; background-color: #fee2e2; border: 1px solid #fca5a5; color: #dc2626; font-size: 6pt; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; padding: 3px 8px;">
                        &#10003; PDF Tersedia
                    </span>
                @else
                    <div style="font-size: 8pt; color: #d1d5db; font-weight: 500;">Tidak tersedia</div>
                @endif
            </div>

            <div class="rule"></div>

            <div>
                <div class="t-label" style="margin-bottom: 2mm;">Pemilik Karya</div>
                <div style="font-size: 8pt; font-weight: 700; color: #0a0a0a; line-height: 1.4;">{{ $user->name }}</div>
                <div class="t-micro" style="margin-top: 1mm;">NIS: {{ $user->nis_nip ?? '—' }}</div>
            </div>

            {{-- Watermark DKV (dekoratif, sangat transparan) --}}
            <div style="margin-top: 10mm; text-align: center; opacity: 0.05;" aria-hidden="true">
                <div style="font-size: 20pt; font-weight: 900; color: #0a0a0a; letter-spacing: -1px; line-height: 1;">DKV</div>
                <div style="font-size: 5pt; font-weight: 700; letter-spacing: 0.3em; color: #0a0a0a;">SMEKDA</div>
            </div>

        </div>
    </div>

</div>

@empty

<div class="pb-avoid" style="break-after: page; padding: 14mm; display: flex; align-items: center; justify-content: center; min-height: 267mm;">
    <div style="text-align: center; color: #9ca3af;">
        <div style="font-size: 24pt; opacity: 0.2; margin-bottom: 6mm;">&#9651;</div>
        <div class="t-label">Belum ada karya yang tersedia</div>
    </div>
</div>

@endforelse

{{-- ============================================================
     HALAMAN REKAP — STATISTIK PORTOFOLIO
     Alasan: Statistik visual memberi gambaran cepat kepada
     pembaca (rekruter/guru) tentang breadth & depth karya siswa.
============================================================ --}}
@if($portfolios->count() > 0)

<div class="pb-avoid" style="break-after: page; padding: 16mm 14mm; min-height: 267mm; position: relative;">

    <div class="bg-number" aria-hidden="true">&#x221E;</div>

    <div class="section-marker">
        <span class="t-label" style="color: #dc2626;">Rekap — Statistik Portofolio</span>
    </div>

    <div class="t-section" style="margin-bottom: 12mm;">
        Statistik &<br>Rekap Karya
    </div>

    {{-- Big 3 stats --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); margin-bottom: 12mm;">

        <div style="border-top: 3px solid #0a0a0a; padding-top: 5mm; padding-right: 8mm;">
            <div style="font-size: 48pt; font-weight: 900; color: #0a0a0a; letter-spacing: -3px; line-height: 0.88;">{{ $totalKarya }}</div>
            <div class="t-label" style="margin-top: 3mm;">Total Karya</div>
        </div>

        <div style="border-top: 3px solid #dc2626; padding-top: 5mm; padding: 0 8mm; padding-top: 5mm;">
            <div style="font-size: 48pt; font-weight: 900; color: #dc2626; letter-spacing: -3px; line-height: 0.88;">{{ $totalKat }}</div>
            <div class="t-label" style="margin-top: 3mm;">Kategori</div>
        </div>

        <div style="border-top: 3px solid #e5e7eb; padding-top: 5mm; padding-left: 8mm;">
            <div style="font-size: 48pt; font-weight: 900; color: #9ca3af; letter-spacing: -3px; line-height: 0.88;">{{ $totalPdf }}</div>
            <div class="t-label" style="margin-top: 3mm;">Dokumen PDF</div>
        </div>

    </div>

    <div class="rule"></div>

    {{-- Category breakdown bars --}}
    <div style="margin-top: 8mm; margin-bottom: 10mm;">
        <div class="t-label" style="margin-bottom: 6mm; border-bottom: 0.5px solid #f3f4f6; padding-bottom: 3mm;">
            Distribusi Per Kategori
        </div>

        @foreach($byKat as $katName => $items)
        @php $pct = $totalKarya > 0 ? round(($items->count() / $totalKarya) * 100) : 0; @endphp
        <div style="margin-bottom: 5mm;" class="pb-avoid">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2mm;">
                <div style="font-size: 8.5pt; font-weight: 700; color: #0a0a0a;">{{ $katName }}</div>
                <div style="font-size: 8pt; font-weight: 600; color: #6b7280;">{{ $items->count() }} karya &bull; {{ $pct }}%</div>
            </div>
            <div class="stat-bar-bg">
                <div class="stat-bar-fill" style="width: {{ $pct }}%;"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="rule"></div>

    {{-- QR Code section --}}
    <div style="margin-top: 8mm; border: 0.5px solid #e5e7eb; border-top: 2px solid #0a0a0a; padding: 7mm;">
        <div style="display: flex; gap: 8mm; align-items: center;">

            {{-- QR Code --}}
            <div style="flex-shrink: 0;">
                <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&color=0a0a0a&bgcolor=ffffff&data={{ urlencode(route('siswa.dashboard')) }}"
                    alt="QR Code Portfolio Online"
                    style="width: 90px; height: 90px; display: block; border: 0.5px solid #e5e7eb;"
                    onerror="
                        this.style.display='none';
                        document.getElementById('qr-fallback').style.display='flex';
                    "
                >
                <div id="qr-fallback" style="display: none; width: 90px; height: 90px; border: 2px solid #0a0a0a; align-items: center; justify-content: center; flex-direction: column; gap: 3px;">
                    <div style="font-size: 6pt; font-weight: 900; color: #0a0a0a; letter-spacing: 0.2em;">QR CODE</div>
                    <div style="font-size: 5pt; color: #9ca3af; text-align: center;">Scan untuk<br>akses online</div>
                </div>
            </div>

            {{-- QR Info --}}
            <div style="flex: 1;">
                <div class="t-label" style="margin-bottom: 3mm;">Akses Portfolio Online</div>
                <div style="font-size: 11pt; font-weight: 800; color: #0a0a0a; margin-bottom: 2mm; line-height: 1.3;">
                    Portofolio Digital Interaktif
                </div>
                <div class="t-body" style="margin-bottom: 4mm;">
                    Scan QR code untuk mengakses portofolio digital secara online melalui Sistem Manajemen Portfolio DKV SMEKDA.
                </div>
                <div class="t-label">
                    Dikembangkan oleh Rafli Ahmad Zulkarnain &mdash; Skripsi 2026
                </div>
            </div>
        </div>
    </div>

</div>

@endif

{{-- ============================================================
     HALAMAN TERAKHIR — PENUTUP
     Alasan: Closing page hitam yang kuat menciptakan "bookend"
     visual yang simetris dengan cover — standar editorial design.
     Terima kasih + ringkasan = closure yang profesional.
============================================================ --}}
<div class="pb-avoid" style="background-color: #0a0a0a; min-height: 267mm; padding: 16mm 14mm; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;">

    {{-- Geometric accent kiri --}}
    <div style="position: absolute; top: 0; left: 0; width: 3mm; height: 100%; background-color: #dc2626;"></div>

    {{-- Top: Thank you --}}
    <div style="padding-left: 6mm;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14mm;">
            <div style="width: 16px; height: 1.5px; background-color: #dc2626;"></div>
            <div class="t-label" style="color: rgba(255,255,255,0.28);">Penutup</div>
        </div>

        <div style="font-size: 56pt; font-weight: 900; color: #ffffff; letter-spacing: -3px; line-height: 0.88; margin-bottom: 3mm;">
            TERIMA
        </div>
        <div style="font-size: 56pt; font-weight: 900; color: #dc2626; letter-spacing: -3px; line-height: 0.88; margin-bottom: 8mm;">
            KASIH.
        </div>

        <div style="width: 50px; height: 2px; background-color: #dc2626; margin-bottom: 8mm;"></div>

        <div class="t-body" style="color: rgba(255,255,255,0.38); max-width: 120mm; line-height: 1.85;">
            Dokumen portofolio ini merupakan representasi karya dan kompetensi yang
            telah dikembangkan selama masa pembelajaran di Kompetensi Keahlian
            Desain Komunikasi Visual, SMK Negeri 2 Padang Panjang.
            Semua karya dalam buku ini adalah hasil dari eksplorasi,
            dedikasi, dan proses kreatif yang berkelanjutan.
        </div>
    </div>

    {{-- Middle: Stats summary --}}
    <div style="padding-left: 6mm; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); padding-top: 8mm; padding-bottom: 8mm; margin: 10mm 0;">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0;">
            <div style="padding-right: 8mm; border-right: 1px solid rgba(255,255,255,0.06);">
                <div style="font-size: 26pt; font-weight: 900; color: #ffffff; letter-spacing: -1.5px; line-height: 1;">{{ $totalKarya }}</div>
                <div class="t-label" style="color: rgba(255,255,255,0.25); margin-top: 2mm;">Karya Terdokumentasi</div>
            </div>
            <div style="padding: 0 8mm; border-right: 1px solid rgba(255,255,255,0.06);">
                <div style="font-size: 26pt; font-weight: 900; color: #dc2626; letter-spacing: -1.5px; line-height: 1;">{{ $totalKat }}</div>
                <div class="t-label" style="color: rgba(255,255,255,0.25); margin-top: 2mm;">Bidang Kategori</div>
            </div>
            <div style="padding-left: 8mm;">
                <div style="font-size: 26pt; font-weight: 900; color: rgba(255,255,255,0.4); letter-spacing: -1.5px; line-height: 1;">{{ now()->format('Y') }}</div>
                <div class="t-label" style="color: rgba(255,255,255,0.25); margin-top: 2mm;">Tahun Akademik</div>
            </div>
        </div>
    </div>

    {{-- Bottom: Identity + copyright --}}
    <div style="padding-left: 6mm;">
        <div style="margin-bottom: 6mm;">
            <div style="font-size: 14pt; font-weight: 800; color: #ffffff; letter-spacing: -0.3px; line-height: 1.3; margin-bottom: 2mm;">
                {{ $user->name }}
            </div>
            <div class="t-label" style="color: rgba(255,255,255,0.28);">
                {{ $user->nis_nip ?? '—' }} &bull; DKV &bull; SMKN 2 Padang Panjang &bull; {{ now()->format('Y') }}
            </div>
        </div>

        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 5mm;">
            <div class="t-label" style="color: rgba(255,255,255,0.14); margin-bottom: 2mm;">
                SISTEM MANAJEMEN PORTFOLIO DIGITAL — DKV SMEKDA
            </div>
            <div class="t-label" style="color: rgba(255,255,255,0.1);">
                Developed by Rafli Ahmad Zulkarnain &mdash; 2026
                &bull; Skripsi Teknik Informatika / RPL
            </div>
        </div>
    </div>

</div>

</div>{{-- end .a4 --}}

{{-- Screen spacer --}}
<div class="screen-only" style="height: 40px;"></div>

{{-- ================================================================
     AUTO-PRINT SCRIPT
     Alasan: Delay 1200ms memastikan font + gambar loaded sebelum
     dialog cetak terbuka — mencegah layout kosong saat print
================================================================ --}}
<script>
    /**
     * Auto Print Handler
     * Menunggu semua gambar dan font selesai dimuat sebelum membuka
     * dialog cetak browser. Fallback timeout jika ada gambar gagal load.
     */
    (function() {
        var printed = false;

        function doPrint() {
            if (printed) return;
            printed = true;
            setTimeout(function() { window.print(); }, 400);
        }

        // Tunggu semua gambar
        var images = document.querySelectorAll('img');
        var loaded = 0;
        var total  = images.length;

        if (total === 0) {
            setTimeout(doPrint, 1000);
            return;
        }

        function onImgDone() {
            loaded++;
            if (loaded >= total) doPrint();
        }

        images.forEach(function(img) {
            if (img.complete) {
                onImgDone();
            } else {
                img.addEventListener('load',  onImgDone);
                img.addEventListener('error', onImgDone);
            }
        });

        // Fallback timeout 5 detik
        setTimeout(doPrint, 5000);
    })();
</script>

</body>
</html>