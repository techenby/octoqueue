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
        $printer = Printer::factory()->for($user->currentTeam)->create();

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
            ->create();

        Livewire::actingAs($user)->test(Show::class, ['printer' => $printer])
            ->call('deletePrinter')
            ->assertRedirect(route('printers'));

        $this->assertDatabaseMissing('printers', ['id' => $printer->id]);
        $this->assertDatabaseMissing('tools', ['printer_id' => $printer->id]);
    }

    /** @test */
    public function can_assign_material_to_tool()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $material = Material::factory()->for($user->currentTeam)->create();
        $printer = Printer::factory()
            ->for($user->currentTeam)
            ->has(Tool::factory())
            ->create();
        $tool = $printer->tools->first();

        Livewire::actingAs($user)->test(Show::class, ['printer' => $printer])
            ->set("tools.{$tool->id}.material_id", $material->id)
            ->assertNotified();

        $this->assertEquals($material->id, $tool->refresh()->material_id);
    }
}
