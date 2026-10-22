@extends('layouts.app')

@section('title', 'Lupa Password — DKV SMEKDA Portal')

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
            <h1 class="gallery-headline">DKV Archive<br><em>// Reset</em></h1>
            <p class="gallery-sub">Masukkan email akun Anda. Jika email terdaftar, kami akan mengirimkan tautan untuk mereset password.</p>
        </div>

        <div class="wall-label">
            <span class="status-line"><span class="status-dot"></span> Status &mdash; Sistem Aktif</span>
            <span>Katalog No. 002 &mdash; Autentikasi</span>
            <span>Medium: Sistem Portofolio Digital</span>
            <span>Tahun {{ date('Y') }}</span>
        </div>
    </div>

    {{-- ===== PANEL KANAN — form forgot password ===== --}}
    <div class="form-panel">
        <div class="form-shell">

            <a href="{{ route('login') }}" class="back-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Login
            </a>

            <div class="brandmark">
                <x-school-logo />
                <span class="brandmark-text"><b>DKV SMEKDA</b> / Portal</span>
            </div>

            <h2 class="form-headline">Lupa<br><em>Password?</em></h2>
            <p class="form-sub">Masukkan alamat email Anda untuk menerima tautan reset password.</p>

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
                    </span>
                </div>
            @endif

            <div class="form-divider">
                <div class="form-divider-line"></div>
                <div class="form-divider-text">Kirim Tautan Reset</div>
                <div class="form-divider-line"></div>
            </div>

            {{-- ===== FORM ===== --}}
            <form method="POST" action="{{ route('password.email') }}" novalidate id="forgot-form">
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

                {{-- Submit --}}
                <button type="submit" class="btn-submit" id="btn-submit">
                    <span class="btn-submit-label">Kirim Tautan Reset</span>
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

            {{-- Bottom Login Link --}}
            <div class="bottom-link">
                Ingat password? <a href="{{ route('login') }}">Masuk ke Arsip &rarr;</a>
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
    // Status memproses saat submit, mencegah klik ganda
    const forgotForm  = document.getElementById('forgot-form');
    const submitBtn   = document.getElementById('btn-submit');
    const submitText  = submitBtn.querySelector('.btn-submit-label');

    forgotForm.addEventListener('submit', () => {
        submitBtn.classList.add('is-loading');
        submitText.textContent = 'Mengirim…';
    });
</script>
@endpush