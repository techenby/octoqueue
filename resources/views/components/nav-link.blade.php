@props([
    'active' => false,
    'responsive' => false,
    'icon' => null
])

@php
if(isset($responsive)) {
    $classes = ($active == false)
            ? 'text-gray-600 dark:text-gray-400 dark:hover:bg-gray-850 dark:hover:text-gray-200 hover:bg-gray-50 hover:text-gray-900'
            : 'text-gray-900 bg-gray-100 dark:text-gray-200 dark:bg-gray-850';
} else {
    $classes = ($active == false)
            ? 'flex items-center px-2 py-2 text-sm font-medium text-gray-600 rounded-md dark:text-gray-400 dark:hover:bg-gray-850 dark:hover:text-gray-200 hover:bg-gray-50 hover:text-gray-900 group'
            : 'flex items-center px-2 py-2 text-sm font-medium text-gray-900 bg-gray-100 rounded-md dark:text-gray-200 dark:bg-gray-850 group';
}
@endphp

<a {{ $attributes->merge(['class' => "flex items-center px-2 py-2 text-sm font-medium rounded-md group $classes"]) }}>
    @if($icon)
    <x-dynamic-component :component="$icon" class="flex-shrink-0 w-6 h-6 mr-3 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-400 dark:hover:text-gray-400" />
    @endif
    {{ $slot }}
</a>
