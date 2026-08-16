{{-- Navbar identitas sekolah --}}
<header class="sticky top-0 z-50 w-full border-b border-paper-border bg-paper-elevated/90 backdrop-blur-md">
    <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-3 sm:px-6 sm:py-4">

        {{-- LOGO + NAMA WEBSITE --}}
        <a href="{{ url('/') }}"
           class="flex min-w-0 shrink-0 items-center gap-3">

            {{-- LOGO SMK --}}
            <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-paper-border bg-paper-muted p-1 sm:h-11 sm:w-11">
                <img
                    src="{{ asset('images/logo-sekolah.png') }}"
                    alt="Logo SMK Negeri 2 Padang Panjang"
                    class="!block !h-full !w-full !max-w-none !object-contain"
                >
            </div>

            {{-- IDENTITAS WEBSITE --}}
            <div class="min-w-0">
                <span class="block whitespace-nowrap font-sans text-sm font-extrabold uppercase tracking-[0.15em] text-ink sm:text-base sm:tracking-widest">
                    DKV<span class="text-accent-600">.</span>SMEKDA
                </span>

                <span class="mt-0.5 hidden text-[9px] font-medium uppercase tracking-[0.18em] text-ink-faint sm:block">
                    Digital Creative Portfolio
                </span>
            </div>

        </a>

        {{-- SITUS RESMI --}}
        <a
            href="https://smkn2-padangpanjang.sch.id"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-lg border border-paper-border bg-paper-muted px-3 py-2 text-xs font-medium text-ink-muted transition hover:border-accent-200 hover:bg-accent-50 hover:text-accent-700 sm:px-4 sm:text-sm"
        >
            <span class="hidden sm:inline">Situs Resmi Sekolah</span>
            <span class="sm:hidden">Sekolah</span>
            <span class="ml-1">↗</span>
        </a>

    </div>
</header>