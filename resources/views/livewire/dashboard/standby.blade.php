@if(! $printers->isEmpty())
<div class="bg-white rounded-md shadow dark:bg-gray-800">
    <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">Printers on Standby</h3>
    </div>
    <div class="flow-root overflow-y-scroll max-h-64">
        <ul role="list" x-data class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($printers as $printer)
            <li class="p-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-200">{{ $printer->name }}</p>
                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ $printer->model }}</p>
                    </div>
                    <div>
                        <x-ui.icon-button wire:click="fetchStatus({{ $printer->id }})" label="Refresh Status">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                            </svg>
                        </x-ui.icon-button>
                        <x-ui.icon-button href="{{ route('printers.show', $printer) }}" label="View Printer Details">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M17 2.75a.75.75 0 00-1.5 0v5.5a.75.75 0 001.5 0v-5.5zM17 15.75a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zM3.75 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM4.5 2.75a.75.75 0 00-1.5 0v5.5a.75.75 0 001.5 0v-5.5zM10 11a.75.75 0 01.75.75v5.5a.75.75 0 01-1.5 0v-5.5A.75.75 0 0110 11zM10.75 2.75a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zM10 6a2 2 0 100 4 2 2 0 000-4zM3.75 10a2 2 0 100 4 2 2 0 000-4zM16.25 10a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                        </x-ui.icon-button>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
