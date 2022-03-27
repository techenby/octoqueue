<?php

namespace Tests\Feature\Livewire\PrintJobs;

use App\Http\Livewire\PrintJobs\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $component = Livewire::actingAs($user)->test(Form::class);

        $component->assertStatus(200);
    }
}
