<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Show;
use App\Models\Printer;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTest extends TestCase
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

    /** @test */
    public function the_component_can_render()
    {
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/bed' => Http::response([], 204),
            'bulbasaur.local/api/connection' => Http::response($this->connectionResponse),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->createQuietly([
            'url' => 'http://bulbasaur.local',
        ]);

        Livewire::actingAs($user)->test(Show::class, ['printer' => $printer])
            ->assertStatus(200);
    }

    /** @test */
    public function can_delete_pritner()
    {
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/bed' => Http::response([], 204),
            'bulbasaur.local/api/connection' => Http::response($this->connectionResponse),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()
            ->for($user->currentTeam)
            ->has(Tool::factory())
            ->createQuietly([
                'url' => 'http://bulbasaur.local',
            ]);

        Livewire::actingAs($user)->test(Show::class, ['printer' => $printer])
            ->call('deletePrinter')
            ->assertRedirect(route('printers'));

        $this->assertDatabaseMissing('printers', ['id' => $printer->id]);
        $this->assertDatabaseMissing('tools', ['printer_id' => $printer->id]);
    }

    // Axis Control Tests

    /** @test */
    public function can_move_x_positively()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Show::class, ['printer' => $printer])
            ->set('amount', '0.1')
            ->call('move', 'x');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'jog' &&
                $request['x'] == '0.1' &&
                $request['y'] == '0' &&
                $request['z'] == '0';
        });
    }

    /** @test */
    public function can_move_x_negatively()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Show::class, ['printer' => $printer])
            ->set('amount', '0.1')
            ->call('move', 'x', '-');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'jog' &&
                $request['x'] == '-0.1' &&
                $request['y'] == '0' &&
                $request['z'] == '0';
        });
    }

    /** @test */
    public function can_move_y_positively()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Show::class, ['printer' => $printer])
            ->set('amount', '1')
            ->call('move', 'y');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'jog' &&
                $request['x'] == '0' &&
                $request['y'] == '1' &&
                $request['z'] == '0';
        });
    }

    /** @test */
    public function can_move_y_negatively()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Show::class, ['printer' => $printer])
            ->set('amount', '1')
            ->call('move', 'y', '-');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'jog' &&
                $request['x'] == '0' &&
                $request['y'] == '-1' &&
                $request['z'] == '0';
        });
    }

    /** @test */
    public function can_move_z_positively()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Show::class, ['printer' => $printer])
            ->set('amount', '10')
            ->call('move', 'z');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'jog' &&
                $request['x'] == '0' &&
                $request['y'] == '0' &&
                $request['z'] == '10';
        });
    }

    /** @test */
    public function can_move_z_negatively()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Show::class, ['printer' => $printer])
            ->set('amount', '10')
            ->call('move', 'z', '-');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'jog' &&
                $request['x'] == '0' &&
                $request['y'] == '0' &&
                $request['z'] == '-10';
        });
    }

    /** @test */
    public function can_home_x_and_y()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Show::class, ['printer' => $printer])
            ->call('home', ['x', 'y']);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'home' &&
                $request['axes'] == ['x', 'y'];
        });
    }

    /** @test */
    public function can_home_z()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Show::class, ['printer' => $printer])
            ->call('home', ['z']);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'home' &&
                $request['axes'] == ['z'];
        });
    }
}
