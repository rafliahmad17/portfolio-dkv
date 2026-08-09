{{-- Navbar identitas sekolah. Otomatis dipakai oleh layouts.app kecuali
     halaman child mendefinisikan @section('navbar') sendiri. --}}
<header class="sticky top-0 z-50 border-b border-white/10 bg-black/80 backdrop-blur-md">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <img
                src="{{ asset('images/logo-sekolah.png') }}"
                alt="Logo SMK Negeri 2 Padang Panjang"
                class="h-9 w-9 rounded-sm object-contain"
                onerror="this.style.display='none'"
            >
            <span class="font-sans text-sm font-extrabold uppercase tracking-widest text-white/90">
                DKV<span class="text-brand-600">.</span>SMEKDA
            </span>
        </a>

        <a
            href="https://smkn2-padangpanjang.sch.id"
            target="_blank"
            rel="noopener"
            class="text-sm text-white/50 transition hover:text-white"
        >
            Situs Resmi Sekolah ↗
        </a>
    </div>
</header>
