<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nis_nip',
        'photo',
        'bio',
        'contact',
        'skills',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => 'string',
            'skills'            => 'array',
        ];
    }

    /**
     * Daftar skill baku yang ditawarkan sistem ke siswa DKV,
     * dikelompokkan menjadi Software Desain dan Kompetensi Inti.
     * Siswa tetap bisa menambah skill custom di luar daftar ini.
     */
    public const SKILL_OPTIONS = [
        'Software Desain' => [
            'Adobe Illustrator',
            'Adobe Photoshop',
            'Adobe InDesign',
            'CorelDraw',
            'Figma',
            'Canva',
        ],
        'Kompetensi Inti' => [
            'Tipografi',
            'Nirmana (Garis, Bentuk, Warna)',
            'Ilustrasi Digital',
            'Layouting',
            'Fotografi',
            'Videografi',
        ],
    ];

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    
    public function getAvatarAttribute()
    {
        return $this->photo;
    }
}