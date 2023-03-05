<?php

namespace Database\Factories;

use App\Models\Printer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tool>
 */
class ToolFactory extends Factory
{
    public function definition(): array
    {
        return [
            'printer_id' => Printer::factory(),
            'name' => 'tool0',
        ];
    }
}
