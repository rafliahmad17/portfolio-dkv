<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nis_nip',
        'photo',
        'bio',
        'contact',

        /*
         * Instagram ditambahkan agar data Instagram
         * dapat disimpan dari ProfileController.
         */
        'instagram',

        /*
         * Skills disimpan sebagai JSON/array.
         */
        'skills',
    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',

            /*
             * Password otomatis di-hash oleh Laravel.
             */
            'password' => 'hashed',

            'role' => 'string',

            /*
             * Kolom skills otomatis dikonversi:
             *
             * Database JSON
             *       ↓
             * PHP Array
             *
             * sehingga kita bisa menggunakan:
             * $user->skills
             */
            'skills' => 'array',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PILIHAN SKILL DKV
    |--------------------------------------------------------------------------
    |
    | Daftar ini digunakan oleh:
    | - Form profil siswa
    | - ProfileController
    | - PDF portfolio
    |
    */

    public const SKILL_OPTIONS = [

        /*
        |--------------------------------------------------------------------------
        | SOFTWARE DESAIN
        |--------------------------------------------------------------------------
        */

        'Software Desain' => [
            'Adobe Illustrator',
            'Adobe Photoshop',
            'Adobe InDesign',
            'CorelDraw',
            'Figma',
            'Canva',
        ],

        /*
        |--------------------------------------------------------------------------
        | KOMPETENSI INTI
        |--------------------------------------------------------------------------
        */

        'Kompetensi Inti' => [
            'Tipografi',
            'Nirmana (Garis, Bentuk, Warna)',
            'Ilustrasi Digital',
            'Layouting',
            'Fotografi',
            'Videografi',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | SLUG PORTOFOLIO PUBLIK (portfolio_slug)
    |--------------------------------------------------------------------------
    |
    | SATU-SATUNYA sumber kebenaran untuk pembuatan portfolio_slug siswa.
    | Dipakai oleh StudentController (saat mendaftarkan/backfill akun siswa)
    | dan DashboardController (backfill defensif untuk akun lama). Sengaja
    | TIDAK pernah menyertakan ID database pada slug — memakai Str::slug()
    | dari nama + suffix acak, lalu dicek keunikannya ke database (termasuk
    | baris yang sudah soft-deleted) supaya tidak pernah collision.
    |
    */

    /**
     * Buat slug publik unik untuk portofolio siswa (dipakai oleh rute
     * /u/{slug} dan /u/{slug}/print). TIDAK boleh dipanggil berulang kali
     * untuk siswa yang sama — hanya dipakai sekali saat portfolio_slug
     * masih kosong, supaya URL publik yang sudah dibagikan tidak berubah.
     */
    public static function generateUniquePortfolioSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'siswa';

        do {
            $slug = $base . '-' . Str::random(6);
        } while (static::withTrashed()->where('portfolio_slug', $slug)->exists());

        return $slug;
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI PORTFOLIO
    |--------------------------------------------------------------------------
    */

    /**
     * Satu siswa memiliki banyak portfolio.
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI ACHIEVEMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Satu siswa memiliki banyak prestasi/sertifikat.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | AVATAR
    |--------------------------------------------------------------------------
    */

    /**
     * Accessor untuk mengambil foto/avatar user.
     */
    public function getAvatarAttribute()
    {
        return $this->photo;
    }
}