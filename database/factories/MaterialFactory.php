<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'printer_type' => 'fdm',
            'color' => 'Galaxy Black',
            'color_hex' => '#000000',
            'brand' => 'Prusament',
            'cost' => '28.50',
            'type' => 'PLA',
            'diameter' => '1.75',
            'empty' => 250,
            'weights' => [['weight' => 1250, 'timestamp' => now()]],
        ];
    }
}
