<?php

namespace Tests\Unit\Models;

use App\Models\Job;
use App\Models\Material;
use App\Models\Printer;
use App\Models\Team;
use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function can_copy_job()
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
    public function can_copy_job_with_different_color()
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
    public function can_print_job()
    {
        Http::fake(['*' => Http::response([], 204)]);

        $team = Team::factory()->create();
        $material = Material::factory()->for($team)->create(['color_hex' => '#FFFF00', 'color' => 'Yellow']);
        [$printerA, $printerB] = Printer::factory()->for($team)->count(2)->has(Tool::factory())->createQuietly(['status' => 'operational']);
        $printerA->tools->first()->update(['material_id' => $material->id]);

        $job = Job::factory()->for($team)->create([
            'name' => 'Rubber Ducky',
            'color_hex' => '#FFFF00',
            'files' => [
                ["file" => "Fun/CE3PRO-rubber-ducky.gcode", "printer" => $printerA->id],
                ["file" => "Fun/CE3-rubber-ducky.gcode", "printer" => $printerB->id],
            ]
        ]);

        $job->print();

        $job->refresh();
        $this->assertEquals($printerA->id, $job->printer_id);
        $this->assertEquals($material->id, $job->material_id);
        $this->assertNotNull($job->started_at);
    }
}
