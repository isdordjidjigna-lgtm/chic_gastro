<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catégories</h2>
            <a href="{{ route('admin.categories.create') }}" class="bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">+ Nouvelle catégorie</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-2">Nom</th>
                            <th class="text-left px-4 py-2">Ordre</th>
                            <th class="text-left px-4 py-2">Plats</th>
                            <th class="text-right px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $categorie)
                            <tr class="hover:bg-amber-50/50 transition">
                                <td class="px-4 py-3 font-semibold">{{ $categorie->nom }}</td>
                                <td class="px-4 py-3">{{ $categorie->ordre }}</td>
                                <td class="px-4 py-3">{{ $categorie->plats_count }}</td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <a href="{{ route('admin.categories.edit', $categorie) }}" class="text-blue-600 font-semibold">Modifier</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}" class="inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 font-semibold">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucune catégorie.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
