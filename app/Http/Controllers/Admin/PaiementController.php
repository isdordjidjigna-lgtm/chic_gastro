<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\View\View;

class PaiementController extends Controller
{
    /**
     * Historique des paiements encaissés (espèces).
     */
    public function index(): View
    {
        $paiements = Paiement::with(['commande.table', 'encaisseur'])
            ->latest()
            ->paginate(20);

        return view('admin.paiements.index', ['paiements' => $paiements]);
    }
}
