<?php

namespace Database\Seeders;

use App\Models\Candidatura;
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
        User::firstOrCreate(
            ['email' => 'iagosousa201486@gmail.com'],
            [
                'name' => 'Iago Sousa',
                'password' => Hash::make('123456'),
            ]
        );
        $this->call([
            TecnologiaSeeder::class,
        ]);
        User::factory()->count(50)->create();
        Candidatura::factory()->count(50)->create();
    }
}
