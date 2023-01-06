<?php

namespace Tests\Feature\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource\Pages\CreateJob;
use App\Models\Material;
use App\Models\Printer;
use App\Models\PrintType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class CreateJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * This was taken from the OctoPrint documentation on 2023-01-03
     * https://docs.octoprint.org/en/master/api/files.html#get--api-files
     */
    public $filesResponse = [
        'files' => [
            [
                'name' => 'whistle_v2.gcode',
                'path' => 'whistle_v2.gcode',
                'type' => 'machinecode',
                'typePath' => ['machinecode', 'gcode'],
                'hash' => '...',
                'size' => 1468987,
                'date' => 1378847754,
                'origin' => 'local',
                'refs' => [
                    'resource' => 'http://example.com/api/files/local/whistle_v2.gcode',
                    'download' => 'http://example.com/downloads/files/local/whistle_v2.gcode',
                ],
                'gcodeAnalysis' => [
                    'estimatedPrintTime' => 1188,
                    'filament' => [
                        'length' => 810,
                        'volume' => 5.36,
                    ],
                ],
                'print' => [
                    'failure' => 4,
                    'success' => 23,
                    'last' => [
                        'date' => 1387144346,
                        'success' => true,
                    ],
                ],
            ],
            [
                'name' => 'whistle_.gco',
                'path' => 'whistle_.gco',
                'type' => 'machinecode',
                'typePath' => ['machinecode', 'gcode'],
                'origin' => 'sdcard',
                'refs' => [
                    'resource' => 'http://example.com/api/files/sdcard/whistle_.gco',
                ],
            ],
            [
                'name' => 'folderA',
                'path' => 'folderA',
                'type' => 'folder',
                'typePath' => ['folder'],
                'children' => [
                    [
                        'name' => 'whistle_v2_copy.gcode',
                        'path' => 'whistle_v2_copy.gcode',
                        'type' => 'machinecode',
                        'typePath' => ['machinecode', 'gcode'],
                        'hash' => '...',
                        'size' => 1468987,
                        'date' => 1378847754,
                        'origin' => 'local',
                        'refs' => [
                            'resource' => 'http://example.com/api/files/local/folderA/whistle_v2_copy.gcode',
                            'download' => 'http://example.com/downloads/files/local/folderA/whistle_v2_copy.gcode',
                        ],
                        'gcodeAnalysis' => [
                            'estimatedPrintTime' => 1188,
                            'filament' => [
                                'length' => 810,
                                'volume' => 5.36,
                            ],
                        ],
                        'print' => [
                            'failure' => 4,
                            'success' => 23,
                            'last' => [
                                'date' => 1387144346,
                                'success' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'free' => '3.2GB',
    ];

    /** @test */
    public function can_create_job_with_existing_file()
    {
        Http::fake([
            'bulbasaur.local/*' => Http::response($this->filesResponse),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        $type = PrintType::factory()->for($user->currentTeam)->create();
        $material = Material::factory()->for($user->currentTeam)->create();
        $printer = Printer::factory()->for($user->currentTeam)->createQuietly([
            'url' => 'http://bulbasaur.local',
        ]);

        Livewire::actingAs($user)
            ->test(CreateJob::class)
            ->fillForm([
                'name' => 'Whistle',
                'print_type_id' => $type->id,
                'color_hex' => $material->color_hex,
                'notes' => 'New design',
                'files' => [
                    [
                        'printer' => $printer->id,
                        'file' => 'whistle_v2.gcode',
                    ]
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('jobs', [
            'team_id' => $user->currentTeam->id,
            'user_id' => $user->id,
            'name' => 'Whistle',
            'print_type_id' => $type->id,
            'color_hex' => $material->color_hex,
            'notes' => 'New design',
            'files' => json_encode([
                ['printer' => $printer->id, 'file' => 'whistle_v2.gcode'],
            ]),
        ]);
    }
}
