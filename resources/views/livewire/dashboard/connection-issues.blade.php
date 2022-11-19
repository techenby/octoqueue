@if(! $printers->isEmpty())
<div class="bg-white rounded-md shadow dark:bg-gray-800">
    <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">Printers with Connection Issues</h3>
    </div>
    <div class="flow-root overflow-y-scroll max-h-64">
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($printers as $printer)
            <li class="p-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-200">{{ $printer->name }}</p>
                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ $printer->model }}</p>
                    </div>
                    <div>
                        <a href="{{ route('printers.show', $printer) }}" class="inline-flex items-center rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-2.5 py-0.5 text-sm font-medium leading-5 text-gray-700 dark:text-gray-400 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-850">View</a>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
