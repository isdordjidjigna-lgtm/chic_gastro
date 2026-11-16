<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    public const EN_ATTENTE = 'en_attente';
    public const CONFIRMEE = 'confirmee';
    public const EN_PREPARATION = 'en_preparation';
    public const PRETE = 'prete';
    public const SERVIE = 'servie';
    public const TERMINEE = 'terminee';
    public const ANNULEE = 'annulee';

    public const STATUTS = [
        self::EN_ATTENTE => 'En attente',
        self::CONFIRMEE => 'Confirmée',
        self::EN_PREPARATION => 'En préparation',
        self::PRETE => 'Prête',
        self::SERVIE => 'Servie',
        self::TERMINEE => 'Terminée',
        self::ANNULEE => 'Annulée',
    ];

    protected $fillable = [
        'numero', 'table_id', 'statut', 'total', 'note',
    ];

    protected function casts(): array
    {
        return ['total' => 'decimal:2'];
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneCommande::class, 'commande_id');
    }

    public function paiement(): HasOne
    {
        return $this->hasOne(Paiement::class, 'commande_id');
    }

    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class, 'commande_id');
    }

    public function statutLabel(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    /**
     * Génère le prochain numéro de commande (ex. CMD-20261116-0007).
     */
    public static function genererNumero(): string
    {
        $prefix = 'CMD-'.now()->format('Ymd').'-';
        $dernier = static::where('numero', 'like', $prefix.'%')->max('numero');
        $seq = $dernier ? ((int) substr($dernier, -4)) + 1 : 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
