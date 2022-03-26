<div class="px-2 md:px-0">
    <table class="min-w-full overflow-hidden divide-y divide-gray-300 rounded-md dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-850">
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 sm:pl-6">Location</th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 lg:table-cell">Brand</th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 sm:table-cell">Material</th>
                <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Color</th>
                <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Cost</th>
                <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Weight</th>
                <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Length</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
            @forelse($spools as $spool)
            <tr>
                <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-200 max-w-0 sm:w-auto sm:max-w-none sm:pl-6">
                {{ $spool->location }}
                <dl class="font-normal lg:hidden">
                    <dt class="sr-only">Brand</dt>
                    <dd class="mt-1 text-gray-700 truncate dark:text-gray-400">{{ $spool->brand }}</dd>
                    <dt class="sr-only sm:hidden">Material</dt>
                    <dd class="mt-1 text-gray-500 truncate dark:text-gray-200 sm:hidden">{{ $spool->material }}</dd>
                </dl>
                </td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $spool->brand }}</td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $spool->material }}</td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">
                    <div class="w-4 h-4 border border-gray-300 rounded dark:border-gray-700" style="background:{{ $spool->color_hex }}">
                        <span class="sr-only">{{ $spool->color }}</span>
                    </div>
                </td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 sm:table-cell">{{ $spool->cost }}</td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $spool->formattedCurrentWeight }}</td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $spool->formattedCurrentLength }}</td>
                <td class="py-4 pl-3 pr-4 text-sm font-medium text-right sm:pr-6">
                    <a href="{{ route('spools.edit', $spool) }}" class="text-blue-600 hover:text-blue-900">Edit<span class="sr-only"> {{ $spool->name }}</span></a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="flex items-center justify-center w-full px-3 py-4">
                        <a href="/spools/create">
                            <div class="flex items-center text-lg font-semibold text-blue-700 group dark:text-blue-400 dark:hover:text-blue-300 hover:text-blue-500">
                                <div>Create your first Spool</div>

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
