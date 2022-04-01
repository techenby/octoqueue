@props([
    'condensed' => false,
    'direction' => null,
    'muted' => false,
    'sortable' => null,
])

@php
    $padding = $condensed ? 'px-1 py-2' : 'px-3 py-3.5';
    $color = $muted ? 'text-gray-700 dark:text-gray-400' : 'text-gray-900 dark:text-gray-200';
    $classes = "{$padding} text-left text-sm font-semibold {$color} first:pl-4 first:sm:pl-6 last:pr-4 last:sm:pr-6";
@endphp

<th {{ $attributes->merge(['scope' => 'col', 'class' => $classes])->except('wire:click') }} >
    @unless ($sortable)
        {{ $slot }}
    @else
        <button {{ $attributes->except(['class', 'style']) }} class="group flex space-x-2 text-left text-sm font-semibold {{ $color }}">
            <span>{{ $slot }}</span>

            <span class="relative flex items-center">
                @if ($direction === 'asc')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                @elseif ($direction === 'desc')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                @else
                    <svg class="w-3 h-3 transition-opacity duration-300 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                @endif
            </span>
        </button>
    @endif
</th>
