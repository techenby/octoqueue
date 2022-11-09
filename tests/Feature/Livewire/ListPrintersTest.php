<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ListPrinters;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ListPrintersTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(ListPrinters::class);

        $component->assertStatus(200);
    }
}
