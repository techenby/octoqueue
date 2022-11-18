<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\Printer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tool>
 */
class ToolFactory extends Factory
{
    public function definition()
    {
        return [
            'printer_id' => Printer::factory(),
            'name' => 'tool0',
        ];
    }
}
