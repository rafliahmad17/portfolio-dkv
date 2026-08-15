<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'DKV SMEKDA — Platform Portfolio Digital')</title>

    {{-- Tempat halaman child menambahkan <meta> khusus miliknya sendiri
         (mis. og:title/og:description/og:image untuk halaman publik yang
         perlu preview bagus saat dibagikan ke WhatsApp/media sosial). --}}
    @stack('meta')

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Tailwind CSS dimuat lewat Vite (asset hasil build), bukan CDN lagi.
         Seluruh design token yang sebelumnya ada di sini sebagai
         tailwind.config inline (font sans/display, warna brand 50-950,
         spacing.sidebar, border radius custom xs-pill) sudah dipindahkan
         apa adanya ke resources/css/app.css lewat @theme. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fraunces:wght@600..900&display=swap"
        rel="stylesheet"
    >

    {{-- Tempat halaman child menambahkan <style> khusus miliknya sendiri
         (mis. blok CSS besar yang belum sempat dipecah menjadi komponen). --}}
    @stack('styles')
</head>

<body>

    {{-- Navbar & footer identitas sekolah tampil otomatis di sini, KECUALI
         halaman child mendefinisikan @section('navbar')/@section('footer')
         sendiri (kosongkan untuk menyembunyikan — dipakai oleh halaman yang
         sudah punya navigasi/footer sendiri seperti dashboard guru/siswa). --}}
    @section('navbar')
        @include('layouts.partials.navbar')
    @show

    @yield('content')

    @section('footer')
        @include('layouts.partials.footer')
    @show

    @stack('scripts')

</body>
</html>