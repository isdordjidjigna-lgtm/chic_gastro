@php
    $badgeCompteur = 'inline-flex items-center justify-center min-w-8 px-2.5 py-1 rounded-full text-sm font-bold';
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    {{-- Nouvelles commandes --}}
    <section>
        <div class="flex items-center gap-3 mb-4">
            <h3 class="text-xl font-bold text-gray-800">Nouvelles commandes</h3>
            <span class="{{ $badgeCompteur }} bg-amber-500 text-white">{{ $nouvelles->count() }}</span>
        </div>
        <div class="space-y-4">
            @forelse($nouvelles as $commande)
                @php $attente = (int) $commande->created_at->diffInMinutes(now()); @endphp
                <article class="bg-white rounded-2xl shadow-md p-5 border-l-8 {{ $attente > 15 ? 'border-red-500' : 'border-amber-500' }}">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-4">
                            <span class="text-5xl font-black text-amber-600 leading-none">{{ $commande->table->numero ?? '—' }}</span>
                            <div>
                                <p class="font-bold text-gray-900">{{ $commande->numero }}</p>
                                <p class="text-sm text-gray-500">reçue à {{ $commande->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-sm font-bold {{ $attente > 15 ? 'bg-red-100 text-red-700 animate-pulse' : 'bg-gray-100 text-gray-600' }}">
                            {{ $attente }} min
                        </span>
                    </div>

                    <ul class="space-y-2 mb-3">
                        @foreach($commande->lignes as $ligne)
                            <li class="flex items-baseline gap-3 text-lg">
                                <span class="inline-flex items-center justify-center min-w-9 h-9 px-2 rounded-lg bg-amber-100 text-amber-800 font-bold text-base">{{ $ligne->quantite }}×</span>
                                <span class="font-semibold text-gray-900">{{ $ligne->plat->nom ?? 'Plat supprimé' }}</span>
                            </li>
                            @if($ligne->observations)
                                <li class="ml-12 bg-yellow-100 border border-yellow-300 text-yellow-900 text-base font-semibold rounded-lg px-3 py-2">
                                    ⚠ {{ $ligne->observations }}
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    @if($commande->note)
                        <p class="mb-3 text-sm text-gray-600 italic bg-gray-50 rounded-lg px-3 py-2">Note : {{ $commande->note }}</p>
                    @endif

                    <form method="POST" action="{{ route('cuisine.commandes.accepter', $commande) }}">
                        @csrf
                        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-bold py-4 rounded-xl transition">
                            Accepter la préparation
                        </button>
                    </form>
                </article>
            @empty
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-500">
                    <p class="text-4xl mb-2">🍽️</p>
                    <p class="font-semibold">Aucune nouvelle commande</p>
                    <p class="text-sm mt-1">Les nouvelles commandes apparaîtront ici automatiquement.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- En préparation --}}
    <section>
        <div class="flex items-center gap-3 mb-4">
            <h3 class="text-xl font-bold text-gray-800">En préparation</h3>
            <span class="{{ $badgeCompteur }} bg-indigo-600 text-white">{{ $enPreparation->count() }}</span>
        </div>
        <div class="space-y-4">
            @forelse($enPreparation as $commande)
                @php $attente = (int) $commande->created_at->diffInMinutes(now()); @endphp
                <article class="bg-white rounded-2xl shadow-md p-5 border-l-8 {{ $attente > 15 ? 'border-red-500' : 'border-indigo-500' }}">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-4">
                            <span class="text-5xl font-black text-indigo-600 leading-none">{{ $commande->table->numero ?? '—' }}</span>
                            <div>
                                <p class="font-bold text-gray-900">{{ $commande->numero }}</p>
                                <p class="text-sm text-gray-500">reçue à {{ $commande->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-sm font-bold {{ $attente > 15 ? 'bg-red-100 text-red-700 animate-pulse' : 'bg-indigo-100 text-indigo-700' }}">
                            {{ $attente }} min
                        </span>
                    </div>

                    <ul class="space-y-2 mb-3">
                        @foreach($commande->lignes as $ligne)
                            <li class="flex items-baseline gap-3 text-lg">
                                <span class="inline-flex items-center justify-center min-w-9 h-9 px-2 rounded-lg bg-indigo-100 text-indigo-800 font-bold text-base">{{ $ligne->quantite }}×</span>
                                <span class="font-semibold text-gray-900">{{ $ligne->plat->nom ?? 'Plat supprimé' }}</span>
                            </li>
                            @if($ligne->observations)
                                <li class="ml-12 bg-yellow-100 border border-yellow-300 text-yellow-900 text-base font-semibold rounded-lg px-3 py-2">
                                    ⚠ {{ $ligne->observations }}
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    @if($commande->note)
                        <p class="mb-3 text-sm text-gray-600 italic bg-gray-50 rounded-lg px-3 py-2">Note : {{ $commande->note }}</p>
                    @endif

                    <form method="POST" action="{{ route('cuisine.commandes.prete', $commande) }}">
                        @csrf
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white text-lg font-bold py-4 rounded-xl transition">
                            Signaler « Prête »
                        </button>
                    </form>
                </article>
            @empty
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-500">
                    <p class="text-4xl mb-2">👨‍🍳</p>
                    <p class="font-semibold">Rien en préparation</p>
                    <p class="text-sm mt-1">Acceptez une commande pour la voir apparaître ici.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
