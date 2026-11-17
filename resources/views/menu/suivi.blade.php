<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Suivi de commande {{ $commande->numero }} — Chic Gastro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900">Chic Gastro</h1>
            <p class="text-sm text-gray-500">Table {{ $table->numero }} · Commande {{ $commande->numero }}</p>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-6 space-y-6" x-data="suivi()">
        {{-- Statut --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 text-center">
            <p class="text-sm text-gray-500 mb-1">Statut de votre commande</p>
            <p class="text-2xl font-bold" :class="couleurStatut" x-text="statutLabel"></p>
            <p class="text-sm text-gray-400 mt-2">Mise à jour automatique toutes les 5 secondes</p>
        </div>

        {{-- Détail --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="font-bold text-gray-800 mb-3">Détail de la commande</h2>
            <ul class="divide-y divide-gray-100">
                @foreach($commande->lignes as $ligne)
                    <li class="py-2 flex justify-between text-sm">
                        <span>{{ $ligne->quantite }}× {{ $ligne->plat->nom ?? 'Plat supprimé' }}
                            @if($ligne->observations)
                                <span class="block text-xs text-gray-400">{{ $ligne->observations }}</span>
                            @endif
                        </span>
                        <span class="font-semibold">{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} FCFA</span>
                    </li>
                @endforeach
            </ul>
            @if($commande->note)
                <p class="mt-3 text-sm text-gray-500 italic">Note : {{ $commande->note }}</p>
            @endif
            <div class="mt-4 pt-3 border-t border-gray-200 flex justify-between font-bold">
                <span>Total</span>
                <span class="text-amber-700">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <a href="{{ route('menu.show', $table->token) }}" class="block text-center text-amber-700 font-semibold text-sm">
            ← Retour à la carte
        </a>
    </main>

    <script>
        const STATUT_URL = @json($statutUrl);

        function suivi() {
            return {
                statut: @json($commande->statut),
                statutLabel: @json($commande->statutLabel()),
                timer: null,

                init() {
                    this.timer = setInterval(() => this.rafraichir(), 5000);
                },

                get couleurStatut() {
                    return {
                        'en_attente': 'text-amber-600',
                        'confirmee': 'text-blue-600',
                        'en_preparation': 'text-indigo-600',
                        'prete': 'text-green-600',
                        'servie': 'text-emerald-700',
                        'terminee': 'text-gray-600',
                        'annulee': 'text-red-600',
                    }[this.statut] || 'text-gray-700';
                },

                async rafraichir() {
                    try {
                        const res = await fetch(STATUT_URL, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) return;
                        const data = await res.json();
                        this.statut = data.statut;
                        this.statutLabel = data.statut_label;
                        if (['terminee', 'annulee'].includes(data.statut)) {
                            clearInterval(this.timer);
                        }
                    } catch (e) { /* on réessaie au prochain tick */ }
                },
            };
        }
    </script>
</body>
</html>
