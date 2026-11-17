<?php

namespace App\Http\Controllers\Cuisine;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommandeController extends Controller
{
    /**
     * File de cuisine : nouvelles commandes et commandes en préparation.
     */
    public function index(): View
    {
        return view('cuisine.dashboard', [
            'nouvelles' => $this->nouvellesCommandes(),
            'enPreparation' => $this->commandesEnPreparation(),
        ]);
    }

    /**
     * Partiel HTML rafraîchi par polling (toutes les 7 s).
     */
    public function liste(): View
    {
        return view('cuisine._commandes', [
            'nouvelles' => $this->nouvellesCommandes(),
            'enPreparation' => $this->commandesEnPreparation(),
        ]);
    }

    private function nouvellesCommandes()
    {
        return Commande::with(['table', 'lignes.plat'])
            ->whereIn('statut', [Commande::EN_ATTENTE, Commande::CONFIRMEE])
            ->latest()
            ->get();
    }

    private function commandesEnPreparation()
    {
        return Commande::with(['table', 'lignes.plat'])
            ->where('statut', Commande::EN_PREPARATION)
            ->latest()
            ->get();
    }

    /**
     * Le cuisinier accepte la préparation (en_attente/confirmée → en préparation).
     */
    public function accepter(Commande $commande): RedirectResponse
    {
        abort_unless(in_array($commande->statut, [Commande::EN_ATTENTE, Commande::CONFIRMEE], true), 422);
        $commande->update(['statut' => Commande::EN_PREPARATION]);

        return redirect()->route('cuisine.dashboard');
    }

    /**
     * La cuisine signale que la commande est prête.
     */
    public function prete(Commande $commande): RedirectResponse
    {
        abort_unless($commande->statut === Commande::EN_PREPARATION, 422);
        $commande->update(['statut' => Commande::PRETE]);

        return redirect()->route('cuisine.dashboard');
    }
}
