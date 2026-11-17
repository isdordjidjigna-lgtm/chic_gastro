<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PlatController extends Controller
{
    public function index(Request $request): View
    {
        $plats = Plat::with('categorie')
            ->when($request->filled('categorie_id'), fn ($q) => $q->where('categorie_id', $request->categorie_id))
            ->when($request->filled('q'), fn ($q) => $q->where('nom', 'like', '%'.$request->q.'%'))
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString();

        return view('admin.plats.index', [
            'plats' => $plats,
            'categories' => Category::orderBy('ordre')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.plats.form', [
            'plat' => new Plat,
            'categories' => Category::orderBy('ordre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Plat::create($this->donneesValidees($request));

        return redirect()->route('admin.plats.index')->with('success', 'Plat créé.');
    }

    public function edit(Plat $plat): View
    {
        return view('admin.plats.form', [
            'plat' => $plat,
            'categories' => Category::orderBy('ordre')->get(),
        ]);
    }

    public function update(Request $request, Plat $plat): RedirectResponse
    {
        $data = $this->donneesValidees($request, $plat);

        // Suppression de l'ancienne photo si remplacée.
        if ($plat->photo && isset($data['photo'])) {
            Storage::disk('public')->delete($plat->photo);
        }

        $plat->update($data);

        return redirect()->route('admin.plats.index')->with('success', 'Plat mis à jour.');
    }

    public function destroy(Plat $plat): RedirectResponse
    {
        if ($plat->photo) {
            Storage::disk('public')->delete($plat->photo);
        }
        $plat->delete();

        return redirect()->route('admin.plats.index')->with('success', 'Plat supprimé.');
    }

    /**
     * Bascule rapide de la disponibilité depuis la liste.
     */
    public function toggleDisponible(Plat $plat): RedirectResponse
    {
        $plat->update(['disponible' => ! $plat->disponible]);

        return back();
    }

    private function donneesValidees(Request $request, ?Plat $plat = null): array
    {
        $data = $request->validate([
            'categorie_id' => ['required', 'exists:categories,id'],
            'nom' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'prix' => ['required', 'numeric', 'min:0'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'disponible' => ['nullable', 'boolean'],
        ]);

        $data['disponible'] = $request->boolean('disponible');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('plats', 'public');
        } elseif ($plat) {
            unset($data['photo']); // conserve la photo existante
        }

        return $data;
    }
}
