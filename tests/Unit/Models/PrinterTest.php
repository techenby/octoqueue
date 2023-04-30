<?php

namespace Tests\Unit\Models;

use App\Jobs\FetchPrinterStatus;
use App\Models\Job;
use App\Models\Material;
use App\Models\Printer;
use App\Models\PrintType;
use App\Models\Team;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PrinterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * This was taken from the OctoPrint documentation on 2022-11-16
     * https://docs.octoprint.org/en/master/api/files.html#retrieve-all-files
     */
    private $filesResponse = [
        'files' => [
            0 => [
                'name' => 'whistle_v2.gcode',
                'path' => 'whistle_v2.gcode',
                'type' => 'machinecode',
                'typePath' => [
                    0 => 'machinecode',
                    1 => 'gcode',
                ],
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
            1 => [
                'name' => 'whistle_.gco',
                'path' => 'whistle_.gco',
                'type' => 'machinecode',
                'typePath' => [
                    0 => 'machinecode',
                    1 => 'gcode',
                ],
                'origin' => 'sdcard',
                'refs' => [
                    'resource' => 'http://example.com/api/files/sdcard/whistle_.gco',
                ],
            ],
            2 => [
                'name' => 'folderA',
                'path' => 'folderA',
                'type' => 'folder',
                'typePath' => [
                    0 => 'folder',
                ],
                'children' => [
                    0 => [
                        'name' => 'test.gcode',
                        'path' => 'folderA/test.gcode',
                        'type' => 'machinecode',
                        'typePath' => [
                            0 => 'machinecode',
                            1 => 'gcode',
                        ],
                        'hash' => '...',
                        'size' => 1234,
                        'date' => 1378847754,
                        'origin' => 'local',
                        'refs' => [
                            'resource' => 'http://example.com/api/files/local/folderA/test.gcode',
                            'download' => 'http://example.com/downloads/files/local/folderA/test.gcode',
                        ],
                    ],
                    1 => [
                        'name' => 'subfolder',
                        'path' => 'folderA/subfolder',
                        'type' => 'folder',
                        'typePath' => [
                            0 => 'folder',
                        ],
                        'children' => [
                            0 => [
                                'name' => 'test.gcode',
                                'path' => 'folderA/subfolder/test2.gcode',
                                'type' => 'machinecode',
                                'typePath' => [
                                    0 => 'machinecode',
                                    1 => 'gcode',
                                ],
                                'hash' => '...',
                                'size' => 100,
                                'date' => 1378847754,
                                'origin' => 'local',
                                'refs' => [
                                    'resource' => 'http://example.com/api/files/local/folderA/subfolder/test2.gcode',
                                    'download' => 'http://example.com/downloads/files/local/folderA/subfolder/test2.gcode',
                                ],
                            ],
                        ],
                        'size' => 100,
                        'refs' => [
                            'resource' => 'http://example.com/api/files/local/folderA/subfolder',
                        ],
                    ],
                ],
                'size' => 1334,
                'refs' => [
                    'resource' => 'http://example.com/api/files/local/folderA',
                ],
            ],
        ],
        'free' => '3.2GB',
    ];

    /**
     * This was taken from the OctoPrint documentation on 2022-11-27
     * https://docs.octoprint.org/en/master/api/job.html#get--api-job
     */
    private $jobResponse = [
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
            'completion' => 0.2298468264184775,
            'filepos' => 337942,
            'printTime' => 276,
            'printTimeLeft' => 912,
        ],
        'state' => 'Printing',
    ];

    /** @test */
    public function can_cancel_print(): void
    {
        Queue::fake();
        Http::preventStrayRequests();

        Http::fake();

        $printer = Printer::factory()->make([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $printer->cancel();

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/job' &&
                $request['command'] == 'cancel';
        });

        Queue::assertPushed(FetchPrinterStatus::class);
    }

    /** @test */
    public function when_cancelling_job_is_marked_as_failed_and_duplicated(): void
    {
        Queue::fake();

        Http::fake([
            'http://bulbasaur.local/api/job' => Http::response($this->jobResponse),
        ]);

        $team = Team::factory()->create();
        $printer = Printer::factory()->for($team)->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);
        $material = Material::factory()->for($team)->create(['type' => 'PLA', 'diameter' => 1.75]);

        $job = Job::factory()->for($team)->create([
            'printer_id' => $printer->id,
            'material_id' => $material->id,
            'name' => 'Whistle',
            'color_hex' => '#FFFF00',
            'files' => [
                ['file' => 'whistle_v2.gcode', 'printer' => $printer->id],
            ],
            'started_at' => now()->subSeconds(276),
        ]);

        $printer->cancel();

        $this->assertNotNull($job->fresh()->failed_at);
        $this->assertNotNull(Job::where('id', '<>', $job->id)->where('name', 'Whistle')->first());
    }

    /** @test */
    public function can_get_what_is_currently_printing(): void
    {
        Http::fake([
            'bulbasaur.local/api/job' => Http::response($this->jobResponse),
        ]);

        $printer = Printer::factory()->make([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $job = $printer->currentlyPrinting();

        $this->assertEquals('whistle_v2.gcode', $job['job']['file']['name']);
        $this->assertEquals('Printing', $job['state']);
    }

    /** @test */
    public function can_get_files(): void
    {
        Http::preventStrayRequests();

        Http::fake([
            'bulbasaur.local/api/files?recursive=1' => Http::response($this->filesResponse),
        ]);

        $printer = Printer::factory()->make([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $files = $printer->files();

        $this->assertCount(3, $files);
    }

    /** @test */
    public function can_pause_print(): void
    {
        Queue::fake();
        Http::preventStrayRequests();

        Http::fake();

        $printer = Printer::factory()->make([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $printer->pause();

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/job' &&
                $request['command'] == 'pause' &&
                $request['action'] == 'pause';
        });

        Queue::assertPushed(FetchPrinterStatus::class);
    }

    /** @test */
    public function can_get_list_of_printable_files(): void
    {
        Http::preventStrayRequests();

        Http::fake([
            'bulbasaur.local/api/files?recursive=1' => Http::response($this->filesResponse),
        ]);

        $printer = Printer::factory()->make([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $files = $printer->printableFiles();

        $this->assertEquals([
            'whistle_v2.gcode' => 'whistle_v2.gcode',
            'whistle_.gco' => 'whistle_.gco',
            'folderA/test.gcode' => 'folderA/test.gcode',
            'folderA/subfolder/test2.gcode' => 'folderA/subfolder/test2.gcode',
        ], $files);
    }

    /** @test */
    public function can_resume_print(): void
    {
        Queue::fake();
        Http::preventStrayRequests();

        Http::fake();

        $printer = Printer::factory()->make([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $printer->resume();

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Api-Key', 'TEST-KEY') &&
                $request->url() == 'http://bulbasaur.local/api/job' &&
                $request['command'] == 'pause' &&
                $request['action'] == 'resume';
        });

        Queue::assertPushed(FetchPrinterStatus::class);
    }

    /** @test */
    public function can_safely_delete_printer(): void
    {
        $printer = Printer::factory()->create([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $printer->safeDelete();

        $this->assertDatabaseMissing('printers', ['id' => $printer->id]);
    }

    /** @test */
    public function can_save_currently_printing(): void
    {
        Http::fake([
            'bulbasaur.local/api/job' => Http::response($this->jobResponse),
        ]);

        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);
        $material = Material::factory()->for($user->currentTeam)->create([
            'color_hex' => '#ffffff',
        ]);
        $printer->tools->first()->update(['material_id' => $material->id]);
        PrintType::factory()->for($user->currentTeam)->create();

        $job = $printer->saveCurrentlyPrinting($user);

        $this->assertEquals('whistle_v2.gcode', $job->name);
        $this->assertEquals('#ffffff', $job->color_hex);
        $this->assertEquals('whistle_v2.gcode', $job->files->first()['data']['file']);
        $this->assertEquals($material->id, $job->material_id);
        $this->assertEquals($printer->id, $job->printer_id);
        $this->assertEquals($printer->id, $job->printer_id);
        $this->assertTrue(now()->subSeconds(276) > $job->started_at);
    }
}
