<?php

namespace Tests\Feature\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource\Pages\ListPrinters;
use App\Models\Printer;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListPrintersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_all_printers_for_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printers = Printer::factory()
            ->for($user->currentTeam)
            ->count(5)
            ->createQuietly();

        Livewire::actingAs($user)->test(ListPrinters::class)
            ->assertStatus(200)
            ->assertCanSeeTableRecords($printers);
    }

    /** @test */
    public function cannot_view_all_printers_for_different_team()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printers = Printer::factory()->for(Team::factory())->count(5)->createQuietly();

        Livewire::actingAs($user)->test(ListPrinters::class)
            ->assertStatus(200)
            ->assertCanNotSeeTableRecords($printers);
    }

    /** @test */
    public function can_bulk_delete()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printers = Printer::factory()->for($user->currentTeam)->count(5)->createQuietly();

        Livewire::actingAs($user)
            ->test(ListPrinters::class)
            ->callTableBulkAction('delete', $printers)
            ->assertHasNoTableActionErrors();

        $this->assertEmpty($printers->fresh());
    }
}
