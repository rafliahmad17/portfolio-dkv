<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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