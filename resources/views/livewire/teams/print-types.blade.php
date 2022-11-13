<div id="job-types">
    <x-jet-section-border />

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Print Types') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Add a print type to manage the prefered order prints queued.') }}
            </x-slot>

            <x-slot name="content">
                <div class="w-2/3 space-y-4">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($types as $type)
                        <div class="flex items-center justify-between px-3 py-2 text-gray-600 dark:text-gray-400">
                            <div>
                                <span>{{ $type->priority }}.</span>
                                @if (isset($editing) && $editing->id === $type->id)
                                <form class="inline" wire:submit.prevent="save">
                                    <x-jet-label for="job-type" class="sr-only" value="{{ __('Print Type') }}" />
                                    <x-jet-input type="text" autofocus wire:model="editing.name" />
                                </form>
                                @else
                                <span wire:click="edit({{ $type->id }})">{{ $type->name }}</span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-1">
                                <button wire:click="move({{ $type->id }}, 'up')" :disabled="$loop->first">
                                    <span class="sr-only">Move Up</span>
                                    <x-heroicon-o-chevron-up class="w-4 h-4" />
                                </button>
                                <button wire:click="move({{ $type->id }}, 'down')" :disabled="$loop->last">
                                    <span class="sr-only">Move Down</span>
                                    <x-heroicon-o-chevron-down class="w-4 h-4" />
                                </button>
                                <button wire:click="edit({{ $type->id }})">
                                    <span class="sr-only">Edit</span>
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <form wire:submit.prevent="saveNewType">
                        <x-jet-label for="job-type" value="{{ __('Print Type') }}" />
                        <div class="flex mt-1 rounded-md shadow-sm">
                            <x-jet-input id="job-type" type="text" class="block w-full rounded-r-none focus:border-cyan-500" :disabled="$editing !== null" placeholder="New Print Type" wire:model.defer="newType" />
                            <x-jet-button class="-ml-px rounded-l-none">Add</x-jet-button>
                        </div>
                        <x-jet-input-error for="job-type" class="mt-2" />
                    </form>
                </div>
            </x-slot>
        </x-jet-action-section>
    </div>
</div>
