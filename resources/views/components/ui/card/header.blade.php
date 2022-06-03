@props(['title' => 'Define a title', 'left' => false, 'right' => false])

<div class="flex items-center justify-between px-4 py-5 space-x-4 bg-white border-b border-gray-200 rounded-t-lg dark:bg-gray-800 dark:border-gray-700 sm:px-6">
    @if ($left)
        {{ $left }}
    @else
    <h2 class="text-xl font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $title }}</h2>
    @endif

    <div>
        @if ($right)
            {{ $right}}
        @else
        {{ $slot }}
        @endif
    </div>
</div>
