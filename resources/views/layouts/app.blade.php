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

    {{-- Tailwind lewat CDN — WAJIB dipertahankan (bukan @vite), sesuai
         proposal BAB III yang sudah di-ACC. Warning
         "cdn.tailwindcss.com should not be used in production" di
         console browser MEMANG akan tetap muncul — itu sudah sesuai
         proposal dan bukan sesuatu yang perlu dihilangkan. --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fraunces:wght@600..900&display=swap"
        rel="stylesheet"
    >

    {{-- Design token tunggal untuk seluruh aplikasi. brand.500/600/700
         SENGAJA disamakan persis dengan hex yang sudah beredar di semua
         halaman (--red-bright/--red/--red-700 lama) supaya tidak ada
         perubahan visual saat halaman lain menyusul pindah ke layout ini. --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        // Font UI/body — tidak berubah dari yang sudah dipakai.
                        sans: ['Inter', 'sans-serif'],

                        // Untuk headline halaman publik/katalog ke depan
                        // (belum dipakai di halaman manapun saat ini).
                        display: ['Fraunces', 'serif'],
                    },

                    colors: {
                        // Skala penuh warna aksen sekolah (identitas resmi).
                        // Dulunya cuma ada sebagai variabel CSS
                        // --red / --red-bright.
                        brand: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                            950: '#450a0a',
                        },
                    },

                    spacing: {
                        // Lebar sidebar guru/siswa — sebelumnya angka
                        // "260px" ditulis ulang manual di banyak file.
                        sidebar: '260px',
                    },

                    borderRadius: {
                        xs: '4px',
                        sm: '8px',
                        md: '12px',
                        lg: '16px',
                        xl: '20px',
                        '2xl': '28px',
                        pill: '999px',
                    },
                },
            },
        };
    </script>

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