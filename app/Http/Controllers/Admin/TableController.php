<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Support\QrCodes;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TableController extends Controller
{
    public function index(): View
    {
        return view('admin.tables.index', [
            'tables' => RestaurantTable::withCount(['commandes' => fn ($q) => $q->whereIn('statut', ['en_attente', 'confirmee', 'en_preparation', 'prete'])])
                ->orderBy('numero')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.tables.form', ['table' => new RestaurantTable]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'numero' => ['required', 'string', 'max:20', 'unique:tables,numero'],
            'actif' => ['nullable', 'boolean'],
        ]);

        $data['actif'] = $request->boolean('actif', true);
        $data['token'] = Str::random(48);

        RestaurantTable::create($data);

        return redirect()->route('admin.tables.index')->with('success', 'Table créée.');
    }

    public function edit(RestaurantTable $table): View
    {
        return view('admin.tables.form', ['table' => $table]);
    }

    public function update(Request $request, RestaurantTable $table): RedirectResponse
    {
        $data = $request->validate([
            'numero' => ['required', 'string', 'max:20', 'unique:tables,numero,'.$table->id],
            'actif' => ['nullable', 'boolean'],
        ]);

        $data['actif'] = $request->boolean('actif');
        $table->update($data);

        return redirect()->route('admin.tables.index')->with('success', 'Table mise à jour.');
    }

    public function destroy(RestaurantTable $table): RedirectResponse
    {
        // Empêche la suppression d'une table ayant un historique de commandes.
        if ($table->commandes()->exists()) {
            return back()->withErrors(['table' => 'Impossible de supprimer une table ayant des commandes. Désactivez-la plutôt.']);
        }

        $table->delete();

        return redirect()->route('admin.tables.index')->with('success', 'Table supprimée.');
    }

    /**
     * QR Code PNG de la table.
     */
    public function qrPng(RestaurantTable $table): Response
    {
        $png = QrCodes::png($table->urlMenu());

        return response($png, 200, ['Content-Type' => 'image/png']);
    }

    /**
     * QR Code SVG de la table (téléchargement vectoriel).
     */
    public function qrSvg(RestaurantTable $table): Response
    {
        $svg = QrCodes::svg($table->urlMenu());

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    /**
     * Page d'impression de tous les QR Codes.
     */
    public function imprimer(): View
    {
        return view('admin.tables.imprimer', [
            'tables' => RestaurantTable::where('actif', true)->orderBy('numero')->get(),
        ]);
    }
}
