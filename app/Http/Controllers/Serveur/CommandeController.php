<?php

namespace App\Http\Controllers\Serveur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommandeController extends Controller
{
    /**
     * Espace serveur : commandes prêtes à servir + historique du jour.
     */
    public function index(): View
    {
        return view('serveur.dashboard', [
            'pretes' => $this->commandesPretes(),
            'aEncaisser' => $this->commandesAEncaisser(),
            'historique' => $this->historique(),
        ]);
    }

    /**
     * Partiel HTML rafraîchi par polling (toutes les 7 s).
     */
    public function liste(): View
    {
        return view('serveur._commandes', [
            'pretes' => $this->commandesPretes(),
            'aEncaisser' => $this->commandesAEncaisser(),
            'historique' => $this->historique(),
        ]);
    }

    private function commandesPretes()
    {
        return Commande::with(['table', 'lignes.plat'])
            ->where('statut', Commande::PRETE)
            ->oldest()
            ->get();
    }

    /**
     * Commandes servies en attente d'encaissement.
     */
    private function commandesAEncaisser()
    {
        return Commande::with(['table', 'lignes.plat', 'paiement'])
            ->where('statut', Commande::SERVIE)
            ->oldest()
            ->get();
    }

    private function historique()
    {
        return Commande::with(['table', 'lignes.plat'])
            ->whereIn('statut', [Commande::SERVIE, Commande::TERMINEE])
            ->whereDate('updated_at', today())
            ->latest('updated_at')
            ->limit(20)
            ->get();
    }

    /**
     * Le serveur confirme que la commande a été servie à la table.
     */
    public function servie(Commande $commande): RedirectResponse
    {
        abort_unless($commande->statut === Commande::PRETE, 422);
        $commande->update(['statut' => Commande::SERVIE]);

        return redirect()->route('serveur.dashboard');
    }
}
