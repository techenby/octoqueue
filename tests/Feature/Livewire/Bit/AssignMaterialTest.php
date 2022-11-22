<?php

namespace Tests\Feature\Livewire\Bit;

use App\Http\Livewire\Bit\AssignMaterial;
use App\Models\Material;
use App\Models\Printer;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class AssignMaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_assign_material_to_tool()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->has(Tool::factory())->createQuietly();
        $material = Material::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(AssignMaterial::class, ['tools' => $printer->tools])
            ->assertStatus(200)
            ->set("tools.0.material_id", $material->id)
            ->assertNotified();

        $this->assertEquals($material->id, $printer->fresh()->tools->first()->material_id);
    }
}
