<?php

namespace Tests\Feature\Livewire\Filaments;

use App\Http\Livewire\Filaments\Table;
use App\Models\Filament;
use App\Models\Team;
use App\Models\User;
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
        $filaments = Filament::factory()->for($user->currentTeam)->count(5)->create();

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200)
            ->assertCanSeeTableRecords($filaments);
    }

    /** @test */
    public function cannot_view_all_filaments_for_different_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $filaments = Filament::factory()->for(Team::factory())->count(5)->create();

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200)
            ->assertCanNotSeeTableRecords($filaments);
    }
}
