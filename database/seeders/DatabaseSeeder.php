<?php


namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Ibu Uskha Melisa Ahmad, S.Pd.',
            'email'    => 'guru@smkn2.sch.id',
            'password' => Hash::make('password123'),
            'role'     => 'guru',
            'nis_nip'  => '198001012005012001',
        ]);

        User::create([
            'name'     => 'Rafli Ahmad',
            'email'    => 'Rafli@siswa.smkn2.sch.id',
            'password' => Hash::make('password123'),
            'role' 
                => 'siswa',
            'nis_nip'  => '2201100011',
        ]);
    }
}