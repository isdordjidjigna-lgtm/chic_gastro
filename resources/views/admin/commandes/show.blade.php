<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Commande {{ $commande->numero }}</h2>
            <a href="{{ route('admin.commandes.index') }}" class="text-gray-600 text-sm">← Retour</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                    <div><p class="text-gray-500">Table</p><p class="font-bold">{{ $commande->table->numero ?? '—' }}</p></div>
                    <div><p class="text-gray-500">Date</p><p class="font-bold">{{ $commande->created_at->format('d/m/Y H:i') }}</p></div>
                    <div><p class="text-gray-500">Total</p><p class="font-bold">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</p></div>
                    <div>
                        <p class="text-gray-500">Statut</p>
                        <form method="POST" action="{{ route('admin.commandes.update', $commande) }}" class="flex gap-2 mt-1">
                            @csrf @method('PUT')
                            <select name="statut" class="border-gray-300 rounded-md shadow-sm text-sm">
                                @foreach($statuts as $value => $label)
                                    <option value="{{ $value }}" @selected($commande->statut === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button class="bg-amber-600 text-white px-3 py-1 rounded-lg text-sm font-semibold">OK</button>
                        </form>
                    </div>
                </div>
                @if($commande->note)
                    <p class="text-sm text-gray-500 italic">Note client : {{ $commande->note }}</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-3">Plats commandés</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach($commande->lignes as $ligne)
                        <li class="py-3 flex justify-between">
                            <div>
                                <span class="font-semibold">{{ $ligne->quantite }}×</span> {{ $ligne->plat->nom ?? 'Plat supprimé' }}
                                <span class="text-xs text-gray-400">({{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA)</span>
                                @if($ligne->observations)
                                    <span class="block text-xs text-red-600 font-medium">⚠ {{ $ligne->observations }}</span>
                                @endif
                            </div>
                            <span class="font-semibold">{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} FCFA</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if(!in_array($commande->statut, [\App\Models\Commande::SERVIE, \App\Models\Commande::TERMINEE, \App\Models\Commande::ANNULEE]))
                <form method="POST" action="{{ route('admin.commandes.annuler', $commande) }}"
                      onsubmit="return confirm('Annuler définitivement la commande {{ $commande->numero }} ?');">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">Annuler la commande</button>
                </form>
            @endif

            @if($commande->paiement || $commande->facture)
                <div class="bg-white rounded-xl shadow-sm p-6 text-sm">
                    <h3 class="font-bold text-gray-800 mb-2">Règlement</h3>
                    @if($commande->paiement)
                        <p>Paiement : {{ number_format($commande->paiement->montant, 0, ',', ' ') }} FCFA en espèces
                            @if($commande->paiement->encaisseur) par {{ $commande->paiement->encaisseur->name }}@endif
                            le {{ $commande->paiement->created_at->format('d/m/Y H:i') }}</p>
                    @endif
                    @if($commande->facture)
                        <p>Facture {{ $commande->facture->numero }} — {{ number_format($commande->facture->montant, 0, ',', ' ') }} FCFA
                            <a href="{{ route('admin.commandes.facture', $commande) }}" target="_blank" class="text-amber-700 font-semibold">(imprimer)</a></p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
