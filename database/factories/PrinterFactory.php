<?php

namespace Database\Factories;

use App\Models\Printer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrinterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => 'Charmander',
            'model' => 'Prusa Mini',
            'url' => 'http://charmander.local',
            'api_key' => 'TEST-API-KEY',
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
