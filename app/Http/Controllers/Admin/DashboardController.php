<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Plat;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'nbPlats' => Plat::count(),
            'nbCategories' => Category::count(),
            'nbTables' => RestaurantTable::count(),
            'nbUsers' => User::count(),
            'commandesEnCours' => Commande::whereIn('statut', [
                Commande::EN_ATTENTE, Commande::CONFIRMEE,
                Commande::EN_PREPARATION, Commande::PRETE,
            ])->count(),
            'commandesAujourdhui' => Commande::whereDate('created_at', today())->count(),
            'dernieresCommandes' => Commande::with('table')->latest()->limit(8)->get(),
            'caAujourdhui' => (float) Paiement::whereDate('created_at', today())->sum('montant'),
            'ventes7j' => $this->ventesSeptJours(),
        ]);
    }

    /**
     * Chiffre d'affaires des 7 derniers jours pour le graphique du tableau de bord.
     */
    private function ventesSeptJours(): array
    {
        $debut = Carbon::today()->subDays(6);
        $parJour = Paiement::query()
            ->selectRaw('DATE(created_at) as jour, SUM(montant) as total')
            ->where('created_at', '>=', $debut)
            ->groupBy('jour')
            ->pluck('total', 'jour');

        $labels = [];
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $debut->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            $data[] = (float) ($parJour[$date->toDateString()] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
