<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = User::where('role', 'siswa')->get();

        if ($siswa->isEmpty()) {
            $this->command->warn('Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Sesuai enum di migration: ['prestasi', 'sertifikat']
        $data = [
            'prestasi' => [
                'titles' => [
                    'Juara 1 Lomba Desain Poster Tingkat Provinsi',
                    'Juara 2 Lomba Fotografi Pelajar',
                    'Juara Harapan 1 Lomba Ilustrasi Digital',
                    'Peserta Terbaik Lomba UI/UX Design Antar Sekolah',
                ],
                'issuers' => [
                    'Dinas Pendidikan Provinsi',
                    'Panitia Lomba Desain Nasional',
                    'Kemendikbudristek',
                    'Yayasan Seni Rupa Indonesia',
                ],
            ],
            'sertifikat' => [
                'titles' => [
                    'Sertifikat Kompetensi Adobe Photoshop',
                    'Sertifikat Uji Kompetensi Keahlian DKV',
                    'Sertifikat Pelatihan UI/UX Design',
                    'Sertifikat Adobe Illustrator',
                ],
                'issuers' => [
                    'Adobe Certified Associate',
                    'Politeknik Negeri',
                    'Kemendikbudristek',
                    'Lembaga Sertifikasi Profesi',
                ],
            ],
        ];

        $types = array_keys($data);

        foreach ($siswa as $user) {
            $count = rand(1, 3);

            for ($i = 1; $i <= $count; $i++) {
                $type = $types[array_rand($types)];

                Achievement::create([
                    'user_id'     => $user->id,
                    'type'        => $type,
                    'title'       => $data[$type]['titles'][array_rand($data[$type]['titles'])],
                    'issuer'      => $data[$type]['issuers'][array_rand($data[$type]['issuers'])],
                    'description' => fake('id_ID')->sentence(12),
                    'achieved_at' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'image_path'  => fake()->boolean(70)
                        ? 'achievements/dummy/' . fake()->numberBetween(1, 10) . '.jpg'
                        : null,
                    'file_path' => fake()->boolean(50)
                        ? 'achievements/dummy/sertifikat-' . $i . '.pdf'
                        : null,
                ]);
            }
        }
    }
}