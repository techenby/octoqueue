<?php

namespace Tests\Feature\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\MaterialResource\Pages\ListMaterials;
use App\Models\Material;
use App\Models\Printer;
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

    /** @test */
    public function can_move_material_from_printer_to_storage()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $materials = Material::factory()->for($user->currentTeam)->count(5)->create();
        $loadedPrinter = Printer::factory()->for($user->currentTeam)->for($materials->first())->createQuietly();

        Livewire::actingAs($user)->test(ListMaterials::class)
            ->callTableAction('change_location', $materials->first(), ['location' => null])
            ->assertHasNoTableActionErrors();

        $this->assertNull($loadedPrinter->fresh()->material_id);
    }

    /** @test */
    public function can_move_material_from_printer_to_printer()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $materials = Material::factory()->for($user->currentTeam)->count(5)->create();
        $emptyPrinter = Printer::factory()->for($user->currentTeam)->createQuietly();
        $loadedPrinter = Printer::factory()->for($user->currentTeam)->for($materials->first())->createQuietly();

        Livewire::actingAs($user)->test(ListMaterials::class)
            ->callTableAction('change_location', $materials->first(), ['location' => $emptyPrinter->id])
            ->assertHasNoTableActionErrors();

        $this->assertNull($loadedPrinter->fresh()->material_id);
        $this->assertEquals($materials->first()->id, $emptyPrinter->fresh()->material_id);
    }

    /** @test */
    public function can_move_material_from_storage_to_printer()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $materials = Material::factory()->for($user->currentTeam)->count(5)->create();
        $emptyPrinter = Printer::factory()->for($user->currentTeam)->createQuietly();

        Livewire::actingAs($user)->test(ListMaterials::class)
            ->callTableAction('change_location', $materials->first(), ['location' => $emptyPrinter->id])
            ->assertHasNoTableActionErrors();

        $this->assertEquals($materials->first()->id, $emptyPrinter->fresh()->material_id);
    }
}
