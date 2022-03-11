<div class="px-2 md:px-0">
    <table class="min-w-full overflow-hidden divide-y divide-gray-300 rounded-md dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-850">
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 sm:pl-6">Location</th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 lg:table-cell">Brand</th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 sm:table-cell">Material</th>
                <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Color</th>
                <th scope="col" class="hidden lg:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Cost</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
            @foreach($spools as $spool)
            <tr>
                <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-200 max-w-0 sm:w-auto sm:max-w-none sm:pl-6">
                {{ $spool->name }}
                <dl class="font-normal lg:hidden">
                    <dt class="sr-only">Model</dt>
                    <dd class="mt-1 text-gray-700 truncate dark:text-gray-400">{{ $spool->model }}</dd>
                    <dt class="sr-only sm:hidden">Spool</dt>
                    <dd class="mt-1 text-gray-500 truncate dark:text-gray-200 sm:hidden">{{ $spool->spool }}</dd>
                </dl>
                </td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $spool->model }}</td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 sm:table-cell">{{ $spool->spool }}</td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">{{ $spool->status }}</td>
                <td class="py-4 pl-3 pr-4 text-sm font-medium text-right sm:pr-6">
                    <a href="{{ route('spools.edit', $spool) }}" class="text-blue-600 hover:text-blue-900">Edit<span class="sr-only"> {{ $printer->name }}</span></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
