<?php

namespace Tests\Feature\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource\Pages\CreateJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_job()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(CreateJob::class)
            ->fillForm([
                //
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('jobs', [
            //
        ]);
    }
}
