<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_CUISINIER = 'cuisinier';
    public const ROLE_SERVEUR = 'serveur';

    public const ROLES = [
        self::ROLE_ADMIN => 'Administrateur',
        self::ROLE_CUISINIER => 'Cuisinier',
        self::ROLE_SERVEUR => 'Serveur',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'actif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCuisinier(): bool
    {
        return $this->role === self::ROLE_CUISINIER;
    }

    public function isServeur(): bool
    {
        return $this->role === self::ROLE_SERVEUR;
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? ($this->role ?: '—');
    }

    /**
     * Route d'accueil selon le rôle de l'utilisateur.
     */
    public function routeAccueil(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => route('admin.dashboard'),
            self::ROLE_CUISINIER => route('cuisine.dashboard'),
            default => route('serveur.dashboard'),
        };
    }
}
