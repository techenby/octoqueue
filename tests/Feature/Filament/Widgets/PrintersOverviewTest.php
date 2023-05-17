<?php

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Widgets\PrintersOverview;
use App\Models\Material;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PrintersOverviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_printers_that_do_not_have_materials_assigned()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $material = Material::factory()->for($user->currentTeam)->create();
        $printerA = Printer::factory()->for($material)->for($user->currentTeam)->createQuietly();
        $printerB = Printer::factory()->for($user->currentTeam)->createQuietly();

        Livewire::actingAs($user)
            ->test(PrintersOverview::class)
            ->assertCanSeeTableRecords([$printerB])
            ->assertCanSeeTableRecords([$printerA]);
    }
}
