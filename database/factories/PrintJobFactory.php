<?php

namespace Database\Factories;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintJobFactory extends Factory
{
    protected $model = PrintJob::class;

    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'printer_id' => Printer::factory(),
            'user_id' => User::factory(),
            'name' => 'Coaster',
        ];
    }

    public function started()
    {
        return $this->state(fn () => ['started_at' => now()->subMinutes(10)]);
    }

    public function finished()
    {
        return $this->state(function () {
            return [
                'started_at' => now()->subMinutes(10),
                'completed_at' => now(),
                'filament_used' => 15,
            ];
        });
    }
}
