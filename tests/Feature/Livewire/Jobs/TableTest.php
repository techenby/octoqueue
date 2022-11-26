<?php

namespace Tests\Feature\Livewire\Jobs;

use App\Http\Livewire\Jobs\Table;
use App\Models\Job;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_all_jobs_for_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $jobs = Job::factory()->for($user->currentTeam)->count(5)->create();

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200)
            ->assertCanSeeTableRecords($jobs);
    }

    /** @test */
    public function cannot_view_all_jobs_for_different_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $jobs = Job::factory()->for(Team::factory())->count(5)->create();

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200)
            ->assertCanNotSeeTableRecords($jobs);
    }

    /** @test */
    public function can_bulk_delete()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $jobs = Job::factory()->for($user->currentTeam)->count(5)->create();

        Livewire::actingAs($user)->test(Table::class)
            ->callTableBulkAction('delete', $jobs)
            ->assertHasNoTableActionErrors();

        $this->assertEmpty($jobs->fresh());
    }

    /** @test */
    public function duplicating_job_nulls_out_dates_and_material_used()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $job = Job::factory()->for($user->currentTeam)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => 1, 'file' => 'ducky.gcode']],
        ]);

        Livewire::actingAs($user)->test(Table::class)
            ->callTableBulkAction('duplicate', [$job], ['color_hex' => '#000000'])
            ->assertHasNoTableActionErrors();

        $newJob = Job::whereTeamId($user->current_team_id)->where('id', '<>', $job->id)->first();

        $this->assertNotEquals($job->id, $newJob->id);
        $this->assertEquals('Rubber Ducky', $newJob->name);
        $this->assertEquals('#000000', $newJob->color_hex);
        $this->assertEquals('Should be cute', $newJob->notes);
        $this->assertNull($newJob->started_at);
        $this->assertNull($newJob->completed_at);
        $this->assertNull($newJob->failed_at);
        $this->assertNull($newJob->material_used);
    }
}
