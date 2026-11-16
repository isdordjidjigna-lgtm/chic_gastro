<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    public const MODE_ESPECES = 'especes';

    protected $fillable = [
        'commande_id', 'montant', 'mode', 'encaisse_par',
    ];

    protected function casts(): array
    {
        return ['montant' => 'decimal:2'];
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    public function encaisseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'encaisse_par');
    }
}
