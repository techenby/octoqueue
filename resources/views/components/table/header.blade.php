@props([
    'left' => null,
    'right' => null,
])

@if ($left === null && $right === null)
<div class="flex justify-between space-x-4">
    <div class="flex items-end mt-4 space-x-2 md:mt-0">
        <x-form.input class="text-sm" x-on:keydown.slash="this.focus()" wire:model="search" placeholder="Search..." />
    </div>
    <div class="flex items-end mt-4 space-x-2 md:mt-0">
        <x-bit.per-page />
    </div>
</div>
@else
<div class="md:flex md:justify-between">
    <div class="flex flex-col space-y-2 md:items-end md:space-x-2 md:space-y-0 md:flex-row">
        <x-form.input class="text-sm" x-on:keydown.slash="this.focus()" wire:model="search" placeholder="Search..." />
        {{ $left }}
    </div>
    <div class="flex items-end mt-4 space-x-2 md:mt-0">
        <x-bit.per-page />
        {{ $right }}
    </div>
</div>
@endif
