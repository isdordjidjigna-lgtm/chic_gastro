<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tableau de bord</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.paiements.index') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">Paiements</a>
                <a href="{{ route('admin.stats') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">Statistiques</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            {{-- Cartes de chiffres --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($caAujourdhui, 0, ',', ' ') }} FCFA</p>
                        <p class="text-sm text-gray-500">CA du jour</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $commandesAujourdhui }}</p>
                        <p class="text-sm text-gray-500">Commandes aujourd'hui</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $commandesEnCours }}</p>
                        <p class="text-sm text-gray-500">En cours</p>
                    </div>
                </div>
                <a href="{{ route('admin.tables.index') }}" class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $nbTables }}</p>
                        <p class="text-sm text-gray-500">Tables</p>
                    </div>
                </a>
            </div>

            {{-- Ventes 7 jours --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">Ventes — 7 derniers jours</h3>
                <canvas id="chart-ventes" height="90"></canvas>
            </div>

            {{-- Raccourcis --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                <a href="{{ route('admin.plats.index') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md hover:border-amber-300 border border-transparent transition">
                    <p class="text-2xl font-bold text-amber-700">{{ $nbPlats }}</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">Plats</p>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md hover:border-amber-300 border border-transparent transition">
                    <p class="text-2xl font-bold text-amber-700">{{ $nbCategories }}</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">Catégories</p>
                </a>
                <a href="{{ route('admin.tables.index') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md hover:border-amber-300 border border-transparent transition">
                    <p class="text-2xl font-bold text-amber-700">{{ $nbTables }}</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">Tables & QR</p>
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md hover:border-amber-300 border border-transparent transition">
                    <p class="text-2xl font-bold text-amber-700">{{ $nbUsers }}</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">Utilisateurs</p>
                </a>
                <a href="{{ route('admin.commandes.index') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md hover:border-amber-300 border border-transparent transition">
                    <p class="text-2xl font-bold text-amber-700">{{ $commandesEnCours }}</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">Commandes en cours</p>
                </a>
                <a href="{{ route('admin.paiements.index') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md hover:border-amber-300 border border-transparent transition">
                    <p class="text-2xl font-bold text-amber-700">💵</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">Paiements</p>
                </a>
                <a href="{{ route('admin.stats') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md hover:border-amber-300 border border-transparent transition">
                    <p class="text-2xl font-bold text-amber-700">📊</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">Statistiques</p>
                </a>
                <a href="{{ route('admin.tables.imprimer') }}" target="_blank" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md hover:border-amber-300 border border-transparent transition">
                    <p class="text-2xl font-bold text-amber-700">🖨️</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">Imprimer QR</p>
                </a>
            </div>

            {{-- Dernières commandes --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Dernières commandes</h3>
                    <a href="{{ route('admin.commandes.index') }}" class="text-amber-700 text-sm font-semibold">Tout voir →</a>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3">N°</th>
                            <th class="text-left px-4 py-3">Table</th>
                            <th class="text-left px-4 py-3">Statut</th>
                            <th class="text-left px-4 py-3">Total</th>
                            <th class="text-left px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dernieresCommandes as $commande)
                            <tr class="hover:bg-amber-50/50 transition">
                                <td class="px-4 py-3"><a class="text-amber-700 font-semibold" href="{{ route('admin.commandes.show', $commande) }}">{{ $commande->numero }}</a></td>
                                <td class="px-4 py-3">{{ $commande->table->numero ?? '—' }}</td>
                                <td class="px-4 py-3"><x-statut-badge :statut="$commande->statut" /></td>
                                <td class="px-4 py-3">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                <td class="px-4 py-3">{{ $commande->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Aucune commande.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>window.DASH_VENTES = @json($ventes7j);</script>
    @vite(['resources/js/dashboard.js'])
</x-app-layout>
