<?php

namespace Tests\Feature\Livewire\Spools;

use App\Http\Livewire\Spools\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class FormTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Form::class);

        $component->assertStatus(200);
    }
}
