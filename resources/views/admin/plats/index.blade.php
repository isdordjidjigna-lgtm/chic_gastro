<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Plats</h2>
            <a href="{{ route('admin.plats.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm">+ Ajouter un plat</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="GET" class="bg-white rounded-xl shadow-sm p-4 flex flex-wrap gap-3 items-end">
                <div>
                    <x-input-label for="q" value="Recherche" />
                    <x-text-input id="q" name="q" :value="request('q')" placeholder="Nom du plat…" />
                </div>
                <div>
                    <x-input-label for="categorie_id" value="Catégorie" />
                    <select id="categorie_id" name="categorie_id" class="mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">Toutes</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" @selected(request('categorie_id') == $categorie->id)>{{ $categorie->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <x-primary-button>Filtrer</x-primary-button>
            </form>

            <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-2">Photo</th>
                            <th class="text-left px-4 py-2">Nom</th>
                            <th class="text-left px-4 py-2">Catégorie</th>
                            <th class="text-left px-4 py-2">Prix</th>
                            <th class="text-left px-4 py-2">Disponible</th>
                            <th class="text-right px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($plats as $plat)
                            <tr class="hover:bg-amber-50/50 transition {{ $plat->disponible ? '' : 'opacity-50' }}">
                                <td class="px-4 py-3"><img src="{{ $plat->photoUrl() }}" class="w-12 h-12 object-cover rounded-lg" alt=""></td>
                                <td class="px-4 py-3 font-semibold">{{ $plat->nom }}</td>
                                <td class="px-4 py-3">{{ $plat->categorie->nom }}</td>
                                <td class="px-4 py-3">{{ number_format($plat->prix, 0, ',', ' ') }} FCFA</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('admin.plats.disponible', $plat) }}">
                                        @csrf @method('PATCH')
                                        <button class="px-2 py-1 rounded-full text-xs font-semibold {{ $plat->disponible ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $plat->disponible ? 'Disponible' : 'Indisponible' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                                    <a href="{{ route('admin.plats.edit', $plat) }}" class="text-blue-600 font-semibold">Modifier</a>
                                    <form method="POST" action="{{ route('admin.plats.destroy', $plat) }}" class="inline" onsubmit="return confirm('Supprimer ce plat ?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 font-semibold">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun plat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $plats->links() }}
        </div>
    </div>
</x-app-layout>
