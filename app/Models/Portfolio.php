<?php

namespace App\Models;

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