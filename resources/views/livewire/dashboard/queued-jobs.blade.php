<div class="bg-white rounded-md shadow dark:bg-gray-800">
    <div class="flex items-center justify-between px-4 py-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">Print Queue</h3>
        <x-jet-secondary-button href="{{ route('queue') }}">View All</x-jet-secondary-button>
    </div>
    <div class="flow-root overflow-y-scroll max-h-96">
        <table wire:poll.visible class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
            <thead>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Color</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">User</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($queuedJobs as $job)
                <tr x-data>
                    <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-200 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-200">{{ $job->name }}</p>
                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ $job->printType->name }}</p>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                        <div style="background-color: #{{ $job->color_hex }}" class="relative flex w-6 h-6 ml-4 rounded-md">
                        </div>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{ $job->user->name }}
                    </td>
                    <td x-data class="relative py-4 pl-3 pr-4 text-sm font-medium text-right whitespace-nowrap">
                        <x-ui.icon-button label="View Printer Details">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M17 2.75a.75.75 0 00-1.5 0v5.5a.75.75 0 001.5 0v-5.5zM17 15.75a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zM3.75 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM4.5 2.75a.75.75 0 00-1.5 0v5.5a.75.75 0 001.5 0v-5.5zM10 11a.75.75 0 01.75.75v5.5a.75.75 0 01-1.5 0v-5.5A.75.75 0 0110 11zM10.75 2.75a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zM10 6a2 2 0 100 4 2 2 0 000-4zM3.75 10a2 2 0 100 4 2 2 0 000-4zM16.25 10a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                        </x-ui.icon-button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400" colspan="5">
                        Nothing is queued to print.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
