<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Printers;
use App\Models\Printer;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class PrintersTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $component = Livewire::actingAs($user)->test(Printers::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function index_displays_view()
    {
        $user = User::factory()->withPersonalTeam()->create();
        Printer::factory()->for($user->currentTeam)->create(['name' => 'Pikachu']);
        Printer::factory()->for($user->currentTeam)->create(['name' => 'Eevee']);
        Printer::factory()->for(Team::factory()->create())->create(['name' => 'Rocket']);

        $this->actingAs($user)->get('/printers')
            ->assertSee('Pikachu')
            ->assertSee('Eevee')
            ->assertDontSee('Rocket');
    }
}
