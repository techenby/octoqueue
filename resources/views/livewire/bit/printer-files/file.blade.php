<button type="button" wire:click="select('{{ $file['path'] }}')" class="flex items-center px-2 py-1 space-x-2 rounded dark:hover:bg-blue-800 hover:bg-blue-200">
    <x-heroicon-o-code class="w-4 h-4 text-gray-700 dark:text-gray-400" />
    <span class="text-gray-900 dark:text-gray-200">{{ $file['name'] }}</span>
</button>
