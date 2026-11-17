# Chic Gastro — Commande par QR Code

Application web de commande de plats au restaurant via QR Code. Chaque table possède un QR Code unique : le client le scanne, consulte la carte, compose son panier et passe commande depuis son smartphone, sans compte. La commande arrive en cuisine, est servie par le personnel, puis réglée en espèces avec facture.

## Stack

- **Backend** : Laravel 12 (PHP 8.2+), architecture MVC, Eloquent ORM
- **Base de données** : MySQL (XAMPP en local)
- **Frontend** : Blade + Tailwind CSS + Alpine.js (mobile-first)
- **QR Code** : `simplesoftwareio/simple-qrcode`
- **Temps réel** : polling AJAX (5–10 s) — pas de WebSocket
- **Auth** : Laravel Breeze, rôles `admin` / `cuisinier` / `serveur`

## Rôles

| Rôle | Accès |
|---|---|
| Client (anonyme) | `/menu/{token}` via QR Code — carte, panier, commande, suivi |
| Administrateur | `/admin` — CRUD, utilisateurs, tables, statistiques |
| Cuisinier | `/cuisine` — accepte les commandes, signale « Prête » |
| Serveur | `/serveur` — sert les commandes prêtes, historique |

## Installation (équipe)

### Prérequis

- PHP 8.2+ avec extensions `curl`, `gd`, `intl`, `zip`, `mbstring`, `pdo_mysql`
- Composer 2
- Node.js + npm (pour compiler les assets Vite/Tailwind)
- MySQL via **XAMPP** (déjà installé sur le poste de référence)

### Étapes

```powershell
# 1. Cloner le dépôt
git clone https://github.com/isdordjidjigna-lgtm/chic_gastro.git
cd chic_gastro

# 2. Dépendances PHP et JS
composer install
npm install

# 3. Configuration
copy .env.example .env
php artisan key:generate

# 4. Base de données
#    - Démarrer MySQL depuis le panneau de contrôle XAMPP
#    - Créer la base :
#      mysql -u root -e "CREATE DATABASE chic_gastro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
#    - Renseigner DB_* dans .env si besoin (défaut : mysql/root sans mot de passe)
php artisan migrate --seed
php artisan storage:link

# 5. Assets frontend
npm run build        # ou : npm run dev pour le rechargement à chaud

# 6. Lancer le serveur de dev
php artisan serve    # http://localhost:8000
```

### Comptes de test (mot de passe : `password`)

| Rôle | Email |
|---|---|
| Administrateur | `admin@chicgastro.com` |
| Cuisinier | `cuisine@chicgastro.com` |
| Serveur | `serveur@chicgastro.com` |

### Tester le parcours client

Après `migrate --seed`, 12 tables sont créées (T01 à T12). Récupérer le token d'une table :

```powershell
php artisan tinker --execute="echo App\Models\RestaurantTable::first()->token;"
```

puis ouvrir `http://localhost:8000/menu/{token}` sur un smartphone ou dans un navigateur mobile.

### Tests

```powershell
php artisan test
```

Les tests tournent sur SQLite en mémoire (`phpunit.xml`), sans toucher à la base MySQL de dev.

## Workflow Git

Une branche par fonctionnalité, merge via pull request vers `main` :

- `feature/fondations` — socle (auth, schéma, seeders)
- `feature/menu-client` — carte, panier, commande, suivi
- `feature/cuisine-serveur` — files cuisine et serveur
- `feature/admin-qr` — administration et QR Codes
- `feature/paiement-stats` — paiement, facture, statistiques

## Statuts d'une commande

`en_attente` → `confirmee` → `en_preparation` → `prete` → `servie` → `terminee` (`annulee` possible en début de parcours)
