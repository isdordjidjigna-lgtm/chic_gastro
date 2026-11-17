<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Administration</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.paiements.index') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">Paiements</a>
                <a href="{{ route('admin.stats') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">Statistiques</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.plats.index') }}" class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">
                    <p class="text-3xl font-bold text-amber-700">{{ $nbPlats }}</p>
                    <p class="text-gray-600 text-sm">Plats</p>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">
                    <p class="text-3xl font-bold text-amber-700">{{ $nbCategories }}</p>
                    <p class="text-gray-600 text-sm">Catégories</p>
                </a>
                <a href="{{ route('admin.tables.index') }}" class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">
                    <p class="text-3xl font-bold text-amber-700">{{ $nbTables }}</p>
                    <p class="text-gray-600 text-sm">Tables</p>
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">
                    <p class="text-3xl font-bold text-amber-700">{{ $nbUsers }}</p>
                    <p class="text-gray-600 text-sm">Utilisateurs</p>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-2xl font-bold text-indigo-700">{{ $commandesEnCours }}</p>
                    <p class="text-gray-600 text-sm">Commandes en cours</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-2xl font-bold text-green-700">{{ $commandesAujourdhui }}</p>
                    <p class="text-gray-600 text-sm">Commandes aujourd'hui</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Dernières commandes</h3>
                    <a href="{{ route('admin.commandes.index') }}" class="text-amber-700 text-sm font-semibold">Tout voir →</a>
                </div>
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
                        @forelse($dernieresCommandes as $commande)
                            <tr>
                                <td class="px-4 py-2"><a class="text-amber-700 font-semibold" href="{{ route('admin.commandes.show', $commande) }}">{{ $commande->numero }}</a></td>
                                <td class="px-4 py-2">{{ $commande->table->numero ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $commande->statutLabel() }}</td>
                                <td class="px-4 py-2">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                <td class="px-4 py-2">{{ $commande->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucune commande.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
