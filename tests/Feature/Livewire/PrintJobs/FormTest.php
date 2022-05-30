<?php

namespace Tests\Feature\Livewire\PrintJobs;

use App\Http\Livewire\PrintJobs\Form;
use App\Models\PrintJob;
use App\Models\PrintJobType;
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
    public function job_types_are_sorted_by_order()
    {
        $user = User::factory()->withPersonalTeam()->create();

        PrintJobType::factory()->for($user->currentTeam)->createMany([
            ['name' => 'Home', 'priority' => 3],
            ['name' => 'Fun', 'priority' => 2],
            ['name' => 'Access', 'priority' => 1],
        ]);

        Livewire::actingAs($user)
            ->test(Form::class)
            ->assertSeeHtmlInOrder(['Access', 'Fun', 'Home']);
    }

    /** @test */
    public function can_choose_any_color()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $type = PrintJobType::factory()->for($user->currentTeam)->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('job.color_hex', '')
            ->set('job.name', 'Dice')
            ->set('job.job_type_id', $type->id)
            ->call('save');

        $dice = PrintJob::where('team_id', $user->currentTeam->id)->where('name', 'Dice')->first();
        $this->assertNull($dice->color_hex);
    }
}
