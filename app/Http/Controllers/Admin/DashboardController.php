<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Commande;
use App\Models\Plat;
use App\Models\RestaurantTable;
use App\Models\User;
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
        ]);
    }
}
