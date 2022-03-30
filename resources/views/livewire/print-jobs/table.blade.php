<div class="flex-col space-y-4">
    <div class="md:flex md:justify-between">
        <div class="flex flex-col space-y-4 md:items-end md:space-x-4 md:flex-row">
            <x-form.input wire:model="search" placeholder="Search..." />
        </div>
        <div class="flex items-end mt-4 space-x-2 md:mt-0">
            <x-bit.per-page />
        </div>
    </div>
    <div class="px-2 md:px-0">
        <table class="min-w-full overflow-hidden divide-y divide-gray-300 rounded-md dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-850">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 sm:pl-6">Name</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 lg:table-cell">Type</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 sm:table-cell">Printer</th>
                    <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Creator</th>
                    <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Color</th>
                    <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Completed At</th>
                    <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Filament Used</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Edit</span>
                        <span class="sr-only">Delete</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                @forelse ($rows as $job)
                <tr>
                    <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-200 max-w-0 sm:w-auto sm:max-w-none sm:pl-6">
                        {{ $job->name }}
                    </td>
                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $job->type->name }}</td>
                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 sm:table-cell">{{ $job->printer->name ?? 'Any' }}</td>
                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $job->user->name }}</td>
                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">
                        <div class="w-4 h-4 border border-gray-300 rounded dark:border-gray-700" style="background:{{ $job->color_hex }}">
                        </div>
                    </td>
                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $job->completed_at }}</td>
                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $job->completed ? $job->filament_used : '' }}</td>
                    <td class="py-4 pl-3 pr-4 space-x-2 text-sm font-medium text-right sm:pr-6">
                        <a href="{{ route('jobs.edit', $job) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">Edit<span class="sr-only"> {{ $job->name }}</span></a>
                        <button type="button" wire:click="delete({{ $job->id }})" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Delete<span class="sr-only"> {{ $job->name }}</span></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="flex items-center justify-center w-full px-3 py-4">
                            <a href="/jobs/create">
                                <div class="flex items-center text-lg font-semibold text-blue-700 group dark:text-blue-400 dark:hover:text-blue-300 hover:text-blue-500">
                                    <div>Create a job</div>

                                    <div class="ml-1">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $rows->links() }}
    </div>
</div>
