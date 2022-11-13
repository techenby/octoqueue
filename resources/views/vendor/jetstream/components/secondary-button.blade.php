@php
    $class = 'inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-md shadow-sm dark:text-white dark:bg-gray-600 dark:border-transparent hover:bg-gray-50 dark:hover:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25';
@endphp

@if ($attributes->has('href'))
<a {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => $class]) }}>
    {{ $slot }}
</button>
@endif
