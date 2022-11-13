<?php

namespace Tests\Feature\Livewire\Materials;

use App\Http\Livewire\Materials\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Form::class);

        $component->assertStatus(200);
    }
}
