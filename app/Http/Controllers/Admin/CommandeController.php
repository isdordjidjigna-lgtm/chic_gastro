<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommandeController extends Controller
{
    /**
     * Consultation de toutes les commandes (filtres : statut, table, recherche).
     */
    public function index(Request $request): View
    {
        $commandes = Commande::with(['table'])
            ->when($request->filled('statut'), fn ($q) => $q->where('statut', $request->statut))
            ->when($request->filled('table_id'), fn ($q) => $q->where('table_id', $request->table_id))
            ->when($request->filled('q'), fn ($q) => $q->where('numero', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.commandes.index', [
            'commandes' => $commandes,
            'statuts' => Commande::STATUTS,
            'tables' => \App\Models\RestaurantTable::orderBy('numero')->get(),
        ]);
    }

    public function show(Commande $commande): View
    {
        $commande->load(['table', 'lignes.plat', 'paiement.encaisseur', 'facture']);

        return view('admin.commandes.show', [
            'commande' => $commande,
            'statuts' => Commande::STATUTS,
        ]);
    }

    /**
     * L'admin peut faire avancer ou reculer le statut (contrairement aux autres rôles).
     */
    public function update(Request $request, Commande $commande): RedirectResponse
    {
        $data = $request->validate([
            'statut' => ['required', 'in:'.implode(',', array_keys(Commande::STATUTS))],
        ]);

        $commande->update(['statut' => $data['statut']]);

        return redirect()->route('admin.commandes.show', $commande)->with('success', 'Statut mis à jour.');
    }

    /**
     * Facture imprimable de la commande.
     */
    public function facture(Commande $commande): View
    {
        abort_unless($commande->facture()->exists(), 404);
        $commande->load(['table', 'lignes.plat', 'facture', 'paiement.encaisseur']);

        return view('admin.commandes.facture', ['commande' => $commande]);
    }
}
