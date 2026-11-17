<div class="grid gap-6 lg:grid-cols-2">
    {{-- Prêtes à servir --}}
    <section>
        <h3 class="font-bold text-gray-800 mb-3">Prêtes à servir <span class="text-sm font-normal text-gray-500">({{ $pretes->count() }})</span></h3>
        <div class="space-y-4">
            @forelse($pretes as $commande)
                <article class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-gray-900">Table {{ $commande->table->numero ?? '—' }}</p>
                            <p class="text-sm text-gray-500">{{ $commande->numero }} · {{ $commande->created_at->format('H:i') }} · {{ number_format($commande->total, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <form method="POST" action="{{ route('serveur.commandes.servie', $commande) }}">
                            @csrf
                            <button class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">Servie</button>
                        </form>
                    </div>
                    <ul class="text-sm space-y-1">
                        @foreach($commande->lignes as $ligne)
                            <li>
                                <span class="font-semibold">{{ $ligne->quantite }}×</span> {{ $ligne->plat->nom ?? 'Plat supprimé' }}
                                @if($ligne->observations)
                                    <span class="block text-xs text-red-600 font-medium">⚠ {{ $ligne->observations }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </article>
            @empty
                <p class="text-gray-500 text-sm bg-white rounded-xl p-4 shadow-sm">Aucune commande prête.</p>
            @endforelse
        </div>
    </section>

    {{-- Historique du jour --}}
    <section>
        <h3 class="font-bold text-gray-800 mb-3">Historique du jour</h3>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-2">Heure</th>
                        <th class="text-left px-4 py-2">Table</th>
                        <th class="text-left px-4 py-2">Commande</th>
                        <th class="text-left px-4 py-2">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($historique as $commande)
                        <tr>
                            <td class="px-4 py-2">{{ $commande->updated_at->format('H:i') }}</td>
                            <td class="px-4 py-2 font-semibold">{{ $commande->table->numero ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $commande->numero }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $commande->statut === 'servie' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $commande->statutLabel() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucune commande servie aujourd'hui.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
