@props([
    'format' => 'Y-m-d',
    'date' => false,
])

@php
    $timezone = session('timezone');
@endphp

<span {{ $attributes }} >
    {{ $date->timezone($timezone)->format($format) }}
</span>
