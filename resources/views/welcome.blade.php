{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Etalase portofolio digital resmi siswa Desain Komunikasi Visual SMKN 2 Padang Panjang. Arsip karya tersimpan rapi, siap dicetak, siap dipresentasikan.">
    <meta name="theme-color" content="#FAF7F2">
    <title>DKV SMEKDA — Etalase Portofolio Digital</title>

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='7' fill='%23FAF7F2'/%3E%3Cpath d='M16 5l9 4.5v6c0 6.5-4 10.5-9 11.5-5-1-9-5-9-11.5v-6L16 5z' fill='none' stroke='%237A2E2E' stroke-width='1.6'/%3E%3C/svg%3E">

    <meta property="og:type" content="website">
    <meta property="og:title" content="DKV SMEKDA — Etalase Portofolio Digital">
    <meta property="og:description" content="Etalase portofolio digital resmi siswa Desain Komunikasi Visual SMKN 2 Padang Panjang. Arsip karya tersimpan rapi, siap dicetak, siap dipresentasikan.">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="DKV SMEKDA — Etalase Portofolio Digital">
    <meta name="twitter:description" content="Etalase portofolio digital resmi siswa Desain Komunikasi Visual SMKN 2 Padang Panjang.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400;1,500;1,600&family=Public+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* ==========================================================
           DESIGN TOKENS — Editorial Light (ivory / charcoal / oxblood)
           Catatan: token ini ditulis sebagai CSS variable di file ini
           sendiri (seperti pola file aslinya), mengikuti palet yang
           sudah dipakai di app.css & navbar (Tahap 1–2). Kalau app.css
           sudah punya token Tailwind resmi untuk warna ini, nilai di
           bawah tinggal diarahkan ke token itu.
        ========================================================== */
        :root {
            --ivory: #FAF7F2;
            --surface: #FFFFFF;
            --charcoal: #1C1A17;
            --charcoal-soft: rgba(28, 26, 23, 0.62);
            --charcoal-faint: rgba(28, 26, 23, 0.66);
            --oxblood: #7A2E2E;
            --oxblood-dark: #5E2222;
            --oxblood-soft: rgba(122, 46, 46, 0.08);
            --oxblood-line: rgba(122, 46, 46, 0.24);
            --hairline: rgba(28, 26, 23, 0.13);
            --hairline-soft: rgba(28, 26, 23, 0.07);
            --ochre: #B98B4E;
            --sage: #6E7A5E;
            --gold: #A8823C;
            --gold-line: rgba(168, 130, 60, 0.35);
            --ink: #0F0D0B;
            --shadow-warm: 0 30px 70px -20px rgba(28, 26, 23, 0.18);
            --shadow-soft: 0 2px 10px rgba(28, 26, 23, 0.05);
            --font-display: var(--font-serif);
            --font-body: var(--font-sans);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            * { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
        }

        body {
            background-color: var(--ivory);
            color: var(--charcoal);
            font-family: var(--font-body);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--ivory); }
        ::-webkit-scrollbar-thumb { background: var(--oxblood-line); border-radius: 10px; }

        /* Skip link — a11y */
        .skip-link {
            position: absolute;
            top: -48px; left: 12px;
            background: var(--charcoal);
            color: var(--ivory);
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 200;
            transition: top 0.2s ease;
        }
        .skip-link:focus { top: 12px; }

        :focus-visible {
            outline: 2px solid var(--oxblood);
            outline-offset: 3px;
        }

        /* Fixed paper-grain overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
            opacity: 0.035;
            mix-blend-mode: multiply;
        }

        /* Scroll progress */
        .scroll-progress {
            position: fixed;
            top: 0; left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, var(--oxblood), var(--gold));
            z-index: 150;
            transition: width 0.1s linear;
        }

        /* Back to top */
        .back-to-top {
            position: fixed;
            bottom: 24px; right: 24px;
            width: 46px; height: 46px;
            border-radius: 50%;
            background: var(--charcoal);
            color: var(--ivory);
            border: none;
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-warm);
            opacity: 0; visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            z-index: 90;
            cursor: pointer;
        }
        .back-to-top.visible { opacity: 1; visibility: visible; transform: translateY(0); }
        .back-to-top:hover { background: var(--oxblood); }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--oxblood);
        }
        .eyebrow svg { flex-shrink: 0; }

        /* ====== NAVBAR ====== */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 20px 0;
            transition: all 0.35s ease;
            border-bottom: 1px solid transparent;
        }

        .navbar.scrolled {
            background: rgba(250, 247, 242, 0.86);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--hairline);
            padding: 13px 0;
        }

        .nav-inner { display: flex; align-items: center; justify-content: space-between; }

        .nav-logo {
            display: flex; align-items: center; gap: 10px;
            font-family: var(--font-display);
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            color: var(--charcoal);
            text-decoration: none;
        }

        .nav-logo-mark {
            width: 32px; height: 32px;
            background: var(--surface);
            border: 1px solid var(--hairline);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .nav-logo-mark img { width: 100%; height: 100%; object-fit: contain; padding: 3px; }
        .nav-logo em { font-style: italic; color: var(--oxblood); }

        .nav-actions { display: flex; align-items: center; gap: 10px; }

        .btn-ghost {
            border: 1px solid var(--hairline);
            color: var(--charcoal);
            padding: 9px 20px;
            border-radius: 7px;
            font-size: 0.84rem;
            font-weight: 600;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            background: transparent;
        }
        .btn-ghost:hover { border-color: var(--charcoal); background: var(--hairline-soft); }

        .btn-solid {
            background: var(--oxblood);
            color: var(--ivory);
            padding: 9px 20px;
            border-radius: 7px;
            font-size: 0.84rem;
            font-weight: 600;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            border: 1px solid var(--oxblood);
        }
        .btn-solid:hover { background: var(--oxblood-dark); border-color: var(--oxblood-dark); transform: translateY(-1px); box-shadow: var(--shadow-soft); }

        /* ====== HERO ====== */
        .hero {
            min-height: 100dvh;
            display: flex;
            align-items: center;
            padding-top: 110px;
            padding-bottom: 60px;
            position: relative;
            overflow: hidden;
        }

        .hero-glow {
            position: absolute;
            top: -10%;
            right: -10%;
            width: 60%;
            height: 70%;
            background: radial-gradient(ellipse, rgba(122,46,46,0.09) 0%, transparent 68%);
            pointer-events: none;
        }

        .hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr;
            gap: 56px;
            align-items: center;
        }

        .hero-eyebrow { margin-bottom: 26px; }

        .hero-headline {
            font-family: var(--font-display);
            font-size: clamp(2.5rem, 8vw, 4.2rem);
            font-weight: 600;
            line-height: 1.04;
            letter-spacing: -0.015em;
            color: var(--charcoal);
            margin-bottom: 26px;
            text-wrap: balance;
        }
        .hero-headline em {
            font-style: italic;
            font-weight: 500;
            color: var(--oxblood);
        }

        .hero-sub {
            font-size: 1.02rem;
            color: var(--charcoal-soft);
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 36px;
            font-weight: 400;
        }

        .hero-cta-row { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; margin-bottom: 34px; }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: var(--oxblood);
            color: var(--ivory);
            padding: 8px 8px 8px 24px;
            border-radius: 100px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.15s ease-out, background 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-primary:hover { background: var(--oxblood-dark); box-shadow: var(--shadow-warm); }
        .btn-primary .icon-chip {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: rgba(250,247,242,0.16);
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .btn-primary:hover .icon-chip { transform: translateX(3px); }

        .hero-proof { display: flex; align-items: center; gap: 12px; }
        .proof-swatches { display: flex; }
        .proof-swatch {
            width: 22px; height: 22px;
            border-radius: 5px;
            border: 2px solid var(--ivory);
            box-shadow: var(--shadow-soft);
        }
        .proof-swatch:not(:first-child) { margin-left: -7px; }
        .proof-swatch.c1 { background: var(--oxblood); }
        .proof-swatch.c2 { background: var(--ochre); }
        .proof-swatch.c3 { background: var(--sage); }
        .hero-proof-text { font-size: 0.8rem; color: var(--charcoal-faint); }

        /* ---- Hero visual: editorial photo plate ---- */
        @keyframes floatY { 0%, 100% { transform: translateY(0) rotate(var(--tag-rot, 0deg)); } 50% { transform: translateY(-7px) rotate(var(--tag-rot, 0deg)); } }

        /* Reusable duotone photo treatment — shared by hero plate + feature tile */
        .duo-photo { position: relative; overflow: hidden; }
        .duo-photo img { width: 100%; height: 100%; object-fit: cover; display: block; filter: grayscale(1) contrast(1.08) brightness(0.94); }
        .duo-photo::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(150deg, var(--oxblood) 0%, var(--ochre) 100%);
            mix-blend-mode: color;
            opacity: 0.92;
        }
        .duo-photo::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(200deg, transparent 40%, var(--charcoal) 135%);
            mix-blend-mode: multiply;
            opacity: 0.4;
            z-index: 1;
        }

        .hero-visual { position: relative; max-width: 400px; margin: 40px auto 0; }

        .ghost-type {
            position: absolute;
            top: -9%; left: 50%;
            transform: translateX(-50%);
            font-family: var(--font-display);
            font-weight: 900;
            font-size: clamp(6rem, 15vw, 10rem);
            line-height: 1;
            color: transparent;
            -webkit-text-stroke: 1.5px var(--oxblood-line);
            letter-spacing: -0.02em;
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
            user-select: none;
        }

        .plate-tape {
            position: absolute;
            top: 2px; left: 50%;
            transform: translateX(-50%) rotate(-4deg);
            width: 76px; height: 26px;
            background: rgba(168, 130, 60, 0.3);
            border: 1px solid var(--gold-line);
            z-index: 3;
        }

        .plate {
            position: relative;
            z-index: 2;
            background: var(--surface);
            padding: 14px 14px 16px;
            border-radius: 4px;
            box-shadow: var(--shadow-warm);
            transform: rotate(-1.6deg);
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .hero-visual:hover .plate { transform: rotate(0deg); }

        .plate-photo {
            aspect-ratio: 4 / 5;
            border-radius: 2px;
            clip-path: polygon(0% 0%,100% 0%,100% 91%,96% 94%,92% 90%,88% 95%,84% 91%,80% 96%,76% 90%,72% 95%,68% 89%,64% 96%,60% 91%,56% 96%,52% 90%,48% 95%,44% 89%,40% 96%,36% 91%,32% 96%,28% 90%,24% 95%,20% 89%,16% 96%,12% 91%,8% 96%,4% 90%,0% 94%);
        }

        .plate-caption {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px dashed var(--hairline);
            font-family: var(--font-mono);
            font-size: 9px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--charcoal-faint);
        }

        .swing-tag {
            --tag-rot: -6deg;
            position: absolute;
            bottom: -16px; left: -20px;
            z-index: 3;
            background: var(--ivory);
            border: 1px solid var(--hairline);
            border-radius: 3px;
            padding: 10px 14px 10px 24px;
            font-family: var(--font-mono);
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--oxblood);
            box-shadow: var(--shadow-warm);
            transform: rotate(-6deg);
            animation: floatY 5.5s ease-in-out infinite;
        }
        .swing-tag-hole {
            position: absolute;
            left: 9px; top: 50%; transform: translateY(-50%);
            width: 6px; height: 6px;
            border-radius: 50%;
            border: 1.5px solid var(--charcoal-faint);
            background: var(--ivory);
        }

        /* ====== STATS STRIP ====== */
        .stats-strip {
            padding: 44px 0;
            border-top: 1px solid var(--gold-line);
            border-bottom: 1px solid var(--gold-line);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px 16px;
        }
        .stat-item { text-align: center; padding: 0 8px; }
        .stat-number {
            font-family: var(--font-mono);
            font-size: clamp(1.6rem, 4vw, 2.3rem);
            font-weight: 700;
            color: var(--charcoal);
            letter-spacing: -0.02em;
            font-variant-numeric: tabular-nums;
        }
        .stat-number span { color: var(--oxblood); }
        .stat-label {
            font-size: 0.72rem;
            color: var(--charcoal-faint);
            font-weight: 500;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .stat-icon { width: 24px; height: 24px; margin: 0 auto 10px; color: var(--oxblood); opacity: 0.75; }

        /* ====== COMPETENCY CHIPS ====== */
        .competency-strip { padding: 40px 0 88px; }
        .competency-label {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--charcoal-faint);
            margin-bottom: 18px;
            text-align: center;
        }
        .competency-chips { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; max-width: 760px; margin: 0 auto; }
        .chip {
            font-family: var(--font-mono);
            font-size: 0.76rem;
            padding: 7px 16px;
            border: 1px solid var(--hairline);
            border-radius: 100px;
            color: var(--charcoal-soft);
            transition: all 0.25s ease;
        }
        .chip:hover { border-color: var(--oxblood-line); color: var(--oxblood); background: var(--oxblood-soft); }

        /* ====== GALLERY (Karya Pilihan) ====== */
        .gallery-section { padding: 20px 0 96px; }
        .gallery-head { max-width: 560px; margin-bottom: 40px; }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 190px;
            gap: 12px;
        }
        .gallery-item--a { grid-column: span 2; grid-row: span 2; }
        .gallery-item--b { grid-column: span 2; grid-row: span 1; }

        .gallery-item {
            position: relative;
            display: block;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--hairline);
            text-decoration: none;
        }
        .gallery-item .gallery-photo { position: absolute; inset: 0; }
        .gallery-item .gallery-photo img { transition: transform 0.7s cubic-bezier(0.22,1,0.36,1); }
        .gallery-item .gallery-photo::after { transition: opacity 0.5s ease; }
        .gallery-item:hover .gallery-photo img { transform: scale(1.07); }
        .gallery-item:hover .gallery-photo::after { opacity: 0.4; }

        .gallery-badge {
            position: absolute;
            top: 12px; left: 12px;
            z-index: 3;
            background: rgba(250,247,242,0.85);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,0.5);
            color: var(--charcoal);
            font-family: var(--font-mono);
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 5px 10px;
            border-radius: 100px;
        }

        .gallery-scrim {
            position: absolute; inset: 0;
            z-index: 1;
            background: linear-gradient(0deg, rgba(15,13,11,0.88) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .gallery-item:hover .gallery-scrim { opacity: 1; }

        .gallery-title-wrap {
            position: absolute; left: 0; right: 0; bottom: 0;
            z-index: 2;
            padding: 14px;
            color: var(--ivory);
            transform: translateY(10px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.22,1,0.36,1);
        }
        .gallery-item:hover .gallery-title-wrap { transform: translateY(0); opacity: 1; }
        .gallery-title { font-family: var(--font-display); font-size: 0.95rem; font-weight: 600; line-height: 1.3; }
        .gallery-item--a .gallery-title { font-size: 1.25rem; }

        /* ====== MARQUEE TICKER ====== */
        .marquee-strip { background: var(--oxblood); overflow: hidden; padding: 16px 0; }
        .marquee-track { display: flex; width: max-content; animation: marquee 26s linear infinite; }
        .marquee-track span {
            display: flex; align-items: center; gap: 14px;
            padding: 0 22px;
            font-family: var(--font-mono);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ivory);
            white-space: nowrap;
        }
        .marquee-track span .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--gold); flex-shrink: 0; }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        /* ====== FEATURES ====== */
        .features { padding: 96px 0; }

        .features-head { max-width: 560px; margin-bottom: 56px; }
        .section-title {
            font-family: var(--font-display);
            font-size: clamp(1.9rem, 3.4vw, 2.6rem);
            font-weight: 600;
            color: var(--charcoal);
            letter-spacing: -0.01em;
            line-height: 1.18;
            margin: 16px 0 14px;
        }
        .section-sub { font-size: 0.98rem; color: var(--charcoal-soft); line-height: 1.7; max-width: 480px; }

        .feature-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        .feature-stack { display: grid; grid-template-columns: 1fr; gap: 16px; }

        .feature-plate {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            letter-spacing: 0.08em;
            color: var(--charcoal-faint);
            margin-bottom: 22px;
        }

        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 11px;
            background: var(--oxblood-soft);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
            color: var(--oxblood);
            transition: all 0.3s ease;
        }
        .feature-card--plain:hover .feature-icon { background: var(--oxblood); color: var(--ivory); box-shadow: 0 0 0 3px var(--gold-line); }

        .feature-title {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--charcoal);
            margin-bottom: 10px;
            letter-spacing: -0.01em;
        }
        .feature-desc { font-size: 0.9rem; color: var(--charcoal-soft); line-height: 1.7; }

        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 20px;
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--oxblood);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* ---- Plain card (with cursor spotlight) ---- */
        .feature-card--plain {
            position: relative;
            overflow: hidden;
            background: var(--surface);
            border: 1px solid var(--hairline);
            border-radius: 16px;
            padding: 28px 24px;
            transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .feature-card--plain::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(280px circle at var(--mx, 50%) var(--my, 50%), var(--oxblood-soft), transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .feature-card--plain:hover::before { opacity: 1; }
        .feature-card--plain:hover {
            border-color: var(--oxblood-line);
            transform: translateY(-4px);
            box-shadow: var(--shadow-warm);
        }
        .feature-card--plain > * { position: relative; z-index: 1; }

        /* ---- Large photographic tile ---- */
        .feature-card--image {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            border: 1px solid var(--hairline);
            min-height: 360px;
        }
        .feature-card--image .feature-card-photo { position: absolute; inset: 0; }
        .feature-card--image .feature-card-photo img { filter: grayscale(1) contrast(1.05) brightness(0.68); }
        .feature-card--image .feature-card-photo::before {
            background: linear-gradient(0deg, rgba(15,13,11,0.9) 8%, rgba(15,13,11,0.1) 62%);
        }
        .feature-card-overlay {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px 26px;
        }
        .feature-card--image .feature-plate { color: rgba(250,247,242,0.65); }
        .feature-card--image .feature-title { color: var(--ivory); }
        .feature-card--image .feature-desc { color: rgba(250,247,242,0.72); }
        .feature-card--image .feature-tag { color: var(--gold); }
        .feature-card--image .feature-icon { background: rgba(250,247,242,0.14); color: var(--ivory); }

        /* ====== MANIFESTO (dark editorial break) ====== */
        .manifesto {
            position: relative;
            background: var(--ink);
            color: var(--ivory);
            padding: 100px 0;
            overflow: hidden;
            text-align: center;
        }
        .manifesto-mark {
            position: absolute;
            top: -60px; left: 50%;
            transform: translateX(-50%);
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 26rem;
            line-height: 1;
            color: rgba(250, 247, 242, 0.035);
            pointer-events: none;
            user-select: none;
        }
        .manifesto .eyebrow { justify-content: center; color: var(--gold); position: relative; z-index: 2; }
        .manifesto-text {
            position: relative;
            z-index: 2;
            font-family: var(--font-display);
            font-weight: 500;
            font-size: clamp(2rem, 6vw, 3.4rem);
            line-height: 1.16;
            letter-spacing: -0.01em;
            margin: 18px 0 20px;
        }
        .manifesto-text em { font-style: italic; font-weight: 400; color: var(--gold); }
        .manifesto-sub {
            position: relative;
            z-index: 2;
            font-size: 0.95rem;
            color: rgba(250, 247, 242, 0.55);
            max-width: 440px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ====== FOOTER CONTACT + MAP (compact, matches parent school site pattern) ====== */
        .footer-contact { display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; }
        .footer-contact-row {
            display: flex; align-items: flex-start; gap: 10px;
            text-decoration: none;
            color: var(--charcoal-faint);
            font-size: 0.83rem;
            line-height: 1.5;
            transition: color 0.2s ease;
        }
        .footer-contact-row:hover { color: var(--oxblood); }
        .footer-contact-icon { width: 15px; height: 15px; flex-shrink: 0; margin-top: 2px; color: var(--oxblood); }

        .footer-social { display: flex; gap: 8px; }
        .footer-social-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            border: 1px solid var(--hairline);
            display: flex; align-items: center; justify-content: center;
            color: var(--charcoal-faint);
            transition: all 0.2s ease;
        }
        .footer-social-icon:hover { border-color: var(--oxblood-line); color: var(--oxblood); background: var(--oxblood-soft); }

        .footer-map {
            position: relative;
            display: block;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--hairline);
            aspect-ratio: 4 / 3;
        }
        .footer-map iframe {
            width: 100%; height: 100%; border: 0;
            pointer-events: none;
            filter: grayscale(75%) sepia(15%) hue-rotate(-8deg) contrast(1.05) brightness(0.92);
            transition: filter 0.5s ease;
        }
        .footer-map:hover iframe, .footer-map:focus-visible iframe { filter: grayscale(15%) sepia(4%) contrast(1) brightness(1); }
        .footer-map-cta {
            position: absolute; left: 10px; bottom: 10px; z-index: 2;
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--ivory); color: var(--charcoal);
            font-family: var(--font-mono);
            font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
            padding: 6px 10px; border-radius: 100px;
            box-shadow: var(--shadow-soft);
        }

        /* ====== CTA ====== */
        .cta-section {
            padding: 96px 0;
            position: relative;
            text-align: center;
            border-top: 1px solid var(--hairline);
        }

        .cta-title {
            font-family: var(--font-display);
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 600;
            color: var(--charcoal);
            letter-spacing: -0.015em;
            line-height: 1.12;
            margin: 20px 0 18px;
        }
        .cta-title em { font-style: italic; font-weight: 500; color: var(--oxblood); }

        .cta-sub { font-size: 1rem; color: var(--charcoal-soft); max-width: 440px; margin: 0 auto 36px; line-height: 1.7; }

        .cta-foot-note { margin-top: 44px; font-size: 0.8rem; color: var(--charcoal-faint); }

        /* ====== FOOTER ====== */
        .footer {
            border-top: 1px solid var(--hairline);
            padding: 52px 0 30px;
        }

        .footer-grid { display: grid; grid-template-columns: 1fr; gap: 36px; margin-bottom: 36px; }

        .footer-logo {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--charcoal);
            letter-spacing: -0.01em;
            margin-bottom: 10px;
        }
        .footer-logo em { font-style: italic; color: var(--oxblood); }

        .footer-desc { font-size: 0.85rem; color: var(--charcoal-faint); line-height: 1.7; max-width: 280px; }

        .footer-heading {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--charcoal);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 16px;
        }

        .footer-text { font-size: 0.85rem; color: var(--charcoal-faint); line-height: 1.85; }

        .footer-links { display: flex; flex-direction: column; gap: 10px; }
        .footer-links a {
            font-size: 0.85rem;
            color: var(--charcoal-faint);
            text-decoration: none;
            transition: color 0.2s ease;
            width: fit-content;
        }
        .footer-links a:hover { color: var(--oxblood); }

        .footer-divider { border: none; border-top: 1px solid var(--hairline); margin: 0 0 22px; }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .footer-copy { font-size: 0.76rem; color: var(--charcoal-faint); }
        .footer-copy em { font-style: normal; color: var(--oxblood); }
        .footer-dev { font-size: 0.76rem; color: var(--charcoal-faint); }
        .footer-dev strong { color: var(--charcoal-soft); font-weight: 600; }

        /* ====== SCROLL REVEAL ====== */
        .anim-fade-up {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1), transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .anim-fade-up.visible { opacity: 1; transform: translateY(0); }
        .anim-d1 { transition-delay: 0.08s; }
        .anim-d2 { transition-delay: 0.16s; }
        .anim-d3 { transition-delay: 0.24s; }

        /* ====== RESPONSIVE (mobile-first enhancements) ====== */
        @media (min-width: 640px) {
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
            .footer-bottom { flex-wrap: nowrap; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (min-width: 700px) {
            .gallery-grid { grid-template-columns: repeat(4, 1fr); grid-auto-rows: 200px; }
        }

        @media (min-width: 768px) {
            .feature-grid { grid-template-columns: 1.1fr 1fr; align-items: stretch; }
            .feature-card--image { min-height: 100%; }
        }

        @media (min-width: 980px) {
            .footer-grid { grid-template-columns: 1.3fr 0.8fr 1fr 0.9fr; gap: 32px; }
        }

        @media (min-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr 0.92fr; gap: 60px; }
        }
    </style>
</head>
<body>

<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

<button class="back-to-top" id="backToTop" aria-label="Kembali ke atas halaman">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M5 12l7-7 7 7"/>
    </svg>
</button>

<a href="#konten-utama" class="skip-link">Lompat ke konten utama</a>

{{-- ========================================================
     NAVBAR
======================================================== --}}
<nav class="navbar" id="navbar" aria-label="Navigasi utama">
    <div class="container nav-inner">
        <a href="{{ url('/') }}" class="nav-logo">
            <span class="nav-logo-mark">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Negeri 2 Padang Panjang">
            </span>
            <span>DKV<em>.</em>SMEKDA</span>
        </a>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
            <a href="{{ route('login') }}" class="btn-solid">Daftar Siswa</a>
        </div>
    </div>
</nav>

<main id="konten-utama">

{{-- ========================================================
     HERO
======================================================== --}}
<section class="hero">
    <div class="hero-glow" aria-hidden="true"></div>

    <div class="container hero-grid">

        {{-- LEFT: TEXT --}}
        <div>
            <div class="eyebrow hero-eyebrow">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/><path d="M12 3v4M12 17v4M3 12h4M17 12h4"/>
                </svg>
                Portofolio Resmi — DKV SMEKDA
            </div>

            <h1 class="hero-headline">
                Karya desainmu,<br>
                disusun selayak<br>
                <em>katalog pameran.</em>
            </h1>

            <p class="hero-sub">
                Etalase portofolio digital resmi Jurusan Desain Komunikasi Visual
                SMKN 2 Padang Panjang — tempat poster, ilustrasi, dan identitas
                visual tersimpan rapi, siap dicetak kapan saja.
            </p>

            <div class="hero-cta-row">
                <a href="{{ route('login') }}" class="btn-primary">
                    <span>Mulai Susun Portofolio</span>
                    <span class="icon-chip">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </a>

                <div class="hero-proof">
                    <div class="proof-swatches" aria-hidden="true">
                        <div class="proof-swatch c1"></div>
                        <div class="proof-swatch c2"></div>
                        <div class="proof-swatch c3"></div>
                    </div>
                    <span class="hero-proof-text">Digunakan siswa DKV setiap angkatan</span>
                </div>
            </div>
        </div>

        {{-- RIGHT: VISUAL --}}
        <div>
            <div class="hero-visual">
                <div class="ghost-type" aria-hidden="true">DKV</div>
                <div class="plate-tape" aria-hidden="true"></div>

                <div class="plate">
                    <div class="plate-photo duo-photo">
                        <img src="https://picsum.photos/seed/dkv-print-studio-2026/700/875" alt="" loading="lazy">
                    </div>
                    <div class="plate-caption">
                        <span>Fig. 01 — Proses Kreatif</span>
                        <span>DKV / 2026</span>
                    </div>
                </div>

                <div class="swing-tag">
                    <span class="swing-tag-hole" aria-hidden="true"></span>
                    PDF Siap Dicetak
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================
     STATS STRIP
======================================================== --}}
<div class="stats-strip">
    <div class="container stats-grid">

        <div class="stat-item anim-fade-up">
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 016.5 17H20M4 19.5A2.5 2.5 0 006.5 22H20V2H6.5A2.5 2.5 0 004 4.5v15z"/>
            </svg>
            <div class="stat-number">12<span>+</span></div>
            <div class="stat-label">Kompetensi DKV</div>
        </div>

        <div class="stat-item anim-fade-up anim-d1">
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <rect x="3" y="3" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M3 15l4-4 4 4 5-6 5 4"/>
            </svg>
            <div class="stat-number"><span>∞</span></div>
            <div class="stat-label">Karya Tersimpan</div>
        </div>

        <div class="stat-item anim-fade-up anim-d2">
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V5a2 2 0 012-2h8a2 2 0 012 2v4M6 18h12M6 9h12v9a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"/>
            </svg>
            <div class="stat-number">1<span>×</span></div>
            <div class="stat-label">Klik Ekspor PDF</div>
        </div>

        <div class="stat-item anim-fade-up anim-d3">
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M3 12h18M12 3c2.5 2.6 3.8 6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-6-3.8-9s1.3-6.4 3.8-9z"/>
            </svg>
            <div class="stat-number">100<span>%</span></div>
            <div class="stat-label">Berbasis Web</div>
        </div>

    </div>
