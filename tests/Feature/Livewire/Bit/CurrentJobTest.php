<?php

namespace Tests\Feature\Livewire\Bit;

use App\Http\Livewire\Bit\CurrentJob;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\Spool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CurrentJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $printer = Printer::factory()->create();
        PrintJob::factory()->for($printer)->started()->create(['name' => 'Whistle']);

        Livewire::test(CurrentJob::class, ['printer' => $printer])
            ->assertStatus(200)
            ->assertSee('Whistle');
    }

    /** @test */
    public function can_mark_as_completed()
    {
        $spool = Spool::factory()->create();
        $printer = Printer::factory()->for($spool)->create();
        $currentJob = PrintJob::factory()
            ->for($printer)
            ->for($spool)
            ->started()
            ->create([
                'name' => 'Whistle',
                'files' => [
                    $printer->id => 'whistle_v2.gcode',
                ],
            ]);

        Livewire::test(CurrentJob::class, ['printer' => $printer])
            ->assertStatus(200)
            ->call('completed');

        $this->assertNotNull($currentJob->fresh()->completed_at);
        $this->assertNotNull($currentJob->fresh()->filament_used);
    }

    /** @test */
    public function can_stop_print()
    {
        // Stops job on OctoPrint
        // Fails current print
        // Adds used filaments
        // Duplicates job for future printing
        $spool = Spool::factory()->create();
        $printer = Printer::factory()->for($spool)->create();
        $currentJob = PrintJob::factory()
            ->for($printer)
            ->for($spool)
            ->started()
            ->create([
                'name' => 'Whistle',
                'files' => [
                    $printer->id => 'whistle_v2.gcode',
                ],
            ]);

        Livewire::test(CurrentJob::class, ['printer' => $printer])
            ->assertStatus(200)
            ->call('stop');

        $this->assertNotNull($currentJob->fresh()->failed_at);
        $this->assertNotNull($currentJob->fresh()->filament_used);
    }
}
