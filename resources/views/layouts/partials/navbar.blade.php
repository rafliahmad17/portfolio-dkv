{{-- Navbar identitas sekolah --}}
<header class="sticky top-0 z-50 w-full border-b theme-border bg-[color:var(--theme-surface)]/90 backdrop-blur-md transition-colors duration-200">
    <div class="mx-auto flex w-full max-w-6xl items-center justify-between gap-3 px-4 py-3 sm:px-6 sm:py-4">
        <a href="{{ url('/') }}" class="flex min-w-0 shrink-0 items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg border theme-border theme-surface-muted p-1 sm:h-11 sm:w-11">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Negeri 2 Padang Panjang" class="!block !h-full !w-full !max-w-none !object-contain">
            </div>
            <div class="min-w-0">
                <span class="block whitespace-nowrap font-sans text-sm font-extrabold uppercase tracking-[0.15em] theme-text sm:text-base sm:tracking-widest">
                    DKV<span class="theme-accent">.</span>SMEKDA
                </span>
                <span class="mt-0.5 hidden text-[9px] font-medium uppercase tracking-[0.18em] theme-text-faint sm:block">
                    Digital Creative Portfolio
                </span>
            </div>
        </a>

        <div class="flex shrink-0 items-center gap-2">
            <button
                type="button"
                data-theme-toggle
                aria-pressed="false"
                aria-label="Gunakan mode gelap"
                title="Mode gelap"
                class="inline-flex h-11 w-11 items-center justify-center rounded-lg border theme-border theme-surface-muted theme-text transition hover:theme-accent-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--theme-accent)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--theme-bg)]"
            >
                <span aria-hidden="true" class="text-base">◐</span>
            </button>

            <a href="https://smkn2-padangpanjang.sch.id" target="_blank" rel="noopener noreferrer"
               class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-lg border theme-border theme-surface-muted px-3 py-2 text-xs font-medium theme-text-muted transition hover:theme-accent-soft hover:theme-accent sm:px-4 sm:text-sm">
                <span class="hidden sm:inline">Situs Resmi Sekolah</span>
                <span class="sm:hidden">Sekolah</span>
                <span class="ml-1">↗</span>
            </a>
        </div>
    </div>
</header>