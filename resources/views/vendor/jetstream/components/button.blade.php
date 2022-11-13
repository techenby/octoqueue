@php
    $class = 'inline-flex items-center px-4 py-2 text-sm font-medium text-white transition border border-transparent rounded-md shadow-sm bg-cyan-600 dark:bg-cyan-500 hover:bg-cyan-700 dark:hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25';
@endphp

@if ($attributes->has('href'))
<a {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'submit', 'class' => $class]) }}>
    {{ $slot }}
</button>
@endif
