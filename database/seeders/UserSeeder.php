<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $comptes = [
            ['name' => 'Administrateur', 'email' => 'admin@chicgastro.com', 'role' => User::ROLE_ADMIN],
            ['name' => 'Chef Cuisinier', 'email' => 'cuisine@chicgastro.com', 'role' => User::ROLE_CUISINIER],
            ['name' => 'Serveur Un', 'email' => 'serveur@chicgastro.com', 'role' => User::ROLE_SERVEUR],
        ];

        foreach ($comptes as $compte) {
            User::updateOrCreate(
                ['email' => $compte['email']],
                [
                    'name' => $compte['name'],
                    'password' => Hash::make('password'),
                    'role' => $compte['role'],
                    'actif' => true,
                ]
            );
        }
    }
}
