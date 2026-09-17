{{-- resources/views/guru/partials/topbar.blade.php
     ============================================================
     FASE 5.4 — Shared topbar Guru/Admin.

     Bagian yang identik di keempat halaman (wrapper .topbar dan
     .topbar-pill/date) disatukan di sini. Tombol mobile-toggle tetap
     bercabang lewat $topbarToggle karena markupnya berbeda per varian.
     Sejak penyatuan mekanisme mobile toggle di Fase 5.5, keempat
     halaman guru (dashboard, siswa/index, kategori/index, profile)
     sama-sama memanggil partial ini dengan topbarToggle="bars"; varian
     'none' dan 'hamburger' di bawah tidak lagi dipakai halaman manapun
     saat ini, tapi tetap dipertahankan di partial.

     Parameter:
     - $topbarTitle  (string, wajib)
         Teks halaman setelah "Portal DKV SMEKDA /".
     - $topbarToggle (string, opsional, default 'none')
         'bars'      -> dipakai SEMUA halaman guru saat ini (dashboard,
                        siswa/index, kategori/index, profile)
                        (animasi 3 batang, class="hamburger-btn",
                        id="sidebarToggle", aria-controls="guruSidebar";
                        title dibungkus <span class="topbar-crumb"> agar
                        bisa disembunyikan di mobile oleh CSS halaman itu)
         'hamburger' -> tidak dipakai halaman manapun saat ini
                        (SVG 3-garis, class="mobile-menu-btn",
                        id="btnMobileMenu", dibungkus .topbar-left
                        bersama title)
         'none'      -> tidak dipakai halaman manapun saat ini
                        (tidak ada toggle sama sekali; title langsung
                        jadi child pertama .topbar)
============================================================ --}}
@php($topbarToggle = $topbarToggle ?? 'none')

<div class="topbar">
    @if($topbarToggle === 'hamburger')
        <div class="topbar-left">
            <button type="button" class="mobile-menu-btn" id="btnMobileMenu" aria-label="Buka menu navigasi">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
