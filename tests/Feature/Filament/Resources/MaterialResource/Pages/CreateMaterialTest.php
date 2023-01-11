<?php

namespace Tests\Feature\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource\Pages\CreateJob;
use App\Filament\Resources\MaterialResource\Pages\CreateMaterial;
use App\Models\Material;
use App\Models\Printer;
use App\Models\PrintType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class CreateMaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_material()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(CreateMaterial::class)
            ->fillForm([
                'brand' => 'Prusament',
                'cost' => '28.50',
                'color' => 'Blue',
                'color_hex' => '#0000FF',
                'printer_type' => 'fdm',
                'type' => 'PLA',
                'diameter' => '1.75',
                'empty' => 250,
                'current_weight' => 1250,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('materials', [
            'brand' => 'Prusament',
            'cost' => '28.50',
            'color' => 'Blue',
            'color_hex' => '#0000FF',
            'printer_type' => 'fdm',
            'type' => 'PLA',
            'diameter' => '1.75',
            'empty' => 250,
        ]);

        $material = Material::where('brand', 'Prusament')->where('team_id', $user->current_team_id)->first();
        $this->assertNotNull($material->weights);
        $this->assertEquals(1250, $material->weights->first()['weight']);
    }
}
