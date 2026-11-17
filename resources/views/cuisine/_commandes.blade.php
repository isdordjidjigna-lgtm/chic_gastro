<div class="grid gap-6 lg:grid-cols-2">
    {{-- Nouvelles commandes --}}
    <section>
        <h3 class="font-bold text-gray-800 mb-3">Nouvelles commandes <span class="text-sm font-normal text-gray-500">({{ $nouvelles->count() }})</span></h3>
        <div class="space-y-4">
            @forelse($nouvelles as $commande)
                <article class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-amber-500">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-gray-900">{{ $commande->numero }}</p>
                            <p class="text-sm text-gray-500">Table {{ $commande->table->numero ?? '—' }} · {{ $commande->created_at->format('H:i') }}</p>
                        </div>
                        <form method="POST" action="{{ route('cuisine.commandes.accepter', $commande) }}">
                            @csrf
                            <button class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">Accepter</button>
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
                    @if($commande->note)
                        <p class="mt-2 text-xs text-gray-500 italic">Note : {{ $commande->note }}</p>
                    @endif
                </article>
            @empty
                <p class="text-gray-500 text-sm bg-white rounded-xl p-4 shadow-sm">Aucune nouvelle commande.</p>
            @endforelse
        </div>
    </section>

    {{-- En préparation --}}
    <section>
        <h3 class="font-bold text-gray-800 mb-3">En préparation <span class="text-sm font-normal text-gray-500">({{ $enPreparation->count() }})</span></h3>
        <div class="space-y-4">
            @forelse($enPreparation as $commande)
                <article class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-indigo-500">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-gray-900">{{ $commande->numero }}</p>
                            <p class="text-sm text-gray-500">Table {{ $commande->table->numero ?? '—' }} · {{ $commande->created_at->format('H:i') }}</p>
                        </div>
                        <form method="POST" action="{{ route('cuisine.commandes.prete', $commande) }}">
                            @csrf
                            <button class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">Prête</button>
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
                <p class="text-gray-500 text-sm bg-white rounded-xl p-4 shadow-sm">Aucune commande en préparation.</p>
            @endforelse
        </div>
    </section>
</div>
