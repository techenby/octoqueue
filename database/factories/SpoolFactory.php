<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Color;
use App\Models\Spool;
use App\Models\Team;

class SpoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Spool::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'color_id' => Color::factory(),
            'brand' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'cost' => $this->faker->word,
            'material' => $this->faker->word,
            'diameter' => $this->faker->word,
            'weights' => '{}',
        ];
    }
}
