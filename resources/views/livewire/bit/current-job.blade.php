<x-ui.card.body class="relative">
    @if ($printer->currentJob === null)
    <button wire:click="save" class="absolute top-0 rounded-t-none right-1 btn btn-sm btn-blue">
        <x-heroicon-o-save class="w-5 h-5" />
    </button>
    @endif
    <dl>
        <div class="flex items-center space-x-2">
            <dt class="font-medium text-gray-500 dark:text-gray-400">Currently Printing</dt>
            <dd class="text-gray-900 dark:text-gray-200">{{ $label }}</dd>
        </div>
        <div class="flex items-center space-x-2">
            <dt class="font-medium text-gray-500 dark:text-gray-400">Elapsed Time</dt>
            <dd class="text-gray-900 dark:text-gray-200">
                <x-date :date="$elapsed" diff />
            </dd>
        </div>
        <div class="flex items-center space-x-2">
            <dt class="font-medium text-gray-500 dark:text-gray-400">Progress</dt>
            <dd class="text-gray-900 dark:text-gray-200">{{ $progress }}</dd>
        </div>
    </dl>
</x-ui.card.body>
