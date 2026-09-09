{{-- resources/views/guru/partials/sidebar.blade.php
     ============================================================
     FASE 5.4 — Shared sidebar navigasi Guru/Admin.
     Diekstrak dari markup yang identik di:
       - guru/dashboard.blade.php
       - guru/kategori/index.blade.php
       - guru/siswa/index.blade.php
       - guru/profile.blade.php
     Nilai/markup TIDAK diubah kecuali penambahan aria-current="page"
     (item nav aktif) dan id="guruSidebar" pada <aside> — lihat laporan
     Fase 5.4 untuk detail & alasan.

     Parameter opsional:
     - $showSidebarClose (bool, default false)
         true  -> render tombol close drawer (.sidebar-close-btn) di
                  dalam .sidebar-logo-row. HANYA dipakai oleh
                  guru/profile.blade.php (mekanisme drawer miliknya
                  sendiri: .hamburger-btn + .sidebar-close-btn +
                  .sidebar-overlay). CSS untuk elemen ini TETAP di
                  guru/profile.blade.php (tidak dipindah), karena hanya
                  halaman itu yang mengaktifkan flag ini.
     - $sidebarAvatarPath (string|null, default null)
         Path kolom `photo` untuk foto avatar bulat di kartu sidebar.
         guru/dashboard, guru/kategori/index, guru/siswa/index mengirim
         auth()->user()->photo (tampil foto jika ada, inisial jika
         tidak — perilaku asli mereka). guru/profile.blade.php SEBELUM
         Fase 5.4 tidak pernah mengecek kolom ini sama sekali (selalu
         inisial) — nilai ini SENGAJA dibiarkan null di halaman itu agar
         perilaku lama tidak berubah. Lihat "perbedaan yang ditemukan"
         di laporan Fase 5.4 (kemungkinan inconsistency/bug lama, bukan
         hasil ekstraksi ini).

     id="guruSidebar" SELALU dipasang di <aside>. Dibutuhkan oleh JS
     guru/profile.blade.php (document.getElementById('guruSidebar')).
     Halaman lain memakai document.querySelector('.sidebar') sehingga
     id ini netral/tidak mempengaruhi mereka.
============================================================ --}}
@php
    $showSidebarClose = $showSidebarClose ?? false;
    $sidebarAvatarPath = $sidebarAvatarPath ?? null;
@endphp

<aside class="sidebar" id="guruSidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        @if($showSidebarClose)
            <div class="sidebar-logo-row">
                <div class="logo-wordmark">
                    <div class="logo-icon" style="background: var(--surface-sunk); border: 1px solid var(--hairline); box-shadow: none;">
                        <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK" style="width: 100%; height: 100%; object-fit: contain; padding: 2px;">
                    </div>
                    DKV<span style="color:var(--color-accent-600);">.</span>SMEKDA
                </div>
                <button type="button" class="sidebar-close-btn" id="sidebarClose" aria-label="Tutup menu navigasi">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @else
            <div class="logo-wordmark">
                <div class="logo-icon" style="background: var(--surface-sunk); border: 1px solid var(--hairline); box-shadow: none;">
                    <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK" style="width: 100%; height: 100%; object-fit: contain; padding: 2px;">
                </div>
                DKV<span style="color:var(--color-accent-600);">.</span>SMEKDA
            </div>
        @endif
        <div style="font-size:0.62rem; color:var(--color-ink-faint); margin-top:4px; letter-spacing:1px; text-transform:uppercase; font-weight:600; padding-left:35px;">
            Portal Guru
        </div>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <div class="profile-avatar" style="overflow: hidden;">
                @if($sidebarAvatarPath)
                    <img src="{{ asset('storage/' . $sidebarAvatarPath) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1; min-width:0;">
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-nip">NIP: {{ auth()->user()->nis_nip ?? '—' }}</div>
            </div>
        </div>
        <div class="badge-role">
            <div class="live-dot"></div>
            Guru Pembimbing
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('guru.dashboard') }}"
           class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
           @if(request()->routeIs('guru.dashboard')) aria-current="page" @endif>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard Monitor
        </a>

        <a href="{{ route('guru.siswa.index') }}"
           class="nav-item {{ request()->routeIs('guru.siswa*') ? 'active' : '' }}"
           @if(request()->routeIs('guru.siswa*')) aria-current="page" @endif>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Data Siswa
        </a>

        <a href="{{ route('guru.kategori.index') }}"
           class="nav-item {{ request()->routeIs('guru.kategori*') ? 'active' : '' }}"
           @if(request()->routeIs('guru.kategori*')) aria-current="page" @endif>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Kelola Kategori
        </a>

        <a href="{{ route('guru.profile') }}"
           class="nav-item {{ request()->routeIs('guru.profile*') ? 'active' : '' }}"
           @if(request()->routeIs('guru.profile*')) aria-current="page" @endif>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Profil Saya
        </a>

        <div class="nav-label" style="margin-top:20px;">Laporan</div>

        <a href="javascript:void(0)" onclick="return false;"
           class="nav-item nav-item-disabled {{ request()->routeIs('guru.rekap*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="nav-item-label">
                Rekap & Statistik
                <span class="badge-soon">Segera Hadir</span>
            </span>
        </a>
    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar dari Portal
            </button>
        </form>
    </div>
</aside>
