<div x-data="{tab: @entangle('tab')}">
    <div>
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a tab</label>
            <x-form.select wire:model="tab" id="tabs" :options="$options"/>
        </div>
        <div class="hidden sm:block">
            <nav class="relative z-0 flex divide-x divide-gray-200 shadow dark:border-b dark:border-gray-700 dark:divide-gray-700" aria-label="Tabs">
                @foreach ($options as $key => $option)
                <button type="button" @click="tab = '{{ $key }}'"
                    class="relative flex-1 min-w-0 px-4 py-4 overflow-hidden text-sm font-medium text-center bg-white dark:bg-gray-800 group hover:bg-gray-50 dark:hover:bg-gray-850 focus:z-10"
                    :class="tab === '{{ $key }}' ? 'text-gray-900 dark:text-gray-200' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                >
                    <span>{{ $option }}</span>
                    <span aria-hidden="true"
                        class="absolute inset-x-0 bottom-0 h-0.5"
                        :class="tab === '{{ $key }}' ? 'bg-blue-500' : 'bg-transparent'"
                    >
                    </span>
                </button>
                @endforeach
            </nav>
        </div>
    </div>
    @foreach ($options as $key => $option)
        <div x-show="tab === '{{ $key }}'">
            @include('livewire.bit.' . $key)
        </div>
    @endforeach
</div>
