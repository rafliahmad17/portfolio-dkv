<?php

namespace App\Console\Commands;

use App\Models\Portfolio;
use App\Services\ImageOptimizer;
use Illuminate\Console\Command;

/**
 * Optimasi gambar untuk data yang sudah ada sebelum fitur thumbnail/WebP:
 *
 *   php artisan images:optimize           -> thumbnail + dimensi karya lama
 *   php artisan images:optimize --logo    -> juga optimasi logo sekolah
 *
 * Aman dijalankan berulang: karya yang sudah lengkap dilewati. Gambar utama
 * karya lama tidak diubah (hanya ditambah thumbnail WebP dan dimensinya).
 */
class OptimizeImages extends Command
{
    protected $signature = 'images:optimize
                            {--logo : Buat logo-sekolah.webp (256 px) dan logo-sekolah-64.png dari logo asli}';

    protected $description = 'Buat thumbnail WebP & dimensi untuk karya lama, dan/atau optimasi logo sekolah';

    public function handle(ImageOptimizer $optimizer): int
    {
        if (! ImageOptimizer::supported()) {
            $this->error('Ekstensi GD dengan dukungan WebP tidak tersedia di PHP ini (aktifkan extension=gd di php.ini).');

            return self::FAILURE;
        }

        $this->backfillPortfolios($optimizer);

        if ($this->option('logo')) {
            $this->optimizeLogo($optimizer);
        }

        return self::SUCCESS;
    }

    private function backfillPortfolios(ImageOptimizer $optimizer): void
    {
        $done = 0;
        $skipped = 0;

        Portfolio::query()
            ->where(fn ($q) => $q->whereNull('thumbnail_path')->orWhereNull('image_width'))
            ->chunkById(50, function ($portfolios) use ($optimizer, &$done, &$skipped) {
                foreach ($portfolios as $portfolio) {
                    // Path contoh dari seeder (berkas tidak ada) dilewati.
                    if (str_contains($portfolio->image_path, '/dummy/')) {
                        $skipped++;

                        continue;
                    }

                    $result = $optimizer->thumbnailForStored($portfolio->image_path, 'portfolios/thumbs');

                    if ($result === null) {
                        $this->warn("Dilewati (berkas tidak ada / tidak bisa dibaca): {$portfolio->image_path}");
                        $skipped++;

                        continue;
                    }

                    $portfolio->update([
                        'thumbnail_path' => $result['thumbnail_path'],
                        'image_width'    => $result['width'],
                        'image_height'   => $result['height'],
                    ]);

                    $done++;
                }
            });

        $this->info("Karya: {$done} diproses, {$skipped} dilewati.");
    }

    private function optimizeLogo(ImageOptimizer $optimizer): void
    {
        $source = public_path('images/logo-sekolah.png');

        if (! is_file($source)) {
            $this->warn('Logo asli tidak ditemukan: public/images/logo-sekolah.png');

            return;
        }

        $before = filesize($source);

        $webp = $optimizer->convertFile($source, public_path('images/logo-sekolah.webp'), 256, 'webp', 90);
        $icon = $optimizer->convertFile($source, public_path('images/logo-sekolah-64.png'), 64, 'png');

        if ($webp === null || $icon === null) {
            $this->error('Gagal membuat versi logo yang dioptimasi.');

            return;
        }

        $this->info(sprintf(
            'Logo: %s KB (asli) -> %s KB (webp %dx%d), %s KB (ikon %dx%d).',
            number_format($before / 1024, 1),
            number_format(filesize(public_path('images/logo-sekolah.webp')) / 1024, 1),
            $webp[0],
            $webp[1],
            number_format(filesize(public_path('images/logo-sekolah-64.png')) / 1024, 1),
            $icon[0],
            $icon[1],
        ));
        $this->line('Commit file public/images/logo-sekolah.webp dan logo-sekolah-64.png.');
    }
}
