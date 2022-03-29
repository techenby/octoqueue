@if ($folder['children'])
<div>
    <div class="flex items-center px-2 py-1 space-x-2">
        <x-heroicon-o-folder class="w-4 h-4 text-gray-700 dark:text-gray-400" />
        <span class="text-gray-900 dark:text-gray-200">{{ $folder['name'] }}</span>
    </div>
    @include('livewire.bit.printer-files.loop', ['files' => $folder['children'], 'level' => true])
</div>
@else
<div class="flex items-center px-2 py-1 space-x-2">
    <x-heroicon-o-folder class="w-4 h-4 text-gray-700 dark:text-gray-400" />
    <span class="text-gray-900 dark:text-gray-200">{{ $folder['name'] }}</span>
</div>
@endif
