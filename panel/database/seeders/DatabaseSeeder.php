<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RankSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RankSeeder::class);

        // Crear el usuario admin
        User::create([
            'username' => 'admin',
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('123'), // Encripta la contraseña
            'rank' => 1, // Asegúrate de que este ID de rank exista
        ]);

    }
}
