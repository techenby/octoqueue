@props([
    'muted' => false,
    'condensed' => false,
])

@php
    $padding = $condensed ? 'px-1 py-2' : 'px-3 py-3.5';
    $color = $muted ? 'text-gray-700 dark:text-gray-400' : 'text-gray-900 dark:text-gray-200';
    $classes = "{$padding} text-left text-sm font-semibold {$color} first:pl-4 first:sm:pl-6 last:pr-4 last:sm:pr-6";
@endphp

<th {{ $attributes->merge(['scope' => 'col', 'class' => $classes])}} >
    {{ $slot }}
</th>
