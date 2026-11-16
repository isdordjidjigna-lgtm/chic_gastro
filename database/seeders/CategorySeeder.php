<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Entrées',
            'Plats principaux',
            'Grillades',
            'Poissons',
            'Boissons',
            'Desserts',
        ];

        foreach ($categories as $i => $nom) {
            Category::updateOrCreate(
                ['nom' => $nom],
                ['ordre' => $i]
            );
        }
    }
}
