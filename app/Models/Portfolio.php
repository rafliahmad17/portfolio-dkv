<?php

namespace App\Models;

use App\Services\ImageOptimizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image_path',
        'thumbnail_path',
        'image_width',
        'image_height',
        'file_pdf_path',
        'user_id',
        'category_id',
    ];

    /**
     * Buat slug publik unik dari judul karya (dipakai oleh rute /p/{slug}).
     * Dua karya dengan judul sama (mis. "Karya Poster" & "Karya Poster")
     * tetap menghasilkan slug berbeda karena keunikannya diverifikasi
     * langsung ke database sebelum dipakai — bukan sekadar berharap suffix
     * acak tidak pernah bentrok — sehingga collision tidak akan pernah
     * berujung error 500 saat disimpan. Tidak pernah memakai ID database
     * sebagai suffix.
     */
    public static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'karya';

        do {
            $slug = $base . '-' . Str::random(6);
        } while (static::where('slug', $slug)->exists());

        return $slug;
    }

    /** URL gambar utama (WebP maks. 1600 px untuk karya baru). */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * URL thumbnail untuk kartu/galeri. Karya lama yang belum punya thumbnail
     * memakai gambar utama sebagai cadangan, jadi tampilan tidak pernah rusak.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return asset('storage/' . ($this->thumbnail_path ?: $this->image_path));
    }

    /**
     * Atribut width/height untuk tag <img> (mencegah layout bergeser).
     * Kosong jika dimensi belum diketahui (karya lama sebelum backfill).
     * Aman dicetak dengan {!! !!} karena hanya berisi angka.
     */
    public function imageSizeAttributes(bool $thumbnail = false): string
    {
        if (! $this->image_width || ! $this->image_height) {
            return '';
        }

        [$width, $height] = ($thumbnail && $this->thumbnail_path)
            ? ImageOptimizer::fit((int) $this->image_width, (int) $this->image_height, ImageOptimizer::THUMB_MAX)
            : [(int) $this->image_width, (int) $this->image_height];

        return 'width="' . $width . '" height="' . $height . '"';
    }

    // Relasi: Satu portfolio dimiliki oleh satu user (Siswa)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu portfolio masuk ke dalam satu kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}