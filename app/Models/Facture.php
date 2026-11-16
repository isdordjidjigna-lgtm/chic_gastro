<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    protected $fillable = [
        'commande_id', 'numero', 'montant', 'date', 'details',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'date' => 'datetime',
            'details' => 'array',
        ];
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }
}
