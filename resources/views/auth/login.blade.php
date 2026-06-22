<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DKV SMEKDA Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        :root {
            --red:        #dc2626;
            --red-bright: #ef4444;
            --red-glow:   rgba(220, 38, 38, 0.5);
            --red-soft:   rgba(220, 38, 38, 0.12);
            --red-border: rgba(220, 38, 38, 0.55);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #080808;
            color: #f5f5f5;
            overflow-x: hidden;
        }

        /* ── NOISE OVERLAY ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* ── GRID ── */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(220,38,38,0.045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220,38,38,0.045) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── AMBIENT GLOW BLOBS ── */
        .blob-center {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 820px; height: 820px;
            background: radial-gradient(circle, rgba(220,38,38,0.13) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
            animation: blobBreath 7s ease-in-out infinite alternate;
        }

        .blob-top-right {
            position: fixed;
            top: -180px; right: -180px;
            width: 520px; height: 520px;
            background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
            animation: blobBreath 9s ease-in-out infinite alternate-reverse;
        }

        .blob-bottom-left {
            position: fixed;
            bottom: -150px; left: -150px;
            width: 460px; height: 460px;
            background: radial-gradient(circle, rgba(220,38,38,0.07) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
            animation: blobBreath 11s ease-in-out infinite alternate;
        }

        @keyframes blobBreath {
            0%   { transform: translate(-50%,-50%) scale(1);    opacity: 0.8; }
            100% { transform: translate(-50%,-50%) scale(1.18); opacity: 1;   }
        }

        .blob-top-right, .blob-bottom-left {
            transform: none;
            animation: blobFloat 9s ease-in-out infinite alternate;
        }

        @keyframes blobFloat {
            0%   { transform: scale(1)    translate(0,0);      opacity: 0.7; }
            100% { transform: scale(1.15) translate(16px,12px); opacity: 1;  }
        }

        /* ── WRAPPER ── */
        .page-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        /* ── LOGIN CARD ── */
        .login-card {
            width: 100%;
            max-width: 460px;
            background: rgba(12, 12, 12, 0.72);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 24px;
            padding: 44px 40px;
            box-shadow:
                0 0 0 1px rgba(220,38,38,0.06),
                0 32px 80px rgba(0,0,0,0.6),
                0 0 60px rgba(220,38,38,0.08);
            transition: box-shadow 0.4s ease, border-color 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220,38,38,0.6), transparent);
        }

        .login-card:hover {
            border-color: rgba(220,38,38,0.2);
            box-shadow:
                0 0 0 1px rgba(220,38,38,0.15),
                0 32px 80px rgba(0,0,0,0.65),
                0 0 80px rgba(220,38,38,0.14);
        }

        /* ── LOGO ── */
        .logo-text {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 32px;
        }

        .logo-dot {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px; height: 22px;
            background: var(--red);
            border-radius: 6px;
            box-shadow: 0 0 14px var(--red-glow);
        }

        .logo-dot svg { width: 12px; height: 12px; }

        /* ── HEADLINE ── */
        .headline {
            font-size: 1.85rem;
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: -1px;
            color: #f5f5f5;
            margin-bottom: 10px;
        }

        .headline .kreator {
            color: var(--red);
            text-shadow: 0 0 30px rgba(220,38,38,0.5);
            position: relative;
            display: inline-block;
        }

        .sub-headline {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.35);
            line-height: 1.6;
            margin-bottom: 36px;
        }

        /* ── DIVIDER ── */
        .form-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .form-divider-line {
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.06);
        }

        .form-divider-text {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.18);
        }

        /* ── INPUT WRAPPER ── */
        .field-wrap {
            position: relative;
            margin-bottom: 18px;
        }

        .field-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 8px;
        }

        .input-icon-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%; left: 16px;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.2);
            transition: color 0.25s ease;
            pointer-events: none;
            width: 16px; height: 16px;
        }

        .input-field {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.09);
            border-radius: 12px;
            padding: 13px 16px 13px 44px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #f5f5f5;
            outline: none;
            transition: all 0.25s ease;
            caret-color: var(--red);
        }

        .input-field::placeholder {
            color: rgba(255,255,255,0.18);
            font-weight: 400;
        }

        .input-field:focus {
            border-color: var(--red);
            background: rgba(220,38,38,0.06);
            box-shadow:
                0 0 0 3px rgba(220,38,38,0.18),
                0 0 20px rgba(220,38,38,0.12);
        }

        .input-field:focus + .input-icon,
        .input-icon-wrap:focus-within .input-icon {
            color: var(--red);
        }

        .input-field.is-error {
            border-color: var(--red-bright);
            background: rgba(239,68,68,0.07);
            box-shadow: 0 0 0 3px rgba(239,68,68,0.16);
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            top: 50%; right: 14px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,0.2);
            transition: color 0.2s ease;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .pw-toggle:hover { color: rgba(255,255,255,0.55); }

        /* ── ERROR MESSAGE ── */
        .field-error {
            margin-top: 7px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #f87171;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .field-error svg { width: 13px; height: 13px; flex-shrink: 0; }

        /* ── REMEMBER + FORGOT ── */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox {
            width: 16px; height: 16px;
            border-radius: 4px;
            border: 1.5px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.04);
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .remember-checkbox:checked {
            background: var(--red);
            border-color: var(--red);
            box-shadow: 0 0 10px rgba(220,38,38,0.4);
        }

        .remember-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 2px; left: 5px;
            width: 4px; height: 7px;
            border: 2px solid white;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .remember-text {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.35);
            font-weight: 500;
        }

        .forgot-link {
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.3);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover { color: var(--red-bright); }

        /* ── SUBMIT BUTTON ── */
        .btn-submit {
            width: 100%;
            background: var(--red);
            color: white;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            padding: 15px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(220,38,38,0.3);
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #b91c1c, #ef4444);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(220,38,38,0.55), 0 0 0 4px rgba(220,38,38,0.15);
        }

        .btn-submit:hover::before { opacity: 1; }

        .btn-submit:active { transform: translateY(0); }

        .btn-submit span,
        .btn-submit svg { position: relative; z-index: 1; }

        .btn-submit svg {
            width: 17px; height: 17px;
            transition: transform 0.3s ease;
        }

        .btn-submit:hover svg { transform: translateX(4px); }

        /* ── BOTTOM LINK ── */
        .bottom-link {
            margin-top: 28px;
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.25);
        }

        .bottom-link a {
            color: rgba(255,255,255,0.45);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .bottom-link a:hover { color: var(--red-bright); }

        /* ── SESSION STATUS ── */
        .status-box {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: 10px;
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.25);
            font-size: 0.82rem;
            color: #86efac;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-box svg { width: 15px; height: 15px; flex-shrink: 0; color: #4ade80; }

        /* ── GLOBAL ERROR ALERT ── */
        .error-alert {
            margin-bottom: 22px;
            padding: 13px 16px;
            border-radius: 12px;
            background: rgba(220,38,38,0.1);
            border: 1px solid rgba(220,38,38,0.28);
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .error-alert svg { width: 16px; height: 16px; flex-shrink: 0; color: #f87171; margin-top: 1px; }

        .error-alert-text {
            font-size: 0.82rem;
            font-weight: 600;
            color: #fca5a5;
            line-height: 1.5;
        }

        /* ── CORNER DECORATION ── */
        .corner-deco {
            position: absolute;
            bottom: 0; right: 0;
            width: 120px; height: 120px;
            pointer-events: none;
        }

        .corner-deco::before {
            content: '';
            position: absolute;
            bottom: 0; right: 0;
            width: 60px; height: 60px;
            border-bottom-right-radius: 24px;
            border-bottom: 2px solid rgba(220,38,38,0.12);
            border-right: 2px solid rgba(220,38,38,0.12);
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #080808; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        /* ── PULSE RING ANIMATION ── */
        @keyframes focusPulse {
            0%   { box-shadow: 0 0 0 0 rgba(220,38,38,0.4), 0 0 20px rgba(220,38,38,0.12); }
            70%  { box-shadow: 0 0 0 6px rgba(220,38,38,0), 0 0 20px rgba(220,38,38,0.12); }
            100% { box-shadow: 0 0 0 0 rgba(220,38,38,0), 0 0 20px rgba(220,38,38,0.12); }
        }

        .input-field:focus {
            border-color: var(--red);
            background: rgba(220,38,38,0.06);
            animation: focusPulse 1.5s ease-out;
        }

        /* ── FLOATING BADGE ── */
        .floating-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: #0f0f0f;
            border: 1px solid rgba(220,38,38,0.25);
            border-radius: 30px;
            padding: 5px 16px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        .floating-badge .live-dot {
            width: 5px; height: 5px;
            background: var(--red);
            border-radius: 50%;
            animation: livePulse 1.4s ease-in-out infinite;
            box-shadow: 0 0 6px var(--red-glow);
        }

        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.3; transform: scale(0.65); }
        }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="blob-center"></div>
<div class="blob-top-right"></div>
<div class="blob-bottom-left"></div>

<div class="page-wrapper">
    <div style="width:100%; max-width:460px; position:relative; padding-top:14px;">

        {{-- Floating Badge --}}
        <div class="floating-badge">
            <div class="live-dot"></div>
            Sistem Aktif &bull; SMKN 2 Padang Panjang
        </div>

        {{-- ===== LOGIN CARD ===== --}}
        <div class="login-card">
            <div class="corner-deco"></div>

            {{-- Logo --}}
            <div class="logo-text">
                <div class="logo-dot">
                    <svg fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                DKV SMEKDA <span style="color: rgba(255,255,255,0.12);">/</span> Portal
            </div>

            {{-- Headline --}}
            <h1 class="headline">
                Selamat Datang<br>Kembali, <span class="kreator">Kreator.</span>
            </h1>
            <p class="sub-headline">Masuk untuk mengelola portofolio digital Anda.</p>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="status-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Global Error Alert --}}
            @if ($errors->any())
                <div class="error-alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="error-alert-text">
                        @error('email') {{ $message }} @enderror
                        @error('password') {{ $message }} @enderror
                    </div>
                </div>
            @endif

            {{-- Divider --}}
            <div class="form-divider">
                <div class="form-divider-line"></div>
                <div class="form-divider-text">Masuk dengan akun Anda</div>
                <div class="form-divider-line"></div>
            </div>

            {{-- ===== FORM ===== --}}
            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="field-wrap">
                    <label for="email" class="field-label">Alamat Email</label>
                    <div class="input-icon-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
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
                        <svg class="input-icon" id="lock-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
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
                        <button type="button" class="pw-toggle" id="pw-toggle" tabindex="-1" aria-label="Toggle password">
                            <svg id="eye-icon-show" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-icon-hide" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
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
                    <a href="#" class="forgot-link">Lupa Password?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">
                    <span>Masuk ke Platform</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </button>

            </form>

            {{-- Bottom Register Link --}}
            <div class="bottom-link">
                Belum punya akun? &nbsp;
                <a href="{{ route('login') }}">Daftar sebagai Siswa &rarr;</a>
            </div>

        </div>

        {{-- Footer below card --}}
        <div style="margin-top:28px; text-align:center;">
            <p style="font-size:0.72rem; color:rgba(255,255,255,0.15); line-height:1.8;">
                &copy; {{ date('Y') }} &nbsp;<span style="color:rgba(255,255,255,0.28); font-weight:600;">DKV SMEKDA</span>&nbsp;
                &bull; SMK Negeri 2 Padang Panjang<br>
                <span style="color:rgba(220,38,38,0.4);">Sistem Portofolio Digital</span>
                &bull; Dikembangkan oleh Rafli &mdash; 2026
            </p>
        </div>

    </div>
</div>

<script>
    // ── PASSWORD TOGGLE ──
    const pwToggle  = document.getElementById('pw-toggle');
    const pwInput   = document.getElementById('password');
    const eyeShow   = document.getElementById('eye-icon-show');
    const eyeHide   = document.getElementById('eye-icon-hide');

    pwToggle.addEventListener('click', () => {
        const isHidden = pwInput.type === 'password';
        pwInput.type   = isHidden ? 'text' : 'password';
        eyeShow.style.display = isHidden ? 'none'  : 'block';
        eyeHide.style.display = isHidden ? 'block' : 'none';
    });

    // ── INPUT ICON COLOR ON FOCUS ──
    document.querySelectorAll('.input-field').forEach(input => {
        const wrap = input.closest('.input-icon-wrap');
        const icon = wrap?.querySelector('.input-icon');

        input.addEventListener('focus', () => {
            if (icon) icon.style.color = '#dc2626';
        });

        input.addEventListener('blur', () => {
            if (icon) icon.style.color = 'rgba(255,255,255,0.2)';
        });
    });
</script>

</body>
</html>