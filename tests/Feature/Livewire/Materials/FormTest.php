<?php

namespace Tests\Feature\Livewire\Materials;

use App\Http\Livewire\Materials\Form;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_material()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->fillForm([
                'brand' => 'Prusament',
                'cost' => '28.50',
                'color' => 'Blue',
                'color_hex' => '#0000FF',
                'printer_type' => 'fdm',
                'type' => 'PLA',
                'diameter' => '1.75',
                'empty' => 250,
                'current_weight' => 1250,
            ])
            ->call('submit')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('materials', [
            'brand' => 'Prusament',
            'cost' => '28.50',
            'color' => 'Blue',
            'color_hex' => '#0000FF',
            'printer_type' => 'fdm',
            'type' => 'PLA',
            'diameter' => '1.75',
            'empty' => 250,
        ]);

        $material = Material::where('brand', 'Prusament')->where('team_id', $user->current_team_id)->first();
        $this->assertNotNull($material->weights);
        $this->assertEquals(1250, $material->weights->first()['weight']);
    }

    /** @test */
    public function can_edit_material()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $material = Material::factory()->for($user->currentTeam)->create([
            'brand' => 'Prusament',
            'cost' => '28.50',
            'color' => 'Blue',
            'color_hex' => '#0000FF',
            'printer_type' => 'fdm',
            'type' => 'PLA',
            'diameter' => '1.75',
            'empty' => 250,
            'weights' => [['weight' => 1250, 'timestamp' => now()->subDay()]],
        ]);

        Livewire::actingAs($user)
            ->test(Form::class, ['material' => $material])
            ->fillForm([
                'brand' => 'Inland',
                'cost' => '17.95',
                'color' => 'Blue',
                'color_hex' => '#0000FF',
                'printer_type' => 'fdm',
                'type' => 'PLA',
                'diameter' => '1.75',
                'empty' => 250,
                'current_weight' => 1000,
            ])
            ->call('submit')
            ->assertHasNoFormErrors();

        $material->refresh();
        $this->assertEquals('Inland', $material->brand);
        $this->assertEquals('17.95', $material->cost);
        $this->assertEquals(1000, $material->weights->last()['weight']);
    }

    /** @test */
    public function check_fields_are_hidden_on_render()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->assertFormFieldIsHidden('type')
            ->assertFormFieldIsHidden('diameter')
            ->assertFormFieldIsHidden('empty');
    }

    /** @test */
    public function when_printer_type_is_fdm_all_fields_are_visible()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->fillForm([
                'printer_type' => 'fdm',
            ])
            ->assertFormFieldIsVisible('type')
            ->assertFormFieldIsVisible('diameter')
            ->assertFormFieldIsVisible('empty');
    }

    /** @test */
    public function when_printer_type_is_sla_diameter_is_hidden()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->fillForm([
                'printer_type' => 'sla',
            ])
            ->assertFormFieldIsVisible('type')
            ->assertFormFieldIsHidden('diameter')
            ->assertFormFieldIsVisible('empty');
    }
}
