<?php

namespace Tests\Feature\Livewire\PrintJobs;

use App\Http\Livewire\PrintJobs\Form;
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
