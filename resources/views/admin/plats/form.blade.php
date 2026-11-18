@extends('layouts.admin')

@section('title', $plat->exists ? 'Modifier le plat' : 'Nouveau plat')
@section('eyebrow', 'Menu (plats)')

@section('actions')
    <a href="{{ route('admin.plats.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">← Retour</a>
@endsection

@section('content')
    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-4">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="POST" enctype="multipart/form-data"
                  action="{{ $plat->exists ? route('admin.plats.update', $plat) : route('admin.plats.store') }}"
                  class="space-y-4">
                @csrf
                @if($plat->exists) @method('PUT') @endif

                <div>
                    <x-input-label for="categorie_id" value="Catégorie" />
                    <select id="categorie_id" name="categorie_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">— Choisir —</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" @selected(old('categorie_id', $plat->categorie_id) == $categorie->id)>{{ $categorie->nom }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('categorie_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="nom" value="Nom" />
                    <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom', $plat->nom)" required />
                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $plat->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="prix" value="Prix (FCFA)" />
                        <x-text-input id="prix" name="prix" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('prix', $plat->prix)" required />
                        <x-input-error :messages="$errors->get('prix')" class="mt-2" />
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="disponible" value="1" @checked(old('disponible', $plat->exists ? $plat->disponible : true)) class="rounded border-gray-300">
                            <span class="text-sm text-gray-700">Disponible à la commande</span>
                        </label>
                    </div>
                </div>

                <div>
                    <x-input-label for="photo" value="Photo" />
                    @if($plat->photo)
                        <img src="{{ $plat->photoUrl() }}" class="mt-2 w-32 h-32 object-cover rounded-lg" alt="">
                    @endif
                    <input id="photo" name="photo" type="file" accept="image/*" class="mt-2 block w-full text-sm text-gray-600">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG ou WebP — 2 Mo max. Laisser vide pour conserver la photo actuelle.</p>
                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                </div>

                <div class="flex gap-3">
                    <x-primary-button>Enregistrer</x-primary-button>
                    <a href="{{ route('admin.plats.index') }}" class="px-4 py-2 text-gray-600">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
