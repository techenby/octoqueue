<div class="p-4 rounded-md bg-blue-50 dark:bg-blue-900">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="font-medium text-blue-800 dark:text-blue-400">We could not connect to {{ $printer->name }}. The URL appears to be inaccessable.</p>
            <div class="mt-4">
                <div class="-mx-2 -my-1.5 flex space-x-4">
                    <button type="button" wire:click="fetchStatus" class="flex items-center space-x-2 rounded-md bg-blue-50 dark:bg-blue-900 px-2 py-1.5 text-sm font-medium text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span>Retry</span>
                    </button>
                    <a href="{{ route('printers.edit', $printer) }}" class="flex items-center space-x-2 rounded-md bg-blue-50 dark:bg-blue-900 px-2 py-1.5 text-sm font-medium text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        <span>Edit</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
