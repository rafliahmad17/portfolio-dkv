{{-- resources/views/siswa/portfolio/edit.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karya — DKV SMEKDA Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <style>
        :root {
            --red:        #dc2626;
            --red-bright: #ef4444;
            --red-glow:   rgba(220,38,38,0.45);
            --red-soft:   rgba(220,38,38,0.10);
            --border:     rgba(255,255,255,0.07);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #080808;
            color: #f5f5f5;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }

        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(220,38,38,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220,38,38,0.035) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none; z-index: 0;
        }

        .blob { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
        .blob-1 {
            top: -160px; right: 80px;
            width: 560px; height: 560px;
            background: radial-gradient(circle, rgba(220,38,38,0.09) 0%, transparent 65%);
            animation: blobF 10s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -140px; left: -80px;
            width: 460px; height: 460px;
            background: radial-gradient(circle, rgba(220,38,38,0.06) 0%, transparent 65%);
            animation: blobF 13s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobF {
            0%   { transform: scale(1)    translate(0,0); }
            100% { transform: scale(1.14) translate(18px,14px); }
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #080808; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 260px; height: 100vh;
            background: rgba(8,8,8,0.9);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 50; overflow-y: auto;
        }

        .sidebar::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220,38,38,0.4), transparent);
        }

        .sidebar-logo { padding: 28px 24px 22px; border-bottom: 1px solid var(--border); }

        .logo-wordmark {
            font-size: 0.78rem; font-weight: 900;
            letter-spacing: 3px; text-transform: uppercase;
            color: rgba(255,255,255,0.85);
            display: flex; align-items: center; gap: 9px;
        }

        .logo-icon {
            width: 26px; height: 26px; background: var(--red); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px var(--red-glow); flex-shrink: 0;
        }

        .logo-icon svg { width: 13px; height: 13px; }

        .sidebar-profile { padding: 20px 24px; border-bottom: 1px solid var(--border); }

        .profile-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: white;
            flex-shrink: 0; box-shadow: 0 0 18px rgba(220,38,38,0.3);
        }

        .profile-name {
            font-size: 0.78rem; font-weight: 700; color: #f5f5f5;
            line-height: 1.3; margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .profile-nis { font-size: 0.68rem; color: rgba(255,255,255,0.28); margin-bottom: 7px; }

        .badge-role {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.22);
            color: #fca5a5; padding: 2px 9px; border-radius: 30px;
            font-size: 0.63rem; font-weight: 700;
            letter-spacing: 0.8px; text-transform: uppercase;
        }

        .live-dot {
            width: 5px; height: 5px; background: var(--red);
            border-radius: 50%; box-shadow: 0 0 6px var(--red-glow);
            animation: livePulse 1.5s ease-in-out infinite;
        }

        @keyframes livePulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.3; transform: scale(0.6); }
        }

        .sidebar-nav { flex: 1; padding: 20px 14px; }

        .nav-label {
            font-size: 0.62rem; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.18);
            padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: all 0.22s ease;
            border: 1px solid transparent;
            margin-bottom: 3px; position: relative;
        }

        .nav-item:hover {
            color: rgba(255,255,255,0.7);
            background: rgba(255,255,255,0.04);
            border-color: var(--border);
        }

        .nav-item.active {
            color: #fca5a5;
            background: rgba(220,38,38,0.1);
            border-color: rgba(220,38,38,0.2);
        }

        .nav-item.active::before {
            content: '';
            position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 18px; background: var(--red);
            border-radius: 0 3px 3px 0; box-shadow: 0 0 10px var(--red-glow);
        }

        .nav-item.active svg { color: var(--red); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }

        .sidebar-footer { padding: 14px; border-top: 1px solid var(--border); }

        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            background: none; border: 1px solid transparent;
            color: rgba(255,255,255,0.28);
            font-size: 0.82rem; font-weight: 600;
            font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all 0.22s ease;
        }

        .btn-logout:hover {
            color: #fca5a5;
            background: rgba(220,38,38,0.08);
            border-color: rgba(220,38,38,0.18);
        }

        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ── MAIN ── */
        .main-content { margin-left: 260px; min-height: 100vh; position: relative; z-index: 1; }

        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between;
        }

        .topbar-title {
            font-size: 0.8rem; font-weight: 700;
            color: rgba(255,255,255,0.22); letter-spacing: 0.5px;
        }

        .topbar-title span { color: rgba(255,255,255,0.5); margin-left: 6px; }

        .topbar-pill {
            display: flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.18);
            border-radius: 30px; padding: 5px 13px;
            font-size: 0.68rem; font-weight: 700;
            color: rgba(220,38,38,0.65); letter-spacing: 0.5px;
        }

        .page-inner { padding: 40px 36px 60px; }

        /* ── BACK BUTTON ── */
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.3); font-size: 0.78rem; font-weight: 700;
            text-decoration: none; padding: 8px 14px;
            border: 1px solid var(--border); border-radius: 10px;
            background: rgba(255,255,255,0.03);
            transition: all 0.22s ease; margin-bottom: 28px;
            width: fit-content;
        }

        .btn-back:hover {
            color: var(--red-bright);
            border-color: rgba(220,38,38,0.3);
            background: rgba(220,38,38,0.06);
        }

        .btn-back svg { width: 14px; height: 14px; transition: transform 0.22s ease; }
        .btn-back:hover svg { transform: translateX(-3px); }

        /* ── FORM HEADER ── */
        .form-headline {
            font-size: clamp(1.5rem, 2.2vw, 2rem);
            font-weight: 900; letter-spacing: -1px; line-height: 1.15;
            color: #f5f5f5; margin-bottom: 6px;
        }

        .form-headline .hl { color: var(--red); text-shadow: 0 0 26px rgba(220,38,38,0.4); }

        .form-sub {
            font-size: 0.875rem; color: rgba(255,255,255,0.28);
            font-weight: 400; margin-bottom: 36px;
        }

        /* ── INPUT STYLES ── */
        .field-label {
            display: block; font-size: 0.7rem; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            color: rgba(255,255,255,0.35); margin-bottom: 8px;
        }

        .field-label .req { color: var(--red); margin-left: 3px; }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute; top: 50%; left: 14px;
            transform: translateY(-50%);
            width: 15px; height: 15px;
            color: rgba(255,255,255,0.2);
            pointer-events: none; transition: color 0.22s ease;
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 11px;
            padding: 12px 14px 12px 42px;
            font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif;
            color: #f5f5f5; outline: none;
            caret-color: var(--red);
            transition: all 0.25s ease;
        }

        .form-input::placeholder { color: rgba(255,255,255,0.18); }

        .form-input:focus {
            border-color: var(--red);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15), 0 0 18px rgba(220,38,38,0.08);
        }

        .input-wrap:focus-within .input-icon { color: var(--red); }

        .form-input.is-error {
            border-color: var(--red-bright);
            background: rgba(239,68,68,0.06);
            box-shadow: 0 0 0 3px rgba(239,68,68,0.14);
        }

        .form-textarea {
            width: 100%; resize: none;
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 11px;
            padding: 12px 14px;
            font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif;
            color: #f5f5f5; outline: none;
            caret-color: var(--red);
            transition: all 0.25s ease;
            line-height: 1.6;
        }

        .form-textarea::placeholder { color: rgba(255,255,255,0.18); }

        .form-textarea:focus {
            border-color: var(--red);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15), 0 0 18px rgba(220,38,38,0.08);
        }

        .form-textarea.is-error {
            border-color: var(--red-bright);
            box-shadow: 0 0 0 3px rgba(239,68,68,0.14);
        }

        .form-select {
            width: 100%; appearance: none; -webkit-appearance: none;
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 11px;
            padding: 12px 40px 12px 42px;
            font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif;
            color: rgba(255,255,255,0.7); outline: none;
            cursor: pointer; transition: all 0.25s ease;
        }

        .form-select:focus {
            border-color: var(--red);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15);
            color: #f5f5f5;
        }

        .form-select option { background: #1a1a1a; color: #f5f5f5; }

        .select-arrow {
            position: absolute; right: 13px; top: 50%;
            transform: translateY(-50%);
            width: 14px; height: 14px;
            color: rgba(255,255,255,0.2); pointer-events: none;
        }

        .field-error {
            margin-top: 7px; font-size: 0.73rem; font-weight: 600;
            color: #f87171;
            display: flex; align-items: center; gap: 6px;
        }

        .field-error svg { width: 12px; height: 12px; flex-shrink: 0; }

        .field-wrap { margin-bottom: 20px; }

        /* ── FORM CARD ── */
        .form-card {
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: 20px; overflow: hidden;
            position: relative;
        }

        .form-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }

        .form-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }

        .card-header-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--red-soft); border: 1px solid rgba(220,38,38,0.18);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }

        .card-header-icon svg { width: 16px; height: 16px; color: var(--red); }

        .card-header-title { font-size: 0.85rem; font-weight: 800; color: #f5f5f5; }
        .card-header-sub { font-size: 0.7rem; color: rgba(255,255,255,0.25); margin-top: 1px; }

        .form-card-body { padding: 24px; }

        /* ── DROP ZONE ── */
        .drop-zone {
            position: relative;
            border: 2px dashed rgba(255,255,255,0.1);
            border-radius: 16px;
            min-height: 340px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            cursor: pointer; overflow: hidden;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.02);
        }

        .drop-zone:hover,
        .drop-zone.drag-over {
            border-color: var(--red);
            background: rgba(220,38,38,0.04);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1), inset 0 0 40px rgba(220,38,38,0.04);
        }

        .drop-zone.has-preview {
            border-color: rgba(220,38,38,0.4);
            border-style: solid;
        }

        @keyframes borderPulse {
            0%,100% { border-color: var(--red); box-shadow: 0 0 0 3px rgba(220,38,38,0.1); }
            50%      { border-color: var(--red-bright); box-shadow: 0 0 0 5px rgba(220,38,38,0.18); }
        }

        .drop-zone.drag-over { animation: borderPulse 1s ease-in-out infinite; }

        .drop-zone-prompt {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 14px; padding: 40px 24px; text-align: center;
        }

        .drop-icon-wrap {
            width: 72px; height: 72px; border-radius: 20px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s ease;
        }

        .drop-zone:hover .drop-icon-wrap,
        .drop-zone.drag-over .drop-icon-wrap {
            background: var(--red-soft);
            border-color: rgba(220,38,38,0.25);
            box-shadow: 0 0 24px rgba(220,38,38,0.15);
        }

        .drop-icon-wrap svg {
            width: 32px; height: 32px;
            color: rgba(255,255,255,0.18); transition: color 0.3s ease;
        }

        .drop-zone:hover .drop-icon-wrap svg,
        .drop-zone.drag-over .drop-icon-wrap svg { color: var(--red); }

        .drop-title {
            font-size: 0.88rem; font-weight: 800; color: rgba(255,255,255,0.5);
            transition: color 0.3s ease;
        }

        .drop-zone:hover .drop-title,
        .drop-zone.drag-over .drop-title { color: rgba(255,255,255,0.75); }

        .drop-sub { font-size: 0.72rem; color: rgba(255,255,255,0.2); line-height: 1.6; }

        .drop-types { display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; }

        .drop-type-pill {
            font-size: 0.62rem; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase;
            padding: 3px 9px; border-radius: 20px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            color: rgba(255,255,255,0.25);
        }

        /* ── IMAGE PREVIEW ── */
        .preview-wrap {
            position: absolute; inset: 0;
        }

        .preview-img {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
        }

        .preview-overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,0.58);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 10px; opacity: 0;
            transition: opacity 0.3s ease;
        }

        .drop-zone:hover .preview-overlay { opacity: 1; }

        .preview-change-btn {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(220,38,38,0.88);
            color: white; padding: 10px 20px; border-radius: 10px;
            font-size: 0.8rem; font-weight: 800;
            cursor: pointer; border: none;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 22px rgba(220,38,38,0.45);
            transition: all 0.22s ease;
        }

        .preview-change-btn:hover { background: var(--red-bright); }
        .preview-change-btn svg { width: 14px; height: 14px; }

        .preview-name {
            font-size: 0.72rem; color: rgba(255,255,255,0.55);
            font-weight: 600; max-width: 240px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .preview-badge {
            position: absolute; top: 12px; left: 12px;
            background: rgba(8,8,8,0.75); backdrop-filter: blur(10px);
            border: 1px solid rgba(220,38,38,0.35);
            color: #fca5a5; padding: 4px 10px; border-radius: 20px;
            font-size: 0.65rem; font-weight: 800;
            letter-spacing: 0.8px; text-transform: uppercase;
        }

        /* Edit indicator badge */
        .existing-badge {
            position: absolute; top: 12px; right: 12px;
            background: rgba(8,8,8,0.75); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.4); padding: 4px 10px; border-radius: 20px;
            font-size: 0.62rem; font-weight: 700;
            letter-spacing: 0.5px; text-transform: uppercase;
        }

        /* ── PDF UPLOAD ── */
        .pdf-upload-area {
            border: 1.5px dashed rgba(255,255,255,0.08);
            border-radius: 12px; padding: 16px;
            display: flex; align-items: center; gap: 14px;
            cursor: pointer; transition: all 0.25s ease;
            background: rgba(255,255,255,0.02);
        }

        .pdf-upload-area:hover {
            border-color: rgba(220,38,38,0.3);
            background: rgba(220,38,38,0.04);
        }

        .pdf-upload-area.has-file {
            border-color: rgba(220,38,38,0.35);
            border-style: solid;
            background: rgba(220,38,38,0.05);
        }

        .pdf-icon-box {
            width: 42px; height: 42px; border-radius: 10px;
            background: rgba(255,255,255,0.04); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            transition: all 0.25s ease;
        }

        .pdf-upload-area:hover .pdf-icon-box,
        .pdf-upload-area.has-file .pdf-icon-box {
            background: var(--red-soft); border-color: rgba(220,38,38,0.25);
        }

        .pdf-icon-box svg { width: 18px; height: 18px; color: rgba(255,255,255,0.2); transition: color 0.25s ease; }
        .pdf-upload-area:hover .pdf-icon-box svg,
        .pdf-upload-area.has-file .pdf-icon-box svg { color: var(--red); }

        .pdf-text-main {
            font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.4);
            transition: color 0.25s ease;
        }

        .pdf-upload-area:hover .pdf-text-main { color: rgba(255,255,255,0.65); }
        .pdf-upload-area.has-file .pdf-text-main { color: #fca5a5; }

        .pdf-text-sub { font-size: 0.68rem; color: rgba(255,255,255,0.18); margin-top: 2px; }

        /* ── SUBMIT ── */
        .btn-submit {
            width: 100%;
            background: var(--red); color: white; border: none;
            border-radius: 12px; padding: 15px 24px;
            font-size: 0.9rem; font-weight: 800;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.3px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            position: relative; overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(220,38,38,0.3);
        }

        .btn-submit::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, #b91c1c, #ef4444);
            opacity: 0; transition: opacity 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px var(--red-glow), 0 0 0 4px rgba(220,38,38,0.15);
        }

        .btn-submit:hover::before { opacity: 1; }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit span, .btn-submit svg { position: relative; z-index: 1; }
        .btn-submit svg { width: 18px; height: 18px; }

        /* ── TIPS ── */
        .tips-card {
            background: rgba(220,38,38,0.05);
            border: 1px solid rgba(220,38,38,0.15);
            border-radius: 14px; padding: 18px;
        }

        .tips-title {
            font-size: 0.7rem; font-weight: 800;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: rgba(220,38,38,0.7); margin-bottom: 12px;
            display: flex; align-items: center; gap: 7px;
        }

        .tips-title svg { width: 13px; height: 13px; }

        .tips-item {
            display: flex; align-items: flex-start; gap: 8px;
            font-size: 0.75rem; color: rgba(255,255,255,0.3);
            line-height: 1.6; margin-bottom: 8px; font-weight: 500;
        }

        .tips-item:last-child { margin-bottom: 0; }

        .tips-bullet {
            width: 4px; height: 4px; border-radius: 50%;
            background: var(--red); margin-top: 7px; flex-shrink: 0;
            box-shadow: 0 0 6px var(--red-glow);
        }

        /* ── INFO BANNER ── */
        .info-banner {
            display: flex; align-items: flex-start; gap: 12px;
            background: rgba(251,191,36,0.06);
            border: 1px solid rgba(251,191,36,0.15);
            border-radius: 12px; padding: 14px 16px;
            margin-bottom: 28px;
        }

        .info-banner svg { width: 16px; height: 16px; color: #fbbf24; flex-shrink: 0; margin-top: 1px; }

        .info-banner-text {
            font-size: 0.78rem; color: rgba(251,191,36,0.7);
            font-weight: 600; line-height: 1.6;
        }

        .info-banner-text strong { color: rgba(251,191,36,0.9); }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

{{-- ================================================================
     SIDEBAR
================================================================ --}}
<aside class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-wordmark">
            <div class="logo-icon">
                <svg fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            DKV<span style="color:var(--red);">.</span>SMEKDA
        </div>
        <div style="font-size:0.62rem; color:rgba(255,255,255,0.2); margin-top:4px; letter-spacing:1px; text-transform:uppercase; font-weight:600; padding-left:35px;">
            Portal Siswa
        </div>
    </div>

    <div class="sidebar-profile">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="flex:1; min-width:0;">
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-nis">NIS: {{ auth()->user()->nis_nip ?? '—' }}</div>
            </div>
        </div>
        <div class="badge-role">
            <div class="live-dot"></div>
            Siswa DKV
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('siswa.dashboard') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('siswa.portfolio.create') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Karya
        </a>

        <a href="{{ route('siswa.portfolio.export') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export PDF
        </a>

        <div class="nav-label" style="margin-top:20px;">Akun</div>

        <a href="#" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar dari Portal
            </button>
        </form>
    </div>
</aside>

{{-- ================================================================
     MAIN CONTENT
================================================================ --}}
<div class="main-content">

    <div class="topbar">
        <div class="topbar-title">
            Portal DKV SMEKDA <span>/</span> Edit Karya
        </div>
        <div class="topbar-pill">
            <div class="live-dot"></div>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="page-inner">

        {{-- Back --}}
        <a href="{{ route('siswa.dashboard') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Dashboard
        </a>

        {{-- Header --}}
        <div style="margin-bottom:28px;">
            <div style="font-size:0.68rem; font-weight:700; letter-spacing:3px; text-transform:uppercase; color:rgba(220,38,38,0.65); margin-bottom:10px;">
                &#9998; Mode Edit Karya
            </div>
            <h1 class="form-headline">
                Perbarui <span class="hl">Karya Anda</span>
            </h1>
            <p class="form-sub">Edit metadata dan visual karya portofolio Anda.</p>
        </div>

        {{-- Info Banner --}}
        <div class="info-banner">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="info-banner-text">
                Anda sedang mengedit karya: <strong>{{ $portfolio->title }}</strong>.
                Kosongkan field gambar atau PDF jika tidak ingin menggantinya — file lama akan tetap dipertahankan.
            </div>
        </div>

        {{-- ── FORM ── --}}
        <form
            method="POST"
            action="{{ route('siswa.portfolio.update', $portfolio) }}"
            enctype="multipart/form-data"
            id="editForm"
        >
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">

                {{-- ============ LEFT: IMAGE ============ --}}
                <div>
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-header-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="card-header-title">Thumbnail Karya</div>
                                <div class="card-header-sub">Klik atau seret gambar baru untuk mengganti</div>
                            </div>
                        </div>
                        <div class="form-card-body">

                            {{-- Drop Zone - Existing image shown by default --}}
                            <div
                                class="drop-zone has-preview"
                                id="imageDropZone"
                                onclick="document.getElementById('imageInput').click()"
                            >
                                {{-- Hidden prompt (shown when no existing image somehow) --}}
                                <div class="drop-zone-prompt" id="imagePrompt" style="display:none;">
                                    <div class="drop-icon-wrap">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="drop-title">Pilih atau letakkan gambar baru di sini</div>
                                        <div class="drop-sub" style="margin-top:5px;">
                                            Klik area ini atau seret file gambar.
                                        </div>
                                    </div>
                                    <div class="drop-types">
                                        <span class="drop-type-pill">JPG</span>
                                        <span class="drop-type-pill">PNG</span>
                                        <span class="drop-type-pill">WEBP</span>
                                        <span class="drop-type-pill">Max 2MB</span>
                                    </div>
                                </div>

                                {{-- Preview: shown with existing image by default --}}
                                <div class="preview-wrap" id="imagePreviewWrap">
                                    <img
                                        src="{{ asset('storage/' . $portfolio->image_path) }}"
                                        alt="{{ $portfolio->title }}"
                                        class="preview-img"
                                        id="imagePreviewImg"
                                    >
                                    <div class="preview-overlay">
                                        <button
                                            type="button"
                                            class="preview-change-btn"
                                            onclick="event.stopPropagation(); document.getElementById('imageInput').click()"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            Ganti Gambar
                                        </button>
                                        <div class="preview-name" id="imageFileName">Gambar saat ini tersimpan</div>
                                    </div>
                                    <div class="preview-badge" id="previewBadge" style="display:block;">&#10003; Gambar Aktif</div>
                                    <div class="existing-badge" id="existingBadge">Foto Lama</div>
                                </div>
                            </div>

                            <input
                                type="file"
                                id="imageInput"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp"
                                style="display:none;"
                            >

                            @error('image')
                                <div class="field-error" style="margin-top:10px;">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                    </div>

                    {{-- Tips --}}
                    <div class="tips-card" style="margin-top:16px;">
                        <div class="tips-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tips Edit
                        </div>
                        <div class="tips-item">
                            <div class="tips-bullet"></div>
                            Jika tidak mengunggah gambar baru, gambar lama akan tetap dipertahankan secara otomatis.
                        </div>
                        <div class="tips-item">
                            <div class="tips-bullet"></div>
                            Perbarui deskripsi secara berkala agar karya terasa hidup dan relevan.
                        </div>
                        <div class="tips-item">
                            <div class="tips-bullet"></div>
                            Gunakan resolusi minimal <strong style="color:rgba(255,255,255,0.45);">800×600px</strong> untuk tampilan terbaik.
                        </div>
                    </div>
                </div>

                {{-- ============ RIGHT: METADATA ============ --}}
                <div>
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-header-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="card-header-title">Informasi Karya</div>
                                <div class="card-header-sub">Perbarui metadata karya Anda</div>
                            </div>
                        </div>
                        <div class="form-card-body">

                            {{-- Judul --}}
                            <div class="field-wrap">
                                <label for="title" class="field-label">
                                    Judul Karya <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <input
                                        type="text"
                                        id="title"
                                        name="title"
                                        value="{{ old('title', $portfolio->title) }}"
                                        placeholder="Judul karya Anda"
                                        class="form-input {{ $errors->has('title') ? 'is-error' : '' }}"
                                    >
                                </div>
                                @error('title')
                                    <div class="field-error">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="field-wrap">
                                <label for="category_id" class="field-label">
                                    Kategori <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <select
                                        id="category_id"
                                        name="category_id"
                                        class="form-select {{ $errors->has('category_id') ? 'is-error' : '' }}"
                                    >
                                        <option value="" disabled>— Pilih Kategori Karya —</option>
                                        @foreach($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                {{ old('category_id', $portfolio->category_id) == $category->id ? 'selected' : '' }}
                                            >
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                @error('category_id')
                                    <div class="field-error">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="field-wrap">
                                <label for="description" class="field-label">
                                    Deskripsi <span class="req">*</span>
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    placeholder="Ceritakan konsep, proses kreatif, tools yang digunakan..."
                                    class="form-textarea {{ $errors->has('description') ? 'is-error' : '' }}"
                                >{{ old('description', $portfolio->description) }}</textarea>
                                @error('description')
                                    <div class="field-error">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PDF Upload --}}
                            <div class="field-wrap">
                                <label class="field-label">
                                    File Dokumen PDF
                                    <span style="color:rgba(255,255,255,0.2); font-weight:500; text-transform:none; letter-spacing:0; margin-left:6px; font-size:0.65rem;">(Opsional — ganti atau pertahankan)</span>
                                </label>

                                <div
                                    class="pdf-upload-area"
                                    id="pdfUploadArea"
                                    onclick="document.getElementById('pdfInput').click()"
                                >
                                    <div class="pdf-icon-box">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div style="flex:1; min-width:0;">
                                        <div class="pdf-text-main" id="pdfTextMain">
                                            {{ $portfolio->file_pdf_path ? 'Klik untuk mengganti file PDF' : 'Klik untuk memilih file PDF' }}
                                        </div>
                                        <div class="pdf-text-sub">Format PDF &bull; Maksimal 5MB</div>
                                    </div>
                                    <div style="flex-shrink:0;">
                                        <span style="font-size:0.65rem; font-weight:800; color:rgba(255,255,255,0.18); background:rgba(255,255,255,0.04); border:1px solid var(--border); padding:3px 9px; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">
                                            Browse
                                        </span>
                                    </div>
                                </div>

                                <input
                                    type="file"
                                    id="pdfInput"
                                    name="file_pdf"
                                    accept=".pdf"
                                    style="display:none;"
                                >

                                {{-- Existing PDF indicator --}}
                                @if($portfolio->file_pdf_path)
                                    <div style="margin-top:10px; display:flex; align-items:center; gap:8px; background:rgba(34,197,94,0.06); border:1px solid rgba(34,197,94,0.18); border-radius:10px; padding:10px 14px;">
                                        <svg style="width:14px;height:14px;color:#4ade80;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span style="font-size:0.72rem; font-weight:600; color:rgba(74,222,128,0.8); line-height:1.5;">
                                            File PDF saat ini: <strong style="color:#4ade80;">Tersimpan.</strong>
                                            Unggah file baru untuk menimpanya.
                                        </span>
                                    </div>
                                @endif

                                @error('file_pdf')
                                    <div class="field-error" style="margin-top:8px;">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Buttons --}}
                            <div style="padding-top:8px; border-top:1px solid var(--border); margin-top:4px;">
                                <button type="submit" class="btn-submit">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Simpan Perubahan</span>
                                </button>

                                <a href="{{ route('siswa.dashboard') }}"
                                   style="display:block; text-align:center; margin-top:12px; font-size:0.78rem; font-weight:700; color:rgba(255,255,255,0.2); text-decoration:none; transition:color 0.22s ease;"
                                   onmouseover="this.style.color='rgba(255,255,255,0.5)'"
                                   onmouseout="this.style.color='rgba(255,255,255,0.2)'">
                                    Batalkan &amp; Kembali
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>

        {{-- Footer --}}
        <div style="margin-top:48px; padding-top:24px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <span style="font-size:0.7rem; color:rgba(255,255,255,0.14);">
                &copy; {{ date('Y') }} <strong style="color:rgba(255,255,255,0.26);">DKV SMEKDA</strong>
                &nbsp;&bull;&nbsp; SMK Negeri 2 Padang Panjang
            </span>
            <span style="font-size:0.7rem; color:rgba(255,255,255,0.12);">
                Dikembangkan untuk Skripsi oleh <strong style="color:rgba(255,255,255,0.22);">Rafli</strong> &mdash; 2026
            </span>
        </div>

    </div>
