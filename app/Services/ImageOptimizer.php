<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Optimasi gambar unggahan: perkecil, konversi ke WebP, dan (untuk karya)
 * buat thumbnail. Memakai ekstensi GD bawaan PHP, tanpa paket tambahan.
 *
 * Sebelumnya gambar (maks. 2 MB) disimpan apa adanya dan ditampilkan penuh
 * di setiap kartu. Sekarang:
 *   - gambar utama  : sisi terpanjang maks. 1600 px, WebP kualitas 82
 *   - thumbnail     : sisi terpanjang maks.  480 px, WebP kualitas 78
 *
 * Jika GD/WebP tidak tersedia, gambar terlalu besar untuk didekode dengan
 * aman, atau berkasnya tidak bisa dibaca, berkas asli disimpan seperti
 * sebelumnya (tanpa thumbnail). Unggahan TIDAK PERNAH gagal karena optimasi.
 */
class ImageOptimizer
{
    public const MAIN_MAX = 1600;
    public const THUMB_MAX = 480;

    private const MAIN_QUALITY = 82;
    private const THUMB_QUALITY = 78;

    /**
     * Batas piksel yang aman didekode GD (4 byte per piksel di memori).
     * 16 MP ~ 64 MB, masih aman untuk memory_limit 128M.
     */
    private const MAX_PIXELS = 16_000_000;

    public function __construct(private string $disk = 'public')
    {
    }

    /** GD aktif dan mendukung WebP? */
    public static function supported(): bool
    {
        return extension_loaded('gd') && function_exists('imagewebp');
    }

    /**
     * Ukuran setelah diperkecil agar sisi terpanjang <= $max (tidak pernah
     * memperbesar). Dipakai juga oleh model untuk menghitung dimensi thumbnail.
     *
     * @return array{0:int,1:int} [lebar, tinggi]
     */
    public static function fit(int $width, int $height, int $max): array
    {
        $longest = max($width, $height);

        if ($longest <= $max) {
            return [$width, $height];
        }

        $scale = $max / $longest;

        return [
            max(1, (int) round($width * $scale)),
            max(1, (int) round($height * $scale)),
        ];
    }

    /**
     * Simpan gambar karya: versi utama + thumbnail.
     *
     * @return array{path:string, thumbnail_path:?string, width:?int, height:?int}
     */
    public function storeWithThumbnail(UploadedFile $file, string $imageDir, string $thumbDir): array
    {
        $decoded = $this->decode($file->getRealPath());

        if ($decoded !== null) {
            [$source, $width, $height] = $decoded;

            [$mainW, $mainH] = self::fit($width, $height, self::MAIN_MAX);
            [$thumbW, $thumbH] = self::fit($width, $height, self::THUMB_MAX);

            $main = $this->encodeWebp($source, $width, $height, $mainW, $mainH, self::MAIN_QUALITY);
            $thumb = $this->encodeWebp($source, $width, $height, $thumbW, $thumbH, self::THUMB_QUALITY);
            unset($source);

            if ($main !== null && $thumb !== null) {
                $name = Str::random(40) . '.webp';
                $path = trim($imageDir, '/') . '/' . $name;
                $thumbPath = trim($thumbDir, '/') . '/' . $name;

                $disk = Storage::disk($this->disk);
                $disk->put($path, $main);
                $disk->put($thumbPath, $thumb);

                return [
                    'path'           => $path,
                    'thumbnail_path' => $thumbPath,
                    'width'          => $mainW,
                    'height'         => $mainH,
                ];
            }
        }

        // Fallback: simpan berkas asli apa adanya.
        $size = @getimagesize($file->getRealPath());

        return [
            'path'           => $file->store($imageDir, $this->disk),
            'thumbnail_path' => null,
            'width'          => $size ? (int) $size[0] : null,
            'height'         => $size ? (int) $size[1] : null,
        ];
    }

    /**
     * Simpan satu gambar yang diperkecil (foto profil, gambar sertifikat).
     * Mengembalikan path file yang tersimpan.
     */
    public function storeResized(UploadedFile $file, string $dir, int $max = 512): string
    {
        $decoded = $this->decode($file->getRealPath());

        if ($decoded !== null) {
            [$source, $width, $height] = $decoded;
            [$w, $h] = self::fit($width, $height, $max);

            $data = $this->encodeWebp($source, $width, $height, $w, $h, self::MAIN_QUALITY);
            unset($source);

            if ($data !== null) {
                $path = trim($dir, '/') . '/' . Str::random(40) . '.webp';
                Storage::disk($this->disk)->put($path, $data);

                return $path;
            }
        }

        return $file->store($dir, $this->disk);
    }

