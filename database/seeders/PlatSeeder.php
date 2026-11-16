<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Plat;
use Illuminate\Database\Seeder;

class PlatSeeder extends Seeder
{
    public function run(): void
    {
        $menu = [
            'Entrées' => [
                ['Salade César', 'Laitue romaine, parmesan, croûtons, sauce César.', 4500, true],
                ['Soupe de poisson', 'Bouillon de poisson, légumes de saison.', 3500, true],
                ['Samoussas à la viande', 'Feuilletage croustillant, farce épicée.', 3000, false],
            ],
            'Plats principaux' => [
                ['Poulet braisé', 'Quart de poulet braisé aux épices locales, attiéké.', 6500, true],
                ['Riz gras au poisson', 'Riz parfumé, poisson braisé, légumes.', 6000, true],
                ['Alloco poulet', 'Plantain frit, poulet en sauce, piment.', 5500, true],
                ['Yassa au poulet', 'Poulet mariné au citron et oignons, riz blanc.', 7000, true],
            ],
            'Grillades' => [
                ['Brochettes de bœuf', 'Brochettes marinées, sauce piquante.', 8000, true],
                ['Poisson braisé grillé', 'Poisson entier braisé au feu de bois.', 9500, true],
                ['Chawarma poulet', 'Poulet mariné, frites, sauce blanche.', 5000, false],
            ],
            'Poissons' => [
                ['Capitaine braisé', 'Capitaine entier braisé, alloco.', 9000, true],
                ['Thiof yassa', 'Thiof mariné, oignons caramélisés.', 10000, true],
            ],
            'Boissons' => [
                ['Eau minérale 50cl', null, 1000, true],
                ['Jus de bissap 33cl', null, 2000, true],
                ['Coca-Cola 33cl', null, 2000, true],
                ['Bière locale 65cl', null, 2500, true],
                ['Vin rouge (verre)', null, 3000, false],
            ],
            'Desserts' => [
                ['Gâteau au chocolat', 'Gâteau moelleux au chocolat noir.', 3500, true],
                ['Salade de fruits', 'Fruits de saison au jus de citron.', 3000, true],
                ['Glace vanille', null, 2000, true],
            ],
        ];

        foreach ($menu as $nomCategorie => $plats) {
            $categorie = Category::where('nom', $nomCategorie)->first();
            if (! $categorie) {
                continue;
            }

            foreach ($plats as [$nom, $description, $prix, $disponible]) {
                Plat::updateOrCreate(
                    ['nom' => $nom, 'categorie_id' => $categorie->id],
                    [
                        'description' => $description,
                        'prix' => $prix,
                        'photo' => null,
                        'disponible' => $disponible,
                    ]
                );
            }
        }
    }
}
