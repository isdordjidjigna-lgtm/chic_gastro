<?php

namespace App\Http\Controllers\Serveur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Services\PaiementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    /**
     * Encaissement en espèces : crée le paiement, la facture et passe à « Terminée ».
     */
    public function store(Request $request, Commande $commande, PaiementService $paiements): RedirectResponse
    {
        $paiements->encaisser($commande, $request->user());

        return redirect()->route('serveur.dashboard')->with('success', 'Paiement enregistré, commande terminée.');
    }
}
