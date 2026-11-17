<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Paiement;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatsController extends Controller
{
    public function index(): View
    {
        return view('admin.stats');
    }

    /**
     * Données JSON pour les graphiques Chart.js.
     */
    public function donnees(Request $request): JsonResponse
    {
        $jours = min(max((int) $request->get('jours', 30), 7), 90);
        $debut = Carbon::today()->subDays($jours - 1);

        // Chiffre d'affaires par jour (basé sur les paiements encaissés).
        $caParJour = Paiement::query()
            ->selectRaw('DATE(created_at) as jour, SUM(montant) as total')
            ->where('created_at', '>=', $debut)
            ->groupBy('jour')
            ->pluck('total', 'jour');

        // Nombre de commandes par jour (hors annulées).
        $commandesParJour = Commande::query()
            ->selectRaw('DATE(created_at) as jour, COUNT(*) as n')
            ->where('created_at', '>=', $debut)
            ->where('statut', '!=', Commande::ANNULEE)
            ->groupBy('jour')
            ->pluck('n', 'jour');

        // Top plats par quantité commandée.
        $topPlats = LigneCommande::query()
            ->join('plats', 'plats.id', '=', 'ligne_commandes.plat_id')
            ->selectRaw('plats.nom, SUM(ligne_commandes.quantite) as qte')
            ->groupBy('plats.id', 'plats.nom')
            ->orderByDesc('qte')
            ->limit(10)
            ->get()
            ->map(fn ($l) => ['nom' => $l->nom, 'qte' => (int) $l->qte]);

        // Séries complètes sur la période (jours sans données = 0).
        $labels = [];
        $ca = [];
        $commandes = [];
        for ($i = 0; $i < $jours; $i++) {
            $date = $debut->copy()->addDays($i);
            $cle = $date->toDateString();
            $labels[] = $date->format('d/m');
            $ca[] = (float) ($caParJour[$cle] ?? 0);
            $commandes[] = (int) ($commandesParJour[$cle] ?? 0);
        }

        return response()->json([
            'labels' => $labels,
            'ca' => $ca,
            'commandes' => $commandes,
            'top_plats' => $topPlats,
            'ca_total' => number_format((float) Paiement::sum('montant'), 0, ',', ' '),
            'nb_commandes' => Commande::where('statut', '!=', Commande::ANNULEE)->count(),
            'nb_en_attente' => Commande::whereIn('statut', [Commande::EN_ATTENTE, Commande::EN_PREPARATION, Commande::PRETE])->count(),
        ]);
    }
}
