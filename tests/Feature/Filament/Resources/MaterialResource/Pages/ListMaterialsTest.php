<?php

namespace Tests\Feature\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\MaterialResource\Pages\ListMaterials;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListMaterialsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_all_materials_for_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $materials = Material::factory()->for($user->currentTeam)->count(5)->create();

        Livewire::actingAs($user)->test(ListMaterials::class)
            ->assertStatus(200)
            ->assertCanSeeTableRecords($materials);
    }
}
