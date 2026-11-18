<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Paiements</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 text-sm">← Retour</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3">Date</th>
                            <th class="text-left px-4 py-3">Commande</th>
                            <th class="text-left px-4 py-3">Table</th>
                            <th class="text-left px-4 py-3">Montant</th>
                            <th class="text-left px-4 py-3">Mode</th>
                            <th class="text-left px-4 py-3">Encaissé par</th>
                            <th class="text-left px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($paiements as $paiement)
                            <tr class="hover:bg-amber-50/50 transition">
                                <td class="px-4 py-3">{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <a class="text-amber-700 font-semibold" href="{{ route('admin.commandes.show', $paiement->commande) }}">
                                        {{ $paiement->commande->numero ?? '—' }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $paiement->commande->table->numero ?? '—' }}</td>
                                <td class="px-4 py-3 font-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                <td class="px-4 py-3">Espèces</td>
                                <td class="px-4 py-3">{{ $paiement->encaisseur->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if($paiement->commande?->facture)
                                        <a href="{{ route('admin.commandes.facture', $paiement->commande) }}" target="_blank" class="text-amber-700 font-semibold">Facture</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucun paiement enregistré.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">{{ $paiements->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
