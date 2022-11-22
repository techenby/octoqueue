<?php

namespace Tests\Feature\Livewire\Bit;

use App\Http\Livewire\Bit\AssignMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class AssignMaterialTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(AssignMaterial::class);

        $component->assertStatus(200);
    }
}
