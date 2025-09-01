@props(['label', 'active' => false])

@php
    $map = [
        'Terminé' => 'bg-green-100 text-green-700',
        'Signature' => 'bg-purple-100 text-purple-700',
        'Envoi courrier' => 'bg-orange-100 text-orange-700',
        'Relance' => 'bg-blue-100 text-blue-700',
        'Vérif BDG' => 'bg-yellow-100 text-yellow-700',
    ];
    $color = $map[$label] ?? 'bg-gray-200 text-gray-600';
@endphp

<span class="px-3 py-1 rounded text-xs font-medium {{ $color }}">
    {{ $label }}
</span>