<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Encaissement d'une commande : paiement en espèces + facture + passage à « Terminée ».
 */
class PaiementService
{
    public function encaisser(Commande $commande, User $encaisseur): Commande
    {
        // Une commande ne peut être encaissée qu'après avoir été servie.
        if ($commande->statut !== Commande::SERVIE) {
            throw new HttpException(422, 'Seule une commande servie peut être encaissée.');
        }

        if ($commande->paiement()->exists()) {
            throw new HttpException(422, 'Cette commande est déjà encaissée.');
        }

        return DB::transaction(function () use ($commande, $encaisseur) {
            Paiement::create([
                'commande_id' => $commande->id,
                'montant' => $commande->total,
                'mode' => Paiement::MODE_ESPECES,
                'encaisse_par' => $encaisseur->id,
            ]);

            Facture::create([
                'commande_id' => $commande->id,
                'numero' => Facture::genererNumero(),
                'montant' => $commande->total,
                'date' => now(),
                'details' => [
                    'table' => $commande->table?->numero,
                    'commande' => $commande->numero,
                    'lignes' => $commande->lignes->map(fn ($l) => [
                        'plat' => $l->plat?->nom,
                        'quantite' => $l->quantite,
                        'prix_unitaire' => (float) $l->prix_unitaire,
                        'observations' => $l->observations,
                    ])->all(),
                ],
            ]);

            $commande->update(['statut' => Commande::TERMINEE]);

            return $commande->fresh();
        });
    }
}
