@props([
    'left' => null,
    'right' => null,
])

<div class="md:flex md:justify-between">
    <div class="flex flex-col space-y-4 md:items-end md:space-x-4 md:flex-row">
        <x-form.input wire:model="search" placeholder="Search..." />
        {{ $left }}
    </div>
    <div class="flex items-end mt-4 space-x-2 md:mt-0">
        <x-bit.per-page />
        {{ $right }}
    </div>
</div>
