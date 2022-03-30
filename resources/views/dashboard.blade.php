<x-app-layout>
    @if ($showWelcome)
        <x-jet-welcome :completed="$completed" :team="$team" />
    @endif

    <div class="grid grid-cols-2 gap-6">
        @foreach ($printers as $printer)
        <div wire:key="$printer->id">
            <livewire:bit.printer :printer="$printer" />
        </div>
        @endforeach
    </div>
</x-app-layout>
