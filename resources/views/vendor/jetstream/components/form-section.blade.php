@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    @isset($title)
    <x-jet-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-jet-section-title>
    @endif

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit.prevent="{{ $submit }}" class="overflow-hidden rounded-md dark:border-gray-700 dark:border">
            <div class="px-4 py-5 bg-white shadow dark:bg-gray-800 sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-4 py-3 text-right shadow bg-gray-50 dark:bg-gray-850 sm:px-6">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
