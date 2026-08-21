<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->createUser([
            'name'    => 'Administrator',
            'email'   => 'admin@smkn2.sch.id',
            'role'    => 'admin',
            'nis_nip' => '199001012010011001',
            'bio'     => 'Administrator sistem portfolio DKV SMKN 2.',
            'contact' => '+62 812-3456-7890',
        ]);

        $this->createUser([
            'name'    => 'Ibu Uskha Melisa Ahmad, S.Pd.',
            'email'   => 'guru@smkn2.sch.id',
            'role'    => 'guru',
            'nis_nip' => '198001012005012001',
            'bio'     => 'Guru produktif Desain Komunikasi Visual SMKN 2.',
            'contact' => '+62 813-1234-5678',
            'photo'   => 'users/dummy/guru-1.jpg',
        ]);

        for ($i = 1; $i <= 12; $i++) {
            $name = fake('id_ID')->name();
            $slug = Str::slug($name);

            if (User::withTrashed()->where('portfolio_slug', $slug)->exists()) {
                $slug .= '-' . $i;
            }

            $this->createUser([
                'name'             => $name,
                'email'            => Str::slug($name) . '@siswa.smkn2.sch.id',
                'role'             => 'siswa',
                'nis_nip'          => (string) fake()->unique()->numberBetween(2201100001, 2201109999),
                'bio'              => fake('id_ID')->paragraph(2),
                'contact'          => '+62 8' . fake()->numerify('##-####-####'),
                'photo'            => 'users/dummy/siswa-' . $i . '.jpg',
                'portfolio_slug'   => $slug,
                'skills'           => fake()->randomElements($this->allSkillOptions(), rand(4, 6)),
                'email_verified_at' => now(),
            ]);
        }
    }

    private function createUser(array $data): void
    {
        User::updateOrCreate(
            ['email' => $data['email']],
            array_merge([
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
            ], $data)
        );
    }

    private function allSkillOptions(): array
    {
        return array_merge(...array_values(User::SKILL_OPTIONS));
    }
}