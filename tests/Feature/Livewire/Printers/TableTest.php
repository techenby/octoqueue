<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Form;
use App\Http\Livewire\Printers\Table;
use App\Models\Printer;
use App\Models\Spool;
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

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200);
    }

    /** @test */
    public function can_swap_filaments()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $pink = Spool::factory()->for($user->currentTeam)->create(['color' => 'Pink']);
        $blue = Spool::factory()->for($user->currentTeam)->create(['color' => 'Blue']);

        $dipper = Printer::factory()->for($user->currentTeam)->for($blue)->create(['name' => 'Dipper']);
        $mabel = Printer::factory()->for($user->currentTeam)->for($pink)->create(['name' => 'Mabel']);

        $this->assertEquals($blue->id, $dipper->spool_id);
        $this->assertEquals($pink->id, $mabel->spool_id);

        Livewire::actingAs($user)->test(Table::class)
            ->call('showSpoolModal', $dipper->id)
            ->assertSet('currentPrinter.id', $dipper->id)
            ->assertSet('currentSpool', $blue->id)
            ->set('currentSpool', $pink->id)
            ->call('updateSpool')
            ->assertSet('showModal', false)
            ->assertSet('currentPrinter', false)
            ->assertSet('currentSpool', false);

        $this->assertEquals($pink->id, $dipper->fresh()->spool_id);
        $this->assertNull($mabel->fresh()->spool_id);
    }
}
