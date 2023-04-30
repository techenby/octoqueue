@props(['color' => 'secondary', 'label' => 'Define a tooltip'])

@php
    $color = match($color) {
        'primary' => 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'text-white bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
        'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'success' => 'text-white bg-green-600 hover:bg-green-700 focus:ring-green-500',
        'warning' => 'text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
        default => 'text-white bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
    };

    $class = 'inline-flex items-center rounded border border-transparent px-2.5 py-1.5 text-xs font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2'
@endphp
@if ($attributes->has('href'))
<a x-tooltip.raw="{{ $label }}" {{ $attributes->merge(['class' => $class . ' ' . $color]) }}>
    {{ $slot }}
    <span class="sr-only">{{ $label }}</span>
</a>
@else
<button x-tooltip.raw="{{ $label }}" {{ $attributes->merge(['type' => 'button', 'class' => $class . ' ' . $color]) }}>
    {{ $slot }}
    <span class="sr-only">{{ $label }}</span>
</button>
@endif
