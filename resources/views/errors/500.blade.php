@extends('layouts.app')

@section('title', '500 — Kesalahan Server | DKV SMEKDA')

@section('content')
<section class="mx-auto flex w-full max-w-2xl flex-1 flex-col items-center justify-center px-4 py-16 text-center sm:px-6 sm:py-24">

    <p class="font-mono text-[11px] font-medium uppercase tracking-[0.2em] text-ink-faint">
        Kode Kesalahan
    </p>

    <p aria-hidden="true" class="mt-3 font-serif text-[5.5rem] font-medium leading-none text-ink sm:text-[7.5rem]">
        500
    </p>

    <span aria-hidden="true" class="mt-5 block h-px w-16 bg-accent-500"></span>

    <h1 class="mt-6 font-serif text-2xl font-semibold text-ink sm:text-3xl">
        Terjadi Kesalahan pada Server
    </h1>

    <p class="mt-4 max-w-md text-sm leading-relaxed text-ink-muted sm:text-base">
        Maaf, terjadi kendala teknis di sisi kami. Silakan coba lagi beberapa saat lagi. Jika masalah berlanjut, hubungi admin sekolah.
    </p>

    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <a
            href="{{ url('/') }}"
            class="inline-flex min-h-11 items-center justify-center rounded-lg bg-accent-600 px-6 py-2.5 text-sm font-semibold text-paper transition hover:bg-accent-700"
        >
            Kembali ke Beranda
        </a>
    </div>

</section>
@endsection
