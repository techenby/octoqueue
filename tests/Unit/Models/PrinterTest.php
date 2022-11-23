<?php

namespace Tests\Unit\Models;

use App\Jobs\FetchPrinterStatus;
use App\Models\Printer;
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

    /** @test */
    public function can_get_files()
    {
        Http::preventStrayRequests();

        Http::fake([
            'bulbasaur.local/api/files?recursive=1' => Http::response($this->filesResponse)
        ]);

        $printer = Printer::factory()->make([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $files = $printer->files();

        $this->assertCount(3, $files);
    }

    /** @test */
    public function can_get_list_of_printable_files()
    {
        Http::preventStrayRequests();

        Http::fake([
            'bulbasaur.local/api/files?recursive=1' => Http::response($this->filesResponse)
        ]);

        $printer = Printer::factory()->make([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-KEY',
        ]);

        $files = $printer->printableFiles();

        $this->assertEquals([
            'whistle_v2.gcode',
            'whistle_.gco',
            'folderA/test.gcode',
            'folderA/subfolder/test2.gcode',
        ], $files);
    }

    /** @test */
    public function can_pause_print()
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
    public function can_resume_print()
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
    public function can_cancel_print()
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
}
