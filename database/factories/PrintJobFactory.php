<?php

namespace Database\Factories;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintJobFactory extends Factory
{
    protected $model = PrintJob::class;

    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'printer_id' => Printer::factory(),
            'name' => 'Coaster',
        ];
    }

    public function started()
    {
        return $this->state(function (array $attributes) {
            return [
                'started_at' => now()->subMinutes(10),
            ];
        });
    }

    public function finished()
    {
        return $this->state(function (array $attributes) {
            return [
                'started_at' => now()->subMinutes(10),
                'finished_at' => now(),
                'filament_used' => 15,
            ];
        });
    }
}
