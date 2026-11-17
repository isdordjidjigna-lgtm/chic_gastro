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

    /**
     * Génère le prochain numéro de facture (ex. FAC-20261117-0003).
     * $date permet de backdater le numéro (seeders, imports).
     */
    public static function genererNumero(?\DateTimeInterface $date = null): string
    {
        $jour = ($date ?? now())->format('Ymd');
        $prefix = 'FAC-'.$jour.'-';
        $dernier = static::where('numero', 'like', $prefix.'%')->max('numero');
        $seq = $dernier ? ((int) substr($dernier, -4)) + 1 : 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
