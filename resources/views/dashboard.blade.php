<x-app-layout>
    @if ($completed !== [])
        <x-jet-welcome :completed="$completed" :team="$team" />
    @else
        <x-ui.stats title="Stats">
            <x-ui.stats.item label="# Queued Prints" :value="$metrics['queued']" />
            <x-ui.stats.item label="Completed Prints" :value="$metrics['completed']" />
            <x-ui.stats.item label="Filament Used" :value="$metrics['filament']" />
        </x-ui.stats>
    @endif

    <div class="grid grid-cols-1 gap-6 px-6 mt-8 md:grid-cols-2 md:px-0">
        @foreach ($statuses as $status => $printers)
        <div class="space-y-4 {{ count($printers) > 1 ? 'col-span-2' : '' }}">
            <h2 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $status }}</h2>
            <div class="grid grid-cols-1 gap-6 px-6 md:grid-cols-2 md:px-0">
                @foreach ($printers as $printer)
                    @include('dashboard.' . slugify($status), ['printer' => $printer])
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
