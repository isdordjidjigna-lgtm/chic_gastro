<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('plats')->orderBy('ordre')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['categorie' => new Category]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100', 'unique:categories,nom'],
            'ordre' => ['nullable', 'integer', 'min:0'],
        ]);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(Category $categorie): View
    {
        return view('admin.categories.form', ['categorie' => $categorie]);
    }

    public function update(Request $request, Category $categorie): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100', 'unique:categories,nom,'.$categorie->id],
            'ordre' => ['nullable', 'integer', 'min:0'],
        ]);

        $categorie->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $categorie): RedirectResponse
    {
        // Bloque la suppression si la catégorie contient encore des plats.
        if ($categorie->plats()->exists()) {
            return back()->withErrors(['categorie' => 'Impossible de supprimer une catégorie contenant des plats.']);
        }

        $categorie->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }
}
