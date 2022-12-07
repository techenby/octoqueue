<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Temps;
use App\Models\Printer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class TempsTest extends TestCase
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

    /** @test */
    public function the_component_can_render()
    {
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
            ->assertStatus(200)
            ->assertSee('bed')
            ->assertSee('tool0')
            ->assertSee('tool1');
    }

    /** @test */
    public function can_set_bed_temp()
    {
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/bed' => Http::response([], 204),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
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
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/tool' => Http::response([], 204),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
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
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/bed' => Http::response([], 204),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
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
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/tool' => Http::response([], 204),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
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
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/bed' => Http::response([], 204),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
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
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/tool' => Http::response([], 204),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
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
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/bed' => Http::response([], 204),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
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
        Http::fake([
            'bulbasaur.local/api/printer' => Http::response($this->printerResponse),
            'bulbasaur.local/api/printer/tool' => Http::response([], 204),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Temps::class, ['printer' => $printer])
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
