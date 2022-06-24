<?php

namespace Tests\Unit\Models;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\Spool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrintJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mark_job_as_completed()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $spool = Spool::factory()->for($user->currentTeam)->create(['material' => 'PLA']);
        $printer = Printer::factory()->for($user->currentTeam)->create();
        $job = PrintJob::factory()->for($printer)->for($user->currentTeam)->started()->create([
            'files' => [$printer->id => 'Testing/leveling-squares.gcode'],
            'spool_id' => $spool->id,
        ]);

        $job->completed();

        $this->assertNotNull($job->completed_at);
        $this->assertNotNull($job->filament_used);
    }

    /** @test */
    public function stop_job()
    {
        $spool = Spool::factory()->create();
        $job = PrintJob::factory()->started()->for($spool)->create([
            'name' => 'Whistle',
            'files' => [
                1 => 'C3_whistle.gcode',
                2 => 'C3PRO_whistle.gcode',
            ],
        ]);

        $job->stop();

        $this->assertNotNull($job->fresh()->failed_at);
        $this->assertNotNull($job->fresh()->filament_used);
        $this->assertDatabaseHas('print_jobs', [
            'name' => 'Whistle',
            'failed_at' => null
        ]);
    }

    /** @test */
    public function duplicate_job()
    {
        $job = PrintJob::factory()->finished()->create([
            'printer_id' => 1,
            'files' => [1 => 'testing-squares.gcode', 2 => 'testing-squares.gcode'],
        ]);

        $duplicate = $job->duplicate();

        $this->assertNull($duplicate->printer_id);
        $this->assertNull($duplicate->started_at);
        $this->assertNull($duplicate->completed_at);
        $this->assertNull($duplicate->filament_used);
    }
}