</div>

{{-- ================================================================
     JAVASCRIPT
================================================================ --}}
<script>
    /* ── IMAGE LIVE PREVIEW ── */
    const imageInput       = document.getElementById('imageInput');
    const imageDropZone    = document.getElementById('imageDropZone');
    const imagePrompt      = document.getElementById('imagePrompt');
    const imagePreviewWrap = document.getElementById('imagePreviewWrap');
    const imagePreviewImg  = document.getElementById('imagePreviewImg');
    const imageFileName    = document.getElementById('imageFileName');
    const previewBadge     = document.getElementById('previewBadge');
    const existingBadge    = document.getElementById('existingBadge');

    function applyNewImagePreview(file) {
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviewImg.src        = e.target.result;
            imagePrompt.style.display  = 'none';
            imagePreviewWrap.style.display = 'block';

            // Update badges
            previewBadge.textContent   = '✓ Foto Baru';
            previewBadge.style.display = 'block';
            previewBadge.style.borderColor = 'rgba(220,38,38,0.5)';
            previewBadge.style.color   = '#fca5a5';

            if (existingBadge) existingBadge.style.display = 'none';

            // Update filename display
            const trimmed = file.name.length > 32
                ? file.name.substring(0, 29) + '...'
                : file.name;
            imageFileName.textContent = trimmed;

            imageDropZone.classList.add('has-preview');
        };
        reader.readAsDataURL(file);
    }

    imageInput.addEventListener('change', () => {
        if (imageInput.files[0]) applyNewImagePreview(imageInput.files[0]);
    });

    /* ── DRAG & DROP ── */
    ['dragenter', 'dragover'].forEach(evt => {
        imageDropZone.addEventListener(evt, (e) => {
            e.preventDefault(); e.stopPropagation();
            imageDropZone.classList.add('drag-over');
        });
    });

    ['dragleave', 'dragend'].forEach(evt => {
        imageDropZone.addEventListener(evt, (e) => {
            e.preventDefault(); e.stopPropagation();
            imageDropZone.classList.remove('drag-over');
        });
    });

    imageDropZone.addEventListener('drop', (e) => {
        e.preventDefault(); e.stopPropagation();
        imageDropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            imageInput.files = dt.files;
            applyNewImagePreview(file);
        }
    });

    /* ── PDF HANDLER ── */
    const pdfInput      = document.getElementById('pdfInput');
    const pdfUploadArea = document.getElementById('pdfUploadArea');
    const pdfTextMain   = document.getElementById('pdfTextMain');

    pdfInput.addEventListener('change', () => {
        const file = pdfInput.files[0];
        if (file) {
            const trimmed = file.name.length > 36
                ? file.name.substring(0, 33) + '...'
                : file.name;
            pdfTextMain.textContent = trimmed;
            pdfUploadArea.classList.add('has-file');
        }
    });

    /* ── INPUT ICON FOCUS COLOR ── */
    document.querySelectorAll('.form-input, .form-select').forEach(el => {
        const wrap = el.closest('.input-wrap');
        const icon = wrap?.querySelector('.input-icon');
        if (!icon) return;
        el.addEventListener('focus',  () => { icon.style.color = 'var(--red)'; });
        el.addEventListener('blur',   () => { icon.style.color = 'rgba(255,255,255,0.2)'; });
    });
</script>

</body>
</html>