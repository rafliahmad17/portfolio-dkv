{{-- resources/views/siswa/achievement/index.blade.php --}}
{{-- Halaman "Prestasi & Sertifikat" milik siswa yang login.
     Struktur & gaya mengikuti resources/views/siswa/dashboard.blade.php
     (dark theme #080808, aksen merah #dc2626, Tailwind CDN). --}}
@extends('layouts.app')

@section('title', 'Prestasi & Sertifikat — DKV SMEKDA Portal')

@section('content')

{{-- Tailwind CDN & font Inter dipertahankan di sini sebagai pengaman ganda —
     idempotent, tidak mengubah tailwind.config, aman meski layouts.app sudah memuatnya. --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<style>
        :root {
            --red:        #dc2626;
            --red-bright: #ef4444;
            --red-glow:   rgba(220,38,38,0.45);
            --red-soft:   rgba(220,38,38,0.10);
            --red-border: rgba(220,38,38,0.35);
            --surface:    rgba(255,255,255,0.03);
            --border:     rgba(255,255,255,0.1);
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
            top: -200px; left: 180px; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(220,38,38,0.09) 0%, transparent 65%);
            animation: blobFloat 10s ease-in-out infinite alternate;
        }
        .blob-2 {
            bottom: -150px; right: -100px; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(220,38,38,0.06) 0%, transparent 65%);
            animation: blobFloat 13s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobFloat {
            0%   { transform: scale(1)    translate(0,0);       }
            100% { transform: scale(1.15) translate(20px,15px); }
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #080808; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 10px; }

        /* ── SIDEBAR (sama seperti dashboard) ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: 260px; height: 100vh;
            background: rgba(8,8,8,0.88);
            backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column; z-index: 50; overflow-y: auto;
        }
        .sidebar::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220,38,38,0.4), transparent);
        }
        .sidebar-logo { padding: 28px 24px 22px; border-bottom: 1px solid var(--border); }
        .logo-wordmark {
            font-size: 0.78rem; font-weight: 900; letter-spacing: 3px; text-transform: uppercase;
            color: rgba(255,255,255,0.85); display: flex; align-items: center; gap: 9px;
        }
        .logo-icon {
            width: 26px; height: 26px; background: var(--red); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px var(--red-glow); flex-shrink: 0;
        }
        .logo-icon svg { width: 13px; height: 13px; }
        .sidebar-profile { padding: 20px 24px; border-bottom: 1px solid var(--border); display:flex; flex-direction:column; gap:12px; }
        .sidebar-profile-row { display:flex; align-items:center; gap:12px; }
        .profile-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; color: white; flex-shrink: 0;
            box-shadow: 0 0 18px rgba(220,38,38,0.3);
        }
        .profile-name {
            font-size: 0.82rem; font-weight: 700; color: #f5f5f5; line-height: 1.3; margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .profile-nis { font-size: 0.7rem; color: rgba(255,255,255,0.3); margin-bottom: 6px; }
        .badge-role {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.12); border: 1px solid rgba(220,38,38,0.25);
            color: #fca5a5; padding: 2px 9px; border-radius: 30px;
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;
        }
        .badge-role-dot {
            width: 5px; height: 5px; background: var(--red); border-radius: 50%;
            animation: pulseDot 1.5s ease-in-out infinite; box-shadow: 0 0 6px var(--red-glow);
        }
        @keyframes pulseDot {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.35; transform: scale(0.65); }
        }
        .sidebar-nav { flex: 1; padding: 20px 14px; }
        .nav-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.18); padding: 0 10px; margin-bottom: 8px; margin-top: 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 11px; padding: 10px 12px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.38);
            text-decoration: none; transition: all 0.22s ease; border: 1px solid transparent;
            margin-bottom: 3px; position: relative;
        }
        .nav-item:hover { color: rgba(255,255,255,0.75); background: rgba(255,255,255,0.04); border-color: var(--border); }
        .nav-item.active { color: #fca5a5; background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.2); }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 18px; background: var(--red); border-radius: 0 3px 3px 0;
            box-shadow: 0 0 10px var(--red-glow);
        }
        .nav-item.active svg { color: var(--red); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; transition: color 0.22s ease; }
        .sidebar-footer { padding: 14px; border-top: 1px solid var(--border); }
        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 11px; padding: 10px 12px;
            border-radius: 10px; background: none; border: 1px solid transparent;
            color: rgba(255,255,255,0.28); font-size: 0.82rem; font-weight: 600;
            font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.22s ease;
        }
        .btn-logout:hover { color: #fca5a5; background: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.18); }
        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        .main-content { margin-left: 260px; min-height: 100vh; position: relative; z-index: 1; }
        .topbar {
            position: sticky; top: 0; z-index: 30;
            background: rgba(8,8,8,0.85);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border); padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-title { font-size: 0.82rem; font-weight: 700; color: rgba(255,255,255,0.25); letter-spacing: 0.5px; }
        .topbar-title span { color: rgba(255,255,255,0.55); margin-left: 6px; }
        .topbar-badge {
            display: flex; align-items: center; gap: 6px;
            background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.18);
            border-radius: 30px; padding: 5px 13px; font-size: 0.7rem; font-weight: 700;
            color: rgba(220,38,38,0.7); letter-spacing: 0.5px;
        }
        .page-inner { padding: 40px 36px 60px; }

        .greeting-eyebrow {
            font-size: 0.68rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase;
            color: rgba(220,38,38,0.7); margin-bottom: 10px;
        }
        .greeting-headline {
            font-size: clamp(1.6rem, 2.5vw, 2.2rem); font-weight: 900; letter-spacing: -1px;
            line-height: 1.15; color: #f5f5f5; margin-bottom: 8px;
        }
        .greeting-headline .name-highlight { color: var(--red); text-shadow: 0 0 30px rgba(220,38,38,0.4); }
        .greeting-sub { font-size: 0.875rem; color: rgba(255,255,255,0.3); font-weight: 400; }

        .flash-success {
            display: flex; align-items: center; gap: 12px;
            background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2);
            border-radius: 12px; padding: 14px 18px; margin-bottom: 28px;
            font-size: 0.82rem; font-weight: 600; color: #86efac;
        }
        .flash-success svg { width: 16px; height: 16px; flex-shrink: 0; color: #4ade80; }

        .section-title { font-size: 1.05rem; font-weight: 800; color: #f5f5f5; letter-spacing: -0.3px; }
        .section-sub { font-size: 0.75rem; color: rgba(255,255,255,0.25); margin-top: 3px; font-weight: 500; }

        .btn-add {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--red); border: 1px solid var(--red); color: white;
            padding: 9px 18px; border-radius: 10px; font-size: 0.78rem; font-weight: 700;
            font-family: 'Inter', sans-serif; cursor: pointer; text-decoration: none;
            transition: all 0.3s ease; position: relative; overflow: hidden;
        }
        .btn-add::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, #b91c1c, #ef4444); opacity: 0; transition: opacity 0.3s ease;
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 8px 28px var(--red-glow), 0 0 0 3px rgba(220,38,38,0.15); }
        .btn-add:hover::before { opacity: 1; }
        .btn-add span, .btn-add svg { position: relative; z-index: 1; }
        .btn-add svg { width: 15px; height: 15px; }

        /* ── ACHIEVEMENT CARD ── */
        .achv-card {
            background: rgba(255,255,255,0.025); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden; transition: all 0.3s ease; position: relative;
        }
        .achv-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }
        .achv-card:hover {
            transform: translateY(-5px); border-color: rgba(220,38,38,0.22);
            box-shadow: 0 24px 60px rgba(0,0,0,0.45), 0 0 0 1px rgba(220,38,38,0.08), 0 0 50px rgba(220,38,38,0.06);
        }
        .achv-thumb-wrap { position: relative; overflow: hidden; height: 150px; background:#111; }
        .achv-thumb { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease; }
        .achv-card:hover .achv-thumb { transform: scale(1.04); }
        .achv-thumb-placeholder {
            width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.02);
        }
        .achv-thumb-placeholder svg { width: 40px; height: 40px; color: rgba(255,255,255,0.08); }

        .achv-body { padding: 16px; }
        .achv-type {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.2);
            color: #fca5a5; font-size: 0.62rem; font-weight: 800; letter-spacing: 1.5px;
            text-transform: uppercase; padding: 2px 9px; border-radius: 20px; margin-bottom: 9px;
        }
        .achv-type svg { width: 11px; height: 11px; }
        .achv-title {
            font-size: 0.9rem; font-weight: 800; color: #f5f5f5; letter-spacing: -0.2px;
            line-height: 1.35; margin-bottom: 5px;
        }
        .achv-issuer {
            display: flex; align-items: center; gap: 5px;
            font-size: 0.75rem; color: rgba(255,255,255,0.32); margin-bottom: 8px; font-weight: 500;
        }
        .achv-issuer svg { width: 12px; height: 12px; flex-shrink: 0; }
        .achv-desc {
            font-size: 0.75rem; color: rgba(255,255,255,0.28); line-height: 1.5; margin-bottom: 12px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .achv-meta {
            display: flex; align-items: center; gap: 5px; font-size: 0.68rem;
            color: rgba(255,255,255,0.2); margin-bottom: 14px;
        }
        .achv-meta svg { width: 11px; height: 11px; }

        .achv-actions { display: flex; gap: 8px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.05); flex-wrap: wrap; }
        .btn-action-edit, .btn-action-file {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px; border-radius: 9px; background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07); color: rgba(255,255,255,0.35);
            font-size: 0.73rem; font-weight: 700; text-decoration: none; transition: all 0.22s ease;
        }
        .btn-action-edit:hover, .btn-action-file:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15); color: #f5f5f5; }
        .btn-action-edit svg, .btn-action-file svg { width: 13px; height: 13px; }
        .btn-action-delete {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px; border-radius: 9px; background: rgba(220,38,38,0.06);
            border: 1px solid rgba(220,38,38,0.12); color: rgba(220,38,38,0.5);
            font-size: 0.73rem; font-weight: 700; cursor: pointer; width: 100%;
            font-family: 'Inter', sans-serif; transition: all 0.22s ease;
        }
        .btn-action-delete:hover { background: rgba(220,38,38,0.14); border-color: rgba(220,38,38,0.3); color: #f87171; }
        .btn-action-delete svg { width: 13px; height: 13px; }

        .empty-wrap { padding: 80px 40px; text-align: center; }
        .empty-icon {
            width: 72px; height: 72px; background: rgba(255,255,255,0.03); border: 1px solid var(--border);
            border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
        }
        .empty-icon svg { width: 32px; height: 32px; color: rgba(255,255,255,0.12); }
        .empty-title { font-size: 1rem; font-weight: 800; color: rgba(255,255,255,0.4); margin-bottom: 8px; }
        .empty-sub { font-size: 0.82rem; color: rgba(255,255,255,0.2); margin-bottom: 28px; line-height: 1.6; }

        /* ── MODAL TAMBAH ── */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 100;
            background: rgba(0,0,0,0.65); backdrop-filter: blur(4px);
            display: flex; align-items: flex-start; justify-content: center;
            padding: 48px 20px; overflow-y: auto;
            opacity: 0; pointer-events: none; transition: opacity 0.25s ease;
        }
        .modal-overlay.open { opacity: 1; pointer-events: auto; }
        .modal-box {
            width: 100%; max-width: 640px;
            background: #0d0d0d; border: 1px solid var(--border); border-radius: 20px;
            overflow: hidden; position: relative;
            transform: translateY(-16px); transition: transform 0.25s ease;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6);
        }
        .modal-overlay.open .modal-box { transform: translateY(0); }
        .modal-header {
            padding: 20px 24px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-header-title { font-size: 0.95rem; font-weight: 800; color: #f5f5f5; }
        .modal-header-sub { font-size: 0.72rem; color: rgba(255,255,255,0.25); margin-top: 2px; }
        .modal-close {
            width: 32px; height: 32px; border-radius: 9px; background: rgba(255,255,255,0.04);
            border: 1px solid var(--border); color: rgba(255,255,255,0.4);
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            transition: all 0.2s ease; flex-shrink: 0;
        }
        .modal-close:hover { background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.25); color: #fca5a5; }
        .modal-close svg { width: 15px; height: 15px; }
        .modal-body { padding: 24px; max-height: 70vh; overflow-y: auto; }

        .field-label {
            display: block; font-size: 0.7rem; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 8px;
        }
        .field-label .req { color: var(--red); margin-left: 3px; }
        .field-wrap { margin-bottom: 18px; }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-input, .form-select, .form-textarea {
            width: 100%; background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08); border-radius: 11px;
            padding: 11px 14px; font-size: 0.85rem; font-weight: 500;
            font-family: 'Inter', sans-serif; color: #f5f5f5; outline: none;
            caret-color: var(--red); transition: all 0.25s ease;
        }
        .form-select { appearance: none; -webkit-appearance: none; cursor: pointer; color: rgba(255,255,255,0.7); }
        .form-select option { background: #1a1a1a; color: #f5f5f5; }
        .form-textarea { resize: none; line-height: 1.6; }
        .form-input::placeholder, .form-textarea::placeholder { color: rgba(255,255,255,0.18); }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--red); background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15); color: #f5f5f5;
        }
        .form-input.is-error, .form-select.is-error, .form-textarea.is-error {
            border-color: var(--red-bright) !important; background: rgba(239,68,68,0.06) !important;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.14) !important;
        }
        .field-hint { font-size: 0.68rem; color: rgba(255,255,255,0.2); margin-top: 6px; }
        .field-error {
            margin-top: 7px; font-size: 0.73rem; font-weight: 600; color: #f87171;
            display: flex; align-items: center; gap: 6px;
        }
        .field-error svg { width: 12px; height: 12px; flex-shrink: 0; }

        .file-drop {
            border: 1.5px dashed rgba(255,255,255,0.1); border-radius: 12px; padding: 14px 16px;
            display: flex; align-items: center; gap: 12px; cursor: pointer;
            background: rgba(255,255,255,0.02); transition: all 0.25s ease;
        }
        .file-drop:hover { border-color: rgba(220,38,38,0.3); background: rgba(220,38,38,0.04); }
        .file-drop-icon {
            width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.04);
            border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .file-drop-icon svg { width: 16px; height: 16px; color: rgba(255,255,255,0.2); }
        .file-drop:hover .file-drop-icon { background: var(--red-soft); border-color: rgba(220,38,38,0.25); }
        .file-drop:hover .file-drop-icon svg { color: var(--red); }
        .file-drop-text { font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.4); }
        .file-drop-sub { font-size: 0.66rem; color: rgba(255,255,255,0.18); margin-top: 1px; }

        .error-alert {
            background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.3);
            border-left: 3px solid var(--red); border-radius: 14px; padding: 16px 20px; margin-bottom: 28px;
        }
        .error-alert-title { font-size: 0.8rem; font-weight: 800; color: #fca5a5; display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .error-alert-title svg { width: 16px; height: 16px; flex-shrink: 0; }
        .error-alert-list { list-style: none; }
        .error-alert-list li { font-size: 0.75rem; color: rgba(252,165,165,0.8); padding: 3px 0; display: flex; align-items: flex-start; gap: 7px; }
        .error-alert-list li::before { content: '✕'; color: var(--red); font-weight: 900; font-size: 0.65rem; margin-top: 1px; flex-shrink: 0; }

        .btn-submit {
            width: 100%; background: var(--red); color: white; border: none;
            border-radius: 12px; padding: 14px 24px; font-size: 0.88rem; font-weight: 800;
            font-family: 'Inter', sans-serif; letter-spacing: 0.3px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            position: relative; overflow: hidden; transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(220,38,38,0.3); margin-top: 4px;
        }
        .btn-submit::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, #b91c1c, #ef4444); opacity: 0; transition: opacity 0.3s ease; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 40px var(--red-glow), 0 0 0 4px rgba(220,38,38,0.15); }
        .btn-submit:hover::before { opacity: 1; }
        .btn-submit span, .btn-submit svg { position: relative; z-index: 1; }
        .btn-submit svg { width: 17px; height: 17px; }

        @media (max-width: 768px) {
            .field-row { grid-template-columns: 1fr; }
        }

        /* ================================================================
           STAGE 1 — MOBILE-FIRST & RESPONSIVE ENHANCEMENTS
           Lapisan aditif: hanya menambah properti baru / menimpa properti
           spesifik lewat urutan cascade. Tidak ada rule lama yang dihapus.
        ================================================================ */

        /* Off-canvas sidebar */
        .sidebar { transform: translateX(0); transition: transform .3s ease-in-out; max-width: 85vw; }
        .sidebar-overlay {
            position: fixed; inset: 0; z-index: 45; background: rgba(0,0,0,0.6);
            backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);
            opacity: 0; pointer-events: none; transition: opacity .3s ease;
        }
        .sidebar-overlay.open { opacity: 1; pointer-events: auto; }
        .sidebar-logo-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .sidebar-close-btn {
            display: none; width: 44px; height: 44px; align-items: center; justify-content: center;
            border-radius: 10px; background: rgba(255,255,255,0.04); border: 1px solid var(--border);
            color: rgba(255,255,255,0.4); cursor: pointer; flex-shrink: 0; transition: all .2s ease; font: inherit;
        }
        .sidebar-close-btn:hover { background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.25); color: #fca5a5; }
        .sidebar-close-btn svg { width: 16px; height: 16px; }
        .hamburger-btn {
            display: none; width: 44px; height: 44px; align-items: center; justify-content: center;
            border-radius: 10px; background: rgba(255,255,255,0.04); border: 1px solid var(--border);
            color: rgba(255,255,255,0.55); cursor: pointer; flex-shrink: 0; transition: all .2s ease; font: inherit;
        }
        .hamburger-btn:hover { background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.22); color: #fca5a5; }
        .hamburger-btn svg { width: 19px; height: 19px; }
        .topbar-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .topbar-title { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Fluid grid — 1 kol mobile / 2 kol tablet / 3 kol desktop */
        .achv-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media (min-width: 641px) and (max-width: 1023px) { .achv-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .achv-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; } }

        /* Aspect ratio elegan pada thumbnail — tidak "gepeng" di kolom manapun */
        .achv-thumb-wrap { height: auto; aspect-ratio: 16 / 9; }
        .achv-title, .achv-desc, .achv-issuer { overflow-wrap: break-word; }

        /* Empty state — dashed border sesuai spesifikasi */
        .empty-wrap { border: 1.5px dashed rgba(255,255,255,0.14); border-radius: 20px; }

        /* Touch target minimal 44x44px */
        .nav-item, .btn-logout, .btn-add, .btn-action-edit, .btn-action-file, .btn-action-delete { min-height: 44px; }
        .modal-close { width: 44px; height: 44px; border-radius: 12px; font: inherit; }
        .form-input, .form-select, .form-textarea { min-height: 44px; }

        /* Input file tersembunyi secara visual tapi tetap bisa difokus keyboard */
        .visually-hidden-file {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
        }
        .file-drop:focus-within {
            border-color: var(--red); background: rgba(220,38,38,0.04);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15);
        }

        /* Focus ring lembut untuk navigasi keyboard */
        .nav-item:focus-visible,
        .btn-logout:focus-visible,
        .btn-add:focus-visible,
        .btn-action-edit:focus-visible,
        .btn-action-file:focus-visible,
        .btn-action-delete:focus-visible,
        .modal-close:focus-visible,
        .hamburger-btn:focus-visible,
        .sidebar-close-btn:focus-visible {
            outline: none; box-shadow: 0 0 0 3px rgba(220,38,38,0.35);
        }

        /* Modal tidak pernah keluar layar, di layar sependek apapun */
        .modal-box { max-height: calc(100vh - 32px); display: flex; flex-direction: column; }
        .modal-header { flex-shrink: 0; }
        .modal-body { flex: 1 1 auto; min-height: 0; max-height: none; }

        @media (max-width: 860px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 28px 0 60px rgba(0,0,0,0.5); }
            .sidebar-close-btn { display: flex; }
            .hamburger-btn { display: flex; }
            .main-content { margin-left: 0; }
            .topbar { padding: 12px 16px; }
            .topbar-badge { display: none; }
            .page-inner { padding: 28px 18px 48px; }
        }
        @media (max-width: 640px) {
            .btn-add { width: 100%; justify-content: center; }
            .empty-wrap { padding: 56px 20px; }
            .modal-overlay { padding: 16px; align-items: flex-start; }
            .modal-header { padding: 16px 18px; }
            .modal-body { padding: 18px; }
        }
        @media (max-width: 480px) {
            .page-inner { padding: 22px 14px 40px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition-duration: .001ms !important;
                animation-duration: .001ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>

<div class="bg-grid"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ================================================================
     SIDEBAR
================================================================ --}}
<aside class="sidebar" id="appSidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-row">
            <div class="logo-wordmark">
                <div class="logo-icon">
                    <svg fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                DKV<span style="color:var(--red);">.</span>SMEKDA
            </div>
            <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" onclick="closeSidebar()" aria-label="Tutup menu navigasi">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div style="font-size:0.62rem; color:rgba(255,255,255,0.2); margin-top:4px; letter-spacing:1px; text-transform:uppercase; font-weight:600; padding-left:35px;">
            Portal Siswa
        </div>
    </div>

    <div class="sidebar-profile">
        <div class="sidebar-profile-row">
            <div class="profile-avatar" style="overflow: hidden;">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1; min-width:0;">
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-nis">NIS: {{ auth()->user()->nis_nip ?? '—' }}</div>
            </div>
        </div>
        <div class="badge-role">
            <div class="badge-role-dot"></div>
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

        <a href="{{ route('siswa.portfolio.print') }}" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Cetak Portfolio
        </a>

        <a href="{{ route('siswa.achievement.index') }}" class="nav-item active" aria-current="page">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
            </svg>
            Prestasi &amp; Sertifikat
        </a>

        <div class="nav-label" style="margin-top:20px;">Akun</div>

        <a href="{{ route('siswa.profile.edit') }}" class="nav-item">
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
        <div class="topbar-left">
            <button type="button" class="hamburger-btn" id="hamburgerBtn" onclick="openSidebar()" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="appSidebar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                </svg>
            </button>
            <div class="topbar-title">
                Portal DKV SMEKDA <span>/</span> Prestasi &amp; Sertifikat
            </div>
        </div>
        <div class="topbar-badge">
            <div class="badge-role-dot"></div>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="page-inner">

        {{-- Flash Success --}}
        @if(session('success'))
            <div class="flash-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Global error alert (ditampilkan jika submit modal tambah gagal validasi) --}}
        @if($errors->any())
            <div class="error-alert">
                <div class="error-alert-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Form gagal disimpan — ada {{ $errors->count() }} kesalahan yang harus diperbaiki:
                </div>
                <ul class="error-alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Greeting --}}
        <div style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:20px; margin-bottom:32px;">
            <div>
                <div class="greeting-eyebrow">&#9654; Rekam Jejak Prestasi</div>
                <h1 class="greeting-headline">
                    Prestasi &amp; <span class="name-highlight">Sertifikat</span>
                </h1>
                <p class="greeting-sub">Kelola daftar penghargaan dan sertifikat yang pernah kamu raih.</p>
            </div>
        </div>

        {{-- ── ACHIEVEMENT SECTION ── --}}
        <div style="background:rgba(255,255,255,0.018); border:1px solid var(--border); border-radius:20px; overflow:hidden;">

            <div style="padding:20px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div>
                    <div class="section-title">Daftar Prestasi &amp; Sertifikat</div>
                    <div class="section-sub">
                        {{ $achievements->count() }} data tersimpan &bull; Diurutkan dari tanggal perolehan terbaru
                    </div>
                </div>
                <button type="button" class="btn-add" onclick="openAchievementModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Prestasi/Sertifikat</span>
                </button>
            </div>

            <div style="padding:24px;">

                @if($achievements->isEmpty())

                    <div class="empty-wrap">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
                            </svg>
                        </div>
                        <div class="empty-title">Belum ada prestasi atau sertifikat.</div>
                        <div class="empty-sub">
                            Simpan rekam jejak prestasi dan sertifikat yang pernah kamu raih<br>
                            agar bisa ditampilkan di portofolio publikmu.
                        </div>
                        <button type="button" class="btn-add" style="margin:0 auto;" onclick="openAchievementModal()">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Tambah Data Pertama</span>
                        </button>
                    </div>

                @else

                    <div class="achv-grid">

                        @foreach($achievements as $achievement)
                        <div class="achv-card">
                            <div class="achv-thumb-wrap">
                                @if($achievement->image_path)
                                    <img src="{{ asset('storage/' . $achievement->image_path) }}" alt="{{ $achievement->title }}" class="achv-thumb" loading="lazy" decoding="async">
                                @else
                                    <div class="achv-thumb-placeholder">
                                        @if($achievement->type === 'prestasi')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
                                            </svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="achv-body">
                                <div class="achv-type">
                                    @if($achievement->type === 'prestasi')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 17a3 3 0 013-3h0a3 3 0 013 3v3H9v-3zM6 6h12v2a6 6 0 01-12 0V6zm0 0H4a2 2 0 000 4h2M18 6h2a2 2 0 010 4h-2"/>
                                        </svg>
                                        Prestasi
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Sertifikat
                                    @endif
                                </div>
                                <div class="achv-title">{{ $achievement->title }}</div>

                                @if($achievement->issuer)
                                    <div class="achv-issuer">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m4-14h6m-6 4h6m-6 4h6M9 21v-4a1 1 0 011-1h4a1 1 0 011 1v4"/>
                                        </svg>
                                        {{ $achievement->issuer }}
                                    </div>
                                @endif

                                @if($achievement->description)
                                    <div class="achv-desc">{{ $achievement->description }}</div>
                                @endif

                                @if($achievement->achieved_at)
                                    <div class="achv-meta">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $achievement->achieved_at->translatedFormat('d M Y') }}
                                    </div>
                                @endif

                                <div class="achv-actions">
                                    @if($achievement->file_path)
                                        <a href="{{ asset('storage/' . $achievement->file_path) }}" target="_blank" rel="noopener" class="btn-action-file">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Dokumen
                                        </a>
                                    @endif

                                    <a href="{{ route('siswa.achievement.edit', $achievement) }}" class="btn-action-edit">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                </div>

                                <form
                                    method="POST"
                                    action="{{ route('siswa.achievement.destroy', $achievement) }}"
                                    style="margin-top:8px;"
                                    class="js-loading-form"
                                    data-loading-text="Menghapus..."
                                    onsubmit="return confirm('Hapus prestasi/sertifikat ini? Tindakan tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach

                    </div>

                @endif

            </div>

        </div>

        {{-- Footer Strip --}}
        <div style="margin-top:48px; padding-top:24px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <span style="font-size:0.7rem; color:rgba(255,255,255,0.15);">
                &copy; {{ date('Y') }} <strong style="color:rgba(255,255,255,0.28);">DKV SMEKDA</strong>
                &nbsp;&bull;&nbsp; SMK Negeri 2 Padang Panjang
            </span>
            <span style="font-size:0.7rem; color:rgba(255,255,255,0.12);">
                Dikembangkan untuk Skripsi oleh <strong style="color:rgba(255,255,255,0.22);">Rafli</strong> &mdash; 2026
            </span>
        </div>

    </div>
