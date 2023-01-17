<x-filament::page class="filament-dashboard-page">
    <x-filament::widgets :widgets="$this->getWidgets()" :columns="$this->getColumns()" />

    <livewire:bit.pip />
</x-filament::page>
