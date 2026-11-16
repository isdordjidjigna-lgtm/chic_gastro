<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Plat extends Model
{
    protected $fillable = [
        'categorie_id', 'nom', 'description', 'prix', 'photo', 'disponible',
    ];

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'disponible' => 'boolean',
        ];
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    /**
     * URL de la photo du plat, avec image placeholder par défaut.
     */
    public function photoUrl(): string
    {
        if ($this->photo) {
            return Storage::disk('public')->url($this->photo);
        }

        return asset('images/placeholder.svg');
    }
}