    /**
     * Buat thumbnail untuk gambar yang SUDAH tersimpan di disk (data lama
     * sebelum fitur ini ada). Gambar utama tidak diubah.
     *
     * @return array{thumbnail_path:string, width:int, height:int}|null
     *         width/height = ukuran gambar utama yang sudah ada.
     */
    public function thumbnailForStored(string $imagePath, string $thumbDir): ?array
    {
        $disk = Storage::disk($this->disk);

        if (! $disk->exists($imagePath)) {
            return null;
        }

        $raw = $disk->get($imagePath);
        $decoded = $this->decodeString($raw, $disk->path($imagePath));

        if ($decoded === null) {
            return null;
        }

        [$source, $width, $height] = $decoded;
        [$thumbW, $thumbH] = self::fit($width, $height, self::THUMB_MAX);

        $thumb = $this->encodeWebp($source, $width, $height, $thumbW, $thumbH, self::THUMB_QUALITY);
        unset($source);

        if ($thumb === null) {
            return null;
        }

        $thumbPath = trim($thumbDir, '/') . '/' . pathinfo($imagePath, PATHINFO_FILENAME) . '.webp';
        $disk->put($thumbPath, $thumb);

        return [
            'thumbnail_path' => $thumbPath,
            'width'          => $width,
            'height'         => $height,
        ];
    }

    /**
     * Ubah satu berkas di filesystem lokal (mis. logo di folder public/) menjadi
     * versi yang lebih kecil. Berkas sumber tidak diubah.
     *
     * @param  'webp'|'png'  $format
     * @return array{0:int,1:int}|null [lebar, tinggi] hasil, atau null jika gagal
     */
    public function convertFile(string $source, string $target, int $max, string $format = 'webp', int $quality = 90): ?array
    {
        $raw = @file_get_contents($source);

        if ($raw === false) {
            return null;
        }

        $decoded = $this->decodeString($raw, null);

        if ($decoded === null) {
            return null;
        }

        [$image, $width, $height] = $decoded;
        [$w, $h] = self::fit($width, $height, $max);

        $data = $format === 'png'
            ? $this->encodePng($image, $width, $height, $w, $h)
            : $this->encodeWebp($image, $width, $height, $w, $h, $quality);
        unset($image);

        if ($data === null || @file_put_contents($target, $data) === false) {
            return null;
        }

        return [$w, $h];
    }

    // ------------------------------------------------------------------
    // Internal
    // ------------------------------------------------------------------

    /**
     * @return array{0:\GdImage,1:int,2:int}|null [gambar, lebar, tinggi]
     */
    private function decode(?string $realPath): ?array
    {
        if (! self::supported() || ! $realPath) {
            return null;
        }

        $raw = @file_get_contents($realPath);

        return $raw === false ? null : $this->decodeString($raw, $realPath);
    }

    /**
     * @return array{0:\GdImage,1:int,2:int}|null
     */
    private function decodeString(string $raw, ?string $realPath): ?array
    {
        if (! self::supported()) {
            return null;
        }

        $info = @getimagesizefromstring($raw);

        if (! $info || $info[0] < 1 || $info[1] < 1 || ($info[0] * $info[1]) > self::MAX_PIXELS) {
            return null;
        }

        $image = @imagecreatefromstring($raw);

        if (! $image) {
            return null;
        }

        if ($info[2] === IMAGETYPE_JPEG && $realPath) {
            $image = $this->applyExifOrientation($image, $realPath);
        }

        return [$image, imagesx($image), imagesy($image)];
    }

    /**
     * Foto dari ponsel sering disimpan "miring" dan hanya ditandai lewat EXIF.
     * Re-encode membuang EXIF, jadi rotasinya harus diterapkan dulu.
     */
    private function applyExifOrientation(\GdImage $image, string $realPath): \GdImage
    {
        if (! function_exists('exif_read_data') || ! function_exists('imagerotate')) {
            return $image;
        }

        $exif = @exif_read_data($realPath);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle === 0) {
            return $image;
        }

        $rotated = imagerotate($image, $angle, 0);

        return $rotated ?: $image;
    }

    private function resample(\GdImage $source, int $srcW, int $srcH, int $dstW, int $dstH): ?\GdImage
    {
        $canvas = imagecreatetruecolor($dstW, $dstH);

        if (! $canvas) {
            return null;
        }

        // Pertahankan transparansi PNG.
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        return $canvas;
    }

    private function encodeWebp(\GdImage $source, int $srcW, int $srcH, int $dstW, int $dstH, int $quality): ?string
    {
        $canvas = $this->resample($source, $srcW, $srcH, $dstW, $dstH);

        if (! $canvas) {
            return null;
        }

        ob_start();
        $ok = imagewebp($canvas, null, $quality);
        $data = ob_get_clean();

        unset($canvas);

        return ($ok && is_string($data) && $data !== '') ? $data : null;
    }

    private function encodePng(\GdImage $source, int $srcW, int $srcH, int $dstW, int $dstH): ?string
    {
        $canvas = $this->resample($source, $srcW, $srcH, $dstW, $dstH);

        if (! $canvas) {
            return null;
        }

        ob_start();
        $ok = imagepng($canvas, null, 9);
        $data = ob_get_clean();

        unset($canvas);

        return ($ok && is_string($data) && $data !== '') ? $data : null;
    }
}
