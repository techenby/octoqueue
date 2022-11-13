<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __($title) }}
        </h2>
        @isset($link)
        <a href="{{ route($link['route']) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25">{{ $link['label'] }}</a>
        @endif
    </div>
</x-slot>

<div>
    {{ $this->table }}
</div>
