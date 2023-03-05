<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;
use Tests\TestCase;

class LeaveTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_leave_teams(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                        ->call('leaveTeam');

        $this->assertCount(0, $user->currentTeam->fresh()->users);
    }

    /**
     * @test
     */
    public function team_owners_cant_leave_their_own_team(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                        ->call('leaveTeam')
                        ->assertHasErrors(['team']);

        $this->assertNotNull($user->currentTeam->fresh());
    }
}
