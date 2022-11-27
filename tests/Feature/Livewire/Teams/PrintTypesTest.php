<?php

namespace Tests\Feature\Livewire\Teams;

use App\Http\Livewire\Teams\PrintTypes;
use App\Models\PrintType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PrintTypesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $user = User::factory()->withPersonalTeam()->create();
        PrintType::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['name' => 'Fun', 'priority' => 3],
                ['name' => 'Work', 'priority' => 2],
                ['name' => 'Access', 'priority' => 1],
            ))
            ->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Access',
                'Work',
                'Fun',
            ]);
    }

    /** @test */
    public function can_move_type_up()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printTypes = PrintType::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['name' => 'Fun', 'priority' => 3],
                ['name' => 'Work', 'priority' => 2],
                ['name' => 'Access', 'priority' => 1],
            ))
            ->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->call('move', $printTypes->first()->id, 'up')
            ->assertEmitted('refresh');

        $this->assertEquals(2, $printTypes->first()->refresh()->priority);
    }

    /** @test */
    public function can_move_type_down()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printTypes = PrintType::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['name' => 'Fun', 'priority' => 3],
                ['name' => 'Work', 'priority' => 2],
                ['name' => 'Access', 'priority' => 1],
            ))
            ->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->call('move', $printTypes->last()->id, 'down')
            ->assertEmitted('refresh');

        $this->assertEquals(2, $printTypes->last()->refresh()->priority);
    }

    /** @test */
    public function cannot_move_first_priority_up()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printTypes = PrintType::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['name' => 'Fun', 'priority' => 3],
                ['name' => 'Work', 'priority' => 2],
                ['name' => 'Access', 'priority' => 1],
            ))
            ->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->call('move', $printTypes->last()->id, 'up')
            ->assertEmitted('refresh');

        $this->assertEquals(1, $printTypes->last()->refresh()->priority);
    }

    /** @test */
    public function cannot_move_last_priority_down()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printTypes = PrintType::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->state(new Sequence(
                ['name' => 'Fun', 'priority' => 3],
                ['name' => 'Work', 'priority' => 2],
                ['name' => 'Access', 'priority' => 1],
            ))
            ->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->call('move', $printTypes->first()->id, 'down')
            ->assertEmitted('refresh');

        $this->assertEquals(3, $printTypes->first()->refresh()->priority);
    }

    /** @test */
    public function can_add_type()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->set('newType', 'Fun')
            ->call('saveNewType')
            ->assertSet('newType', null)
            ->assertEmitted('refresh');

        $this->assertEquals(1, $user->currentTeam->printTypes->first()->priority);
    }

    /** @test */
    public function when_adding_type_priority_count_plus_one()
    {
        $user = User::factory()->withPersonalTeam()->create();
        PrintType::factory()
            ->for($user->currentTeam)
            ->count(3)
            ->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->set('newType', 'Fun')
            ->call('saveNewType')
            ->assertSet('newType', null)
            ->assertEmitted('refresh');

        $this->assertEquals(4, $user->currentTeam->printTypes->last()->priority);
    }

    /** @test */
    public function type_name_required_when_creating()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->set('newType', '')
            ->call('saveNewType')
            ->assertHasErrors(['newType' => 'required']);
    }

    /** @test */
    public function can_edit_type()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printType = PrintType::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->call('edit', $printType->id)
            ->set('editing.name', 'Hello')
            ->call('save')
            ->assertEmitted('refresh');

        $this->assertEquals('Hello', $printType->refresh()->name);
    }

    /** @test */
    public function type_name_required_when_editing()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printType = PrintType::factory()->for($user->currentTeam)->create([
            'name' => 'Hello',
        ]);

        Livewire::actingAs($user)
            ->test(PrintTypes::class)
            ->assertStatus(200)
            ->call('edit', $printType->id)
            ->set('editing.name', '')
            ->call('save')
            ->assertHasErrors(['editing.name' => 'required']);

        $this->assertEquals('Hello', $printType->refresh()->name);
    }
}
