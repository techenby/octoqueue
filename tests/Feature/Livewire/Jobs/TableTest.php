<?php

namespace Tests\Feature\Livewire\Jobs;

use App\Http\Livewire\Jobs\Table;
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
    public function can_bulk_duplicate()
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

        Livewire::actingAs($user)->test(Table::class)
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
        Http::fake();

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->has(Tool::factory())->createQuietly(['status' => 'operational']);
        Material::factory()->for($user->currentTeam)->create(['color_hex' => '#FFFF00']);

        $job = Job::factory()->for($user->currentTeam)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => $printer->id, 'file' => 'ducky.gcode']],
        ]);

        Livewire::actingAs($user)->test(Table::class)
            ->callTableAction('print', $job)
            ->assertNotified();

        $this->assertNull($job->fresh()->started_at);
    }
}
