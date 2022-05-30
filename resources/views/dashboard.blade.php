<x-app-layout>
    @isset ($completed)
        <x-jet-welcome :completed="$completed" :team="$team" />
    @endif

    <div class="grid grid-cols-1 gap-6 px-6 md:grid-cols-2 md:px-0">
        @foreach ($printers as $printer)
        <div>
            <livewire:bit.printer :printer="$printer" />
        </div>
        @endforeach
    </div>
</x-app-layout>
