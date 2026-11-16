<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RestaurantTable extends Model
{
    protected $table = 'tables';

    protected $fillable = ['numero', 'token', 'actif'];

    protected function casts(): array
    {
        return ['actif' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::creating(function (RestaurantTable $table) {
            $table->token ??= Str::random(48);
        });
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'table_id');
    }

    public function urlMenu(): string
    {
        return route('menu.show', $this->token);
    }
}
