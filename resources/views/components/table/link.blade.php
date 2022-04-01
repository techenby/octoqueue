@props(['danger' => false])

@php
    $classes = $danger ? 'text-red-600 hover:text-red-900 dark:hover:text-red-400' : 'text-blue-600 hover:text-blue-900 dark:hover:text-blue-400';
@endphp

@if ($attributes->has('href'))
<a {{ $attributes->merge(['class' => $classes]) }} >
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }} >
    {{ $slot }}
</button>
@endif
