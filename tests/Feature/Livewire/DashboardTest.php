<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Dashboard;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_printers_with_connection_issues()
    {
        $user = User::factory()->withPersonalTeam()->create();
        Printer::factory()->for($user->currentTeam)->createQuietly(['name' => 'Charmander', 'status' => 'error']);
        Printer::factory()->for($user->currentTeam)->createQuietly(['name' => 'Squirtle', 'status' => 'offline']);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Printers with Connection Issues',
                'Charmander',
                'Squirtle',
            ]);
    }

    /** @test */
    public function can_view_standby_printers()
    {
        $user = User::factory()->withPersonalTeam()->create();
        Printer::factory()->for($user->currentTeam)->createQuietly(['name' => 'Charmander', 'status' => 'operational']);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Printers on Standby',
                'Charmander',
            ]);
    }

    /** @test */
    public function can_view_printers_that_are_currently_printing()
    {
        Http::fake([
            'charmander.local/api/job' => Http::response([
                'job' => [
                    'file' => [
                        'name' => 'whistle_v2.gcode',
                        'origin' => 'local',
                        'size' => 1468987,
                        'date' => 1378847754,
                    ],
                    'estimatedPrintTime' => 8811,
                    'filament' => [
                        'tool0' => [
                            'length' => 810,
                            'volume' => 5.36,
                        ],
                    ],
                ],
                'progress' => [
                    'completion' => 22,
                    'filepos' => 337942,
                    'printTime' => 276,
                    'printTimeLeft' => 912,
                ],
                'state' => 'Printing',
            ]),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        Printer::factory()->for($user->currentTeam)
            ->createQuietly(['name' => 'Charmander', 'url' => 'http://charmander.local', 'status' => 'printing']);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Currently Printing',
                'Charmander',
                'whistle_v2.gcode',
                '22%',
                '15:12',
            ]);
    }
}
