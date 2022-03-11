<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Spools;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SpoolsTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Spools::class);

        $component->assertStatus(200);
    }
}
