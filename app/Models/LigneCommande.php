<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneCommande extends Model
{
    protected $fillable = [
        'commande_id', 'plat_id', 'quantite', 'prix_unitaire', 'observations',
    ];

    protected function casts(): array
    {
        return ['prix_unitaire' => 'decimal:2'];
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    public function plat(): BelongsTo
    {
        return $this->belongsTo(Plat::class, 'plat_id');
    }

    public function sousTotal(): float
    {
        return $this->quantite * (float) $this->prix_unitaire;
    }
}