</div>

{{-- ========================================================
     KOMPETENSI
======================================================== --}}
<div class="competency-strip">
    <div class="container">
        <div class="competency-label anim-fade-up">Kompetensi yang Dipelajari</div>
        <div class="competency-chips anim-fade-up anim-d1">
            <span class="chip">Desain Grafis</span>
            <span class="chip">Fotografi</span>
            <span class="chip">Videografi</span>
            <span class="chip">Ilustrasi Digital</span>
            <span class="chip">Animasi 2D/3D</span>
            <span class="chip">Multimedia Interaktif</span>
            <span class="chip">Tipografi</span>
            <span class="chip">Desain Kemasan</span>
            <span class="chip">UI/UX Dasar</span>
            <span class="chip">Motion Graphic</span>
        </div>
    </div>
</div>

{{-- ========================================================
     GALERI KARYA
======================================================== --}}
<section class="gallery-section">
    <div class="container">

        <div class="gallery-head anim-fade-up">
            <div class="eyebrow">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/><path d="M12 3v4M12 17v4M3 12h4M17 12h4"/>
                </svg>
                Etalase Karya
            </div>
            <h2 class="section-title">Karya Pilihan dari Etalase</h2>
            <p class="section-sub">
                Sebagian kecil dari beragam karya yang tersimpan di platform —
                dari poster kampanye hingga identitas visual UMKM lokal.
            </p>
        </div>

        <div class="gallery-grid">

            <a href="{{ route('login') }}" class="gallery-item gallery-item--a anim-fade-up">
                <div class="gallery-photo duo-photo">
                    <img src="https://picsum.photos/seed/dkv-coffee-branding-2026/800/800" alt="" loading="lazy">
                </div>
                <span class="gallery-badge">Branding</span>
                <div class="gallery-scrim" aria-hidden="true"></div>
                <div class="gallery-title-wrap">
                    <div class="gallery-title">Identitas Visual Kedai Kopi Nagari</div>
                </div>
            </a>

            <a href="{{ route('login') }}" class="gallery-item gallery-item--b anim-fade-up anim-d1">
                <div class="gallery-photo duo-photo">
                    <img src="https://picsum.photos/seed/dkv-poster-campaign-2026/900/500" alt="" loading="lazy">
                </div>
                <span class="gallery-badge">Poster</span>
                <div class="gallery-scrim" aria-hidden="true"></div>
                <div class="gallery-title-wrap">
                    <div class="gallery-title">Kampanye Sosial: Bijak Sampah</div>
                </div>
            </a>

            <a href="{{ route('login') }}" class="gallery-item anim-fade-up anim-d2">
                <div class="gallery-photo duo-photo">
                    <img src="https://picsum.photos/seed/dkv-illustration-2026/500/500" alt="" loading="lazy">
                </div>
                <span class="gallery-badge">Ilustrasi</span>
                <div class="gallery-scrim" aria-hidden="true"></div>
                <div class="gallery-title-wrap">
                    <div class="gallery-title">Ilustrasi Editorial: Ruang Kelas</div>
                </div>
            </a>

            <a href="{{ route('login') }}" class="gallery-item anim-fade-up anim-d3">
                <div class="gallery-photo duo-photo">
                    <img src="https://picsum.photos/seed/dkv-motion-bumper-2026/500/500" alt="" loading="lazy">
                </div>
                <span class="gallery-badge">Motion</span>
                <div class="gallery-scrim" aria-hidden="true"></div>
                <div class="gallery-title-wrap">
                    <div class="gallery-title">Bumper Motion Wisuda DKV</div>
                </div>
            </a>

            <a href="{{ route('login') }}" class="gallery-item gallery-item--b anim-fade-up">
                <div class="gallery-photo duo-photo">
                    <img src="https://picsum.photos/seed/dkv-product-photo-2026/900/500" alt="" loading="lazy">
                </div>
                <span class="gallery-badge">Fotografi</span>
                <div class="gallery-scrim" aria-hidden="true"></div>
                <div class="gallery-title-wrap">
                    <div class="gallery-title">Fotografi Produk UMKM Lokal</div>
                </div>
            </a>

            <a href="{{ route('login') }}" class="gallery-item gallery-item--b anim-fade-up anim-d1">
                <div class="gallery-photo duo-photo">
                    <img src="https://picsum.photos/seed/dkv-catalog-book-2026/900/500" alt="" loading="lazy">
                </div>
                <span class="gallery-badge">Editorial</span>
                <div class="gallery-scrim" aria-hidden="true"></div>
                <div class="gallery-title-wrap">
                    <div class="gallery-title">Buku Katalog Pameran Angkatan</div>
                </div>
            </a>

        </div>
    </div>
