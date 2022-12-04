<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Control;
use App\Models\Printer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ControlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $printer = Printer::factory()->createQuietly();

        Livewire::test(Control::class, ['printer' => $printer])
            ->assertStatus(200);
    }

    /** @test */
    public function can_move_x_positively()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly(['url' => 'http://bulbasaur.local', 'api_key' => 'TEST-API-KEY']);

        Livewire::test(Control::class, ['printer' => $printer])
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

        Livewire::test(Control::class, ['printer' => $printer])
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

        Livewire::test(Control::class, ['printer' => $printer])
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

        Livewire::test(Control::class, ['printer' => $printer])
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

        Livewire::test(Control::class, ['printer' => $printer])
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

        Livewire::test(Control::class, ['printer' => $printer])
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

        Livewire::test(Control::class, ['printer' => $printer])
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

        Livewire::test(Control::class, ['printer' => $printer])
            ->call('home', ['z']);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-API-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/printer/printhead' &&
                $request['command'] == 'home' &&
                $request['axes'] == ['z'];
        });
    }
}
