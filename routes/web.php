<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Cuisine\DashboardController as CuisineDashboard;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Serveur\DashboardController as ServeurDashboard;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Espace client : accès anonyme via le QR Code de la table.
Route::get('/menu/{token}', [MenuController::class, 'show'])->name('menu.show');

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
});

// Espace cuisine.
Route::middleware(['auth', 'role:'.User::ROLE_CUISINIER.','.User::ROLE_ADMIN])->prefix('cuisine')->name('cuisine.')->group(function () {
    Route::get('/', [CuisineDashboard::class, 'index'])->name('dashboard');
});

// Espace serveur.
Route::middleware(['auth', 'role:'.User::ROLE_SERVEUR.','.User::ROLE_ADMIN])->prefix('serveur')->name('serveur.')->group(function () {
    Route::get('/', [ServeurDashboard::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
