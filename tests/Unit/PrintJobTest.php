<?php

namespace Tests\Unit;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrintJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mark_job_as_completed()
    {
        $this->markTestIncomplete();

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create(['name' => 'Rubber Ducky']);
        $job = PrintJob::factory()->for($printer)->for($user->currentTeam)->create([
            'files' => [$printer->id => 'Testing/leveling-squares.gcode',]
        ]);

        $job->completed();

        $this->assertNotNull($job->completed_at);
        $this->assertNotNull($job->filament_used);
    }

    /** @test */
    public function cancel_job()
    {
        $job = PrintJob::factory()->started()->create();

        $job->cancel();

        $this->assertNull($job->fresh()->started_at);
    }
}