</section>

{{-- ========================================================
     FEATURES
======================================================== --}}
<section class="features" id="mengapa">
    <div class="container">

        <div class="features-head anim-fade-up">
            <div class="eyebrow">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/><path d="M12 3v4M12 17v4M3 12h4M17 12h4"/>
                </svg>
                Mengapa Platform Ini
            </div>
            <h2 class="section-title">Dirancang serius untuk generasi kreatif</h2>
            <p class="section-sub">
                Bukan sekadar penyimpanan biasa — ekosistem digital yang menemani
                perjalanan kreatif siswa DKV dari bangku sekolah hingga industri.
            </p>
        </div>

        <div class="feature-grid">

            <div class="feature-card--image anim-fade-up">
                <div class="feature-card-photo duo-photo">
                    <img src="https://picsum.photos/seed/dkv-archive-shelf-2026/900/1100" alt="" loading="lazy">
                </div>
                <div class="feature-card-overlay">
                    <div class="feature-plate">PLAT · 01</div>
                    <div class="feature-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Arsip Digital Terpusat</h3>
                    <p class="feature-desc">
                        Semua karya tersimpan aman di server, bebas risiko hilang
                        di flashdisk rusak, file terhapus, atau laptop bermasalah.
                    </p>
                    <div class="feature-tag">Aman &amp; Terorganisir</div>
                </div>
            </div>

            <div class="feature-stack">

                <div class="feature-card--plain anim-fade-up anim-d1">
                    <div class="feature-plate">PLAT · 02</div>
                    <div class="feature-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.25A23.9 23.9 0 0112 15c-3.18 0-6.22-.62-9-1.75M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Siap Tampil ke Industri</h3>
                    <p class="feature-desc">
                        Tampilan profesional untuk kebutuhan Teaching Factory,
                        Program Guru Tamu, dan kolaborasi proyek industri kreatif.
                    </p>
                    <div class="feature-tag">Standar Industri Kreatif</div>
                </div>

                <div class="feature-card--plain anim-fade-up anim-d2">
                    <div class="feature-plate">PLAT · 03</div>
                    <div class="feature-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V5a2 2 0 012-2h8a2 2 0 012 2v4M6 18h12M6 9h12v9a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Cetak Katalog Sekali Klik</h3>
                    <p class="feature-desc">
                        Ubah kumpulan karya jadi katalog PDF profesional — untuk
                        lamaran PKL, portofolio wisuda, presentasi ke wali murid.
                    </p>
                    <div class="feature-tag">Langsung Jadi</div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ========================================================
     MARQUEE