</div>

{{-- ================================================================
     MODAL — TAMBAH PRESTASI/SERTIFIKAT
================================================================ --}}
<div class="modal-overlay" id="achievementModal" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="achievementModalTitle">
        <div class="modal-header">
            <div>
                <div class="modal-header-title" id="achievementModalTitle">Tambah Prestasi/Sertifikat</div>
                <div class="modal-header-sub">Lengkapi data di bawah ini, lalu simpan.</div>
            </div>
            <button type="button" class="modal-close" onclick="closeAchievementModal()" aria-label="Tutup modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('siswa.achievement.store') }}" enctype="multipart/form-data" class="js-loading-form" data-loading-text="Menyimpan...">
            @csrf
            <div class="modal-body">

                <div class="field-row">
                    <div class="field-wrap">
                        <label for="type" class="field-label">Jenis <span class="req">*</span></label>
                        <select id="type" name="type" class="form-select {{ $errors->has('type') ? 'is-error' : '' }}">
                            <option value="sertifikat" {{ old('type') == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                            <option value="prestasi" {{ old('type') == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                        </select>
                        @error('type')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-wrap">
                        <label for="achieved_at" class="field-label">Tanggal Diperoleh</label>
                        <input
                            type="date"
                            id="achieved_at"
                            name="achieved_at"
                            value="{{ old('achieved_at') }}"
                            class="form-input {{ $errors->has('achieved_at') ? 'is-error' : '' }}"
                        >
                        @error('achieved_at')
                            <div class="field-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="field-wrap">
                    <label for="title" class="field-label">Judul <span class="req">*</span></label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Contoh: Juara 1 Lomba Desain Poster Tingkat Provinsi"
                        value="{{ old('title') }}"
                        class="form-input {{ $errors->has('title') ? 'is-error' : '' }}"
                    >
                    @error('title')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label for="issuer" class="field-label">Penyelenggara / Penerbit</label>
                    <input
                        type="text"
                        id="issuer"
                        name="issuer"
                        placeholder="Contoh: Dinas Pendidikan Provinsi Sumatera Barat"
                        value="{{ old('issuer') }}"
                        class="form-input {{ $errors->has('issuer') ? 'is-error' : '' }}"
                    >
                    @error('issuer')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label for="description" class="field-label">Deskripsi</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Ceritakan singkat konteks prestasi/sertifikat ini..."
                        class="form-textarea {{ $errors->has('description') ? 'is-error' : '' }}"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label class="field-label">
                        Thumbnail / Foto
                        <span style="color:rgba(255,255,255,0.2); font-weight:500; text-transform:none; letter-spacing:0; margin-left:6px; font-size:0.65rem;">(Opsional &bull; JPG/PNG, maks 2MB)</span>
                    </label>
                    <label class="file-drop" for="imageInput">
                        <div class="file-drop-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="file-drop-text" id="imageFileText">Klik untuk memilih gambar</div>
                            <div class="file-drop-sub">Format JPG/PNG &bull; Maksimal 2MB</div>
                        </div>
                    </label>
                    <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/jpg" class="visually-hidden-file" onchange="updateFileLabel(this, 'imageFileText', 'Klik untuk memilih gambar')">
                    @error('image')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-wrap" style="margin-bottom:4px;">
                    <label class="field-label">
                        Dokumen PDF
                        <span style="color:rgba(255,255,255,0.2); font-weight:500; text-transform:none; letter-spacing:0; margin-left:6px; font-size:0.65rem;">(Opsional &bull; PDF, maks 4MB)</span>
                    </label>
                    <label class="file-drop" for="fileInput">
                        <div class="file-drop-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="file-drop-text" id="fileFileText">Klik untuk memilih file PDF</div>
                            <div class="file-drop-sub">Format PDF &bull; Maksimal 4MB</div>
                        </div>
                    </label>
                    <input type="file" id="fileInput" name="file" accept=".pdf" class="visually-hidden-file" onchange="updateFileLabel(this, 'fileFileText', 'Klik untuk memilih file PDF')">
                    @error('file')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Prestasi/Sertifikat</span>
                </button>

            </div>
        </form>
    </div>
</div>

<script>
    // ── Util: daftar elemen yang bisa menerima fokus di dalam container ──
    function getFocusable(container) {
        if (!container) return [];
        return Array.from(container.querySelectorAll(
            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        )).filter(function (el) { return el.offsetParent !== null; });
    }
    function isMobileNav() {
        return window.matchMedia('(max-width: 860px)').matches;
    }

    // ── Modal Tambah Prestasi/Sertifikat ──
    var lastFocusedBeforeModal = null;

    function openAchievementModal() {
        var modal = document.getElementById('achievementModal');
        lastFocusedBeforeModal = document.activeElement;
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        var focusables = getFocusable(modal.querySelector('.modal-box'));
        if (focusables.length) focusables[0].focus();
    }
    function closeAchievementModal() {
        var modal = document.getElementById('achievementModal');
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (lastFocusedBeforeModal && typeof lastFocusedBeforeModal.focus === 'function') {
            lastFocusedBeforeModal.focus();
        }
    }
    function updateFileLabel(input, labelId, fallback) {
        var label = document.getElementById(labelId);
        label.textContent = input.files && input.files.length > 0 ? input.files[0].name : fallback;
    }
    // Klik di luar modal-box untuk menutup
    document.getElementById('achievementModal').addEventListener('click', function (e) {
        if (e.target === this) closeAchievementModal();
    });
    // Trap fokus (Tab / Shift+Tab) selama modal terbuka
    document.getElementById('achievementModal').addEventListener('keydown', function (e) {
        if (e.key !== 'Tab') return;
        var focusables = getFocusable(this.querySelector('.modal-box'));
        if (!focusables.length) return;
        var first = focusables[0];
        var last = focusables[focusables.length - 1];
        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault(); last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault(); first.focus();
        }
    });

    // ── Sidebar off-canvas (mobile ≤ 860px) ──
    function openSidebar() {
        var sidebar  = document.getElementById('appSidebar');
        var overlay  = document.getElementById('sidebarOverlay');
        var hamburger = document.getElementById('hamburgerBtn');
        var closeBtn = document.getElementById('sidebarCloseBtn');
        sidebar.classList.add('open');
        overlay.classList.add('open');
        sidebar.inert = false;
        document.body.style.overflow = 'hidden';
        hamburger.setAttribute('aria-expanded', 'true');
        if (closeBtn) closeBtn.focus();
    }
    function closeSidebar() {
        var sidebar  = document.getElementById('appSidebar');
        var overlay  = document.getElementById('sidebarOverlay');
        var hamburger = document.getElementById('hamburgerBtn');
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        if (isMobileNav()) sidebar.inert = true;
        document.body.style.overflow = '';
        hamburger.setAttribute('aria-expanded', 'false');
        if (hamburger) hamburger.focus();
    }
    function syncSidebarForViewport() {
        var sidebar = document.getElementById('appSidebar');
        var overlay = document.getElementById('sidebarOverlay');
        if (!sidebar) return;
        if (isMobileNav()) {
            if (!sidebar.classList.contains('open')) sidebar.inert = true;
        } else {
            sidebar.inert = false;
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    }
    window.addEventListener('resize', syncSidebarForViewport);
    syncSidebarForViewport();

    // ── Escape menutup modal / sidebar, mana pun yang sedang terbuka ──
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var modal   = document.getElementById('achievementModal');
        var sidebar = document.getElementById('appSidebar');
        if (modal.classList.contains('open')) { closeAchievementModal(); return; }
        if (sidebar.classList.contains('open')) { closeSidebar(); }
    });

    // ── Loading state ringan pada submit form (cegah submit ganda) ──
    document.querySelectorAll('.js-loading-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            var label = btn.querySelector('span');
            if (label && form.dataset.loadingText) label.textContent = form.dataset.loadingText;
            btn.style.opacity = '0.65';
            btn.style.cursor = 'not-allowed';
        });
    });

    @if($errors->any())
        // Buka kembali modal secara otomatis jika submit gagal validasi,
        // supaya pesan error langsung terlihat oleh siswa.
        openAchievementModal();
    @endif
</script>

@endsection