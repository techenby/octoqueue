<?php

namespace Tests\Feature\Livewire\PrintJobs;

use App\Http\Livewire\PrintJobs\Form;
use App\Http\Livewire\PrintJobs\Table;
use App\Models\PrintJob;
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
    public function can_duplicated_job()
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
}
