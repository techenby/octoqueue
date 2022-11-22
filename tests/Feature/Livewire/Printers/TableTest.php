<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Table;
use App\Models\Printer;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_all_printers_for_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printers = Printer::factory()->for($user->currentTeam)->count(5)->createQuietly();

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200)
            ->assertCanSeeTableRecords($printers);
    }

    /** @test */
    public function cannot_view_all_printers_for_different_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printers = Printer::factory()->for(Team::factory())->count(5)->createQuietly();

        Livewire::actingAs($user)->test(Table::class)
            ->assertStatus(200)
            ->assertCanNotSeeTableRecords($printers);
    }
}
