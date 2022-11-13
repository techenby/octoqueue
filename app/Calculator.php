<?php

namespace App;

class Calculator
{
    public $fdmMaterialTypes = [
        'PLA' => 1.25,
        'ABS' => 1.04,
        'PETG' => 1.27,
        'NYLON' => 1.52,
        'Flexible (TPU)' => 1.21,
        'Polycarbonate (PC)' => 1.3,
        'Wood' => 1.28,
        'Carbon FIber' => 1.3,
        'PC/ABS' => 1.19,
        'HIPS' => 1.03,
        'PVA' => 1.23,
        'ASA' => 1.05,
        'Polypropylene (PP)' => 0.9,
        'Acetal (POM)' => 1.4,
        'PMMA' => 1.18,
        'Semi flexible (FPE)' => 2.16,
    ];

    public $resinMaterialTypes = [
        //
    ];

    public function lengthToGrams($material, $diameter, $length)
    {
        $cm = $diameter / 10;
        $cross = pow(($cm / 2), 2) * pi();
        $filament = $this->fdmMaterialTypes[$material];

        return round($length * 100 * $cross * $filament, 2);
    }

    public function gramsToLength($material, $diameter, $grams)
    {
        if (! array_key_exists($material, $this->fdmMaterialTypes)) {
            return false;
        }

        $cm = $diameter / 10;
        $cross = pow(($cm / 2), 2) * pi();
        $filament = $grams / $this->fdmMaterialTypes[$material];

        return round($filament / $cross / 100, 2);
    }

    public function materialByType($type)
    {
        return $type === 'fdm' ? $this->fdmMaterialTypes : $this->resinMaterialTypes;
    }
}
