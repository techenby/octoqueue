<?php

if (!function_exists('flattenByKey')) {
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
