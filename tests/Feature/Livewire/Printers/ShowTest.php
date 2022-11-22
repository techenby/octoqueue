<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Show;
use App\Models\Material;
use App\Models\Printer;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->createQuietly();

        Livewire::actingAs($user)->test(Show::class, ['printer' => $printer])
            ->assertStatus(200);
    }

    /** @test */
    public function can_delete_pritner()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()
            ->for($user->currentTeam)
            ->has(Tool::factory())
            ->createQuietly();

        Livewire::actingAs($user)->test(Show::class, ['printer' => $printer])
            ->call('deletePrinter')
            ->assertRedirect(route('printers'));

        $this->assertDatabaseMissing('printers', ['id' => $printer->id]);
        $this->assertDatabaseMissing('tools', ['printer_id' => $printer->id]);
    }
}
