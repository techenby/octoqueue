<x-filament::widget>
    <div class="space-y-2 bg-white overflow-hidden rounded-xl shadow dark:border-gray-600 dark:bg-gray-800">
        <div class="px-4 py-3">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <x-filament::card.heading>
                    Temperatures
                </x-filament::card.heading>
            </div>
        </div>
        <x-filament::hr />
        <div class="overflow-x-auto w-full">
            <x-ui.table>
                <x-ui.thead>
                    <tr>
                        <x-ui.th>Tool</x-ui.th>
                        <x-ui.th>Actual</x-ui.th>
                        <x-ui.th>Target</x-ui.th>
                        <x-ui.th>Offset</x-ui.th>
                    </tr>
                </x-ui.thead>
                <x-ui.tbody>
                    @forelse ($temperatures as $name => $temp)
                    <tr>
                        <x-ui.td>{{ $name }}</x-ui.td>
                        <x-ui.td>{{ $temp['actual'] }}℃</x-ui.td>
                        <x-ui.td>
                            <form wire:submit.prevent="setTarget('{{ $name }}')">
                                <x-jet-label for="{{ $name }}-target-temp" class="sr-only">Target Temp for {{ $name }}</x-jet-label>
                                <div class="flex">
                                    <input type="number" wire:model="temperatures.{{ $name }}.target" name="{{ $name }}-target-temp" id="{{ $name }}-target-temp" placeholder="0" class="block w-24 border-gray-300 rounded-none dark:border-gray-700 dark:bg-gray-800 rounded-l-md focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <button type="submit" class="relative inline-flex items-center px-4 py-2 -ml-px space-x-2 text-sm font-medium text-gray-700 dark:text-gray-400 border border-gray-300 dark:border-gray-700 {{ $temp['target'] > 0 ? '' : 'rounded-r-md' }} bg-gray-50 dark:bg-gray-850 hover:bg-gray-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        Set
                                    </button>
                                    @if ($temp['target'] > 0)
                                    <button type="button" wire:click="clear('{{ $name }}', 'target')" class="relative inline-flex items-center px-4 py-2 -ml-px space-x-2 text-sm font-medium text-gray-700 border border-gray-300 dark:text-gray-400 dark:border-gray-700 rounded-r-md bg-gray-50 dark:bg-gray-850 hover:bg-gray-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400">
                                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </form>
                        </x-ui.td>
                        <x-ui.td>
                            <form wire:submit.prevent="setOffset('{{ $name }}')">
                                <x-jet-label for="{{ $name }}-offset-temp" class="sr-only">Offset Temp for {{ $name }}</x-jet-label>
                                <div class="flex">
                                    <input type="number" wire:model="temperatures.{{ $name }}.offset" name="{{ $name }}-offset-temp" id="{{ $name }}-offset-temp" placeholder="0" class="block w-24 border-gray-300 rounded-none dark:border-gray-700 dark:bg-gray-800 rounded-l-md focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <button type="submit" class="relative inline-flex items-center px-4 py-2 -ml-px space-x-2 text-sm font-medium text-gray-700 dark:text-gray-400 border border-gray-300 dark:border-gray-700 {{ $temp['offset'] > 0 ? '' : 'rounded-r-md' }} bg-gray-50 dark:bg-gray-850 hover:bg-gray-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        Set
                                    </button>
                                    @if ($temp['offset'] > 0)
                                    <button type="button" wire:click="clear('{{ $name }}', 'offset')" class="relative inline-flex items-center px-4 py-2 -ml-px space-x-2 text-sm font-medium text-gray-700 border border-gray-300 dark:text-gray-400 dark:border-gray-700 rounded-r-md bg-gray-50 dark:bg-gray-850 hover:bg-gray-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400">
                                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </form>
                        </x-ui.td>
                    </tr>
                    @empty
                    <tr>
                        <x-ui.td colspan="4">Could not get temps{{ $record->status === 'error' ? ', printer is not operational. Please connect to see temperatures.' : '.' }}</x-ui.td>
                    </tr>
                    @endforelse
                </x-ui.tbody>
            </x-ui.table>
        </div>
    </div>
</x-filament::widget>
