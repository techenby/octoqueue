<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\PrintType;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => Job::class,
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'print_type_id' => PrintType::factory(),
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
        ];
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'started_at' => now()->subHour(),
                'completed_at' => now(),
                'material_used' => 1.05,
            ];
        });
    }

    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'started_at' => now()->subHour(),
                'failed_at' => now(),
                'material_used' => 1.05,
            ];
        });
    }

    public function started()
    {
        return $this->state(function (array $attributes) {
            return [
                'started_at' => now(),
            ];
        });
    }
}
