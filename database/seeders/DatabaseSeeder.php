<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // PRODUKSI: hanya data referensi (kategori). Akun demo, karya dummy, dan
        // prestasi dummy TIDAK PERNAH dibuat di produksi -- UserSeeder membuat
        // akun admin/guru/siswa berpassword "password123".
        if (app()->environment('production')) {
            $this->call([
                CategorySeeder::class,
            ]);

            return;
        }

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            PortfolioSeeder::class,
            AchievementSeeder::class,
        ]);
    }
}