<?php

namespace Database\Seeders;

use App\Models\Commande;
use App\Models\Facture;
use App\Models\LigneCommande;
use App\Models\Paiement;
use App\Models\Plat;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CommandeSeeder extends Seeder
{
    /**
     * Commandes de démonstration :
     * - 4 commandes « vivantes » (aujourd'hui) dans différents statuts ;
     * - ~10 jours d'historique de commandes terminées, payées et facturées,
     *   pour alimenter les statistiques.
     */
    public function run(): void
    {
        $tables = RestaurantTable::all();
        $plats = Plat::where('disponible', true)->get();
        $serveurs = User::where('role', User::ROLE_SERVEUR)->get();

        if ($tables->isEmpty() || $plats->isEmpty()) {
            return;
        }

        // Commandes en cours pour la démo des files cuisine/serveur.
        $exemples = [
            [Commande::EN_ATTENTE, 0],
            [Commande::EN_PREPARATION, 1],
            [Commande::PRETE, 2],
            [Commande::SERVIE, 3],
        ];

        foreach ($exemples as [$statut, $indexTable]) {
            $this->creerCommande(
                $tables[$indexTable % $tables->count()],
                $plats,
                $statut,
                null,
                now()->subHours(random_int(1, 4)),
            );
        }

        // Historique : commandes terminées sur les 10 derniers jours.
        for ($j = 10; $j >= 1; $j--) {
            $jour = now()->subDays($j);
            foreach (range(1, random_int(2, 5)) as $i) {
                // Passage en cuisine entre 11h et 21h, aléatoire.
                $commandeLe = $jour->copy()->setTime(random_int(11, 21), random_int(0, 59));
                $encaisseeLe = $commandeLe->copy()->addMinutes(random_int(30, 120));

                $commande = $this->creerCommande(
                    $tables->random(),
                    $plats,
                    Commande::TERMINEE,
                    $serveurs->isNotEmpty() ? $serveurs->random() : null,
                    $commandeLe,
                    $encaisseeLe,
                );
            }
        }
    }

    /**
     * Crée une commande avec ses lignes ; si $encaisseeLe est fourni, ajoute
     * aussi le paiement en espèces et la facture, puis backdate les timestamps.
     */
    private function creerCommande(
        RestaurantTable $table,
        $plats,
        string $statut,
        ?User $encaisseur,
        Carbon $commandeLe,
        ?Carbon $encaisseeLe = null,
    ): Commande {
        $selection = $plats->random(min(random_int(1, 3), $plats->count()));

        $commande = Commande::create([
            'numero' => Commande::genererNumero($commandeLe),
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

        DB::table('commandes')->where('id', $commande->id)->update([
            'total' => $total,
            'created_at' => $commandeLe,
            'updated_at' => $commandeLe,
        ]);
        $commande->refresh();

        if ($encaisseeLe && $encaisseur) {
            $paiement = Paiement::create([
                'commande_id' => $commande->id,
                'montant' => $commande->total,
                'mode' => Paiement::MODE_ESPECES,
                'encaisse_par' => $encaisseur->id,
            ]);
            DB::table('paiements')->where('id', $paiement->id)->update([
                'created_at' => $encaisseeLe,
                'updated_at' => $encaisseeLe,
            ]);

            $facture = Facture::create([
                'commande_id' => $commande->id,
                'numero' => Facture::genererNumero($encaisseeLe),
                'montant' => $commande->total,
                'date' => $encaisseeLe,
                'details' => [
                    'table' => $table->numero,
                    'commande' => $commande->numero,
                    'lignes' => $commande->lignes()->with('plat')->get()->map(fn ($l) => [
                        'plat' => $l->plat?->nom,
                        'quantite' => $l->quantite,
                        'prix_unitaire' => (float) $l->prix_unitaire,
                        'observations' => $l->observations,
                    ])->all(),
                ],
            ]);
            DB::table('factures')->where('id', $facture->id)->update([
                'created_at' => $encaisseeLe,
                'updated_at' => $encaisseeLe,
            ]);
        }

        return $commande;
    }
}
