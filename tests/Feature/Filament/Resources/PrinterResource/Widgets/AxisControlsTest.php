<?php

namespace Tests\Feature\Filament\Resources\PrinterResource;

use App\Filament\Resources\PrinterResource\Widgets\AxisControls;
use App\Models\Printer;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class AxisControlsTest extends TestCase
{
    use RefreshDatabase;

    public $printer;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        Http::fake([
            'bulbasaur.local/api/printer/printhead' => Http::response([], 204),
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
    public function can_move_x_positively()
    {
        Livewire::actingAs($this->user)
            ->test(AxisControls::class, ['record' => $this->printer])
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
            ->test(AxisControls::class, ['record' => $this->printer])
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
            ->test(AxisControls::class, ['record' => $this->printer])
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
            ->test(AxisControls::class, ['record' => $this->printer])
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
            ->test(AxisControls::class, ['record' => $this->printer])
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
            ->test(AxisControls::class, ['record' => $this->printer])
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
            ->test(AxisControls::class, ['record' => $this->printer])
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
            ->test(AxisControls::class, ['record' => $this->printer])
            ->call('home', ['z']);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'home' &&
                $request['axes'] == ['z'];
        });
    }
}
