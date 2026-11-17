<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tables & QR Codes</h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.tables.imprimer') }}" target="_blank" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">Imprimer les QR</a>
                <a href="{{ route('admin.tables.create') }}" class="bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">+ Nouvelle table</a>
            </div>
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

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($tables as $table)
                    <div class="bg-white rounded-xl shadow-sm p-4 {{ $table->actif ? '' : 'opacity-60' }}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-bold text-lg">Table {{ $table->numero }}</p>
                                <p class="text-xs text-gray-500">{{ $table->commandes_count }} commande(s) en cours</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $table->actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $table->actif ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <img src="{{ route('admin.tables.qr.png', $table) }}" alt="QR Table {{ $table->numero }}" class="w-full border border-gray-200 rounded-lg mb-3">
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <a href="{{ route('admin.tables.qr.png', $table) }}" class="text-blue-600">PNG</a>
                            <a href="{{ route('admin.tables.qr.svg', $table) }}" class="text-blue-600">SVG</a>
                            <a href="{{ $table->urlMenu() }}" target="_blank" class="text-gray-500">Menu ↗</a>
                            <a href="{{ route('admin.tables.edit', $table) }}" class="text-amber-700">Modifier</a>
                            <form method="POST" action="{{ route('admin.tables.destroy', $table) }}" class="inline" onsubmit="return confirm('Supprimer cette table ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full bg-white rounded-xl p-6 shadow-sm">Aucune table.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
