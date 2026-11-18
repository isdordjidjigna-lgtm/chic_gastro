@php $badgeCompteur = 'inline-flex items-center justify-center min-w-8 px-2.5 py-1 rounded-full text-sm font-bold'; @endphp

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Prêtes à servir --}}
    <section>
        <div class="flex items-center gap-3 mb-4">
            <h3 class="text-xl font-bold text-green-700">Prêtes à servir</h3>
            <span class="{{ $badgeCompteur }} bg-green-600 text-white">{{ $pretes->count() }}</span>
        </div>
        <div class="space-y-4">
            @forelse($pretes as $commande)
                <article class="bg-white rounded-2xl shadow-md p-5 border-l-8 border-green-500">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-4">
                            <span class="text-5xl font-black text-green-600 leading-none">{{ $commande->table->numero ?? '—' }}</span>
                            <div>
                                <p class="font-bold text-gray-900">{{ $commande->numero }}</p>
                                <p class="text-sm text-gray-500">prête depuis {{ $commande->updated_at->format('H:i') }} · {{ number_format($commande->total, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('serveur.commandes.servie', $commande) }}">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-700 text-white text-base font-bold px-6 py-3 rounded-xl transition whitespace-nowrap">
                                Servie ✓
                            </button>
                        </form>
                    </div>
                    <ul class="space-y-2">
                        @foreach($commande->lignes as $ligne)
                            <li class="flex items-baseline gap-3 text-lg">
                                <span class="inline-flex items-center justify-center min-w-9 h-9 px-2 rounded-lg bg-green-100 text-green-800 font-bold text-base">{{ $ligne->quantite }}×</span>
                                <span class="font-semibold text-gray-900">{{ $ligne->plat->nom ?? 'Plat supprimé' }}</span>
                            </li>
                            @if($ligne->observations)
                                <li class="ml-12 bg-yellow-100 border border-yellow-300 text-yellow-900 text-base font-semibold rounded-lg px-3 py-2">
                                    ⚠ {{ $ligne->observations }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </article>
            @empty
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-500">
                    <p class="text-4xl mb-2">🛎️</p>
                    <p class="font-semibold">Aucune commande prête</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- En attente d'encaissement --}}
    <section>
        <div class="flex items-center gap-3 mb-4">
            <h3 class="text-xl font-bold text-amber-700">À encaisser</h3>
            <span class="{{ $badgeCompteur }} bg-amber-500 text-white">{{ $aEncaisser->count() }}</span>
        </div>
        <div class="space-y-4">
            @forelse($aEncaisser as $commande)
                <article class="bg-white rounded-2xl shadow-md p-5 border-l-8 border-amber-500">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-4">
                            <span class="text-5xl font-black text-amber-600 leading-none">{{ $commande->table->numero ?? '—' }}</span>
                            <div>
                                <p class="font-bold text-gray-900">{{ $commande->numero }}</p>
                                <p class="text-lg font-bold text-amber-700">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('serveur.commandes.payer', $commande) }}">
                            @csrf
                            <button class="bg-amber-600 hover:bg-amber-700 text-white text-base font-bold px-6 py-3 rounded-xl transition whitespace-nowrap">
                                Encaisser 💵
                            </button>
                        </form>
                    </div>
                    <ul class="space-y-2">
                        @foreach($commande->lignes as $ligne)
                            <li class="flex items-baseline gap-3 text-lg">
                                <span class="inline-flex items-center justify-center min-w-9 h-9 px-2 rounded-lg bg-amber-100 text-amber-800 font-bold text-base">{{ $ligne->quantite }}×</span>
                                <span class="font-semibold text-gray-900">{{ $ligne->plat->nom ?? 'Plat supprimé' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @empty
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-500">
                    <p class="text-4xl mb-2">💰</p>
                    <p class="font-semibold">Rien à encaisser</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Historique du jour --}}
    <section>
        <h3 class="text-xl font-bold text-gray-800 mb-4">Historique du jour</h3>
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Heure</th>
                        <th class="text-left px-4 py-3">Table</th>
                        <th class="text-left px-4 py-3">Commande</th>
                        <th class="text-left px-4 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($historique as $commande)
                        <tr class="hover:bg-amber-50/50 transition">
                            <td class="px-4 py-3">{{ $commande->updated_at->format('H:i') }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $commande->table->numero ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $commande->numero }}</td>
                            <td class="px-4 py-3"><x-statut-badge :statut="$commande->statut" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Aucune commande servie aujourd'hui.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
