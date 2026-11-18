@extends('layouts.admin')

@section('title', 'Commandes')
@section('eyebrow', 'Exploitations • ' . $commandes->total() . ' commande(s)')

@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
    @endif

    {{-- Carte filtres --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-44">
                <x-input-label for="q" value="Recherche" />
                <div class="relative mt-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    <x-text-input id="q" name="q" :value="request('q')" placeholder="Référence, table..." class="pl-9" />
                </div>
            </div>
            <div>
                <x-input-label for="statut" value="Statut" />
                <select id="statut" name="statut" class="mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">Tous</option>
                    @foreach($statuts as $value => $label)
                        <option value="{{ $value }}" @selected(request('statut') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="table_id" value="Table" />
                <select id="table_id" name="table_id" class="mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">Toutes</option>
                    @foreach($tables as $table)
                        <option value="{{ $table->id }}" @selected(request('table_id') == $table->id)>{{ $table->numero }}</option>
                    @endforeach
                </select>
            </div>
            <x-primary-button>Filtrer</x-primary-button>
        </form>
        <p class="text-xs text-gray-400 mt-3">Affichage de {{ $commandes->firstItem() ?? 0 }}-{{ $commandes->lastItem() ?? 0 }} sur {{ $commandes->total() }} commande(s)</p>
    </div>

    {{-- Carte tableau --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">N°</th>
                    <th class="text-left px-5 py-3">Table</th>
                    <th class="text-left px-5 py-3">Statut</th>
                    <th class="text-left px-5 py-3">Total</th>
                    <th class="text-left px-5 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($commandes as $commande)
                    <tr class="hover:bg-amber-50/50 transition">
                        <td class="px-5 py-4 font-mono text-xs"><a class="text-amber-700 font-semibold" href="{{ route('admin.commandes.show', $commande) }}">{{ $commande->numero }}</a></td>
                        <td class="px-5 py-4">{{ $commande->table->numero ?? '—' }}</td>
                        <td class="px-5 py-4"><x-statut-badge :statut="$commande->statut" /></td>
                        <td class="px-5 py-4 font-semibold">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                        <td class="px-5 py-4 text-gray-500">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">Aucune commande.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $commandes->links() }}</div>
@endsection
