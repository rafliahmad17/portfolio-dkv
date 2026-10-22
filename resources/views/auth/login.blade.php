@extends('layouts.app')

@section('title', 'Login — DKV SMEKDA Portal')

{{-- Halaman login punya komposisi sendiri (split-screen katalog + panel form)
     yang menggantikan navbar/footer bawaan layout, sama seperti pola di
     guru/dashboard.blade.php. --}}
@section('navbar')@endsection
@section('footer')@endsection

@push('styles')
@vite('resources/css/components/auth.css')
@endpush

@section('content')
<div class="grain-overlay"></div>
<div class="spine-label">Sistem Portofolio Digital &mdash; DKV SMEKDA &mdash; {{ date('Y') }}</div>

<div class="stage">

    {{-- ===== PANEL KIRI — "sampul katalog" ===== --}}
    <div class="gallery-panel">
        <div>
            <p class="gallery-eyebrow">SMK Negeri 2 Padang Panjang &mdash; Jurusan DKV</p>
            <h1 class="gallery-headline">DKV Archive<br><em>// Access</em></h1>
            <p class="gallery-sub">Ruang kerja digital untuk memaparkan hasil rancang, mendokumentasikan proses, dan mengarsipkan karya siswa Desain Komunikasi Visual.</p>
        </div>

        <div class="wall-label">
            <span class="status-line"><span class="status-dot"></span> Status &mdash; Sistem Aktif</span>
            <span>Katalog No. 002 &mdash; Autentikasi</span>
            <span>Medium: Sistem Portofolio Digital</span>
            <span>Tahun {{ date('Y') }}</span>
        </div>
    </div>

    {{-- ===== PANEL KANAN — form login ===== --}}
    <div class="form-panel">
        <div class="form-shell">

            <a href="{{ url('/') }}" class="back-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>

            <div class="brandmark">
                <x-school-logo />
                <span class="brandmark-text"><b>DKV SMEKDA</b> / Portal</span>
            </div>

            <h2 class="form-headline">Selamat datang<br>kembali, <em>Kreator.</em></h2>
            <p class="form-sub">Masuk untuk mengelola arsip portofolio digital Anda.</p>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="status-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Global Error Alert --}}
            @if ($errors->any())
                <div class="error-alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>
                        @error('email') {{ $message }} @enderror
                        @error('password') {{ $message }} @enderror
                    </span>
                </div>
            @endif

            <div class="form-divider">
                <div class="form-divider-line"></div>
                <div class="form-divider-text">Masuk dengan akun Anda</div>
                <div class="form-divider-line"></div>
            </div>

            {{-- ===== FORM ===== --}}
            <form method="POST" action="{{ route('login.post') }}" novalidate id="login-form">
                @csrf

                {{-- Email --}}
                <div class="field-wrap">
                    <label for="email" class="field-label">Alamat Email</label>
                    <div class="input-icon-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@smkn2.sch.id"
                            autocomplete="email"
                            autofocus
                            class="input-field {{ $errors->has('email') ? 'is-error' : '' }}"
                        >
                    </div>
                    @error('email')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field-wrap">
                    <label for="password" class="field-label">Password</label>
                    <div class="input-icon-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••••"
                            autocomplete="current-password"
                            class="input-field {{ $errors->has('password') ? 'is-error' : '' }}"
                            style="padding-right: 44px;"
                        >
                        <button type="button" class="pw-toggle" id="pw-toggle" aria-label="Tampilkan atau sembunyikan password">
                            <svg id="eye-icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-icon-hide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="display:none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="remember-row">
                    <label class="remember-label">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="remember-checkbox"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="remember-text">Ingat Saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Lupa Password?
                    </a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit" id="btn-submit">
                    <span class="btn-submit-label">Masuk ke Arsip</span>
                    <span class="btn-icon">
                        <svg class="icon-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <svg class="icon-spinner" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-dasharray="42 100"/>
                        </svg>
                    </span>
                </button>
            </form>

            {{-- Bottom Register Link --}}
            <div class="bottom-link">
                Belum punya akun? <a href="{{ route('login') }}">Daftar sebagai Siswa &rarr;</a>
            </div>

            <p class="page-footer">
                &copy; {{ date('Y') }} DKV SMEKDA &bull; SMK Negeri 2 Padang Panjang<br>
                <span class="accent">Sistem Portofolio Digital</span> &bull; Dikembangkan oleh Rafli &mdash; 2026
            </p>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Toggle tampil/sembunyi password
    const pwToggle = document.getElementById('pw-toggle');
    const pwInput  = document.getElementById('password');
    const eyeShow  = document.getElementById('eye-icon-show');
    const eyeHide  = document.getElementById('eye-icon-hide');

    pwToggle.addEventListener('click', () => {
        const isHidden = pwInput.type === 'password';
        pwInput.type   = isHidden ? 'text' : 'password';
        eyeShow.style.display = isHidden ? 'none'  : 'block';
        eyeHide.style.display = isHidden ? 'block' : 'none';
    });

    // Status memproses saat submit, mencegah klik ganda
    const loginForm  = document.getElementById('login-form');
    const submitBtn  = document.getElementById('btn-submit');
    const submitText = submitBtn.querySelector('.btn-submit-label');

    loginForm.addEventListener('submit', () => {
        submitBtn.classList.add('is-loading');
        submitText.textContent = 'Memproses…';
    });
</script>
 @endpush
