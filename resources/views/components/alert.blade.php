@php
    $colorClasses = [
        'red' => 'bg-red-100 border-t border-b border-red-500 text-red-700',
        'blue' => 'bg-blue-100 border-t border-b border-blue-500 text-blue-700',
    ];
    $colorClass = $colorClasses[$color] ?? 'bg-blue-100 border-t border-b border-blue-500 text-blue-700';
@endphp

<div class="{{ $colorClass }} px-4 py-3" role="alert">
    <p class="font-bold">{{ $slot }}</p>
</div>
