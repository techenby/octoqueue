<?php

namespace Database\Factories;

use App\Models\PrintType;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'print_type_id' => PrintType::factory(),
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
        ];
    }
}
