{{-- Navbar identitas sekolah --}}
<header class="sticky top-0 z-50 w-full border-b border-white/10 bg-black/90 backdrop-blur-md">
    <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-3 sm:px-6 sm:py-4">

        {{-- LOGO + NAMA WEBSITE --}}
        <a href="{{ url('/') }}"
           class="flex min-w-0 shrink-0 items-center gap-3">

            {{-- LOGO SMK --}}
            <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-white/10 bg-white/[0.04] p-1 sm:h-11 sm:w-11">
                <img
                    src="{{ asset('images/logo-sekolah.png') }}"
                    alt="Logo SMK Negeri 2 Padang Panjang"
                    class="!block !h-full !w-full !max-w-none !object-contain"
                >
            </div>

            {{-- IDENTITAS WEBSITE --}}
            <div class="min-w-0">
                <span class="block whitespace-nowrap font-sans text-sm font-extrabold uppercase tracking-[0.15em] text-white/90 sm:text-base sm:tracking-widest">
                    DKV<span class="text-brand-600">.</span>SMEKDA
                </span>

                <span class="mt-0.5 hidden text-[9px] font-medium uppercase tracking-[0.18em] text-white/30 sm:block">
                    Digital Creative Portfolio
                </span>
            </div>

        </a>

        {{-- SITUS RESMI --}}
        <a
            href="https://smkn2-padangpanjang.sch.id"
            target="_blank"
            rel="noopener noreferrer"
            class="shrink-0 rounded-lg border border-white/10 bg-white/[0.03] px-3 py-2 text-xs font-medium text-white/50 transition hover:border-brand-600/30 hover:bg-brand-600/10 hover:text-white sm:px-4 sm:text-sm"
        >
            <span class="hidden sm:inline">Situs Resmi Sekolah</span>
            <span class="sm:hidden">Sekolah</span>
            <span class="ml-1">↗</span>
        </a>

    </div>
</header>