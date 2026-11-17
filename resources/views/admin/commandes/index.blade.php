<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Commandes</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" class="bg-white rounded-xl shadow-sm p-4 flex flex-wrap gap-3 items-end">
                <div>
                    <x-input-label for="q" value="N° commande" />
                    <x-text-input id="q" name="q" :value="request('q')" placeholder="CMD-…" />
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

            <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-2">N°</th>
                            <th class="text-left px-4 py-2">Table</th>
                            <th class="text-left px-4 py-2">Statut</th>
                            <th class="text-left px-4 py-2">Total</th>
                            <th class="text-left px-4 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($commandes as $commande)
                            <tr>
                                <td class="px-4 py-3"><a class="text-amber-700 font-semibold" href="{{ route('admin.commandes.show', $commande) }}">{{ $commande->numero }}</a></td>
                                <td class="px-4 py-3">{{ $commande->table->numero ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $commande->statutLabel() }}</td>
                                <td class="px-4 py-3">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                <td class="px-4 py-3">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucune commande.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $commandes->links() }}
        </div>
    </div>
</x-app-layout>