======================================================== --}}
<div class="marquee-strip" aria-hidden="true">
    <div class="marquee-track">
        <span><span class="dot"></span>Portofolio Digital Resmi<span class="dot"></span>Arsip Karya DKV<span class="dot"></span>SMKN 2 Padang Panjang<span class="dot"></span>Katalog Pameran 2026</span>
        <span><span class="dot"></span>Portofolio Digital Resmi<span class="dot"></span>Arsip Karya DKV<span class="dot"></span>SMKN 2 Padang Panjang<span class="dot"></span>Katalog Pameran 2026</span>
    </div>
</div>

{{-- ========================================================
     MANIFESTO
======================================================== --}}
<section class="manifesto">
    <div class="manifesto-mark" aria-hidden="true">DKV</div>
    <div class="container">
        <div class="eyebrow">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/><path d="M12 3v4M12 17v4M3 12h4M17 12h4"/>
            </svg>
            Catatan Studio
        </div>
        <p class="manifesto-text anim-fade-up">
            Arsip hari ini,<br>
            portofolio besok,<br>
            <em>karier nanti.</em>
        </p>
        <p class="manifesto-sub">
            Setiap unggahan bukan sekadar penyimpanan — ia langkah menuju
            portofolio yang siap bicara ke industri.
        </p>
    </div>
