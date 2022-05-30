<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Form;
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

        Livewire::actingAs($user)->test(Form::class)
            ->assertStatus(200);
    }

    /** @test */
    public function cannot_save_if_no_connection_before_saving()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)->test(Form::class)
            ->set('printer.name', 'Pikachu')
            ->set('printer.model', 'Ender 3 Pro')
            ->set('printer.url', 'http://octoprint-bad.local')
            ->set('printer.api_key', 'ABC123DEF456')
            ->call('save')
            ->assertRedirect('/printers');
    }
}
