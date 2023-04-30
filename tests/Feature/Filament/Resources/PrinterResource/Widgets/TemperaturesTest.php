<?php

namespace Tests\Feature\Filament\Resources\PrinterResource\Widgets;

use App\Filament\Resources\PrinterResource\Widgets\GeneralControls;
use App\Filament\Resources\PrinterResource\Widgets\Temperatures;
use App\Models\Printer;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class TemperaturesTest extends TestCase
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
    public function can_set_bed_temp(): void
    {
        Livewire::actingAs($this->user)
            ->test(Temperatures::class, ['record' => $this->printer])
            ->assertStatus(200)
            ->set('temperatures.bed.target', 60)
            ->call('setTarget', 'bed');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/bed' &&
                $request['command'] == 'target' &&
                $request['target'] == '60';
        });
    }

    /** @test */
    public function can_set_tool_temp(): void
    {
        Livewire::actingAs($this->user)
            ->test(Temperatures::class, ['record' => $this->printer])
            ->assertStatus(200)
            ->set('temperatures.tool0.target', 210)
            ->call('setTarget', 'tool0');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/tool' &&
                $request['command'] == 'target' &&
                $request['targets'] == ['tool0' => 210];
        });
    }

    /** @test */
    public function can_clear_bed_temp(): void
    {
        Livewire::actingAs($this->user)
            ->test(Temperatures::class, ['record' => $this->printer])
            ->assertStatus(200)
            ->set('temperatures.bed.target', 60)
            ->call('clear', 'bed', 'target')
            ->assertSet('temperatures.bed.target', 0);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/bed' &&
                $request['command'] == 'target' &&
                $request['target'] == '0';
        });
    }

    /** @test */
    public function can_clear_tool_temp(): void
    {
        Livewire::actingAs($this->user)
            ->test(Temperatures::class, ['record' => $this->printer])
            ->assertStatus(200)
            ->set('temperatures.tool0.target', 210)
            ->call('clear', 'tool0', 'target')
            ->assertSet('temperatures.tool0.target', 0);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/tool' &&
                $request['command'] == 'target' &&
                $request['targets'] == ['tool0' => 0];
        });
    }

    /** @test */
    public function can_set_bed_offset(): void
    {
        Livewire::actingAs($this->user)
            ->test(Temperatures::class, ['record' => $this->printer])
            ->assertStatus(200)
            ->set('temperatures.bed.offset', 5)
            ->call('setOffset', 'bed');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/bed' &&
                $request['command'] == 'offset' &&
                $request['offset'] == 5;
        });
    }

    /** @test */
    public function can_set_tool_offset(): void
    {
        Livewire::actingAs($this->user)
            ->test(Temperatures::class, ['record' => $this->printer])
            ->assertStatus(200)
            ->set('temperatures.tool0.offset', -5)
            ->call('setOffset', 'tool0');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/tool' &&
                $request['command'] == 'offset' &&
                $request['offsets'] == ['tool0' => -5];
        });
    }

    /** @test */
    public function can_clear_bed_offset(): void
    {
        Livewire::actingAs($this->user)
            ->test(Temperatures::class, ['record' => $this->printer])
            ->assertStatus(200)
            ->set('temperatures.bed.offset', 5)
            ->call('clear', 'bed', 'offset')
            ->assertSet('temperatures.bed.offset', 0);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/bed' &&
                $request['command'] == 'offset' &&
                $request['offset'] == '0';
        });
    }

    /** @test */
    public function can_clear_tool_offset(): void
    {
        Livewire::actingAs($this->user)
            ->test(Temperatures::class, ['record' => $this->printer])
            ->assertStatus(200)
            ->set('temperatures.tool0.offset', -5)
            ->call('clear', 'tool0', 'offset')
            ->assertSet('temperatures.tool0.offset', 0);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/tool' &&
                $request['command'] == 'offset' &&
                $request['offsets'] == ['tool0' => 0];
        });
    }

    // Tool Tests

    // General Tests

    /** @test */
    public function can_turn_motors_off(): void
    {
        Livewire::actingAs($this->user)
            ->test(GeneralControls::class, ['record' => $this->printer])
            ->call('motorsOff');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/command' &&
                $request['command'] == 'M18';
        });
    }

    /** @test */
    public function can_turn_fans_off(): void
    {
        Livewire::actingAs($this->user)
            ->test(GeneralControls::class, ['record' => $this->printer])
            ->call('fansOff');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/command' &&
                $request['command'] == 'M106 S0';
        });
    }

    /** @test */
    public function can_turn_fans_on(): void
    {
        Livewire::actingAs($this->user)
            ->test(GeneralControls::class, ['record' => $this->printer])
            ->call('fansOn');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/command' &&
                $request['command'] == 'M106 S255';
        });
    }
}
