<div>
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
                        @foreach($types as $type)
                            <div class="flex items-center justify-between px-3 py-2 text-gray-600 border border-gray-300 rounded-md cursor-move dark:border-gray-700 dark:text-gray-400">
                                <span>{{ $type->priority }}. {{ $type->name }}</span>

                                <div class="flex items-center space-x-1">
                                    <button><span class="sr-only">Move Up</span><x-heroicon-o-chevron-up class="w-4 h-4"/></button>
                                    <button><span class="sr-only">Move Down</span><x-heroicon-o-chevron-down class="w-4 h-4"/></button>
                                    <button><span class="sr-only">Edit</span><x-heroicon-o-pencil class="w-4 h-4"/></button>
                                </div>
                            </div>
                        @endforeach

                        <form wire:submit.prevent="saveNewType">
                            <x-jet-label for="job-type" value="{{ __('Job Type') }}" class="sr-only" />
                            <x-jet-input id="job-type" type="text" class="block w-full mt-4" placeholder="New Job Type" wire:model.defer="newType" />
                            <x-jet-input-error for="job-type" class="mt-2" />
                        </form>
                    </div>
                </x-slot>
        </x-jet-action-section>
    </div>
</div>
