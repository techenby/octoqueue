@foreach($files as $file)
    @if($file['type'] === 'folder' && isset($file['children']))
    <div x-data="{open : false}">
        <button type="button" @click="open = !open" class="flex space-x-2 text-gray-900 dark:text-gray-200">
            <x-heroicon-o-folder class="w-6 h-6 text-gray-700 dark:text-gray-400" />
            <span>{{ $file['name'] }}</span>
        </button>
        <div x-show="open" class="mt-2 ml-4 space-y-2">
            @include('livewire.print-jobs.files.loop', ['files' => $file['children']])
        </div>
    </div>
    @else
    <button type="button" wire:click="selectFile([{{ $printer->id }}, '{{ $file['path'] }}'])" class="flex space-x-2 text-gray-900 dark:text-gray-200">
        <x-dynamic-component class="w-6 h-6 text-gray-700 dark:text-gray-400" :component="$file['type'] === 'folder' ? 'heroicon-o-folder' : 'heroicon-o-code'" />
        <span>{{ $file['name'] }}</span>
    </button>
    @endif
@endforeach
