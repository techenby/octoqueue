@props([
    'format' => 'Y-m-d',
    'diff' => false,
    'date' => false,
])

@php
    $timezone = session('timezone');

    if ($diff) {
        $formatted = $date->timezone($timezone)->diffForHumans();
    } else {
        $formatted = $date->timezone($timezone)->format($format);
    }
@endphp

<span {{ $attributes }} >
    {{ $formatted }}
</span>
