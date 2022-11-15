<?php

namespace Tests\Feature\Livewire\Jobs;

use App\Http\Livewire\Jobs\Table;
use App\Models\Job;
use App\Models\Material;
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
}
