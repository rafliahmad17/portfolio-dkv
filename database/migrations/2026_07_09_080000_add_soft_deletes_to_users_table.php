<?php
// database/migrations/2026_07_09_080000_add_soft_deletes_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom deleted_at (soft delete) khusus untuk melindungi
     * data akademik. Tanpa kolom ini, menghapus akun siswa akan memicu
     * cascadeOnDelete() pada tabel portfolios & achievements dan
     * menghapus permanen seluruh karya siswa tanpa bisa dipulihkan.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};