@if(! $printers->isEmpty())
<div class="bg-white rounded-md shadow dark:bg-gray-800">
    <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">Currently Printing</h3>
    </div>
    <div class="flow-root overflow-y-scroll max-h-64">
        <table wire:poll.750ms class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
            <thead>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Printer</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Materials</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">File Progress</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Time Left</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($printers as $printer)
                <tr x-data>
                    <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-200 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-200">{{ $printer->name }}</p>
                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ $printer->model }}</p>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                        @foreach($printer->tools as $tool)
                        <div class="space-x-1">
                            @if($tool->material_id !== null)
                            <x-icon.filament :x-tooltip.raw="$tool->name .' - ' . $tool->material->name" class="w-12 h-12 text-gray-900 dark:text-gray-200" :fill="$tool->material->color_hex" />
                            @endif
                        </div>
                        @endforeach
                    </td>
                    <td class="max-w-xs px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                        @php
                        $job = $printer->currentlyPrinting();
                        @endphp
                        @if ($job)
                        <p class="truncate">{{ $printer->currentJob->isNotEmpty() ? $printer->currentJob->first()->name : $job['job']['file']['name'] }}</p>
                        <x-ui.progress-bar :progress="round($job['progress']['completion'], 2)" />
                        @else
                        <span>No job found</span>
                        @endif
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                        @if ($job)
                        <span>{{ secondsToTime($job['progress']['printTimeLeft']) }}</span>
                        @endif
                    </td>
                    <td x-data class="relative py-4 pl-3 pr-4 text-sm font-medium text-right whitespace-nowrap">
                        @if ($printer->status === 'printing')
                        <x-ui.icon-button color="warning" wire:click="pause({{ $printer->id }})" label="Pause Print">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zM12.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z" />
                            </svg>
                        </x-ui.icon-button>
                        @else
                        <x-ui.icon-button color="success" wire:click="resume({{ $printer->id }})" label="Resume Print">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                            </svg>
                        </x-ui.icon-button>
                        @endif
                        <x-ui.icon-button color="danger" wire:click="stop({{ $printer->id }})" label="Stop Print">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M5.25 3A2.25 2.25 0 003 5.25v9.5A2.25 2.25 0 005.25 17h9.5A2.25 2.25 0 0017 14.75v-9.5A2.25 2.25 0 0014.75 3h-9.5z" />
                            </svg>
                        </x-ui.icon-button>
                        <x-ui.icon-button @click="Livewire.emit('pip', {{ $printer->id }})" color="primary" label="View Webcam">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M3.25 4A2.25 2.25 0 001 6.25v7.5A2.25 2.25 0 003.25 16h7.5A2.25 2.25 0 0013 13.75v-7.5A2.25 2.25 0 0010.75 4h-7.5zM19 4.75a.75.75 0 00-1.28-.53l-3 3a.75.75 0 00-.22.53v4.5c0 .199.079.39.22.53l3 3a.75.75 0 001.28-.53V4.75z" />
                            </svg>
                        </x-ui.icon-button>
                        @if ($printer->currentJob->isEmpty())
                        <x-ui.icon-button wire:click="save({{ $printer->id }})" label="Save Current Print">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                                <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                            </svg>
                        </x-ui.icon-button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
