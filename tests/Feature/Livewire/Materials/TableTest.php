<?php

namespace Tests\Feature\Livewire\Materials;

use App\Http\Livewire\Materials\Table;
use App\Models\Material;
use App\Models\Team;
use App\Models\User;
use Filament\Tables\Actions\ReplicateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_all_filaments_for_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $filaments = Material::factory()->for($user->currentTeam)->count(5)->create();

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200)
            ->assertCanSeeTableRecords($filaments);
    }

    /** @test */
    public function cannot_view_all_filaments_for_different_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $filaments = Material::factory()->for(Team::factory())->count(5)->create();

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200)
            ->assertCanNotSeeTableRecords($filaments);
    }

    /** @test */
    public function can_replicate_a_material_without_weights()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $material = Material::factory()->for($user->currentTeam)->create([
            'brand' => 'Prusa',
            'cost' => '17.95',
            'color' => 'Red',
            'color_hex' => '#FF0000',
            'diameter' => '1.75',
            'empty' => 256,
            'weights' => [['weight' => 1000, 'timestamp' => now()]],
        ]);

        Livewire::actingAs($user)->test(Table::class)
            ->callTableAction(ReplicateAction::class, $material)
            ->assertHasNoTableActionErrors();

        $newMaterial = Material::whereTeamId($user->current_team_id)->where('id', '<>', $material->id)->first();
        $this->assertEquals('Prusa', $newMaterial->brand);
        $this->assertEquals('17.95', $newMaterial->cost);
        $this->assertEquals('Red', $newMaterial->color);
        $this->assertNull($newMaterial->weights);
    }

    /** @test */
    public function can_add_current_weight_to_a_material()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $material = Material::factory()->for($user->currentTeam)->create([
            'brand' => 'Prusa',
            'cost' => '17.95',
            'color' => 'Red',
            'color_hex' => '#FF0000',
            'diameter' => '1.75',
            'empty' => 256,
            'weights' => [['weight' => 1000, 'timestamp' => now()]],
        ]);

        Livewire::actingAs($user)->test(Table::class)
            ->mountTableAction('add_current_weight', $material)
            ->setTableActionData([
                'current_weight' => 750,
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        $material->refresh();
        $this->assertCount(2, $material->weights);
        $this->assertEquals(494, $material->current_weight);
    }
}
