@extends('layouts.admin')

@section('title', 'Catégories')
@section('eyebrow', 'Menu • ' . $categories->total() . ' catégorie(s)')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold shadow-sm transition">+ Nouvelle catégorie</a>
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

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Nom</th>
                    <th class="text-left px-5 py-3">Ordre</th>
                    <th class="text-left px-5 py-3">Plats</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $categorie)
                    <tr class="hover:bg-amber-50/50 transition">
                        <td class="px-5 py-4 font-semibold">
                            <a href="{{ route('admin.plats.index', ['categorie' => $categorie->id]) }}" class="text-amber-700 hover:underline">
                                {{ $categorie->nom }}
                            </a>
                        </td>
                        <td class="px-5 py-4">{{ $categorie->ordre }}</td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.plats.index', ['categorie' => $categorie->id]) }}" class="px-2 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 hover:bg-amber-200">
                                {{ $categorie->plats_count }} plat(s)
                            </a>
                        </td>
                        <td class="px-5 py-4 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $categorie) }}" class="text-amber-700 font-semibold">Modifier</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}" class="inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 font-semibold">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500">Aucune catégorie.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
