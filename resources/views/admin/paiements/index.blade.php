@extends('layouts.admin')

@section('title', 'Paiements')
@section('eyebrow', 'Caisse • ' . $paiements->total() . ' encaissement(s)')

@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Date</th>
                    <th class="text-left px-5 py-3">Commande</th>
                    <th class="text-left px-5 py-3">Table</th>
                    <th class="text-left px-5 py-3">Montant</th>
                    <th class="text-left px-5 py-3">Mode</th>
                    <th class="text-left px-5 py-3">Encaissé par</th>
                    <th class="text-left px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($paiements as $paiement)
                    <tr class="hover:bg-amber-50/50 transition">
                        <td class="px-5 py-4 text-gray-500">{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-4">
                            <a class="text-amber-700 font-semibold font-mono text-xs" href="{{ route('admin.commandes.show', $paiement->commande) }}">
                                {{ $paiement->commande->numero ?? '—' }}
                            </a>
                        </td>
                        <td class="px-5 py-4">{{ $paiement->commande->table->numero ?? '—' }}</td>
                        <td class="px-5 py-4 font-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                        <td class="px-5 py-4">Espèces</td>
                        <td class="px-5 py-4">{{ $paiement->encaisseur->name ?? '—' }}</td>
                        <td class="px-5 py-4">
                            @if($paiement->commande?->facture)
                                <a href="{{ route('admin.commandes.facture', $paiement->commande) }}" target="_blank" class="text-amber-700 font-semibold">Facture</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-8 text-center text-gray-500">Aucun paiement enregistré.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $paiements->links() }}</div>
    </div>
@endsection
