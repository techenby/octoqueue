<?php

namespace Database\Factories;

use App\Models\Spool;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpoolFactory extends Factory
{
    protected $model = Spool::class;

    public function definition()
    {
        return [
            'brand' => 'Inland',
            'cost' => 17.95,
            'color_hex' => '#ffffff',
            'color' => 'White',
            'material' => 'PLA',
            'diameter' => 1.75,
            'weights' => [['weight' => '1200', 'timestamp' => now()]],
        ];
    }
}
