<?php

namespace Database\Factories;

use App\Models\Printer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrinterFactory extends Factory
{
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'name' => 'Charmander',
            'model' => 'Prusa Mini',
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Printer $printer) {
            //
        })->afterCreating(function (Printer $printer) {
            // $printer->tools()->create(['name' => 'tool0']);
        });
    }
}
