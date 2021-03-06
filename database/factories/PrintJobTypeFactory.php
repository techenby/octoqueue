<?php

namespace Database\Factories;

use App\Models\PrintJobType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintJobTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrintJobType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'priority' => $this->faker->numberBetween(1, 6),
        ];
    }
}
