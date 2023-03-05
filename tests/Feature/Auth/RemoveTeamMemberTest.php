<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;
use Tests\TestCase;

class RemoveTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function team_members_can_be_removed_from_teams(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                        ->set('teamMemberIdBeingRemoved', $otherUser->id)
                        ->call('removeTeamMember');

        $this->assertCount(0, $user->currentTeam->fresh()->users);
    }

    /**
     * @test
     */
    public function only_team_owner_can_remove_team_members(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                        ->set('teamMemberIdBeingRemoved', $user->id)
                        ->call('removeTeamMember')
                        ->assertStatus(403);
    }
}
