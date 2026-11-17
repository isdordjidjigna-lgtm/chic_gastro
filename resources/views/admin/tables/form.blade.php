<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $table->exists ? 'Modifier la table' : 'Nouvelle table' }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ $table->exists ? route('admin.tables.update', $table) : route('admin.tables.store') }}" class="space-y-4">
                    @csrf
                    @if($table->exists) @method('PUT') @endif

                    <div>
                        <x-input-label for="numero" value="Numéro de table" />
                        <x-text-input id="numero" name="numero" type="text" class="mt-1 block w-full" :value="old('numero', $table->numero)" placeholder="Ex : T13" required />
                        <x-input-error :messages="$errors->get('numero')" class="mt-2" />
                    </div>

                    @if($table->exists)
                        <div class="bg-gray-50 rounded-lg p-3 text-sm">
                            <p class="text-gray-600 mb-1">Lien du menu (QR Code) :</p>
                            <code class="text-xs break-all">{{ $table->urlMenu() }}</code>
                        </div>
                    @endif

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="actif" value="1" @checked(old('actif', $table->exists ? $table->actif : true)) class="rounded border-gray-300">
                        <span class="text-sm text-gray-700">Table active (le QR Code fonctionne)</span>
                    </label>
                    <p class="text-xs text-gray-500 -mt-2">Décocher pour afficher « indisponible » lors du scan.</p>

                    <div class="flex gap-3">
                        <x-primary-button>Enregistrer</x-primary-button>
                        <a href="{{ route('admin.tables.index') }}" class="px-4 py-2 text-gray-600">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
