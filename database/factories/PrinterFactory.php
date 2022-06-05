<?php

namespace Database\Factories;

use App\Models\Printer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrinterFactory extends Factory
{
    protected $model = Printer::class;

    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'name' => 'Pikachu',
            'model' => 'Ender 3',
            'url' => 'http://octoprint.local',
            'api_key' => 'ABC123DEF456',
            'status' => 'Operational',
        ];
    }
}
