@props([
    'leading' => false,
    'trailing' => false,
])

@php
    $label = 'inline-flex items-center px-3 text-gray-500 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 bg-gray-50 dark:text-gray-400';
    $classes = 'border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-300 dark:focus:border-blue-700 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50'
@endphp

@if($leading && $trailing)
<div class="flex mt-1 rounded-md shadow-sm">
    <span class="{{ $label }} border-r-0 rounded-l-md">
        {{ $leading }}
    </span>
    <input {{ $attributes->merge(['type' => 'text', 'class' => $classes]) }} >
    <span class="{{ $label }} border-l-0 rounded-r-md">
        {{ $trailing }}
    </span>
</div>
@elseif($leading)
<div class="flex mt-1 rounded-md shadow-sm">
    <span class="{{ $label }} border-r-0 rounded-l-md">
        {{ $leading }}
    </span>
    <input {{ $attributes->merge(['type' => 'text', 'class' => $classes . ' rounded-r-md']) }} >
</div>
@elseif($trailing)
<div class="flex mt-1 rounded-md shadow-sm">
    <input {{ $attributes->merge(['type' => 'text', 'class' => $classes . ' rounded-l-md']) }} >
    <span class="{{ $label }} border-l-0 rounded-r-md">
        {{ $trailing }}
    </span>
</div>
@else
<input {{ $attributes->merge(['type' => 'text', 'class' => $classes . 'shadow-sm']) }} >
@endif
