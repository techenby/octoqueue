<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Color;
use App\Models\Spool;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SpoolsController
 */
class SpoolsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** @test */
    public function index_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        Spool::factory()->count(3)->for($user->currentTeam)->create();

        $this->actingAs($user)->get('/spools')
            ->assertInertia(fn (Assert $page) => $page
            ->component('Spools/Index')
            ->has('spools', 3)
        );
    }

    /** @test */
    public function create_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        Color::factory()->count(3)->create();

        $this->actingAs($user)->get('/spools/create')
            ->assertInertia(fn (Assert $page) => $page
            ->component('Spools/Create')
            ->has('colors', 3)
        );
    }

    /** @test */
    public function store_saves_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $team = Team::factory()->create();
        $color = Color::factory()->create();

        $response = $this->actingAs($user)->post('/spools', [
            'team_id' => $team->id,
            'color_id' => $color->id,
            'brand' => 'Inland',
            'weights' => [['weight' => 1000, 'timestamp' => now()]],
        ]);

        $spools = Spool::query()
            ->where('team_id', $team->id)
            ->where('color_id', $color->id)
            ->where('brand', 'Inland')
            ->get();
        $this->assertCount(1, $spools);

        $response->assertRedirect('/spools');
    }

    /** @test */
    public function show_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $spool = Spool::factory()->for($user->currentTeam)->create();

        $this->actingAs($user)->get("/spools/{$spool->id}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Spools/Show')
                ->has('spool', fn (Assert $page) => $page
                    ->where('id', $spool->id)
                    ->where('team_id', $spool->team_id)
                    ->etc()
                )
            );
    }

    /** @test */
    public function edit_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $colors = Color::factory()->count(3)->create();
        $spool = Spool::factory()->for($user->currentTeam)->create(['color_id' => $colors[0]->id]);

        $this->actingAs($user)->get("/spools/{$spool->id}/edit")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Spools/Edit')
                ->has('spool', fn (Assert $page) => $page
                    ->where('id', $spool->id)
                    ->where('team_id', $spool->team_id)
                    ->etc()
                )
                ->has('colors', 3)
            );
    }

    /** @test */
    public function update_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $spool = Spool::factory()->for($user->currentTeam)->create();
        $team = Team::factory()->create();
        $color = Color::factory()->create();

        $response = $this->actingAs($user)->put("/spools/{$spool->id}", [
            'team_id' => $team->id,
            'color_id' => $color->id,
            'brand' => 'Inland',
            'weights' => [['weight' => 1000, 'timestamp' => now()],['weight' => 500, 'timestamp' => now()]],
        ]);

        $spool->refresh();

        $response->assertRedirect('/spools');

        $this->assertEquals($team->id, $spool->team_id);
        $this->assertEquals($color->id, $spool->color_id);
        $this->assertEquals('Inland', $spool->brand);
        $this->assertCount(2, $spool->weights);
    }

    /** @test */
    public function destroy_deletes_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $spool = Spool::factory()->for($user->currentTeam)->create();

        $response = $this->actingAs($user)->delete("/spools/{$spool->id}");

        $response->assertRedirect('/spools');

        $this->assertSoftDeleted($spool);
    }

    /** @test */
    public function restore_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $spool = Spool::factory()->for($user->currentTeam)->create();

        $response = $this->actingAs($user)->post("/spools/{$spool->id}");

        $response->assertRedirect('/spools');

        $this->assertNull($spool->deleted_at);
    }
}
