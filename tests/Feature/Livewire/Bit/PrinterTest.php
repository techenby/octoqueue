<?php

namespace Tests\Feature\Livewire\Bit;

use App\Http\Livewire\Bit\Printer as Component;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\Spool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PrinterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(Component::class, ['printer' => $printer])
            ->assertStatus(200);
    }

    /** @test */
    public function can_view_next_print()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $spool = Spool::factory()->for($user->currentTeam)->create(['color_hex' => '#ffffff']);
        $printer = Printer::factory()->for($user->currentTeam)->for($spool)->create();
        PrintJob::factory()->for($printer)->for($user->currentTeam)->create(['name' => 'Coaster', 'color_hex' => '#ffffff']);

        Livewire::actingAs($user)
            ->test(Component::class, ['printer' => $printer])
            ->assertStatus(200)
            ->set('tab', 'next-job')
            ->assertSee('Coaster');
    }

    public function can_home_axis()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(Component::class, ['printer' => $printer])
            ->call('home', 'xy')
            ->assertStatus(200);
    }

    /** @test */
    public function can_jog_axis()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(Component::class, ['printer' => $printer])
            ->call('jog', ['x', '-'])
            ->assertStatus(200);
    }
}
