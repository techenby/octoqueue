<?php

namespace Tests\Feature\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource\Pages\EditPrinter;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditPrinterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_edit_printer(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create([
            'name' => 'Pikachu',
            'model' => 'Ender 3',
            'url' => 'http://pikachu.local',
            'api_key' => 'pika-pika',
        ]);

        Livewire::actingAs($user)
            ->test(EditPrinter::class, ['record' => $printer->id])
            ->fillForm([
                'name' => 'Eevee',
                'model' => 'Ender 3',
                'url' => 'http://eevee.local',
                'api_key' => 'vee-vee',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $printer->refresh();
        $this->assertEquals('Eevee', $printer->name);
        $this->assertEquals('http://eevee.local', $printer->url);
        $this->assertEquals('vee-vee', $printer->api_key);
    }
}
