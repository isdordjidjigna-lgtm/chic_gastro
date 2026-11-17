<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Commande;
use App\Models\Plat;
use App\Models\RestaurantTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Affiche la carte pour la table identifiée par le token du QR Code.
     */
    public function show(string $token): View
    {
        $table = RestaurantTable::where('token', $token)->firstOrFail();

        if (! $table->actif) {
            return view('menu.indisponible', ['table' => $table]);
        }

        $categories = Category::with(['plats' => fn ($q) => $q->where('disponible', true)->orderBy('nom')])
            ->orderBy('ordre')
            ->get();

        // Données injectées en JSON dans la page pour le panier Alpine.js.
        $categoriesData = $categories->map(fn (Category $c) => [
            'nom' => $c->nom,
            'plats' => $c->plats->map(fn (Plat $p) => [
                'id' => $p->id,
                'nom' => $p->nom,
                'description' => $p->description ?? '',
                'prix' => (float) $p->prix,
                'photo' => $p->photoUrl(),
            ])->values(),
        ])->values();

        return view('menu.show', [
            'table' => $table,
            'categories' => $categories,
            'categoriesJson' => $categoriesData->toJson(),
        ]);
    }

    /**
     * Enregistre une commande passée depuis le panier client.
     * Le total est recalculé côté serveur à partir des prix en base.
     */
    public function store(Request $request, string $token): JsonResponse
    {
        $table = RestaurantTable::where('token', $token)->firstOrFail();

        if (! $table->actif) {
            return response()->json(['message' => 'La commande en ligne est indisponible pour cette table.'], 403);
        }

        $donnees = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.plat_id' => ['required', 'integer', 'exists:plats,id'],
            'items.*.quantite' => ['required', 'integer', 'min:1', 'max:50'],
            'items.*.observations' => ['nullable', 'string', 'max:200'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        // Vérifie que tous les plats sont encore disponibles et fige les prix.
        $ids = collect($donnees['items'])->pluck('plat_id')->unique();
        $plats = Plat::whereIn('id', $ids)->where('disponible', true)->get()->keyBy('id');

        if ($plats->count() !== $ids->count()) {
            return response()->json(['message' => 'Un ou plusieurs plats ne sont plus disponibles.'], 422);
        }

        $commande = DB::transaction(function () use ($table, $donnees, $plats) {
            $commande = Commande::create([
                'numero' => Commande::genererNumero(),
                'table_id' => $table->id,
                'statut' => Commande::EN_ATTENTE,
                'total' => 0,
                'note' => $donnees['note'] ?? null,
            ]);

            $total = 0;
            foreach ($donnees['items'] as $item) {
                $plat = $plats[$item['plat_id']];
                $commande->lignes()->create([
                    'plat_id' => $plat->id,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $plat->prix,
                    'observations' => $item['observations'] ?? null,
                ]);
                $total += $item['quantite'] * (float) $plat->prix;
            }

            $commande->update(['total' => $total]);

            return $commande;
        });

        return response()->json([
            'numero' => $commande->numero,
            'total' => (float) $commande->total,
            'suivi_url' => route('menu.commandes.suivi', [$token, $commande->numero]),
        ], 201);
    }

    /**
     * Page de suivi client d'une commande (polling toutes les 5 s).
     */
    public function suivi(string $token, string $numero): View
    {
        $table = RestaurantTable::where('token', $token)->firstOrFail();
        $commande = Commande::where('numero', $numero)
            ->where('table_id', $table->id)
            ->with('lignes.plat')
            ->firstOrFail();

        return view('menu.suivi', [
            'table' => $table,
            'commande' => $commande,
            'statutUrl' => route('menu.commandes.statut', [$token, $numero]),
        ]);
    }

    /**
     * Endpoint JSON pour le polling du suivi de commande.
     */
    public function statut(string $token, string $numero): JsonResponse
    {
        $table = RestaurantTable::where('token', $token)->firstOrFail();
        $commande = Commande::where('numero', $numero)
            ->where('table_id', $table->id)
            ->firstOrFail();

        return response()->json([
            'statut' => $commande->statut,
            'statut_label' => $commande->statutLabel(),
            'total' => (float) $commande->total,
        ]);
    }
}
