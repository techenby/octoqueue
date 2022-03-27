<?php

namespace Tests\Feature\Livewire\Bit;

use App\Http\Livewire\Bit\Printer;
use App\Models\Printer as ModelsPrinter;
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
        $printer = ModelsPrinter::factory()->create(['team_id' => $user->currentTeam->id]);

        $component = Livewire::actingAs($user)->test(Printer::class, ['printer' => $printer]);

        $component->assertStatus(200);
    }
}
