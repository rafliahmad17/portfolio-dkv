{{-- Footer identitas resmi sekolah. Sama seperti navbar, otomatis
     disembunyikan di halaman yang mendefinisikan @section('footer') kosong
     (mis. dashboard guru/siswa yang sudah punya footer strip sendiri di
     dalam kontennya). Alamat sudah diverifikasi ulang ke situs resmi
     sekolah (smkn2-padangpanjang.sch.id). --}}
<footer class="border-t border-white/10 bg-black px-6 py-10">
    <div class="mx-auto flex max-w-6xl flex-col gap-8 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-start gap-4">
            <img
                src="{{ asset('images/logo-sekolah.png') }}"
                alt="Logo SMK Negeri 2 Padang Panjang"
                class="h-12 w-12 flex-shrink-0 rounded-md object-contain"
                onerror="this.style.display='none'"
            >
            <div>
                <p class="font-sans text-sm font-bold uppercase tracking-wide text-white">
                    SMK Negeri 2 Padang Panjang
                </p>
                <p class="mt-2 max-w-xs text-sm leading-relaxed text-white/40">
                    Jl. Syekh Ibrahim Musa No. 26, Ganting, Kec. Padang Panjang Timur,
                    Kota Padang Panjang, Sumatera Barat 27127
                </p>
            </div>
        </div>

        <div class="text-sm sm:text-right">
            <a
                href="https://smkn2-padangpanjang.sch.id"
                target="_blank"
                rel="noopener"
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
