<?php

namespace Tests\Feature\Filament\Resources\PrinterResource\Widgets;

use App\Filament\Resources\PrinterResource\Widgets\GeneralControls;
use App\Models\Printer;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class GeneralControlsTest extends TestCase
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
            'bulbasaur.local/api/printer/command' => Http::response([], 204),
        ]);

        $this->user = User::factory()->withPersonalTeam()->create();
        $this->printer = Printer::factory()
            ->for($this->user->currentTeam)
            ->has(Tool::factory())
            ->createQuietly([
                'url' => 'http://bulbasaur.local',
                'api_key' => 'TEST-API-KEY',
                'status' => 'operational',
            ]);
    }

    /** @test */
    public function can_turn_motors_off()
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
    public function can_turn_fans_off()
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
    public function can_turn_fans_on()
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
