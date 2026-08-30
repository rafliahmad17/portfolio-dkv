<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `nis_nip` sebelumnya hanya dijaga keunikannya lewat validasi aplikasi
     * (`unique:users,nis_nip` di StudentController/ProfileController), belum
     * lewat constraint database. Migration ini menambahkan unique index yang
     * sesungguhnya. NULL tetap boleh berulang (perilaku standar unique index
     * di MySQL/MariaDB/PostgreSQL/SQLite — beberapa siswa/guru boleh belum
     * mengisi NIS/NIP sama sekali).
     */
    public function up(): void
    {
        // Netralkan dulu kemungkinan data lama yang sudah terlanjur duplikat
        // (mis. dibuat lewat tinker/seeder sebelum validasi unique ada),
        // supaya penambahan constraint di bawah tidak gagal karena data
        // existing. Baris tertua pada tiap grup duplikat dibiarkan apa
        // adanya; baris-baris berikutnya diberi suffix agar tetap unik TANPA
        // kehilangan nilai NIS/NIP aslinya (mudah ditelusuri & diperbaiki
        // manual oleh guru/admin lewat halaman Data Siswa).
        $duplicateValues = DB::table('users')
            ->select('nis_nip')
            ->whereNotNull('nis_nip')
            ->groupBy('nis_nip')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('nis_nip');

        foreach ($duplicateValues as $nisNip) {
            $rows = DB::table('users')
                ->where('nis_nip', $nisNip)
                ->orderBy('id')
                ->get(['id']);

            foreach ($rows->skip(1) as $row) {
                DB::table('users')->where('id', $row->id)->update([
                    'nis_nip' => $nisNip . '-dup-' . $row->id,
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('nis_nip');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nis_nip']);
        });
    }
};
