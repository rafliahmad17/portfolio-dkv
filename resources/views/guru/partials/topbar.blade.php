{{-- resources/views/guru/partials/topbar.blade.php
     ============================================================
     FASE 5.4 — Shared topbar Guru/Admin.

     Bagian yang identik di keempat halaman (wrapper .topbar dan
     .topbar-pill/date) disatukan di sini. Tombol mobile-toggle SENGAJA
     dibiarkan bercabang lewat $topbarToggle karena ketiga varian
     markup & mekanisme JS-nya berbeda (lihat catatan besar di
     resources/css/components/dashboard-shell.css bagian "mobile-toggle").
     Penyatuan penuh mekanisme mobile toggle ditunda ke Fase 5.5.

     Parameter:
     - $topbarTitle  (string, wajib)
         Teks halaman setelah "Portal DKV SMEKDA /".
     - $topbarToggle (string, opsional, default 'none')
         'none'      -> guru/dashboard.blade.php
                        (tidak ada toggle sama sekali; title langsung
                        jadi child pertama .topbar)
         'hamburger' -> guru/siswa/index.blade.php & guru/kategori/index.blade.php
                        (SVG 3-garis, class="mobile-menu-btn",
                        id="btnMobileMenu", dibungkus .topbar-left
                        bersama title — markup & id ini byte-identik di
                        kedua halaman asalnya)
         'bars'      -> guru/profile.blade.php
                        (animasi 3 batang, class="hamburger-btn",
                        id="sidebarToggle", aria-controls="guruSidebar";
                        title dibungkus <span class="topbar-crumb"> agar
                        bisa disembunyikan di mobile oleh CSS halaman itu)
============================================================ --}}
@php($topbarToggle = $topbarToggle ?? 'none')

<div class="topbar">
    @if($topbarToggle === 'hamburger')
        <div class="topbar-left">
            <button type="button" class="mobile-menu-btn" id="btnMobileMenu" aria-label="Buka menu navigasi">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="topbar-title">
                Portal DKV SMEKDA <span>/</span> {{ $topbarTitle }}
            </div>
        </div>
    @elseif($topbarToggle === 'bars')
        <button type="button" class="hamburger-btn" id="sidebarToggle"
                aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="guruSidebar">
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
        </button>
        <div class="topbar-title">
            <span class="topbar-crumb">Portal DKV SMEKDA <span>/</span></span> {{ $topbarTitle }}
        </div>
    @else
        <div class="topbar-title">
            Portal DKV SMEKDA <span>/</span> {{ $topbarTitle }}
        </div>
    @endif

    <div class="topbar-pill">
        <div class="live-dot"></div>
        {{ now()->translatedFormat('d F Y') }}
    </div>
</div>
