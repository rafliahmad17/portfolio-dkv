<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Portfolio {{ $user->name }} — Cetak PDF</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {


            --ink-2: #374151;

            --soft: #9ca3af;
            --line: #e5e7eb;

            --red-soft: #fef2f2;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            font-family: 'Inter', Arial, sans-serif;
            color: var(--color-ink);
            background: #f1f1ef;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-size: 11px;
        }

        /* =========================================================
           TOOLBAR
        ========================================================= */

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            background: rgba(255,255,255,.96);
            border-bottom: 1px solid var(--line);
        }

        .toolbar-brand {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--color-ink-muted);
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .tbtn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border: 0;
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .tbtn svg {
            width: 14px;
            height: 14px;
        }

        .tbtn-gray {
            color: var(--color-ink);
            background: #f3f4f6;
            border: 1px solid var(--line);
        }

        .tbtn-red {
            color: white;
            background: var(--color-brand-600);
        }

        .tbtn-red:hover {
            background: var(--color-brand-700);
        }

        /* =========================================================
           A4
        ========================================================= */

        .stage {
            padding: 36px 0 60px;
        }

        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 14mm 15mm 12mm;
            background: var(--color-paper-elevated);
            box-shadow:
                0 2px 5px rgba(0,0,0,.04),
                0 20px 50px rgba(0,0,0,.08);
        }

        /* =========================================================
           HEADER SEKOLAH
        ========================================================= */

        .school-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 12px;
        }

        .school-logo {
            width: 38px;
            height: 38px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .school-name {
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .school-dept {
            margin-top: 3px;
            font-size: 8.5px;
            color: var(--soft);
            letter-spacing: .3px;
        }

        .top-line {
            height: 1px;
            background: var(--line);
            margin-bottom: 22px;
        }

        /* =========================================================
           IDENTITAS SISWA
        ========================================================= */

        .student-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
        }

        .student-photo {
            width: 92px;
            height: 92px;
            border-radius: 10px;
            overflow: hidden;
            background: var(--color-paper);
            border: 1px solid var(--line);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-brand-600);
            font-size: 28px;
            font-weight: 900;
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .student-name {
            font-size: 29px;
            line-height: 1.05;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .student-role {
            margin-top: 7px;
            color: var(--color-brand-600);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .student-meta {
            margin-top: 8px;
            color: var(--color-ink-muted);
            font-size: 9.5px;
            line-height: 1.6;
        }

        .section-line {
            height: 1px;
            background: var(--line);
            margin-bottom: 21px;
        }

        /* =========================================================
           SECTION
        ========================================================= */

        .section {
            margin-bottom: 22px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 11px;
        }

        .section-mark {
            width: 3px;
            height: 17px;
            background: var(--color-brand-600);
            border-radius: 2px;
        }

        .section-title {
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 2.2px;
            text-transform: uppercase;
            color: var(--color-ink);
        }

        /* =========================================================
           BIO
        ========================================================= */

        .bio-box {
            padding: 12px 14px;
            background: #fafafa;
            border-left: 2px solid var(--color-brand-600);
            border-radius: 0 7px 7px 0;
        }

        .bio-text {
            color: var(--ink-2);
            font-size: 10.5px;
            line-height: 1.75;
        }

        /* =========================================================
           KEAHLIAN
        ========================================================= */

        .skill-groups {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .skill-group {
            page-break-inside: avoid;
        }

        .skill-group-title {
            margin-bottom: 8px;
            font-size: 8px;
            font-weight: 800;
            color: var(--soft);
            text-transform: uppercase;
            letter-spacing: 1.3px;
        }

        .skill-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .skill-card {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
            padding: 8px 9px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            page-break-inside: avoid;
        }

        .skill-icon {
            width: 34px;
            height: 34px;
            border-radius: 7px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--color-paper);
            border: 1px solid var(--line);
            overflow: hidden;
        }

        .skill-icon svg {
            width: 25px;
            height: 25px;
            display: block;
        }

        .skill-icon-text {
            font-size: 9px;
            font-weight: 900;
            color: var(--color-brand-600);
            letter-spacing: -.3px;
        }

        .skill-info {
            flex: 1;
            min-width: 0;
        }

        .skill-name {
            font-size: 9.5px;
            line-height: 1.25;
            font-weight: 700;
            color: var(--color-ink);
        }

        .skill-level-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 5px;
        }

        .skill-bar {
            flex: 1;
            height: 4px;
            overflow: hidden;
            background: #edf0f3;
            border-radius: 99px;
        }

        .skill-bar-fill {
            height: 100%;
            background: var(--color-brand-600);
            border-radius: 99px;
        }

        .skill-percent {
            flex-shrink: 0;
            font-size: 7px;
            font-weight: 800;
            color: var(--color-ink-muted);
        }

        /* =========================================================
           KARYA
        ========================================================= */

        .work-list {
            display: flex;
            flex-direction: column;
        }

        .work-item {
            display: flex;
            gap: 13px;
            padding: 11px 0;
            border-bottom: 1px solid var(--line);
            page-break-inside: avoid;
        }

        .work-item:first-child {
            padding-top: 0;
        }

        .work-item:last-child {
            border-bottom: 0;
        }

        .work-image {
            width: 78px;
            height: 64px;
            border-radius: 7px;
            overflow: hidden;
            background: var(--color-paper);
            border: 1px solid var(--line);
            flex-shrink: 0;
        }

        .work-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .work-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            font-weight: 900;
            color: var(--color-brand-600);
        }

        .work-content {
            flex: 1;
            min-width: 0;
        }

        .work-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }

        .work-title {
            font-size: 11px;
            font-weight: 800;
            line-height: 1.35;
        }

        .work-category {
            flex-shrink: 0;
            padding: 4px 7px;
            border-radius: 99px;
            background: var(--red-soft);
            color: var(--color-brand-600);
            font-size: 7px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .work-description {
            margin-top: 4px;
            color: var(--color-ink-muted);
            font-size: 9px;
            line-height: 1.55;
        }

        /* =========================================================
           KONTAK
        ========================================================= */

        .contact-grid {
            display: grid;
            gap: 0;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
            padding: 11px 10px;
            border-right: 1px solid var(--line);
        }

        .contact-item:first-child {
            padding-left: 0;
        }

        .contact-item:last-child {
            border-right: 0;
        }

        .contact-icon {
            width: 28px;
            height: 28px;
            flex-shrink: 0;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--red-soft);
            color: var(--color-brand-600);
        }

        .contact-icon svg {
            width: 14px;
            height: 14px;
        }

        .contact-label {
            font-size: 7px;
            color: var(--soft);
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .contact-value {
            color: var(--color-ink);
            font-size: 8.5px;
            font-weight: 700;
            line-height: 1.3;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        /* =========================================================
           QR
        ========================================================= */

        .qr-section {
            margin-top: 24px;
            padding: 18px 0 0;
            text-align: center;
            border-top: 1px solid var(--line);
        }

        .qr-image {
            width: 120px;
            height: 120px;
            margin: 0 auto 10px;
            display: block;
        }

        .qr-title {
            font-size: 11px;
            font-weight: 900;
            margin-bottom: 4px;
        }

        .qr-desc {
            max-width: 340px;
            margin: 0 auto;
            color: var(--color-ink-muted);
            font-size: 8.5px;
            line-height: 1.55;
        }

        .qr-url {
            margin-top: 6px;
            color: var(--soft);
            font-size: 7.5px;
            word-break: break-all;
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        .footer {
            margin-top: 18px;
            padding-top: 9px;
            border-top: 1px solid var(--line);
            text-align: center;
            color: #c4c7cc;
            font-size: 7.5px;
            letter-spacing: .2px;
        }

        /* =========================================================
           PRINT
        ========================================================= */

        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            html,
            body {
                background: #fff;
            }

            .toolbar,
            .no-print {
                display: none !important;
            }

            .stage {
                padding: 0;
            }

            .sheet {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 14mm 15mm 12mm;
                box-shadow: none;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .avoid,
            .section,
            .work-item,
            .skill-card,
            .skill-group {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }

        /* =========================================================
           MOBILE
        ========================================================= */

        @media screen and (max-width: 900px) {
            .stage {
                padding: 15px 0;
            }

            .sheet {
                width: 100%;
                min-height: 0;
                padding: 28px 20px;
            }

            .toolbar-brand {
                display: none;
            }

            .toolbar {
                justify-content: flex-end;
            }
        }

        @media screen and (max-width: 600px) {
            .student-name {
                font-size: 24px;
            }

            .student-header {
                gap: 14px;
            }

            .student-photo {
                width: 78px;
                height: 78px;
            }

            .skill-list {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr !important;
            }

            .contact-item {
                border-right: 0;
                border-bottom: 1px solid var(--line);
                padding-left: 0;
            }

            .contact-item:last-child {
                border-bottom: 0;
            }
        }
    </style>
</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | BIO
    |--------------------------------------------------------------------------
    */

    $bioSingkat = null;

    if (!empty($user->bio)) {
        $kalimatBio = preg_split(
            '/(?<=[.!?])\s+/',
            trim($user->bio),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $bioSingkat = implode(
            ' ',
            array_slice($kalimatBio, 0, 3)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | URL PORTFOLIO
    |--------------------------------------------------------------------------
    */

    $galleryUrl = !empty($user->portfolio_slug)
        ? route('portfolio.profile', $user->portfolio_slug)
        : url('/');


    /*
    |--------------------------------------------------------------------------
    | QR CODE
    |--------------------------------------------------------------------------
    */

    $qrImg = function (string $data, int $size = 300) {
        return 'https://api.qrserver.com/v1/create-qr-code/?size='
            . $size . 'x' . $size
            . '&color=111827'
            . '&bgcolor=ffffff'
            . '&qzone=1'
            . '&data=' . urlencode($data);
    };


    /*
    |--------------------------------------------------------------------------
    | INSTAGRAM
    |--------------------------------------------------------------------------
    */

    $formatInstagram = function ($value) {

        if (empty($value)) {
            return null;
        }

        $value = trim((string) $value);

        if (preg_match(
            '#(?:https?://)?(?:www\.)?instagram\.com/([A-Za-z0-9_.]+)#i',
            $value,
            $match
        )) {
            return '@' . $match[1];
        }

        if (str_starts_with($value, '@')) {
            return $value;
        }

        if (!preg_match('#^https?://#i', $value)) {
            return '@' . $value;
        }

        return $value;
    };


    /*
    |--------------------------------------------------------------------------
    | KONTAK
    |--------------------------------------------------------------------------
    */

    $kontakItems = collect([

        !empty($user->contact)
            ? [
                'label' => 'WhatsApp',
                'value' => $user->contact,
                'icon' => 'phone'
            ]
            : null,

        !empty($user->email)
            ? [
                'label' => 'Email',
                'value' => $user->email,
                'icon' => 'mail'
            ]
            : null,

        !empty($user->instagram)
            ? [
                'label' => 'Instagram',
                'value' => $formatInstagram($user->instagram),
                'icon' => 'instagram'
            ]
            : null,

    ])->filter()->values();


    /*
    |--------------------------------------------------------------------------
    | NORMALISASI SKILL
    |--------------------------------------------------------------------------
    |
    | Struktur skill Anda sekarang:
    |
    | [
    |     [
    |         'name'  => 'Adobe Photoshop',
    |         'level' => 80,
    |         'type'  => 'Software Desain'
    |     ]
    | ]
    |
    */

    $rawSkills = $user->skills;

    if (is_string($rawSkills)) {

        $decodedSkills = json_decode($rawSkills, true);

        if (
            json_last_error() === JSON_ERROR_NONE &&
            is_array($decodedSkills)
        ) {
            $rawSkills = $decodedSkills;
        } else {
            $rawSkills = array_filter(
                array_map(
                    'trim',
                    explode(',', $rawSkills)
                )
            );
        }
    }

    if (!is_array($rawSkills)) {
        $rawSkills = [];
    }


    /*
    |--------------------------------------------------------------------------
    | UBAH SKILL MENJADI FORMAT SERAGAM
    |--------------------------------------------------------------------------
    */

    $normalizedSkills = [];

    foreach ($rawSkills as $skill) {

        /*
        | Format baru:
        | ['name' => ..., 'level' => ..., 'type' => ...]
        */

        if (is_array($skill)) {

            $skillName = trim(
                (string) ($skill['name'] ?? '')
            );

            if ($skillName === '') {
                continue;
            }

            $skillLevel = (int) (
                $skill['level'] ?? 50
            );

            $skillType = trim(
                (string) (
                    $skill['type'] ?? 'Keahlian'
                )
            );

            $normalizedSkills[] = [
                'name' => $skillName,
                'level' => max(
                    0,
                    min(100, $skillLevel)
                ),
                'type' => $skillType ?: 'Keahlian',
            ];

        }

        /*
        | Format lama:
        | ['Adobe Photoshop', 'Figma', ...]
        */

        elseif (is_string($skill)) {

            $skillName = trim($skill);

            if ($skillName === '') {
                continue;
            }

            $normalizedSkills[] = [
                'name' => $skillName,
                'level' => 50,
                'type' => 'Keahlian',
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GROUP SKILL
    |--------------------------------------------------------------------------
    */

    $skillGroups = [];

    foreach ($normalizedSkills as $skill) {

        $groupName = $skill['type'];

        if (!isset($skillGroups[$groupName])) {
            $skillGroups[$groupName] = [];
        }

        $skillGroups[$groupName][] = $skill;
    }


    /*
    |--------------------------------------------------------------------------
    | IKON APLIKASI / KOMPETENSI
    |--------------------------------------------------------------------------
    */

    $skillIcon = function ($skillName) {

        $name = strtolower(
            trim((string) $skillName)
        );


        /* PHOTOSHOP */

        if (
            str_contains($name, 'photoshop')
            || str_contains($name, 'adobe ps')
        ) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <rect
                        x="1"
                        y="1"
                        width="30"
                        height="30"
                        rx="6"
                        fill="#001E36"
                    />
                    <text
                        x="5"
                        y="22"
                        font-family="Arial,sans-serif"
                        font-size="13"
                        font-weight="900"
                        fill="#31A8FF"
                    >Ps</text>
                </svg>
            ';
        }


        /* ILLUSTRATOR */

        if (
            str_contains($name, 'illustrator')
            || str_contains($name, 'adobe ai')
        ) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <rect
                        x="1"
                        y="1"
                        width="30"
                        height="30"
                        rx="6"
                        fill="#330000"
                    />
                    <text
                        x="5"
                        y="22"
                        font-family="Arial,sans-serif"
                        font-size="13"
                        font-weight="900"
                        fill="#FF9A00"
                    >Ai</text>
                </svg>
            ';
        }


        /* INDESIGN */

        if (str_contains($name, 'indesign')) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <rect
                        x="1"
                        y="1"
                        width="30"
                        height="30"
                        rx="6"
                        fill="#49021F"
                    />
                    <text
                        x="5"
                        y="22"
                        font-family="Arial,sans-serif"
                        font-size="12"
                        font-weight="900"
                        fill="#FF3366"
                    >Id</text>
                </svg>
            ';
        }


        /* CORELDRAW */

        if (
            str_contains($name, 'corel')
            || str_contains($name, 'coreldraw')
        ) {
            return '
                <svg viewBox="0 0 32 32">
                    <circle
                        cx="16"
                        cy="16"
                        r="14"
                        fill="#5AAE3A"
                    />
                    <text
                        x="7"
                        y="21"
                        font-family="Arial,sans-serif"
                        font-size="11"
                        font-weight="900"
                        fill="white"
                    >CD</text>
                </svg>
            ';
        }


        /* FIGMA */

        if (
            str_contains($name, 'figma')
            || str_contains($name, 'ui/ux')
            || str_contains($name, 'ui ux')
        ) {
            return '
                <svg viewBox="0 0 32 32">
                    <circle cx="11" cy="6" r="5" fill="#F24E1E"/>
                    <circle cx="21" cy="6" r="5" fill="#FF7262"/>
                    <circle cx="11" cy="16" r="5" fill="#A259FF"/>
                    <circle cx="21" cy="16" r="5" fill="#1ABCFE"/>
                    <path
                        d="M6 26c0-2.76 2.24-5 5-5h5v5c0 2.76-2.24 5-5 5s-5-2.24-5-5z"
                        fill="#0ACF83"
                    />
                </svg>
            ';
        }


        /* CANVA */

        if (str_contains($name, 'canva')) {
            return '
                <svg viewBox="0 0 32 32">
                    <circle
                        cx="16"
                        cy="16"
                        r="14"
                        fill="#00C4CC"
                    />
                    <text
                        x="7"
                        y="21"
                        font-family="Georgia,serif"
                        font-size="13"
                        font-weight="700"
                        fill="white"
                    >C</text>
                </svg>
            ';
        }


        /* BLENDER */

        if (str_contains($name, 'blender')) {
            return '
                <svg viewBox="0 0 32 32">
                    <circle
                        cx="16"
                        cy="16"
                        r="14"
                        fill="#E87D0D"
                    />
                    <circle
                        cx="16"
                        cy="17"
                        r="6"
                        fill="white"
                    />
                    <circle
                        cx="16"
                        cy="17"
                        r="2.5"
                        fill="#E87D0D"
                    />
                </svg>
            ';
        }


        /* PREMIERE PRO */

        if (str_contains($name, 'premiere')) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <rect
                        x="1"
                        y="1"
                        width="30"
                        height="30"
                        rx="6"
                        fill="#00005B"
                    />
                    <text
                        x="5"
                        y="22"
                        font-family="Arial,sans-serif"
                        font-size="12"
                        font-weight="900"
                        fill="#9999FF"
                    >Pr</text>
                </svg>
            ';
        }


        /* AFTER EFFECTS */

        if (
            str_contains($name, 'after effects')
            || str_contains($name, 'after effect')
        ) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <rect
                        x="1"
                        y="1"
                        width="30"
                        height="30"
                        rx="6"
                        fill="#00005B"
                    />
                    <text
                        x="4"
                        y="22"
                        font-family="Arial,sans-serif"
                        font-size="11"
                        font-weight="900"
                        fill="#9999FF"
                    >Ae</text>
                </svg>
            ';
        }


        /* FOTOGRAFI */

        if (str_contains($name, 'fotografi')) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <rect
                        x="3"
                        y="8"
                        width="26"
                        height="19"
                        rx="4"
                        stroke="#374151"
                        stroke-width="2"
                    />
                    <path
                        d="M10 8l2-4h8l2 4"
                        stroke="#374151"
                        stroke-width="2"
                    />
                    <circle
                        cx="16"
                        cy="17"
                        r="5"
                        stroke="#dc2626"
                        stroke-width="2"
                    />
                </svg>
            ';
        }


        /* VIDEOGRAFI */

        if (str_contains($name, 'videografi')) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <rect
                        x="3"
                        y="7"
                        width="19"
                        height="18"
                        rx="3"
                        stroke="#374151"
                        stroke-width="2"
                    />
                    <path
                        d="M22 13l7-4v14l-7-4"
                        stroke="#dc2626"
                        stroke-width="2"
                        stroke-linejoin="round"
                    />
                </svg>
            ';
        }


        /* TIPOGRAFI */

        if (str_contains($name, 'tipografi')) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <path
                        d="M7 7h18M16 7v18M11 25h10"
                        stroke="#374151"
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                    <path
                        d="M10 7l-4 18M22 7l4 18"
                        stroke="#dc2626"
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                </svg>
            ';
        }


        /* ILUSTRASI DIGITAL */

        if (str_contains($name, 'ilustrasi')) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <path
                        d="M5 25l4-10 10-10 6 6-10 10-10 4z"
                        stroke="#374151"
                        stroke-width="2"
                        stroke-linejoin="round"
                    />
                    <path
                        d="M18 8l6 6"
                        stroke="#dc2626"
                        stroke-width="2"
                    />
                </svg>
            ';
        }


        /* LAYOUT */

        if (str_contains($name, 'layout')) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <rect
                        x="5"
                        y="5"
                        width="22"
                        height="22"
                        rx="2"
                        stroke="#374151"
                        stroke-width="2"
                    />
                    <path
                        d="M5 12h22M14 12v15"
                        stroke="#dc2626"
                        stroke-width="2"
                    />
                </svg>
            ';
        }


        /* NIRMANA */

        if (str_contains($name, 'nirmana')) {
            return '
                <svg viewBox="0 0 32 32" fill="none">
                    <circle
                        cx="10"
                        cy="10"
                        r="5"
                        stroke="#dc2626"
                        stroke-width="2"
                    />
                    <rect
                        x="16"
                        y="5"
                        width="10"
                        height="10"
                        stroke="#374151"
                        stroke-width="2"
                    />
                    <path
                        d="M6 25l10-10 10 10H6z"
                        stroke="#374151"
                        stroke-width="2"
                        stroke-linejoin="round"
                    />
                </svg>
            ';
        }


        /* DEFAULT */

        $initials = strtoupper(
            substr(
                preg_replace(
                    '/[^A-Za-z0-9]/',
                    '',
                    $skillName
                ),
                0,
                2
            )
        );

        return '
            <span class="skill-icon-text">
                ' . e($initials ?: 'SK') . '
            </span>
        ';
    };


    /*
    |--------------------------------------------------------------------------
    | KARYA UNGGULAN
    |--------------------------------------------------------------------------
    */

    $karyaUnggulan = $portfolios->take(3);

