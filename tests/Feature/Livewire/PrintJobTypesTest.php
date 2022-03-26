<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\PrintJobTypes;
use Livewire\Livewire;
use Tests\TestCase;

class PrintJobTypesTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(PrintJobTypes::class);

        $component->assertStatus(200);
    }
}
