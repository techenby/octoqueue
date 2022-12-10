<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Connection;
use App\Models\Printer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class ConnectionTest extends TestCase
{
    use RefreshDatabase;

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

    /** @test */
    public function the_component_can_render()
    {
        Http::fake([
            'bulbasaur.local/api/connection' => Http::response($this->connectionResponse, 200),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
        ]);

        Livewire::test(Connection::class, ['printer' => $printer])
            ->assertStatus(200)
            ->assertSet('baudrate', 250000)
            ->assertSet('port', '/dev/ttyACM0')
            ->assertSet('printerProfile', '_default')
            ->assertSet('save', false)
            ->assertSet('autoconnect', true);
    }

    /** @test */
    public function can_connect_to_printer()
    {
        Queue::fake();
        Http::fake([
            'bulbasaur.local/api/connection' => Http::response($this->connectionResponse, 200),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Connection::class, ['printer' => $printer])
            ->assertStatus(200)
            ->fillForm([
                'baudrate' => '230400',
                'port' => 'VIRTUAL',
                'printerProfile' => '_default',
                'save' => true,
                'autoconnect' => true,
            ])
            ->call('submit');

        Http::recorded(function (Request $request) {
            if ($request->method() === 'POST') {
                return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                    $request->url() == 'http://bulbasaur.local/api/connection' &&
                    $request['command'] == 'connect' &&
                    $request['baudrate'] == '230400' &&
                    $request['port'] == 'VIRTUAL' &&
                    $request['printerProfile'] == '_default' &&
                    $request['save'] == true &&
                    $request['autoconnect'] == true;
            }
        });
    }

    /** @test */
    public function can_disconect_from_printer()
    {
        Queue::fake();
        Http::fake([
            'bulbasaur.local/api/connection' => Http::response($this->connectionResponse, 200),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Connection::class, ['printer' => $printer])
            ->assertStatus(200)
            ->call('disconnect');

        Http::recorded(function (Request $request) {
            if ($request->method() === 'POST') {
                return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                    $request->url() == 'http://bulbasaur.local/api/connection' &&
                    $request['command'] == 'disconnect';
            }
        });
    }

    /** @test */
    public function saved_preferences_are_loaded_into_form_when_connecting()
    {
        Http::fake([
            'bulbasaur.local/api/connection' => Http::response($this->closedConnectionResponse, 200),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Connection::class, ['printer' => $printer])
            ->assertStatus(200)
            ->assertSet('baudrate', 250000)
            ->assertSet('port', '/dev/ttyACM0')
            ->assertSet('printerProfile', '_default');
    }

    /** @test */
    public function inputs_are_disabled_when_printer_is_connected()
    {
        // assertFormFieldIsDisabled
        Http::fake([
            'bulbasaur.local/api/connection' => Http::response($this->closedConnectionResponse, 200),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
            'status' => 'operational',
        ]);

        Livewire::test(Connection::class, ['printer' => $printer])
            ->assertStatus(200)
            ->assertFormFieldIsDisabled('baudrate')
            ->assertFormFieldIsDisabled('port')
            ->assertFormFieldIsDisabled('printerProfile')
            ->assertFormFieldIsDisabled('save')
            ->assertFormFieldIsDisabled('autoconnect');
    }
}
