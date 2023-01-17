<?php

namespace Tests\Feature\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\MaterialResource\Pages\EditMaterial;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditMaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_edit_material()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $material = Material::factory()->for($user->currentTeam)->create([
            'brand' => 'Inland',
            'cost' => '17.95',
            'color' => 'White',
            'color_hex' => '#ffffff',
            'printer_type' => 'fdm',
            'type' => 'PLA',
            'diameter' => '1.75',
            'empty' => 136,
        ]);

        Livewire::actingAs($user)
            ->test(EditMaterial::class, ['record' => $material->id])
            ->fillForm([
                'color_hex' => '#ffffff',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $material->refresh();
        $this->assertEquals('#ffffff', $material->color_hex);
    }

    /** @test */
    public function can_add_weights()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $material = Material::factory()->for($user->currentTeam)->create();
        $material->addWeight(1400);
        $material->addWeight(1300);
        $material->addWeight(1200);

        Livewire::actingAs($user)
            ->test(EditMaterial::class, ['record' => $material->id])
            ->fillForm([
                'current_weight' => 1100,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $material->refresh();
        $this->assertEquals(1100, $material->weights->last()['weight']);
    }
}
