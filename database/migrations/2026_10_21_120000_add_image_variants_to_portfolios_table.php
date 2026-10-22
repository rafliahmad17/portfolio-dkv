<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thumbnail WebP + dimensi gambar utama untuk setiap karya.
     * Semua kolom nullable: karya lama (sebelum fitur ini) tetap valid dan
     * dapat diisi ulang lewat `php artisan images:optimize`.
     */
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('thumbnail_path')->nullable()->after('image_path');
            $table->unsignedSmallInteger('image_width')->nullable()->after('thumbnail_path');
            $table->unsignedSmallInteger('image_height')->nullable()->after('image_width');
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['thumbnail_path', 'image_width', 'image_height']);
        });
    }
};
