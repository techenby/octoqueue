<?php

namespace Tests\Feature\Livewire\Dashboard;

use App\Http\Livewire\Dashboard\CurrentlyPrinting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CurrentlyPrintingTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(CurrentlyPrinting::class);

        $component->assertStatus(200);
    }
}
