<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $siswa      = User::where('role', 'siswa')->get();
        $categories = Category::all();

        if ($siswa->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Jalankan UserSeeder dan CategorySeeder terlebih dahulu.');
            return;
        }

        $titles = [
            'Poster Event Kampus',
            'Ilustrasi Karakter Fantasi',
            'Company Profile Video',
            'Logo & Brand Identity Kopi Nusantara',
            'Fotografi Produk Fashion',
            'Layout Majalah Digital',
            'Motion Graphic Iklan Layanan Masyarakat',
            'UI Design Aplikasi Mobile',
            'Sampul Buku Novel',
            'Mural Digital Kota Kelahiran',
            'Infografis Data Statistik',
            'Desain Kemasan Produk UMKM',
        ];

        foreach ($siswa as $user) {
            $count = rand(3, 6);

            for ($i = 1; $i <= $count; $i++) {
                $title = $titles[array_rand($titles)];
                $slug  = Str::slug($title . '-' . $user->id . '-' . $i);

                Portfolio::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title'         => $title,
                        'description'   => fake('id_ID')->paragraph(3),
                        'image_path'    => 'portfolios/dummy/' . fake()->numberBetween(1, 20) . '.jpg',
                        'file_pdf_path' => fake()->boolean(40)
                            ? 'portfolios/dummy/file-' . $i . '.pdf'
                            : null,
                        'user_id'     => $user->id,
                        'category_id' => $categories->random()->id,
                    ]
                );
            }
        }
    }
}