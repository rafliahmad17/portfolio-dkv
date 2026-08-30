<?php
// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Normalisasi nama sebelum di-slug-kan: setiap karakter yang bukan
     * huruf atau angka ("/", "&", tanda baca, dst.) diperlakukan sebagai
     * pemisah kata (diganti spasi), bukan dihapus begitu saja. Tanpa ini,
     * Str::slug() menghapus karakter seperti "/" tanpa menyisakan
     * pemisah, sehingga "UI/UX" -> "uiux" alih-alih "ui-ux" seperti
     * "UI UX". Generic untuk semua nama kategori, bukan hanya "UI/UX".
     */
    protected static function normalizeNameForSlug(string $name): string
    {
        return trim(preg_replace('/[^\pL\pN]+/u', ' ', $name));
    }

    /**
     * Buat slug unik dari nama kategori. Dua nama berbeda yang menghasilkan
     * slug dasar sama (mis. "UI/UX" dan "UI UX" -> "ui-ux") tetap bisa
     * disimpan berdampingan karena diberi suffix angka berurutan
     * ("ui-ux", "ui-ux-2", "ui-ux-3", ...) sampai unik — bukan ID database
     * — sehingga collision slug tidak pernah berujung error 500 saat
     * disimpan. $ignoreId dipakai saat update supaya kategori tidak
     * dianggap bentrok dengan slug miliknya sendiri.
     */
    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug(static::normalizeNameForSlug($name)) ?: 'kategori';
        $slug = $base;
        $suffix = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }
}