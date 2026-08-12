<footer class="w-full border-t border-white/10 bg-black">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-8 px-4 py-10 sm:px-6 sm:py-12 md:flex-row md:items-start md:justify-between">

        {{-- IDENTITAS SEKOLAH --}}
        <div class="flex items-start gap-4">

            {{-- LOGO SMK --}}
            <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-white/10 bg-white/[0.04] p-1.5 sm:h-16 sm:w-16">
                <img
                    src="{{ asset('images/logo-sekolah.png') }}"
                    alt="Logo SMK Negeri 2 Padang Panjang"
                    class="!block !h-full !w-full !max-w-none !object-contain"
                >
            </div>

            {{-- INFORMASI SEKOLAH --}}
            <div class="min-w-0">
                <p class="font-sans text-sm font-bold uppercase tracking-wide text-white sm:text-base">
                    SMK Negeri 2 Padang Panjang
                </p>

                <p class="mt-2 max-w-md text-xs leading-relaxed text-white/40 sm:text-sm">
                    Jl. Syekh Ibrahim Musa No. 26, Ganting, Kec. Padang Panjang Timur,
                    Kota Padang Panjang, Sumatera Barat 27127
                </p>
            </div>

        </div>

        {{-- LINK + COPYRIGHT --}}
        <div class="text-left text-xs sm:text-sm md:text-right">

            <a
                href="https://smkn2-padangpanjang.sch.id"
                target="_blank"
                rel="noopener noreferrer"
                class="font-medium text-brand-500 transition hover:text-brand-400"
            >
                smkn2-padangpanjang.sch.id ↗
            </a>

            <p class="mt-3 text-white/30">
                &copy; {{ date('Y') }} DKV SMEKDA — Portofolio Digital DKV.
            </p>

        </div>

    </div>
</footer>