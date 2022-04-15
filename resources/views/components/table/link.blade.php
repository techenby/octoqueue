@props([
    'color' => 'blue',
    'danger' => false
])

@php
    $color = $danger ? 'red' : $color;

    switch ($color) {
        case 'blue':
            $classes = "text-blue-600 hover:text-blue-900 dark:hover:text-blue-400";
            break;
        case 'red':
            $classes = "text-red-600 hover:text-red-900 dark:hover:text-red-400";
            break;
        case 'green':
            $classes = "text-green-600 hover:text-green-900 dark:hover:text-green-400";
            break;
    }

    $classes .= ' disabled:opacity-75 disabled:cursor-not-allowed';
@endphp

@if ($attributes->has('href') && $attribute->hasNot('disabled'))
<a {{ $attributes->merge(['class' => $classes]) }} >
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }} >
    {{ $slot }}
</button>
@endif
