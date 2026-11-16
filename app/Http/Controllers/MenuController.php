<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;
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

        $categories = \App\Models\Category::with(['plats' => fn ($q) => $q->where('disponible', true)->orderBy('nom')])
            ->orderBy('ordre')
            ->get();

        return view('menu.show', [
            'table' => $table,
            'categories' => $categories,
        ]);
    }
}
