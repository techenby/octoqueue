<?php

namespace Tests\Feature\Livewire\Dashboard;

use App\Http\Livewire\Dashboard\ConnectionIssues;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ConnectionIssuesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_only_printers_with_bad_status_are_seen()
    {
        $user = User::factory()->withPersonalTeam()->create();
        Printer::factory()
            ->count(3)
            ->for($user->currentTeam)
            ->state(new Sequence(
                ['name' => 'Bulbasaur', 'status' => 'offline'],
                ['name' => 'Charmander', 'status' => 'error'],
                ['name' => 'Squirtle', 'status' => 'online'],
            ))
            ->createQuietly();

        Livewire::actingAs($user)
            ->test(ConnectionIssues::class)
            ->assertStatus(200)
            ->assertSee('Bulbasaur')
            ->assertSee('Charmander')
            ->assertDontSee('Squirtle');
    }
}
