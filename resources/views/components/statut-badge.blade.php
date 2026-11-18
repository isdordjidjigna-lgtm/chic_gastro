@props(['statut'])

@php
    $styles = [
        'en_attente' => 'bg-gray-100 text-gray-600',
        'confirmee' => 'bg-blue-100 text-blue-700',
        'en_preparation' => 'bg-orange-100 text-orange-700',
        'prete' => 'bg-green-100 text-green-700',
        'servie' => 'bg-purple-100 text-purple-700',
        'terminee' => 'bg-gray-800 text-white',
        'annulee' => 'bg-red-100 text-red-700',
    ];
    $label = \App\Models\Commande::STATUTS[$statut] ?? $statut;
@endphp

<span {{ $attributes->merge(['class' => 'inline-block px-2.5 py-1 rounded-full text-xs font-semibold ' . ($styles[$statut] ?? 'bg-gray-100 text-gray-600')]) }}>
    {{ $label }}
</span>
