@props([
    'leading' => false,
    'trailing' => false,
    'noMargin' => false,
])

@php
    $label = 'inline-flex items-center px-3 text-gray-500 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 bg-gray-50 dark:text-gray-400';
    $classes = 'block w-full border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300';
    $margin = $noMargin ? '' : 'mt-1';
@endphp

@if ($leading && $trailing)
<div class="flex {{ $margin }} rounded-md shadow-sm">
    <span class="{{ $label }} border-r-0 rounded-l-md">
        {{ $leading }}
    </span>
    <input {{ $attributes->merge(['type' => 'text', 'class' => $classes]) }} >
    <span class="{{ $label }} border-l-0 rounded-r-md">
        {{ $trailing }}
    </span>
</div>
@elseif ($leading)
<div class="flex {{ $margin }} rounded-md shadow-sm">
    <span class="{{ $label }} border-r-0 rounded-l-md">
        {{ $leading }}
    </span>
    <input {{ $attributes->merge(['type' => 'text', 'class' => $classes . ' rounded-r-md']) }} >
</div>
@elseif ($trailing)
<div class="flex {{ $margin }} rounded-md shadow-sm">
    <input {{ $attributes->merge(['type' => 'text', 'class' => $classes . ' rounded-l-md']) }} >
    <span class="{{ $label }} border-l-0 rounded-r-md">
        {{ $trailing }}
    </span>
</div>
@else
<input {{ $attributes->merge(['type' => 'text', 'class' => $classes . 'shadow-sm rounded-md']) }} >
@endif
