<?php

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Resources\PrinterResource\Widgets\AxisControls;
use App\Filament\Widgets\AssignMaterials;
use App\Models\Material;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class AssignMaterialsTest extends TestCase
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
            ->test(AssignMaterials::class)
            ->assertCanSeeTableRecords([$printerB])
            ->assertCanNotSeeTableRecords([$printerA]);
    }
}
