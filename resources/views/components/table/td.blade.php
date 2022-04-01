@props([
    'muted' => false,
    'condensed' => false,
])

@php
    $padding = $condensed ? 'px-1 py-2' : 'px-3 py-4';
    $color = $muted ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-gray-200';
    $classes = "whitespace-nowrap {$padding} text-sm {$color} first:pl-4 first:sm:pl-6 last:text-right last:pr-4 last:sm:pr-6";
@endphp

<td {{ $attributes->merge(['class' => $classes]) }} >
    {{ $slot }}
</td>
