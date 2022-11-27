<?php

if (! function_exists('flattenByKey')) {
    function flattenByKey($input, $key)
    {
        $output = [];

        // For each object in the array
        foreach ($input as $object) {
            // separate its children
            $children = isset($object[$key]) ? $object[$key] : [];
            $object[$key] = [];

            // and add it to the output array
            $output[] = $object;

            // Recursively flatten the array of children
            $children = flattenByKey($children, $key);

            //  and add the result to the output array
            foreach ($children as $child) {
                $output[] = $child;
            }
        }

        return $output;
    }
}

if (! function_exists('secondsToTime')) {
    function secondsToTime($inputSeconds)
    {
        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;

        // Extract days
        $days = floor($inputSeconds / $secondsInADay);

        // Extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);

        // Extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        // Extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        // Format and return
        $timeParts = [];
        $sections = [
            'day' => (int) $days,
            'hour' => (int) $hours,
            'minute' => (int) $minutes,
            'second' => (int) $seconds,
        ];

        foreach ($sections as $value) {
            if ($value > 0) {
                $timeParts[] = str_pad($value, 2, '0', STR_PAD_LEFT);
            }
        }

        return implode(':', $timeParts);
    }
}
