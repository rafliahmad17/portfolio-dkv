<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Persetujuan siswa untuk menampilkan kontak (WhatsApp & Instagram) di
     * halaman publik /u/{slug} dan PDF publik /u/{slug}/print.
     *
     * Default FALSE (privacy by default): seluruh siswa yang sudah ada
     * otomatis tidak menampilkan kontak sampai mereka sendiri mengaktifkannya
     * lewat halaman Profil Saya. Penting karena sebagian besar siswa SMK
     * masih di bawah 18 tahun.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('show_contact')->default(false)->after('instagram');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('show_contact');
        });
    }
};
