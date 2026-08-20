<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', 'DKV SMEKDA — Digital Art Showcase & Exhibition Archive')</title>

    @stack('meta')

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet"
    >

    @stack('styles')

    <script>
        (() => {
            const saved = localStorage.getItem('dkv-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body class="min-h-screen bg-[var(--theme-bg)] text-[var(--theme-text)] font-sans antialiased flex flex-col justify-between transition-colors duration-200">
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