<?php

namespace App;

class Calculator
{
    public $materials = [
        'PLA' => [
            'density' => 1.25,
            'cross-section' => .024,
        ],
    ];

    public function lengthToGrams($material, $length)
    {
        $variables = $this->materials[$material];
        return round($length * 100 * $variables['cross-section'] * $variables['density'], 2);
    }

    public function gramsToLength($material, $grams)
    {
        $variables = $this->materials[$material];
        return round($grams / $variables['density'] / $variables['cross-section'] / 100, 2);
    }
}
