<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrintType>
 */
class PrintTypeFactory extends Factory
{
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'name' => 'Fun',
            'priority' => 1,
        ];
    }
}
