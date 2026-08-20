<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'DKV SMEKDA — Digital Art Showcase & Exhibition Archive')</title>

    {{-- Meta tags khusus halaman child --}}
    @stack('meta')

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Asset Tailwind CSS & JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Typography Kurasi: Fraunces (Editorial Display), Inter (Clean Sans), & JetBrains Mono (Archival Labels) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet"
    >

    {{-- Custom styles per halaman --}}
    @stack('styles')
</head>

<body class="min-h-screen bg-[#FAF7F2] text-[#191816] font-sans antialiased selection:bg-[#7A2E2E] selection:text-[#FAF7F2] flex flex-col justify-between">

    @section('navbar')
        @include('layouts.partials.navbar')
    @show

    <main class="flex-grow">
        @yield('content')
    </main>

    @section('footer')
        @include('layouts.partials.footer')
    @show

    @stack('scripts')

</body>
</html>