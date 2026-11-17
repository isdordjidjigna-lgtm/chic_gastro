<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $categorie->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
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
    </div>
</x-app-layout>
