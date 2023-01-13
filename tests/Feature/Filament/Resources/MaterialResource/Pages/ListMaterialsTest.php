<?php

namespace Tests\Feature\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource\Pages\ListJobs;
use App\Models\Job;
use App\Models\Material;
use App\Models\Printer;
use App\Models\Team;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ListMaterialsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_all_jobs_for_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $jobs = Job::factory()->for($user->currentTeam)->count(5)->create();

        Livewire::actingAs($user)->test(ListJobs::class)
            ->assertStatus(200)
            ->assertCanSeeTableRecords($jobs);
    }

    /** @test */
    public function cannot_view_all_jobs_for_different_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $jobs = Job::factory()->for(Team::factory())->count(5)->create();

        Livewire::actingAs($user)->test(ListJobs::class)
            ->assertStatus(200)
            ->assertCanNotSeeTableRecords($jobs);
    }

    /** ACTIONS */

    /** @test */
    public function can_bulk_delete()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $jobs = Job::factory()->for($user->currentTeam)->count(5)->create();

        Livewire::actingAs($user)->test(ListJobs::class)
            ->callTableBulkAction('delete', $jobs)
            ->assertHasNoTableActionErrors();

        $this->assertEmpty($jobs->fresh());
    }

    /** @test */
    public function can_bulk_duplicate()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $job = Job::factory()->for($user->currentTeam)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => 1, 'file' => 'ducky.gcode']],
        ]);

        Livewire::actingAs($user)->test(ListJobs::class)
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

    /** @test */
    public function can_print_job()
    {
        Http::fake();

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->has(Tool::factory())->createQuietly(['status' => 'operational']);
        $material = Material::factory()->for($user->currentTeam)->create(['color_hex' => '#FFFF00']);
        $printer->tools->first()->update(['material_id' => $material->id]);

        $job = Job::factory()->for($user->currentTeam)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => $printer->id, 'file' => 'ducky.gcode']],
        ]);

        Livewire::actingAs($user)->test(ListJobs::class)
            ->callTableAction('print', $job)
            ->assertHasNoTableActionErrors();

        $job->refresh();
        $this->assertNotNull($job->started_at);
        $this->assertEquals($printer->id, $job->printer_id);
        $this->assertEquals($material->id, $job->material_id);
    }

    /** @test */
    public function can_not_print_if_no_printers_available()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->has(Tool::factory())->createQuietly(['status' => 'operational']);
        Material::factory()->for($user->currentTeam)->create(['color_hex' => '#FFFF00']);

        $job = Job::factory()->for($user->currentTeam)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => $printer->id, 'file' => 'ducky.gcode']],
        ]);

        Livewire::actingAs($user)->test(ListJobs::class)
            ->callTableAction('print', $job)
            ->assertNotified('No printers available');

        $this->assertNull($job->fresh()->started_at);
    }

    /** @test */
    public function can_not_print_if_no_materials_found()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->has(Tool::factory())->createQuietly(['status' => 'operational']);

        $job = Job::factory()->for($user->currentTeam)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => $printer->id, 'file' => 'ducky.gcode']],
        ]);

        Livewire::actingAs($user)->test(ListJobs::class)
            ->callTableAction('print', $job)
            ->assertNotified('No materials found with this color');

        $this->assertNull($job->fresh()->started_at);
    }

    /** @test */
    public function can_duplicate_job_number_of_times()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $job = Job::factory()->for($user->currentTeam)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => 1, 'file' => 'ducky.gcode']],
        ]);

        Livewire::actingAs($user)->test(ListJobs::class)
            ->callTableAction('duplicate', $job, ['times' => 5])
            ->assertHasNoTableActionErrors();

        $this->assertCount(6, Job::whereTeamId($user->current_team_id)->where('name', 'Rubber Ducky')->where('color_hex', '#FFFF00')->get());
    }

    /** FILTERS */

    /** @test */
    public function by_default_only_to_print_jobs_are_visible()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $jobs = Job::factory()->for($user->currentTeam)->count(2)->create();
        $started = Job::factory()->for($user->currentTeam)->count(2)->started()->create();
        $completed = Job::factory()->for($user->currentTeam)->count(2)->completed()->create();
        $failed = Job::factory()->for($user->currentTeam)->count(2)->failed()->create();

        Livewire::actingAs($user)->test(ListJobs::class)
            ->assertCanSeeTableRecords($jobs)
            ->assertCanNotSeeTableRecords($started)
            ->assertCanNotSeeTableRecords($completed)
            ->assertCanNotSeeTableRecords($failed);
    }
}
