<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommandeController as AdminCommandeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PlatController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Cuisine\CommandeController as CuisineCommandeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Serveur\CommandeController as ServeurCommandeController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Espace client : accès anonyme via le QR Code de la table.
Route::get('/menu/{token}', [MenuController::class, 'show'])->name('menu.show');
Route::post('/menu/{token}/commandes', [MenuController::class, 'store'])->name('menu.commandes.store');
Route::get('/menu/{token}/commandes/{numero}', [MenuController::class, 'suivi'])->name('menu.commandes.suivi');
Route::get('/menu/{token}/api/commandes/{numero}', [MenuController::class, 'statut'])->name('menu.commandes.statut');

Route::get('/dashboard', function () {
    return redirect(auth()->user()->routeAccueil());
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Espace administrateur.
Route::middleware(['auth', 'role:'.User::ROLE_ADMIN])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::get('tables/qr/imprimer', [TableController::class, 'imprimer'])->name('tables.imprimer');
    Route::get('tables/{table}/qr.png', [TableController::class, 'qrPng'])->name('tables.qr.png');
    Route::get('tables/{table}/qr.svg', [TableController::class, 'qrSvg'])->name('tables.qr.svg');

    Route::resource('categories', CategoryController::class)
        ->parameters(['categories' => 'categorie'])
        ->except('show');
    Route::resource('plats', PlatController::class)->except('show');
    Route::patch('plats/{plat}/disponible', [PlatController::class, 'toggleDisponible'])->name('plats.disponible');
    Route::resource('tables', TableController::class)->except('show');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('commandes', AdminCommandeController::class)->only(['index', 'show', 'update']);
});

// Espace cuisine.
Route::middleware(['auth', 'role:'.User::ROLE_CUISINIER.','.User::ROLE_ADMIN])->prefix('cuisine')->name('cuisine.')->group(function () {
    Route::get('/', [CuisineCommandeController::class, 'index'])->name('dashboard');
    Route::get('/commandes/liste', [CuisineCommandeController::class, 'liste'])->name('commandes.liste');
    Route::post('/commandes/{commande}/accepter', [CuisineCommandeController::class, 'accepter'])->name('commandes.accepter');
    Route::post('/commandes/{commande}/prete', [CuisineCommandeController::class, 'prete'])->name('commandes.prete');
});

// Espace serveur.
Route::middleware(['auth', 'role:'.User::ROLE_SERVEUR.','.User::ROLE_ADMIN])->prefix('serveur')->name('serveur.')->group(function () {
    Route::get('/', [ServeurCommandeController::class, 'index'])->name('dashboard');
    Route::get('/commandes/liste', [ServeurCommandeController::class, 'liste'])->name('commandes.liste');
    Route::post('/commandes/{commande}/servie', [ServeurCommandeController::class, 'servie'])->name('commandes.servie');
});

require __DIR__.'/auth.php';
