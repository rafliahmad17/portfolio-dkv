<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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