@endphp


<!-- =============================================================
     TOOLBAR
============================================================== -->

<div class="toolbar no-print">

    <div class="toolbar-brand">
        DKV SMEKDA — Portfolio PDF
    </div>

    <div class="toolbar-actions">

        @if(auth()->check() && auth()->id() === $user->id)

            <a
                href="{{ route('siswa.dashboard') }}"
                class="tbtn tbtn-gray"
            >
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    />
                </svg>

                Dashboard
            </a>

        @endif


        <a
            href="{{ $galleryUrl }}"
            target="_blank"
            rel="noopener"
            class="tbtn tbtn-gray"
        >
            Lihat Portfolio
        </a>


        <button
            type="button"
            onclick="window.print()"
            class="tbtn tbtn-red"
        >
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
            </svg>

            Cetak / Simpan PDF
        </button>

    </div>
</div>


<!-- =============================================================
     A4
============================================================== -->

<div class="stage">

<div class="sheet">

    <!-- SEKOLAH -->

    <div class="school-header avoid">

        <img
            src="{{ asset('images/logo-sekolah.png') }}"
            alt="Logo SMK Negeri 2 Padang Panjang"
            class="school-logo"
        >

        <div>

            <div class="school-name">
                SMK Negeri 2 Padang Panjang
            </div>

            <div class="school-dept">
                Kompetensi Keahlian · Desain Komunikasi Visual
            </div>

        </div>

    </div>

    <div class="top-line"></div>


    <!-- IDENTITAS -->

    <div class="student-header avoid">

        <div class="student-photo">

            @if($user->photo)

                <img
                    src="{{ asset('storage/' . $user->photo) }}"
                    alt="{{ $user->name }}"
                >

            @else

                {{ strtoupper(substr($user->name, 0, 1)) }}

            @endif

        </div>


        <div>

            <div class="student-name">
                {{ $user->name }}
            </div>

            <div class="student-role">
                Desain Komunikasi Visual
            </div>

            <div class="student-meta">

                SMK Negeri 2 Padang Panjang

                @if($user->nis_nip)
                    · NIS/NIP {{ $user->nis_nip }}
                @endif

            </div>

        </div>

    </div>

    <div class="section-line"></div>


    <!-- TENTANG SAYA -->

    @if($bioSingkat)

        <section class="section avoid">

            <div class="section-heading">

                <span class="section-mark"></span>

                <span class="section-title">
                    Tentang Saya
                </span>

            </div>

            <div class="bio-box">

                <p class="bio-text">
                    {{ $bioSingkat }}
                </p>

            </div>

        </section>

    @endif


    <!-- =========================================================
         KEAHLIAN
    ========================================================== -->

    @if(!empty($normalizedSkills))

        <section class="section avoid">

            <div class="section-heading">

                <span class="section-mark"></span>

                <span class="section-title">
                    Keahlian
                </span>

            </div>


            <div class="skill-groups">

                @foreach($skillGroups as $namaGrup => $items)

                    <div class="skill-group">

                        <div class="skill-group-title">
                            {{ $namaGrup }}
                        </div>


                        <div class="skill-list">

                            @foreach($items as $skill)

                                <div class="skill-card">

                                    <div class="skill-icon">
                                        {!! $skillIcon($skill['name']) !!}
                                    </div>


                                    <div class="skill-info">

                                        <div class="skill-name">
                                            {{ $skill['name'] }}
                                        </div>


                                        <div class="skill-level-row">

                                            <div class="skill-bar">

                                                <div
                                                    class="skill-bar-fill"
                                                    style="width: {{ $skill['level'] }}%;"
                                                ></div>

                                            </div>

                                            <span class="skill-percent">
                                                {{ $skill['level'] }}%
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

        </section>

    @endif


    <!-- KARYA UNGGULAN -->

    @if($karyaUnggulan->isNotEmpty())

        <section class="section avoid">

            <div class="section-heading">

                <span class="section-mark"></span>

                <span class="section-title">
                    Karya Unggulan
                </span>

            </div>


            <div class="work-list">

                @foreach($karyaUnggulan as $karya)

                    <article class="work-item">

                        <div class="work-image">

                            @if(!empty($karya->image_path))

                                <img
                                    src="{{ asset('storage/' . $karya->image_path) }}"
                                    alt="{{ $karya->title }}"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >

                                <div
                                    class="work-fallback"
                                    style="display:none;"
                                >
                                    {{ strtoupper(substr($karya->title ?? 'K', 0, 1)) }}
                                </div>

                            @else

                                <div class="work-fallback">
                                    {{ strtoupper(substr($karya->title ?? 'K', 0, 1)) }}
                                </div>

                            @endif

                        </div>


                        <div class="work-content">

                            <div class="work-top">

                                <div class="work-title">
                                    {{ $karya->title }}
                                </div>


                                @if($karya->category)

                                    <span class="work-category">
                                        {{ $karya->category->name }}
                                    </span>

                                @endif

                            </div>


                            @if(!empty($karya->description))

                                <div class="work-description">

                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($karya->description),
                                        130
                                    ) }}

                                </div>

                            @endif

                        </div>

                    </article>

                @endforeach

            </div>

        </section>

    @endif


    <!-- KONTAK -->

    @if($kontakItems->isNotEmpty())

        <section class="section avoid">

            <div class="section-heading">

                <span class="section-mark"></span>

                <span class="section-title">
                    Kontak
                </span>

            </div>


            <div
                class="contact-grid"
                style="grid-template-columns: repeat({{ $kontakItems->count() }}, minmax(0, 1fr));"
            >

                @foreach($kontakItems as $item)

                    <div class="contact-item">

                        <div class="contact-icon">

                            @if($item['icon'] === 'phone')

                                <!-- PHONE -->

                                <svg
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.7"
                                        d="M3 5a2 2 0 012-2h2.2a1 1 0 01.95.68l1.05 3.15a1 1 0 01-.45 1.17L7.4 9.05a11.1 11.1 0 005.55 5.55l1.05-1.35a1 1 0 011.17-.45l3.15 1.05a1 1 0 01.68.95V17a2 2 0 01-2 2h-1C9.37 19 5 14.63 5 8V7a2 2 0 01-2-2V5z"
                                    />
                                </svg>


                            @elseif($item['icon'] === 'mail')

                                <!-- EMAIL -->

                                <svg
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                        stroke-width="1.7"
                                    />

                                    <path
                                        d="M3 7l9 6 9-6"
                                        stroke-width="1.7"
                                    />
                                </svg>


                            @else

                                <!-- INSTAGRAM -->

                                <svg
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="18"
                                        rx="5"
                                        stroke-width="1.7"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="4"
                                        stroke-width="1.7"
                                    />

                                    <circle
                                        cx="17.5"
                                        cy="6.5"
                                        r="1"
                                        fill="currentColor"
                                        stroke="none"
                                    />
                                </svg>

                            @endif

                        </div>


                        <div>

                            <div class="contact-label">
                                {{ $item['label'] }}
                            </div>

                            <div class="contact-value">
                                {{ $item['value'] }}
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </section>

    @endif


    <!-- QR -->

    <div class="qr-section avoid">

        <img
            src="{{ $qrImg($galleryUrl, 400) }}"
            alt="QR Code Portfolio {{ $user->name }}"
            class="qr-image"
        >

        <div class="qr-title">
            Scan untuk membuka live portfolio
        </div>

        <div class="qr-desc">
            Lihat seluruh karya, deskripsi proyek, proses desain,
            dan pembaruan portfolio secara online.
        </div>

        <div class="qr-url">
            {{ $galleryUrl }}
        </div>

    </div>


    <!-- FOOTER -->

    <div class="footer">

        Sistem Portofolio Digital DKV
        ·
        SMK Negeri 2 Padang Panjang
        ·
        {{ now()->translatedFormat('d F Y') }}

    </div>

</div>

</div>

</body>
</html>