@extends('layouts.admin')

@section('title', $categorieActive ? $categorieActive->nom : 'Menu (plats)')
@section('eyebrow', 'Carte • ' . $plats->total() . ' plat(s)')

@section('actions')
    <a href="{{ route('admin.plats.create', request('categorie') ? ['categorie' => request('categorie')] : []) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold shadow-sm transition">+ Ajouter un plat</a>
@endsection

@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-4">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    {{-- Carte filtres --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-44">
                <x-input-label for="q" value="Recherche" />
                <div class="relative mt-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    <x-text-input id="q" name="q" :value="request('q')" placeholder="Nom du plat..." class="pl-9" />
                </div>
            </div>
            <div>
                <x-input-label for="categorie" value="Catégorie" />
                <select id="categorie" name="categorie" class="mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">Toutes</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" @selected(request('categorie') == $categorie->id)>{{ $categorie->nom }}</option>
                    @endforeach
                </select>
            </div>
            <x-primary-button>Filtrer</x-primary-button>
        </form>
        @if($categorieActive)
            <p class="text-xs text-gray-400 mt-3">{{ $categorieActive->nom }} — {{ $plats->total() }} plat(s) affiché(s) · <a href="{{ route('admin.plats.index') }}" class="text-amber-700 font-semibold">✕ Tout afficher</a></p>
        @else
            <p class="text-xs text-gray-400 mt-3">Affichage de {{ $plats->firstItem() ?? 0 }}-{{ $plats->lastItem() ?? 0 }} sur {{ $plats->total() }} plat(s)</p>
        @endif
    </div>

    {{-- Carte tableau --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Photo</th>
                    <th class="text-left px-5 py-3">Nom</th>
                    <th class="text-left px-5 py-3">Catégorie</th>
                    <th class="text-left px-5 py-3">Prix</th>
                    <th class="text-left px-5 py-3">Disponible</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($plats as $plat)
                    <tr class="hover:bg-amber-50/50 transition {{ $plat->disponible ? '' : 'opacity-50' }}">
                        <td class="px-5 py-4"><img src="{{ $plat->photoUrl() }}" class="w-12 h-12 object-cover rounded-lg" alt=""></td>
                        <td class="px-5 py-4 font-semibold">{{ $plat->nom }}</td>
                        <td class="px-5 py-4">{{ $plat->categorie->nom }}</td>
                        <td class="px-5 py-4">{{ number_format($plat->prix, 0, ',', ' ') }} FCFA</td>
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.plats.disponible', $plat) }}">
                                @csrf @method('PATCH')
                                <button class="px-2 py-1 rounded-full text-xs font-semibold {{ $plat->disponible ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $plat->disponible ? 'Disponible' : 'Indisponible' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-4 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.plats.edit', $plat) }}" class="text-amber-700 font-semibold">Modifier</a>
                            <form method="POST" action="{{ route('admin.plats.destroy', $plat) }}" class="inline" onsubmit="return confirm('Supprimer ce plat ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 font-semibold">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-500">Aucun plat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $plats->links() }}</div>
@endsection
