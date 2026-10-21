{{--
    Navigasi paginasi ringkas (Sebelumnya / Halaman X dari Y / Berikutnya).
    Dipakai bersama oleh dashboard siswa dan profil publik.
    Pemakaian: @include('partials.pagination', ['paginator' => $portfolios])
    Warna mengikuti teks halaman (currentColor) sehingga cocok di tema terang/gelap.
--}}
@if ($paginator->hasPages())
<nav class="pg-nav" role="navigation" aria-label="Navigasi halaman karya">
    @if ($paginator->onFirstPage())
        <span class="pg-btn is-disabled" aria-disabled="true">&larr; Sebelumnya</span>
    @else
        <a class="pg-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">&larr; Sebelumnya</a>
    @endif

    <span class="pg-info">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>

    @if ($paginator->hasMorePages())
        <a class="pg-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya &rarr;</a>
    @else
        <span class="pg-btn is-disabled" aria-disabled="true">Berikutnya &rarr;</span>
    @endif
</nav>

<style>
    .pg-nav { display:flex; align-items:center; justify-content:center; gap:16px; flex-wrap:wrap; margin:40px 0 8px; font:inherit; color:inherit; }
    .pg-btn { display:inline-flex; align-items:center; padding:10px 18px; border:1px solid currentColor; border-radius:999px; font-size:.875rem; text-decoration:none; color:inherit; transition:opacity .15s ease, transform .15s ease; }
    a.pg-btn:hover, a.pg-btn:focus-visible { opacity:.7; transform:translateY(-1px); }
    .pg-btn.is-disabled { opacity:.35; cursor:not-allowed; }
    .pg-info { font-size:.875rem; opacity:.75; }
</style>
@endif
