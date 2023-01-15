<div class="flex items-center justify-between space-x-4">
    <div class="flex items-center space-x-4">
        <div>
            @foreach($record->tools as $tool)
            <x-icon.filament :x-tooltip.raw="$tool->name .' - ' . $tool->material->name" class="w-12 h-12 text-gray-900 dark:text-gray-200" :fill="$tool->material->color_hex" />
            @endforeach
        </div>
        <div>
            <x-filament::header.heading>
                {{ $this->getHeading() }}
            </x-filament::header.heading>

            <div class="mt-1 max-w-2xl tracking-tight text-gray-500 flex items-center space-x-2">
                <div>{{ $record->model }}</div>
                <span>·</span>
                <div>{{ $record->url }}</div>
                <span>·</span>
                <x-ui.badge :color="$record->statusColor">{{ $record->status }}</x-ui.badge>
            </div>
        </div>
    </div>
    <x-filament::pages.actions :actions="$this->getCachedActions()" class="shrink-0" />
</div>