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

    public $printer;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/bed' => Http::response([], 204),
            'bulbasaur.local/api/printer/printhead' => Http::response([], 204),
            'bulbasaur.local/api/printer/tool' => Http::response([], 204),
            'bulbasaur.local/api/connection' => Http::response($this->connectionResponse),
        ]);

        $this->user = User::factory()->withPersonalTeam()->create();
        $this->printer = Printer::factory()
            ->for($this->user->currentTeam)
            ->has(Tool::factory())
            ->createQuietly([
                'url' => 'http://bulbasaur.local',
                'api_key' => 'TEST-API-KEY',
            ]);
    }

    /** @test */
    public function the_component_can_render()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200);
    }

    /** @test */
    public function can_delete_pritner()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->call('deletePrinter')
            ->assertRedirect(route('printers'));

        $this->assertDatabaseMissing('printers', ['id' => $this->printer->id]);
        $this->assertDatabaseMissing('tools', ['printer_id' => $this->printer->id]);
    }

    // Axis Control Tests

    /** @test */
    public function can_move_x_positively()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
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
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
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
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
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
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
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
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
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
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
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
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
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
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->call('home', ['z']);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'home' &&
                $request['axes'] == ['z'];
        });
    }

    // Temps Tests

    /** @test */
    public function can_set_bed_temp()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200)
            ->set('temps.bed.target', 60)
            ->call('setTarget', 'bed');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/bed' &&
                $request['command'] == 'target' &&
                $request['target'] == '60';
        });
    }

    /** @test */
    public function can_set_tool_temp()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200)
            ->set('temps.tool0.target', 210)
            ->call('setTarget', 'tool0');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/tool' &&
                $request['command'] == 'target' &&
                $request['targets'] == ['tool0' => 210];
        });
    }

    /** @test */
    public function can_clear_bed_temp()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200)
            ->set('temps.bed.target', 60)
            ->call('clear', 'bed', 'target')
            ->assertSet('temps.bed.target', 0);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/bed' &&
                $request['command'] == 'target' &&
                $request['target'] == '0';
        });
    }

    /** @test */
    public function can_clear_tool_temp()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200)
            ->set('temps.tool0.target', 210)
            ->call('clear', 'tool0', 'target')
            ->assertSet('temps.tool0.target', 0);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/tool' &&
                $request['command'] == 'target' &&
                $request['targets'] == ['tool0' => 0];
        });
    }

    /** @test */
    public function can_set_bed_offset()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200)
            ->set('temps.bed.offset', 5)
            ->call('setOffset', 'bed');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/bed' &&
                $request['command'] == 'offset' &&
                $request['offset'] == 5;
        });
    }

    /** @test */
    public function can_set_tool_offset()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200)
            ->set('temps.tool0.offset', -5)
            ->call('setOffset', 'tool0');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/tool' &&
                $request['command'] == 'offset' &&
                $request['offsets'] == ['tool0' => -5];
        });
    }

    /** @test */
    public function can_clear_bed_offset()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200)
            ->set('temps.bed.offset', 5)
            ->call('clear', 'bed', 'offset')
            ->assertSet('temps.bed.offset', 0);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/bed' &&
                $request['command'] == 'offset' &&
                $request['offset'] == '0';
        });
    }

    /** @test */
    public function can_clear_tool_offset()
    {
        Livewire::actingAs($this->user)
            ->test(Show::class, ['printer' => $this->printer])
            ->assertStatus(200)
            ->set('temps.tool0.offset', -5)
            ->call('clear', 'tool0', 'offset')
            ->assertSet('temps.tool0.offset', 0);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/tool' &&
                $request['command'] == 'offset' &&
                $request['offsets'] == ['tool0' => 0];
        });
    }
}
