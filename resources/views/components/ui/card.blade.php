@props(['title'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-md shadow dark:bg-gray-800']) }}>
    @isset($title)
    <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $title }}</h3>
    </div>
    @endif

    {{ $slot }}
</div>