</section>

{{-- ========================================================
     CTA
======================================================== --}}
<section class="cta-section">
    <div class="container">

        <div class="eyebrow" style="justify-content:center;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/><path d="M12 3v4M12 17v4M3 12h4M17 12h4"/>
            </svg>
            Bergabung Sekarang
        </div>

        <h2 class="cta-title">
            Waktunya karyamu<br>
            <em>bicara sendiri.</em>
        </h2>

        <p class="cta-sub">
            Masuk ke sistem dan mulai unggah karya terbaikmu. Guru pembimbing
            memantau perkembanganmu secara langsung dari dasbor mereka.
        </p>

        <a href="{{ route('login') }}" class="btn-primary" style="font-size:1rem;">
            <span>Masuk ke Platform</span>
            <span class="icon-chip">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </span>
        </a>

        <div class="cta-foot-note">Gratis · Tanpa biaya · Khusus siswa DKV SMEKDA</div>

    </div>
</section>

</main>

{{-- ========================================================
     FOOTER
======================================================== --}}
<footer class="footer">
    <div class="container">
        <div class="footer-grid">

            <div>
                <div class="footer-logo">DKV<em>.</em>SMEKDA</div>
                <p class="footer-desc">
                    Platform resmi manajemen portofolio digital untuk Jurusan
                    Desain Komunikasi Visual SMK Negeri 2 Padang Panjang.
                </p>
            </div>

            <div>
                <h4 class="footer-heading">Akses Cepat</h4>
                <div class="footer-links">
                    <a href="{{ route('login') }}">→ Masuk Siswa</a>
                    <a href="{{ route('login') }}">→ Masuk Guru</a>
                    <a href="#mengapa">→ Tentang DKV</a>
                </div>
            </div>

            <div>
                <h4 class="footer-heading">Hubungi Kami</h4>
                <div class="footer-contact">
                    <a href="https://www.google.com/maps/search/?api=1&query=SMKN2+Padang+Panjang&query_place_id=ChIJY5KAQmol1S8Ra9ABz0r_vIM" target="_blank" rel="noopener noreferrer" class="footer-contact-row">
                        <svg class="footer-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-6.4-7-11.5A7 7 0 0119 9.5C19 15.6 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.3"/>
                        </svg>
                        <span>Jl. Syech Ibrahim Musa No.26, Kel. Gantiang, Kec. Padang Panjang Timur.</span>
                    </a>
                    <a href="tel:075212345" class="footer-contact-row">
                        <svg class="footer-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6 19.8 19.8 0 01-3.1-8.7A2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .3 2 .7 3a2 2 0 01-.4 2.1L8 10.3a16 16 0 006 6l1.5-1.5a2 2 0 012.1-.4c1 .4 2 .6 3 .7a2 2 0 011.7 2z"/>
                        </svg>
                        <span>0752-12345</span>
                    </a>
                    <a href="mailto:smkn2padangpanjang@gmail.com" class="footer-contact-row">
                        <svg class="footer-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 6l-10 7L2 6"/>
                        </svg>
                        <span>smkn2padangpanjang@gmail.com</span>
                    </a>
                </div>
                <div class="footer-social">
                    <a href="https://facebook.com/smkn2pp" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Facebook SMK Negeri 2 Padang Panjang">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0022 12z"/></svg>
                    </a>
                    <a href="https://instagram.com/smkn2pp" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Instagram SMK Negeri 2 Padang Panjang">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="3.8"/><circle cx="17.3" cy="6.7" r="1"/></svg>
                    </a>
                    <a href="https://youtube.com/smkn2pp" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="YouTube SMK Negeri 2 Padang Panjang">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M23 12s0-3.6-.5-5.3a3 3 0 00-2.1-2C18.7 4.2 12 4.2 12 4.2s-6.7 0-8.4.5a3 3 0 00-2.1 2C1 8.4 1 12 1 12s0 3.6.5 5.3a3 3 0 002.1 2c1.7.5 8.4.5 8.4.5s6.7 0 8.4-.5a3 3 0 002.1-2C23 15.6 23 12 23 12z"/><path fill="var(--oxblood)" d="M9.8 15.5V8.5l6 3.5-6 3.5z"/></svg>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="footer-heading">Peta Lokasi</h4>
                <a href="https://www.google.com/maps/search/?api=1&query=SMKN2+Padang+Panjang&query_place_id=ChIJY5KAQmol1S8Ra9ABz0r_vIM" target="_blank" rel="noopener noreferrer" class="footer-map" aria-label="Buka lokasi SMK Negeri 2 Padang Panjang di Google Maps">
                    <iframe
                        src="https://www.google.com/maps?q=-0.4625443,100.4208631&z=15&output=embed"
                        loading="lazy"
                        tabindex="-1"
                        title=""
                        aria-hidden="true"></iframe>
                    <span class="footer-map-cta">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        Buka Peta
                    </span>
                </a>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <div class="footer-copy">&copy; {{ date('Y') }} <em>DKV SMEKDA</em> — SMK Negeri 2 Padang Panjang.</div>
            <div class="footer-dev">Dikembangkan untuk Skripsi oleh <strong>Rafli</strong> — 2026. Dibangun dengan Laravel.</div>
        </div>
    </div>
