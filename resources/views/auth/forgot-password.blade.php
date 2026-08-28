@extends('layouts.app')

@section('title', 'Lupa Password — DKV SMEKDA Portal')

@section('navbar')@endsection
@section('footer')@endsection

@push('styles')
<style>

        :root {

            --ink-soft:      #6B615A;
            --ink-faint:     #A79E93;
            --oxblood-deep:  #5B2020;
            --oxblood-wash:  rgba(122, 46, 46, 0.07);
            --oxblood-ring:  rgba(122, 46, 46, 0.16);
            --cream:         #F6F0E4;
            --cream-soft:    rgba(246, 240, 228, 0.64);
            --line:          rgba(28, 25, 23, 0.13);
            --success:       #3F6B4A;
            --success-wash:  rgba(63, 107, 74, 0.08);

            --font-display: var(--font-serif);

            /* Skala radius terkunci di seluruh halaman: 4px elemen kecil,
               8px input & tombol, 16px hanya untuk card mobile. */
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }

        body {
            font-family: var(--font-sans);
            background: var(--color-paper);
            color: var(--color-ink);
            -webkit-font-smoothing: antialiased;
            padding: 14px;
        }
        @media (min-width: 768px) {
            body { padding-left: 38px; }
        }

        .grain-overlay {
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.4'/%3E%3C/svg%3E");
            opacity: 0.025;
            mix-blend-mode: multiply;
            pointer-events: none;
            z-index: 40;
        }

        .spine-label {
            position: fixed;
            left: 6px; top: 14px; bottom: 14px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            font-family: var(--font-mono);
            font-size: 0.62rem;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--color-ink-faint);
            pointer-events: none;
            z-index: 2;
        }
        @media (max-width: 767px) { .spine-label { display: none; } }

        .stage {
            position: relative;
            min-height: calc(100dvh - 28px);
            display: grid;
            grid-template-columns: minmax(0, 0.92fr) minmax(0, 1fr);
            border: 1px solid rgba(122, 46, 46, 0.16);
            z-index: 1;
        }

        .gallery-panel {
            position: relative;
            background: var(--color-accent-600);
            background-image:
                radial-gradient(ellipse 620px 460px at 12% -8%, rgba(255,255,255,0.07), transparent 60%),
                radial-gradient(ellipse 520px 520px at 100% 118%, rgba(0,0,0,0.22), transparent 62%),
                repeating-linear-gradient(0deg, rgba(246,240,228,0.05) 0, rgba(246,240,228,0.05) 1px, transparent 1px, transparent 40px),
                repeating-linear-gradient(90deg, rgba(246,240,228,0.05) 0, rgba(246,240,228,0.05) 1px, transparent 1px, transparent 40px);
            color: var(--cream);
            padding: 52px 48px 36px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            animation: panelRise 700ms cubic-bezier(0.16,1,0.3,1) both;
        }

        .gallery-eyebrow {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--cream-soft);
        }

        .gallery-headline {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: clamp(2.5rem, 4.2vw, 4.25rem);
            line-height: 1.05;
            letter-spacing: -0.01em;
            margin-top: 20px;
            text-wrap: balance;
        }
        .gallery-headline em { font-style: italic; font-weight: 500; color: var(--cream); }

        .gallery-sub {
            font-size: 0.94rem;
            line-height: 1.7;
            color: var(--cream-soft);
            max-width: 32ch;
            margin-top: 22px;
        }

        .wall-label {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            letter-spacing: 0.05em;
            color: var(--cream-soft);
            border-top: 1px solid rgba(246,240,228,0.18);
            padding-top: 18px;
            display: grid;
            row-gap: 6px;
        }
        .wall-label .status-line { display: flex; align-items: center; gap: 8px; color: var(--cream); font-weight: 500; }
        .status-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--cream);
            flex-shrink: 0;
            animation: livePulse 1.6s ease-in-out infinite;
        }

        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 44px 28px;
            min-height: 100dvh;
        }
        .form-shell {
            width: 100%;
            max-width: 400px;
        }
        .form-shell > * { animation: panelRise 600ms cubic-bezier(0.16,1,0.3,1) both; }
        .form-shell > *:nth-child(1) { animation-delay: 60ms; }
        .form-shell > *:nth-child(2) { animation-delay: 110ms; }
        .form-shell > *:nth-child(3) { animation-delay: 160ms; }
        .form-shell > *:nth-child(4) { animation-delay: 200ms; }
        .form-shell > *:nth-child(5) { animation-delay: 240ms; }
        .form-shell > *:nth-child(6) { animation-delay: 280ms; }
        .form-shell > *:nth-child(7) { animation-delay: 320ms; }
        .form-shell > *:nth-child(8) { animation-delay: 360ms; }
        .form-shell > *:nth-child(9) { animation-delay: 400ms; }

        @keyframes panelRise {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.35; transform: scale(0.7); }
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--color-ink-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .back-link:hover { color: var(--color-accent-600); }
        .back-link svg { width: 13px; height: 13px; }

        .brandmark { display: flex; align-items: center; gap: 9px; margin-top: 26px; margin-bottom: 30px; }
        .brandmark img {
            width: 26px; height: 26px;
            object-fit: contain;
            border: 1px solid var(--color-paper-border);
            border-radius: var(--radius-xs);
            padding: 3px;
            background: var(--color-paper-elevated);
        }
        .brandmark-text { font-family: var(--font-mono); font-size: 0.68rem; letter-spacing: 0.16em; text-transform: uppercase; color: var(--color-ink-muted); }
        .brandmark-text b { color: var(--color-ink); font-weight: 600; }

        .form-headline { font-family: var(--font-display); font-size: 2rem; font-weight: 600; line-height: 1.18; color: var(--color-ink); }
        .form-headline em { font-style: italic; font-weight: 500; color: var(--color-accent-600); }
        .form-sub { font-size: 0.88rem; color: var(--color-ink-muted); line-height: 1.6; margin-top: 10px; margin-bottom: 30px; }

        .status-box, .error-alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px;
            border-radius: var(--radius-xs);
            font-size: 0.82rem; line-height: 1.5;
            margin-bottom: 20px;
        }
        .status-box { background: var(--success-wash); border-left: 2px solid var(--success); color: var(--success); font-weight: 500; }
        .error-alert { background: var(--color-accent-50); border-left: 2px solid var(--color-accent-600); color: var(--color-accent-700); font-weight: 500; }
        .status-box svg, .error-alert svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }

        .form-divider { display: flex; align-items: center; gap: 12px; margin-bottom: 26px; }
        .form-divider-line { flex: 1; height: 1px; background: var(--color-paper-border); }
        .form-divider-text { font-family: var(--font-mono); font-size: 0.64rem; font-weight: 500; letter-spacing: 0.14em; text-transform: uppercase; color: var(--color-ink-faint); }

        .field-wrap { margin-bottom: 18px; }
        .field-label {
            display: block;
            font-family: var(--font-mono);
            font-size: 0.66rem; font-weight: 500;
            letter-spacing: 0.14em; text-transform: uppercase;
            color: var(--color-ink-muted);
            margin-bottom: 8px;
        }
        .input-icon-wrap { position: relative; }
        .input-icon {
            position: absolute; top: 50%; left: 15px;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--color-ink-faint);
            pointer-events: none;
            transition: color 0.2s ease;
        }
        .input-icon-wrap:focus-within .input-icon { color: var(--color-accent-600); }

        .input-field {
            width: 100%;
            font-family: var(--font-sans);
            font-size: 0.92rem; font-weight: 500;
            color: var(--color-ink);
            background: var(--color-paper-elevated);
            border: 1.5px solid var(--color-paper-border);
            border-radius: var(--radius-sm);
            padding: 13px 16px 13px 43px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .input-field::placeholder { color: var(--color-ink-faint); font-weight: 400; }
        .input-field:focus { border-color: var(--color-accent-600); box-shadow: 0 0 0 3px var(--color-accent-200); }
        .input-field.is-error { border-color: var(--color-accent-600); background: var(--color-accent-50); }

        .field-error { display: flex; align-items: center; gap: 6px; margin-top: 7px; font-size: 0.74rem; font-weight: 500; color: var(--color-accent-700); }
        .field-error svg { width: 13px; height: 13px; flex-shrink: 0; }

        .btn-submit {
            width: 100%;
            display: flex; align-items: center; justify-content: space-between;
            font-family: var(--font-sans);
            font-size: 0.88rem; font-weight: 600; letter-spacing: 0.01em;
            color: var(--color-paper);
            background: var(--color-ink);
            border: none;
            border-radius: var(--radius-sm);
            padding: 6px 6px 6px 22px;
            cursor: pointer;
            transition: background 0.25s cubic-bezier(0.2,0.7,0.3,1), transform 0.2s cubic-bezier(0.2,0.7,0.3,1), box-shadow 0.25s ease;
        }
        .btn-submit:hover { background: var(--color-accent-600); transform: translateY(-2px); box-shadow: 0 12px 28px -10px rgba(122,46,46,0.5); }
        .btn-submit:active { transform: translateY(0) scale(0.98); }
        .btn-submit-label { padding: 8px 0; }

        .btn-icon {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: rgba(250,247,242,0.14);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: transform 0.3s cubic-bezier(0.2,0.7,0.3,1), background 0.25s ease;
        }
        .btn-submit:hover .btn-icon { transform: translateX(3px); background: rgba(250,247,242,0.22); }
        .btn-icon svg { width: 15px; height: 15px; }
        .icon-spinner { display: none; animation: spin 0.8s linear infinite; }
        .btn-submit.is-loading { cursor: default; opacity: 0.9; }
        .btn-submit.is-loading .icon-arrow { display: none; }
        .btn-submit.is-loading .icon-spinner { display: block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .bottom-link { margin-top: 26px; text-align: center; font-size: 0.82rem; color: var(--color-ink-faint); }
        .bottom-link a { color: var(--color-ink); font-weight: 600; text-decoration: none; }
        .bottom-link a:hover { color: var(--color-accent-600); }

        .page-footer {
            margin-top: 30px;
            text-align: center;
            font-family: var(--font-mono);
            font-size: 0.66rem;
            letter-spacing: 0.02em;
            color: var(--color-ink-faint);
            line-height: 1.8;
        }
        .page-footer .accent { color: var(--color-accent-600); }

        a:focus-visible, button:focus-visible, input:focus-visible {
            outline: 2px solid var(--color-accent-600);
            outline-offset: 2px;
        }

        @media (max-width: 967px) {
            .stage { grid-template-columns: 1fr; }

            .gallery-panel { padding: 30px 22px 26px; }
            .gallery-headline { font-size: clamp(2rem, 9vw, 2.6rem); margin-top: 14px; }
            .gallery-sub { display: none; }
            .wall-label { margin-top: 18px; padding-top: 14px; font-size: 0.62rem; }

            .form-panel { padding: 32px 20px 44px; min-height: auto; }
            .form-shell {
                background: var(--color-paper-elevated);
                border: 1px solid var(--color-paper-border);
                border-radius: var(--radius-lg);
                padding: 30px 26px;
                box-shadow: 0 28px 60px -34px rgba(28,25,23,0.35);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
            }
        }

    </style>
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
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Negeri 2 Padang Panjang">
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