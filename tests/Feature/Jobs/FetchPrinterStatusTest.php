<?php

namespace Tests\Feature\Jobs;

use App\Jobs\FetchPrinterStatus;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchPrinterStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function printer_status_is_operational_if_accessable_and_connected()
    {
        Http::preventStrayRequests();

        Http::fake([
            'bulbasaur.local/api/connection' => Http::response([
                'current' => [
                    'state' => 'Operational',
                    'port' => '/dev/ttyACM0',
                    'baudrate' => 250000,
                    'printerProfile' => '_default',
                ],
                'options' => [
                    'ports' => ['/dev/ttyACM0', 'VIRTUAL'],
                    'baudrates' => [250000, 230400, 115200, 57600, 38400, 19200, 9600],
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
            ]),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()
            ->for($user->currentTeam)
            ->createQuietly([
                'url' => 'http://bulbasaur.local',
                'api_key' => 'TEST_API_KEY',
            ]);

        FetchPrinterStatus::dispatch($printer);

        $this->assertEquals('operational', $printer->fresh()->status);
    }

    /** @test */
    public function printer_status_is_closed_if_accessable_but_not_connected()
    {
        Http::fake([
            'bulbasaur.local/api/connection' => Http::response([
                'current' => [
                    'state' => 'Closed',
                    'port' => null,
                    'baudrate' => null,
                    'printerProfile' => '_default',
                ],
                'options' => [
                    'ports' => ['/dev/ttyACM0', 'VIRTUAL'],
                    'baudrates' => [250000, 230400, 115200, 57600, 38400, 19200, 9600],
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
            ]),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST_API_KEY',
        ]);

        FetchPrinterStatus::dispatch($printer);

        $this->assertEquals('closed', $printer->fresh()->status);
    }

    /** @test */
    public function printer_status_is_error_if_url_is_accessable_but_bad_api_key()
    {
        Http::fake([
            'bulbasaur.local/api/connection' => Http::response([
                'error' => 'Invalid API key',
            ], 403),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'BAD_API_KEY',
        ]);

        FetchPrinterStatus::dispatch($printer);

        $this->assertEquals('error', $printer->fresh()->status);
    }

    /** @test */
    public function printer_status_is_offline_if_url_is_inaccessable()
    {
        Http::fake([
            'bulbasaur.local/api/connection' => Http::response([
                'error' => 'Invalid API key',
            ], 500),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'BAD_API_KEY',
        ]);

        FetchPrinterStatus::dispatch($printer);

        $this->assertEquals('error', $printer->fresh()->status);
    }
}
