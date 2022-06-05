@props(['title' => false])

<div>
    @if ($title)
    <h2 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $title }}</h2>
    @endif
    <dl class="grid grid-cols-1 gap-4 mt-5 sm:grid-cols-3">
        {{ $slot }}
    </dl>
</div>
