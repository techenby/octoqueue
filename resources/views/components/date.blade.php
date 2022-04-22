@props([
    'date' => false,
    'diff' => false,
    'format' => 'Y-m-d',
    'from' => false,
    'to' => false,
])

@php
    $timezone = session('timezone');

    if ($from && $to) {
        $diff = $to->diff($from);
        if ($diff->days > 1) {
            $formatted = $diff->format('%dd %H:%M:%S');
        } else {
            $formatted = $diff->format('%H:%M:%S');
        }
    } elseif ($diff) {
        $formatted = $date->timezone($timezone)->diffForHumans();
    } elseif ($date) {
        $formatted = $date->timezone($timezone)->format($format);
    } else {
        $formatted = '';
    }
@endphp

<span {{ $attributes }} >
    {{ $formatted }}
</span>
