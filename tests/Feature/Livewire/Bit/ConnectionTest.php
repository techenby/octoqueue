<?php

namespace Tests\Feature\Livewire\Bit;

use App\Http\Livewire\Bit\Connection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ConnectionTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Connection::class);

        $component->assertStatus(200);
    }
}
