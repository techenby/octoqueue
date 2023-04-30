<?php

namespace Tests\Feature\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource\Pages\ViewPrinter;
use App\Jobs\FetchPrinterStatus;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class ViewPrinterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * This was taken from the OctoPrint documentation on 2022-12-06
     * https://docs.octoprint.org/en/master/api/printer.html#get--api-printer
     */
    public $printerResponse = [
        'temperature' => [
            'tool0' => [
                'actual' => 214.8821,
                'target' => 220.0,
                'offset' => 0,
            ],
            'tool1' => [
                'actual' => 25.3,
                'target' => null,
                'offset' => 0,
            ],
            'bed' => [
                'actual' => 50.221,
                'target' => 70.0,
                'offset' => 5,
            ],
        ],
        'sd' => [
            'ready' => true,
        ],
        'state' => [
            'text' => 'Operational',
            'flags' => [
                'operational' => true,
                'paused' => false,
                'printing' => false,
                'cancelling' => false,
                'pausing' => false,
                'sdReady' => true,
                'error' => false,
                'ready' => true,
                'closedOrError' => false,
            ],
        ],
    ];

    /**
     * This was taken from the OctoPrint documentation on 2022-12-10
     * https://docs.octoprint.org/en/master/api/connection.html#get--api-connection
     */
    public $connectionResponse = [
        'current' => [
            'state' => 'Operational',
            'port' => '/dev/ttyACM0',
            'baudrate' => 250000,
            'printerProfile' => '_default',
        ],
        'options' => [
            'ports' => ['/dev/ttyACM0', 'VIRTUAL'],
            'baudrates' => [
                250000,
                230400,
                115200,
                57600,
                38400,
                19200,
                9600,
            ],
            'printerProfiles' => [
                [
                    'name' => 'Default',
                    'id' => '_default',
                ],
            ],
            'portPreference' => '/dev/ttyACM0',
            'baudratePreference' => 250000,
            'printerProfilePreference' => '_default',
            'autoconnect' => true,
        ],
    ];

    public $closedConnectionResponse = [
        'current' => [
            'baudrate' => null,
            'port' => null,
            'printerProfile' => '_default',
            'state' => 'Closed',
        ],
        'options' => [
            'ports' => ['/dev/ttyACM0', 'VIRTUAL'],
            'baudrates' => [
                250000,
                230400,
                115200,
                57600,
                38400,
                19200,
                9600,
            ],
            'printerProfiles' => [
                [
                    'name' => 'Default',
                    'id' => '_default',
                ],
            ],
            'portPreference' => '/dev/ttyACM0',
            'baudratePreference' => 250000,
            'printerProfilePreference' => '_default',
            'autoconnect' => true,
        ],
    ];

    public $printer;

    public $user;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/bed' => Http::response([], 204),
            'bulbasaur.local/api/printer/command' => Http::response([], 204),
            'bulbasaur.local/api/printer/printhead' => Http::response([], 204),
            'bulbasaur.local/api/printer/tool' => Http::response([], 204),
            'bulbasaur.local/api/connection' => Http::response($this->connectionResponse),
        ]);

        $this->user = User::factory()->withPersonalTeam()->create();
        $this->printer = Printer::factory()
            ->for($this->user->currentTeam)
            ->createQuietly([
                'url' => 'http://bulbasaur.local',
                'api_key' => 'TEST-API-KEY',
                'status' => 'operational',
            ]);
    }

    /** @test */
    public function the_component_can_render(): void
    {
        Livewire::actingAs($this->user)
            ->test(ViewPrinter::class, ['record' => $this->printer->id])
            ->assertStatus(200);
    }

    /** @test */
    public function can_fetch_printer_status(): void
    {
        Queue::fake();

        Livewire::actingAs($this->user)
            ->test(ViewPrinter::class, ['record' => $this->printer->id])
            ->assertSuccessful()
            ->callPageAction('fetch_status')
            ->assertHasNoPageActionErrors();

        Queue::assertPushed(FetchPrinterStatus::class);
    }

    /** @test */
    public function can_delete_pritner(): void
    {
        Livewire::actingAs($this->user)
            ->test(ViewPrinter::class, ['record' => $this->printer->id])
            ->callPageAction('delete');

        $this->assertDatabaseMissing('printers', ['id' => $this->printer->id]);
    }
}
