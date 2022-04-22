<?php

namespace Tests\Feature\Livewire\PrintJobs;

use App\Http\Livewire\PrintJobs\Table;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $user = User::factory()->withPersonalTeam()->create();
        PrintJob::factory()->times(10)
            ->for($user->currentTeam)
            ->create();

        Livewire::actingAs($user)
            ->test(Table::class)
            ->assertStatus(200);
    }

    /** @test */
    public function can_delete_single_job()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $job = PrintJob::factory()
            ->finished()
            ->for($user->currentTeam)
            ->create([
                'name' => 'Coaster',
                'printer_id' => 1,
                'files' => [1 => 'testing-squares.gcode', 2 => 'testing-squares.gcode'],
            ]);

        Livewire::actingAs($user)
            ->test(Table::class)
            ->assertSee('Coaster')
            ->call('delete', $job->id)
            ->assertEmitted('refresh');

        $this->assertCount(0, $user->currentTeam->jobs);
    }

    /** @test */
    public function can_duplicate_job()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $job = PrintJob::factory()
            ->finished()
            ->for($user->currentTeam)
            ->create([
                'name' => 'Coaster',
                'printer_id' => 1,
                'files' => [1 => 'testing-squares.gcode', 2 => 'testing-squares.gcode'],
            ]);

        Livewire::actingAs($user)
            ->test(Table::class)
            ->assertSee('Coaster')
            ->call('duplicate', $job->id)
            ->assertSee(['Coaster', 'Coaster']);

        $this->assertCount(2, $user->currentTeam->jobs);
    }

    /** @test */
    public function can_mass_delete_jobs()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $jobA = PrintJob::factory()->for($user->currentTeam)->finished()->create();
        $jobB = PrintJob::factory()->for($user->currentTeam)->create();
        $jobC = PrintJob::factory()->for($user->currentTeam)->started()->create();

        Livewire::actingAs($user)
            ->test(Table::class)
            ->set('selected', [$jobA->id, $jobB->id, $jobC->id])
            ->call('massDelete')
            ->assertEmitted('refresh');

        // don't delete jobs that have started but not finished
        $this->assertCount(1, $user->currentTeam->fresh()->jobs);
    }

    /** @test */
    public function can_mass_set_color()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $jobA = PrintJob::factory()->for($user->currentTeam)->finished()->create();
        $jobB = PrintJob::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(Table::class)
            ->set('selected', [$jobA->id, $jobB->id])
            ->set('setValue', '#ffffff')
            ->call('massSet', 'color_hex')
            ->assertEmitted('refresh');

        $this->assertEquals('#ffffff', $jobA->fresh()->color_hex);
        $this->assertEquals('#ffffff', $jobB->fresh()->color_hex);
    }

    /** @test */
    public function can_mass_set_printer()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $printer = Printer::factory()->for($user->currentTeam)->create();

        $jobA = PrintJob::factory()->for($user->currentTeam)->finished()->create();
        $jobB = PrintJob::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(Table::class)
            ->set('selected', [$jobA->id, $jobB->id])
            ->set('setValue', $printer->id)
            ->call('massSet', 'printer_id')
            ->assertEmitted('refresh');

        $this->assertEquals($printer->id, $jobA->fresh()->printer_id);
        $this->assertEquals($printer->id, $jobB->fresh()->printer_id);
    }

    /** @test */
    public function can_mass_set_type()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $jobType = PrintJobType::factory()->for($user->currentTeam)->create();

        $jobA = PrintJob::factory()->for($user->currentTeam)->finished()->create();
        $jobB = PrintJob::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(Table::class)
            ->set('selected', [$jobA->id, $jobB->id])
            ->set('setValue', $jobType->id)
            ->call('massSet', 'job_type_id')
            ->assertEmitted('refresh');

        $this->assertEquals($jobType->id, $jobA->fresh()->job_type_id);
        $this->assertEquals($jobType->id, $jobB->fresh()->job_type_id);
    }
}
