<?php

namespace Database\Factories;

use App\Models\PrintJob;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintJobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrintJob::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'job_type_id' => PrintJobTypeFactory::factory(),
            // 'printer_id' => Printer::factory(),
            // 'spool_id' => Spool::factory(),
            // 'color_id' => Color::factory(),
            // 'user_id' => User::factory(),
            'name' => $this->faker->name,
            'files' => '{}',
            'notes' => $this->faker->text,
            'started_at' => $this->faker->dateTime(),
            'completed_at' => $this->faker->dateTime(),
            'filament_used' => $this->faker->randomFloat(0, 0, 9999999999.),
        ];
    }
}
