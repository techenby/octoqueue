<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PrinterFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => 'Charmander',
            'model' => 'Prusa Mini',
        ];
    }
}
