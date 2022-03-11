<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\PrintJobType;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PrintJobTypesController
 */
class PrintJobTypesControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function index_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        PrintJobType::factory()->count(3)->for($user->currentTeam)->create();

        $this->actingAs($user)->get('/job-types')
            ->assertInertia(fn (Assert $page) => $page
            ->component('PrintJobTypes/Index')
            ->has('types', 3));
    }

    /** @test */
    public function create_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user)->get('/job-types/create')
            ->assertInertia(fn (Assert $page) => $page
            ->component('PrintJobTypes/Create'));
    }

    /** @test */
    public function store_saves_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $response = $this->actingAs($user)->withoutExceptionHandling()->post('/job-types', [
            'name' => 'Fun',
            'priority' => 1,
        ]);

        $printers = PrintJobType::query()
            ->where('team_id', $user->currentTeam->id)
            ->where('name', 'Fun')
            ->get();
        $this->assertCount(1, $printers);

        $response->assertRedirect('/job-types');
    }

    /** @test */
    public function show_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $type = PrintJobType::factory()->for($user->currentTeam)->create();

        $this->actingAs($user)->get("/job-types/{$type->id}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('PrintJobTypes/Show')
                ->has('type', fn (Assert $page) => $page
                    ->where('id', $type->id)
                    ->where('team_id', $type->team_id)
                    ->etc()));
    }

    /** @test */
    public function edit_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $type = PrintJobType::factory()->for($user->currentTeam)->create();

        $this->actingAs($user)->get("/job-types/{$type->id}/edit")
            ->assertInertia(fn (Assert $page) => $page
                ->component('PrintJobTypes/Edit')
                ->has('type', fn (Assert $page) => $page
                    ->where('id', $type->id)
                    ->where('team_id', $type->team_id)
                    ->etc()));
    }

    /** @test */
    public function update_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $type = PrintJobType::factory()->for($user->currentTeam)->create();

        $response = $this->actingAs($user)->put("/job-types/{$type->id}", [
            'name' => 'MCC',
            'priority' => 1,
            'url' =>  'http://eevee.local',
            'api_key' => 'ABC123DEF456',
        ]);

        $type->refresh();

        $response->assertRedirect('/job-types');

        $this->assertEquals($user->currentTeam->id, $type->team_id);
        $this->assertEquals('MCC', $type->name);
    }

    /** @test */
    public function destroy_deletes_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = PrintJobType::factory()->for($user->currentTeam)->create();

        $response = $this->actingAs($user)->delete("/job-types/{$printer->id}");

        $response->assertRedirect('/job-types');

        $this->assertSoftDeleted($printer);
    }

    /** @test */
    public function restore_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $type = PrintJobType::factory()->for($user->currentTeam)->create();

        $response = $this->actingAs($user)->post("/job-types/{$type->id}");

        $response->assertRedirect('/job-types');

        $this->assertNull($type->deleted_at);
    }
}
