@extends('layouts.admin')

@section('title', $table->exists ? 'Modifier la table' : 'Nouvelle table')
@section('eyebrow', 'Tables & QR Codes')

@section('actions')
    <a href="{{ route('admin.tables.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">← Retour</a>
@endsection

@section('content')
    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-4">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="max-w-xl">
        <div class="bg-white rounded-2xl shadow-sm p-6">
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
@endsection
