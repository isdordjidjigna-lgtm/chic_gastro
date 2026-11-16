<?php

namespace Database\Seeders;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Plat;
use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;

class CommandeSeeder extends Seeder
{
    /**
     * Commandes de démonstration dans différents statuts.
     */
    public function run(): void
    {
        $tables = RestaurantTable::take(4)->get();
        if ($tables->isEmpty()) {
            return;
        }

        $plats = Plat::where('disponible', true)->get();
        if ($plats->isEmpty()) {
            return;
        }

        $exemples = [
            [Commande::EN_ATTENTE, 0],
            [Commande::EN_PREPARATION, 1],
            [Commande::PRETE, 2],
            [Commande::SERVIE, 3],
        ];

        foreach ($exemples as [$statut, $indexTable]) {
            $table = $tables[$indexTable % $tables->count()];
            $selection = $plats->random(min(3, $plats->count()));

            $commande = Commande::create([
                'numero' => Commande::genererNumero(),
                'table_id' => $table->id,
                'statut' => $statut,
                'total' => 0,
                'note' => null,
            ]);

            $total = 0;
            foreach ($selection as $plat) {
                $quantite = random_int(1, 2);
                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'plat_id' => $plat->id,
                    'quantite' => $quantite,
                    'prix_unitaire' => $plat->prix,
                    'observations' => null,
                ]);
                $total += $quantite * (float) $plat->prix;
            }

            $commande->update(['total' => $total]);
        }
    }
}
