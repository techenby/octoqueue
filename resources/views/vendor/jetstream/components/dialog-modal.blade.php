@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg text-gray-900 dark:text-gray-200">
            {{ $title }}
        </div>

        <div class="mt-4">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row items-center justify-end px-6 py-4 text-right bg-gray-100 dark:bg-gray-850">
        {{ $footer }}
    </div>
</x-jet-modal>
