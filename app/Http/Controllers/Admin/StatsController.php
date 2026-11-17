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

        // Plats les moins commandés (tous les plats, même jamais commandés).
        $platsPeu = \App\Models\Plat::query()
            ->leftJoin('ligne_commandes', 'ligne_commandes.plat_id', '=', 'plats.id')
            ->selectRaw('plats.nom, COALESCE(SUM(ligne_commandes.quantite), 0) as qte')
            ->groupBy('plats.id', 'plats.nom')
            ->orderBy('qte')
            ->orderBy('plats.nom')
            ->limit(5)
            ->get()
            ->map(fn ($p) => ['nom' => $p->nom, 'qte' => (int) $p->qte]);

        // Chiffre d'affaires par mois (12 derniers mois) — regroupé en PHP
        // pour rester compatible MySQL et SQLite (tests).
        $paiementsMois = Paiement::query()
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->get(['montant', 'created_at']);

        $parMois = [];
        foreach ($paiementsMois as $p) {
            $cle = $p->created_at->format('Y-m');
            $parMois[$cle] = ($parMois[$cle] ?? 0) + (float) $p->montant;
        }

        $labelsMois = [];
        $caMois = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labelsMois[] = $date->format('m/Y');
            $caMois[] = (float) ($parMois[$date->format('Y-m')] ?? 0);
        }

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
            'plats_peu' => $platsPeu,
            'labels_mois' => $labelsMois,
            'ca_mois' => $caMois,
            'ca_total' => number_format((float) Paiement::sum('montant'), 0, ',', ' '),
            'nb_commandes' => Commande::where('statut', '!=', Commande::ANNULEE)->count(),
            'nb_en_attente' => Commande::whereIn('statut', [Commande::EN_ATTENTE, Commande::EN_PREPARATION, Commande::PRETE])->count(),
        ]);
    }
}
