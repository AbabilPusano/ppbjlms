<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(['username' => 'guru'], [
            'name' => 'Guru',
            'email' => 'guru@example.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'bio' => 'Akun guru LMS PPBJ.',
            'avatar' => '先',
            'streak' => 0,
            'totalXP' => 0,
            'gems' => 0,
            'league' => 'Bronze',
        ]);

        User::updateOrCreate(['username' => 'siswa'], [
            'name' => 'Siswa',
            'email' => 'siswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'bio' => 'Akun siswa LMS PPBJ.',
            'avatar' => '学',
            'streak' => 0,
            'totalXP' => 0,
            'gems' => 0,
            'league' => 'Bronze',
        ]);
    }
}