</footer>

<script>
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // ======== NAVBAR SCROLL + PROGRESS BAR + BACK-TO-TOP + PARALLAX ========
    const navbar = document.getElementById('navbar');
    const progressBar = document.getElementById('scrollProgress');
    const backToTop = document.getElementById('backToTop');
    const ghostType = document.querySelector('.ghost-type');

    function onScroll() {
        const scrollY = window.scrollY;

        navbar.classList.toggle('scrolled', scrollY > 24);
        backToTop.classList.toggle('visible', scrollY > 600);

        const doc = document.documentElement;
        const max = doc.scrollHeight - doc.clientHeight;
        progressBar.style.width = (max > 0 ? (scrollY / max) * 100 : 0) + '%';

        if (ghostType && !reduceMotion) {
            ghostType.style.transform = `translateX(-50%) translateY(${scrollY * 0.12}px)`;
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });

    // ======== SCROLL REVEAL ========
    const animEls = document.querySelectorAll('.anim-fade-up');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    animEls.forEach(el => observer.observe(el));

    // ======== SPOTLIGHT HOVER (feature cards) ========
    document.querySelectorAll('.feature-card--plain').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const r = card.getBoundingClientRect();
            card.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100) + '%');
            card.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100) + '%');
        });
    });

    // ======== MAGNETIC BUTTONS ========
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if (canHover && !reduceMotion) {
        document.querySelectorAll('.btn-primary').forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const r = btn.getBoundingClientRect();
                const x = e.clientX - r.left - r.width / 2;
                const y = e.clientY - r.top - r.height / 2;
                btn.style.transform = `translate(${x * 0.15}px, ${y * 0.3}px)`;
            });
            btn.addEventListener('mouseleave', () => { btn.style.transform = ''; });
        });
    }
</script>

</body>
</html>