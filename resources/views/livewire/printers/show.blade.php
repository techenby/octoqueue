<div>
    <x-layout.header :breadcrumbs="$breadcrumbs">
        <x-slot:title>
            <span>{{ $title }}</span>
            <x-ui.badge :color="$printer->statusColor">{{ $printer->status }}</x-ui.badge>
        </x-slot:title>

        <div class="space-x-2">
            <x-jet-secondary-button href="{{ route('printers.edit', $printer) }}">Edit</x-jet-secondary-button>
            <x-jet-danger-button wire:click="deletePrinter">Delete</x-jet-danger-button>
        </div>
    </x-layout.header>

    <x-ui.container>
        @includeWhen($printer->status === 'offline', 'livewire.printers.partials.offline-alert')
        @includeWhen($printer->status === 'closed', 'livewire.printers.partials.closed-alert')

        <div class="grid grid-cols-5 gap-4">
            <div id="tools" class="col-span-2 overflow-hidden bg-white rounded-md shadow dark:bg-gray-800">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($tools as $id => $tool)
                    <li class="flex items-center justify-between px-6 py-4 space-x-4">
                        <label for="tool.{{ $id }}.material_id" class="inline text-gray-700 dark:text-gray-400">{{ $tool['name'] }}</label>
                        <select name="tool.{{ $id }}.material_id" id="tool.{{ $id }}.material_id"
                            wire:model="tools.{{ $id }}.material_id"
                            class="block w-full text-gray-900 transition duration-75 border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:border-gray-600"
                        >
                            <option value="">Select an option</option>
                            @foreach($materialOptions as $value => $option)
                                <option value="{{ $value }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-ui.container>
</div>
