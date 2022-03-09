<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Printer;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PrintersController
 */
class PrintersControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function index_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        Printer::factory()->count(3)->for($user->currentTeam)->create();

        $this->actingAs($user)->get('/printers')
            ->assertInertia(fn (Assert $page) => $page
            ->component('Printers/Index')
            ->has('printers', 3)
        );
    }

    /** @test */
    public function create_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user)->get('/printers/create')
            ->assertInertia(fn (Assert $page) => $page
            ->component('Printers/Create')
        );
    }

    /** @test */
    public function store_saves_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $response = $this->actingAs($user)->withoutExceptionHandling()->post('/printers', [
            'name' => 'Pikachu',
            'model' => 'Ender 3 Pro',
            'url' => 'http://pikachu.local',
            'api_key' => 'ABC123DEF456',
        ]);

        $printers = Printer::query()
            ->where('team_id', $user->currentTeam->id)
            ->where('name', 'Pikachu')
            ->get();
        $this->assertCount(1, $printers);

        $response->assertRedirect('/printers');
    }

    /** @test */
    public function show_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create();

        $this->actingAs($user)->get("/printers/{$printer->id}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Printers/Show')
                ->has('printer', fn (Assert $page) => $page
                    ->where('id', $printer->id)
                    ->where('team_id', $printer->team_id)
                    ->etc()
                )
            );
    }

    /** @test */
    public function edit_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create();

        $this->actingAs($user)->get("/printers/{$printer->id}/edit")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Printers/Edit')
                ->has('printer', fn (Assert $page) => $page
                    ->where('id', $printer->id)
                    ->where('team_id', $printer->team_id)
                    ->etc()
                )
            );
    }

    /** @test */
    public function update_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create();

        $response = $this->actingAs($user)->put("/printers/{$printer->id}", [
            'name' => 'Eevee',
            'model' => 'Ender 3',
            'url' => 'http://eevee.local',
            'api_key' => 'ABC123DEF456',
        ]);

        $printer->refresh();

        $response->assertRedirect('/printers');

        $this->assertEquals($user->currentTeam->id, $printer->team_id);
        $this->assertEquals('Eevee', $printer->name);
    }

    /** @test */
    public function destroy_deletes_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create();

        $response = $this->actingAs($user)->delete("/printers/{$printer->id}");

        $response->assertRedirect('/printers');

        $this->assertSoftDeleted($printer);
    }

    /** @test */
    public function restore_and_redirects()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create();

        $response = $this->actingAs($user)->post("/printers/{$printer->id}");

        $response->assertRedirect('/printers');

        $this->assertNull($printer->deleted_at);
    }

}
