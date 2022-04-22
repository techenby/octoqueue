@php
    $model = $attributes->get('wire:model') ?? 'perPage';
@endphp

<x-form.select class="text-sm" wire:model="{{ $model }}" id="per-page">
    <option>10</option>
    <option>15</option>
    <option>25</option>
    <option>50</option>
    <option>100</option>
</x-form.select>
