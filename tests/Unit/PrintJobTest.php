<?php

namespace Tests\Unit;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use TechEnby\OctoPrint\OctoPrint;
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
            'files' => [$printer->id => 'Testing/leveling-squares.gcode']
        ]);

        $job->completed();

        $this->assertNotNull($job->completed_at);
        $this->assertNotNull($job->filament_used);
    }
}
