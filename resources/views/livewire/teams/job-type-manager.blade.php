<div id="job-types">
    <x-jet-section-border />

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                    {{ __('Job Types') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Add a job type to manage the prefered order jobs get printed.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="w-2/3 space-y-1">
                        @foreach ($types as $type)
                            <div class="flex items-center justify-between px-3 py-2 text-gray-600 border border-gray-300 rounded-md dark:border-gray-700 dark:text-gray-400">
                                <div>
                                    <span>{{ $type->priority }}.</span>
                                    @if (isset($editing) && $editing->id === $type->id)
                                    <form class="inline" wire:submit.prevent="save">
                                        <input type="text" class="inline px-1 py-0 text-base border-0 rounded appearance-none dark:text-gray-200 dark:bg-gray-800" autofocus wire:model="editing.name"/>
                                    </form>
                                    @else
                                    <span wire:click="edit({{ $type->id }})">{{ $type->name }}</span>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-1">
                                    <x-table-button wire:click="move({{ $type->id }}, 'up')" :disabled="$loop->first">
                                        <span class="sr-only">Move Up</span><x-heroicon-o-chevron-up class="w-4 h-4"/>
                                    </x-table-button>
                                    <x-table-button wire:click="move({{ $type->id }}, 'down')" :disabled="$loop->last">
                                        <span class="sr-only">Move Down</span><x-heroicon-o-chevron-down class="w-4 h-4"/>
                                    </x-table-button>
                                    <x-table-button wire:click="edit({{ $type->id }})">
                                        <span class="sr-only">Edit</span><x-heroicon-o-pencil class="w-4 h-4"/>
                                    </x-table-button>
                                </div>
                            </div>
                        @endforeach

                        <form wire:submit.prevent="saveNewType">
                            <x-jet-label for="job-type" value="{{ __('Job Type') }}" class="sr-only" />
                            <x-form.input id="job-type" type="text" class="block w-full mt-4" :disabled="$editing !== null" placeholder="New Job Type" wire:model.defer="newType" />
                            <x-jet-input-error for="job-type" class="mt-2" />
                        </form>
                    </div>
                </x-slot>
        </x-jet-action-section>
    </div>
</div>
