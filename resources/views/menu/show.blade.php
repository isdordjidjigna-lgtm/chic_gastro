<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La carte — Chic Gastro · Table {{ $table->numero }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Chic Gastro</h1>
                <p class="text-sm text-gray-500">Table {{ $table->numero }}</p>
            </div>
            <button id="panier-btn" class="relative bg-amber-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                Panier
                <span id="panier-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 hidden items-center justify-center">0</span>
            </button>
        </div>
        <div class="max-w-3xl mx-auto px-4 pb-3">
            <input type="search" id="recherche" placeholder="Rechercher un plat…"
                   class="w-full border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-6">
        @foreach($categories as $categorie)
            @if($categorie->plats->isNotEmpty())
                <section class="mb-8" data-categorie>
                    <h2 class="text-lg font-bold text-gray-800 mb-3 border-b border-gray-200 pb-1">{{ $categorie->nom }}</h2>
                    <div class="space-y-3">
                        @foreach($categorie->plats as $plat)
                            <article class="bg-white rounded-xl shadow-sm p-3 flex gap-3" data-plat data-nom="{{ Str::lower($plat->nom.' '.$plat->description) }}">
                                <img src="{{ $plat->photoUrl() }}" alt="{{ $plat->nom }}"
                                     class="w-20 h-20 object-cover rounded-lg bg-gray-100 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900">{{ $plat->nom }}</h3>
                                    @if($plat->description)
                                        <p class="text-sm text-gray-500 line-clamp-2">{{ $plat->description }}</p>
                                    @endif
                                    <p class="font-bold text-amber-700 mt-1">{{ number_format($plat->prix, 0, ',', ' ') }} FCFA</p>
                                </div>
                                <button class="self-center bg-amber-600 text-white text-sm font-semibold px-3 py-2 rounded-lg shrink-0" disabled title="Bientôt disponible">
                                    +
                                </button>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach
    </main>

    <script>
        // Filtre de recherche côté client (la logique panier arrive avec feature/menu-client).
        document.getElementById('recherche').addEventListener('input', function (e) {
            const q = e.target.value.toLowerCase();
            document.querySelectorAll('[data-plat]').forEach(el => {
                el.style.display = el.dataset.nom.includes(q) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
