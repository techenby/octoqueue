<div class="p-4 rounded-md bg-blue-50 dark:bg-blue-900">
    <div class="flex">
        <div class="flex-shrink-0">
            <!-- Heroicon name: mini/information-circle -->
            <svg class="w-5 h-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M19 10.5a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0zM8.25 9.75A.75.75 0 019 9h.253a1.75 1.75 0 011.709 2.13l-.46 2.066a.25.25 0 00.245.304H11a.75.75 0 010 1.5h-.253a1.75 1.75 0 01-1.709-2.13l.46-2.066a.25.25 0 00-.245-.304H9a.75.75 0 01-.75-.75zM10 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="flex-1 ml-3 md:flex md:justify-between">
            <p class="text-sm text-blue-700 dark:text-blue-400">We could not connect to {{ $printer->name }}. The URL appears to be inaccessable.</p>
            <p class="mt-3 text-sm md:mt-0 md:ml-6">
                <button wire:click="fetchStatus" class="font-medium text-blue-700 dark:text-blue-400 dark:hover:text-blue-200 whitespace-nowrap hover:text-blue-600">
                    Retry
                </button>
                <a href="{{ route('printers.edit', $printer) }}" class="font-medium text-blue-700 dark:text-blue-400 dark:hover:text-blue-200 whitespace-nowrap hover:text-blue-600">
                    Edit
                </a>
            </p>
        </div>
    </div>
</div>
