<?php

namespace Tests\Feature\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource\Pages\CreatePrinter;
use App\Jobs\FetchPrinterStatus;
use App\Jobs\FetchPrinterTools;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;
use Tests\TestCase;

class CreatePrinterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_printer()
    {
        Bus::fake();

        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(CreatePrinter::class)
            ->fillForm([
                'name' => 'Pikachu',
                'model' => 'Ender 3',
                'url' => 'http://pikachu.local',
                'api_key' => 'pika-pika',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('printers', [
            'team_id' => $user->currentTeam->id,
            'name' => 'Pikachu',
            'model' => 'Ender 3',
            'url' => 'http://pikachu.local',
            // 'api_key' => 'pika-pika', // commented out because the API key is encrypted
        ]);

        Bus::assertChained([
            FetchPrinterStatus::class,
            FetchPrinterTools::class,
        ]);
    }
}
