<?php

namespace Tests\Unit\Models;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobType;
use App\Models\Spool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrinterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function next_job_relationship()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $access = PrintJobType::factory()->for($user->currentTeam)->create(['name' => 'Access', 'priority' => 1]);
        $fun = PrintJobType::factory()->for($user->currentTeam)->create(['name' => 'Fun', 'priority' => 2]);

        $spool = Spool::factory()->for($user->currentTeam)->create(['color_hex' => '#ffffff']);

        $printer = Printer::factory()->for($user->currentTeam)->create(['spool_id' => $spool->id]);

        PrintJob::factory()->for($user->currentTeam)->create(['printer_id' => $printer->id, 'job_type_id' => $fun->id, 'color_hex' => '#ffffff']);
        $printJobA = PrintJob::factory()->for($user->currentTeam)->create(['printer_id' => $printer->id, 'job_type_id' => $access->id, 'color_hex' => '#ffffff']);

        $this->assertEquals($printJobA->id, $printer->nextJob->id);
    }

    /** @test */
    public function next_job_cant_be_started_or_completed_relationship()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $type = PrintJobType::factory()->for($user->currentTeam)->create();

        $spool = Spool::factory()->for($user->currentTeam)->create(['color_hex' => '#ffffff']);

        $printer = Printer::factory()->for($user->currentTeam)->create(['spool_id' => $spool->id]);

        PrintJob::factory()->for($user->currentTeam)->started()->create(['printer_id' => $printer->id, 'job_type_id' => $type->id, 'color_hex' => '#ffffff']);
        PrintJob::factory()->for($user->currentTeam)->finished()->create(['printer_id' => $printer->id, 'job_type_id' => $type->id, 'color_hex' => '#ffffff']);
        $printJob = PrintJob::factory()->for($user->currentTeam)->create(['printer_id' => $printer->id, 'job_type_id' => $type->id, 'color_hex' => '#ffffff']);

        $this->assertEquals($printJob->id, $printer->nextJob->id);
    }

    /** @test */
    public function next_job_also_includes_jobs_with_no_color()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $type = PrintJobType::factory()->for($user->currentTeam)->create();

        $spool = Spool::factory()->for($user->currentTeam)->create(['color_hex' => '#ffffff']);

        $printer = Printer::factory()->for($user->currentTeam)->create(['spool_id' => $spool->id]);

        $printJob = PrintJob::factory()->for($user->currentTeam)->create(['printer_id' => $printer->id, 'job_type_id' => $type->id, 'color_hex' => null]);

        $this->assertEquals($printJob->id, $printer->nextJob->id);
    }
}
