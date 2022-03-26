<?php

namespace Tests\Feature\Livewire\Bit;

use App\Http\Livewire\Bit\Printer;
use Livewire\Livewire;
use Tests\TestCase;

class PrinterTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Printer::class);

        $component->assertStatus(200);
    }
}
