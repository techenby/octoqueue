<x-app-layout>
    <x-slot name="header">{{ __('Dashboard') }}</x-slot>

    @if(auth()->user()->currentTeam->showWelcomeWizard)
    <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
        <x-jet-welcome />
    </div>
    @else
        <div class="grid grid-cols-2 gap-8">
            @foreach($printers as $printer)
            <div wire:key="$printer->id">
                <livewire:bit.printer :printer="$printer" />
            </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
