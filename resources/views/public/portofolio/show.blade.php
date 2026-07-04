
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} — Portofolio DKV</title>
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
            --red-border: rgba(220,38,38,0.35);
            --surface:    rgba(255,255,255,0.03);
            --border:     rgba(255,255,255,0.07);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            min-height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #080808;
            color: #f5f5f5;
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

        .content { position: relative; z-index: 1; }

        .hero {
            max-width: 1100px; margin: 0 auto; padding: 64px 24px 40px;
            border-bottom: 1px solid var(--border);
        }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
            color: var(--red-bright);
            background: var(--red-soft); border: 1px solid var(--red-border);
            padding: 6px 14px; border-radius: 999px; margin-bottom: 24px;
        }
        .hero-eyebrow::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: var(--red-bright); box-shadow: 0 0 8px var(--red-glow);
        }
        .hero-avatar {
            width: 72px; height: 72px; border-radius: 20px;
            background: linear-gradient(135deg, var(--red), #7f1d1d);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: 800; color: #fff;
            box-shadow: 0 8px 30px var(--red-glow);
            margin-bottom: 20px;
        }
        .hero-name {
            font-size: clamp(32px, 5vw, 52px); font-weight: 800; letter-spacing: -0.02em;
            line-height: 1.1; margin-bottom: 10px;
        }
        .hero-sub { font-size: 15px; color: #a3a3a3; max-width: 560px; line-height: 1.6; }
        .hero-stats { display: flex; gap: 28px; margin-top: 28px; flex-wrap: wrap; }
        .hero-stat-num { font-size: 24px; font-weight: 800; color: #fff; }
        .hero-stat-label { font-size: 12px; color: #737373; text-transform: uppercase; letter-spacing: .06em; }

        .section-wrap { max-width: 1100px; margin: 0 auto; padding: 48px 24px 100px; }

        .grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;
        }

        .card {
            background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
            overflow: hidden; transition: border-color .2s ease, transform .2s ease;
        }
        .card:hover { border-color: var(--red-border); transform: translateY(-3px); }

        .card-thumb-wrap { position: relative; aspect-ratio: 4/3; background: #111; }
        .card-thumb { width: 100%; height: 100%; object-fit: cover; display: block; }
        .card-pdf-pill {
            position: absolute; top: 10px; right: 10px;
            background: var(--red); color: #fff; font-size: 11px; font-weight: 700;
            padding: 3px 10px; border-radius: 999px; letter-spacing: .04em;
        }

        .card-body { padding: 18px; }
        .card-cat {
            font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
            color: var(--red-bright); margin-bottom: 8px;
        }
        .card-title { font-size: 17px; font-weight: 700; margin-bottom: 8px; line-height: 1.3; }
        .card-desc {
            font-size: 13.5px; color: #a3a3a3; line-height: 1.55;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
            margin-bottom: 14px;
        }
        .card-footer {
            display: flex; align-items: center; justify-content: space-between;
            border-top: 1px solid var(--border); padding-top: 12px;
        }
        .card-date { font-size: 12px; color: #737373; }
        .card-pdf-link {
            font-size: 12.5px; font-weight: 600; color: var(--red-bright);
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
        }
        .card-pdf-link:hover { color: #fff; }

        .empty-wrap {
            text-align: center; padding: 80px 24px; border: 1px dashed var(--border); border-radius: 20px;
        }
        .empty-title { font-size: 20px; font-weight: 700; margin-bottom: 8px; }
        .empty-sub { font-size: 14px; color: #a3a3a3; }

        .site-footer {
            text-align: center; padding: 32px 24px; border-top: 1px solid var(--border);
            font-size: 12.5px; color: #737373;
        }
        .site-footer span { color: var(--red-bright); font-weight: 600; }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="content">
        <div class="hero">
            <div class="hero-eyebrow">Portofolio Publik &bull; Desain Komunikasi Visual</div>

            <div class="hero-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>

            <div class="hero-name">{{ $user->name }}</div>
            <div class="hero-sub">
                Siswa Program Keahlian Desain Komunikasi Visual, SMKN 2 Padang Panjang.
                Kumpulan karya di bawah ini disusun sebagai portofolio digital resmi
                untuk keperluan praktik kerja lapangan maupun rekrutmen kerja.
            </div>

            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">{{ $portfolios->count() }}</div>
                    <div class="hero-stat-label">Karya</div>
                </div>
                <div>
                    <div class="hero-stat-num">{{ $portfolios->pluck('category_id')->unique()->count() }}</div>
                    <div class="hero-stat-label">Kategori</div>
                </div>
            </div>
        </div>

        <div class="section-wrap">
            @if($portfolios->isEmpty())
                <div class="empty-wrap">
                    <div class="empty-title">Belum ada karya yang ditampilkan.</div>
                    <div class="empty-sub">Siswa ini belum mengunggah portofolio.</div>
                </div>
            @else
                <div class="grid">
                    @foreach($portfolios as $portfolio)
                        <div class="card">
                            <div class="card-thumb-wrap">
                                <img
                                    src="{{ asset('storage/' . $portfolio->image_path) }}"
                                    alt="{{ $portfolio->title }}"
                                    class="card-thumb"
                                >
                                @if($portfolio->file_pdf_path)
                                    <div class="card-pdf-pill">PDF</div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="card-cat">{{ $portfolio->category?->name ?? 'Umum' }}</div>
                                <div class="card-title">{{ $portfolio->title }}</div>
                                <div class="card-desc">{{ $portfolio->description }}</div>
                                <div class="card-footer">
                                    <div class="card-date">{{ $portfolio->created_at->format('d M Y') }}</div>
                                    @if($portfolio->file_pdf_path)
                                        <a href="{{ asset('storage/' . $portfolio->file_pdf_path) }}"
                                           target="_blank" class="card-pdf-link">
                                            Lihat PDF &rarr;
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="site-footer">
            Portofolio ini dibuat menggunakan <span>DKV SMEKDA Portal</span>
        </div>
    </div>
</body>
</html>
