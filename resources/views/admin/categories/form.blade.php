@extends('layouts.admin')

@section('title', $categorie->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie')
@section('eyebrow', 'Catégories')

@section('actions')
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">← Retour</a>
@endsection

@section('content')
    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-4">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="max-w-xl">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="POST" action="{{ $categorie->exists ? route('admin.categories.update', $categorie) : route('admin.categories.store') }}" class="space-y-4">
                @csrf
                @if($categorie->exists) @method('PUT') @endif

                <div>
                    <x-input-label for="nom" value="Nom" />
                    <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom', $categorie->nom)" required />
                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="ordre" value="Ordre d'affichage" />
                    <x-text-input id="ordre" name="ordre" type="number" min="0" class="mt-1 block w-full" :value="old('ordre', $categorie->ordre ?? 0)" />
                    <x-input-error :messages="$errors->get('ordre')" class="mt-2" />
                </div>

                <div class="flex gap-3">
                    <x-primary-button>Enregistrer</x-primary-button>
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-gray-600">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
