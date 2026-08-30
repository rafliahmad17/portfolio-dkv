<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `categories.name` sebelumnya hanya dijaga keunikannya lewat validasi
     * aplikasi (`unique:categories,name` di CategoryController), belum lewat
     * constraint database. `slug` memang sudah unique sejak migration awal,
     * tapi itu tidak melindungi kolom `name` itu sendiri dari duplikat kalau
     * suatu saat ada baris yang masuk lewat jalur lain (mis. tinker/seeder).
     */
    public function up(): void
    {
        // Netralkan dulu kemungkinan duplikat nama yang sudah ada, supaya
        // constraint di bawah tidak gagal diterapkan. Baris tertua pada
        // tiap grup duplikat dibiarkan apa adanya; baris berikutnya diberi
        // suffix pembeda yang tetap terbaca oleh guru/admin.
        $duplicateNames = DB::table('categories')
            ->select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('name');

        foreach ($duplicateNames as $name) {
            $rows = DB::table('categories')
                ->where('name', $name)
                ->orderBy('id')
                ->get(['id']);

            $suffix = 2;
            foreach ($rows->skip(1) as $row) {
                DB::table('categories')->where('id', $row->id)->update([
                    'name' => $name . ' (' . $suffix . ')',
                ]);
                $suffix++;
            }
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
