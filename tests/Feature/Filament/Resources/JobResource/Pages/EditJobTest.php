<?php

namespace Tests\Feature\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource\Pages\EditJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_edit_job()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $jobs = Job::factory()->for($user->currentTeam)->create([
            'name' => 'Whistle',

        ]);
        // create job

        Livewire::actingAs($user)
            ->test(EditJob::class, ['record' => $job->id])
            ->fillForm([
                //
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $job->refresh();
        $this->assertEquals('Eevee', $printer->name);
    }
}
