<?php

namespace Tests\Unit\Models;

use App\Models\Job;
use App\Models\Material;
use App\Models\Printer;
use App\Models\Team;
use App\Models\Tool;
use Facades\App\Calculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    private $jobResponse = [
        'job' => [
            'averagePrintTime' => 2870.4015269151,
            'estimatedPrintTime' => 2058.5783016978,
            'filament' => [
                'tool0' => [
                    'length' => 930.13078999995,
                    'volume' => 2.2372267309427,
                ],
            ],
            'file' => [
                'date' => 1669567667,
                'display' => 'CE3PRO_retro-starburst_46m_0.16mm_220C_PLA.gcode',
                'name' => 'CE3PRO_retro-starburst_46m_0.16mm_220C_PLA.gcode',
                'origin' => 'local',
                'path' => 'Chaotic Notions/Ornaments/CE3PRO_retro-starburst_46m_0.16mm_220C_PLA.gcode',
                'size' => 1700301,
            ],
            'lastPrintTime' => 2870.4015269151,
            'user' => 'andymnewhouse',
        ],
        'progress' => [
            'completion' => 72.995016764679,
            'filepos' => 1241135,
            'printTime' => 1953,
            'printTimeLeft' => 798,
            'printTimeLeftOrigin' => 'linear',
        ],
        'state' => 'Printing',
    ];

    /** @test */
    public function can_copy_job(): void
    {
        $team = Team::factory()->create();
        $printer = Printer::factory()->for($team)->createQuietly();
        $job = Job::factory()->completed()->create([
            'printer_id' => $printer->id,
            'material_id' => Material::factory()->for($team),
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => 1, 'file' => 'ducky.gcode']],
        ]);

        $newJob = $job->copy();

        $this->assertEquals('#FFFF00', $newJob->color_hex);
        $this->assertNull($newJob->started_at);
        $this->assertNull($newJob->completed_at);
        $this->assertNull($newJob->printer_id);
        $this->assertNull($newJob->material_id);
    }

    /** @test */
    public function can_copy_job_with_different_color(): void
    {
        $team = Team::factory()->create();
        $printer = Printer::factory()->for($team)->createQuietly();
        $job = Job::factory()->completed()->create([
            'printer_id' => $printer->id,
            'material_id' => Material::factory()->for($team),
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'notes' => 'Should be cute',
            'files' => [['printer' => 1, 'file' => 'ducky.gcode']],
        ]);

        $newJob = $job->copy('#000000');

        $this->assertEquals('#000000', $newJob->color_hex);
        $this->assertNull($newJob->started_at);
        $this->assertNull($newJob->completed_at);
        $this->assertNull($newJob->printer_id);
        $this->assertNull($newJob->material_id);
    }

    /** @test */
    public function can_mark_job_as_complete(): void
    {
        Http::fake([
            'http://bulbasaur.local/api/job' => Http::response($this->jobResponse),
        ]);

        $team = Team::factory()->create();
        $printer = Printer::factory()->for($team)->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);
        $material = Material::factory()->for($team)->create(['type' => 'PLA', 'diameter' => 1.75]);
        $job = Job::factory()->for($team)->create([
            'printer_id' => $printer->id,
            'material_id' => $material->id,
            'name' => 'Retro Starburst',
            'color_hex' => '#FFFF00',
            'files' => [
                ['file' => 'Chaotic Notions/Ornaments/CE3PRO_retro-starburst_46m_0.16mm_220C_PLA.gcode', 'printer' => $printer->id],
            ],
        ]);

        $job->markAsComplete();

        $this->assertNotNull($job->completed_at);
        $this->assertEquals(Calculator::lengthToGrams('PLA', '1.75', 0.93013078999995), $job->material_used);
    }

    /** @test */
    public function can_mark_job_as_failed(): void
    {
        Http::fake([
            'http://bulbasaur.local/api/job' => Http::response($this->jobResponse),
        ]);

        $team = Team::factory()->create();
        $printer = Printer::factory()->for($team)->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST-API-KEY',
        ]);
        $material = Material::factory()->for($team)->create(['type' => 'PLA', 'diameter' => 1.75]);
        $job = Job::factory()->for($team)->create([
            'printer_id' => $printer->id,
            'material_id' => $material->id,
            'name' => 'Retro Starburst',
            'color_hex' => '#FFFF00',
            'files' => [
                ['file' => 'Chaotic Notions/Ornaments/CE3PRO_retro-starburst_46m_0.16mm_220C_PLA.gcode', 'printer' => $printer->id],
            ],
        ]);

        $job->markAsFailed();

        $this->assertNotNull($job->failed_at);
        $this->assertEquals(Calculator::lengthToGrams('PLA', '1.75', 0.93013078999995 * .72995016764679), $job->material_used);
    }

    /** @test */
    public function can_print_job(): void
    {
        Http::fake(['*' => Http::response([], 204)]);

        $team = Team::factory()->create();
        $material = Material::factory()->for($team)->create(['color_hex' => '#FFFF00', 'color' => 'Yellow']);
        [$printerA, $printerB] = Printer::factory()->for($team)->count(2)->createQuietly(['status' => 'operational']);
        $printerA->tools->first()->update(['material_id' => $material->id]);

        $job = Job::factory()->for($team)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'files' => [
                ['type' => 'existing', 'data' => ['file' => 'Fun/CE3PRO-rubber-ducky.gcode', 'printer' => $printerA->id]],
                ['type' => 'existing', 'data' => ['file' => 'Fun/CE3-rubber-ducky.gcode', 'printer' => $printerB->id]],
            ],
        ]);

        $job->print();

        $job->refresh();
        $this->assertEquals($printerA->id, $job->printer_id);
        $this->assertEquals($material->id, $job->material_id);
        $this->assertNotNull($job->started_at);
    }
}
