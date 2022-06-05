<?php

namespace Tests\Feature\Livewire\Bit;

use App\Http\Livewire\Bit\Pip;
use Livewire\Livewire;
use Tests\TestCase;

class PipTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Pip::class);

        $component->assertStatus(200);
    }
}
