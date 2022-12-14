<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Tool;
use App\Models\Printer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Livewire;
use Tests\TestCase;

class ToolTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        Http::fake();

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);

        Livewire::test(Tool::class, ['printer' => $printer])
            ->assertStatus(200)
            ->assertSee('Amount')
            ->assertSee('Sign');
    }
}
