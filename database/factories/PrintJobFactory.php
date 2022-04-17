<?php

namespace Database\Factories;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\Spool;
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
            'name' => 'Coaster',
        ];
    }
}
