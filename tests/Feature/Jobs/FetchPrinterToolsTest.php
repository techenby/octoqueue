<?php

namespace Tests\Feature\Jobs;

use App\Jobs\FetchPrinterTools;
use App\Models\Printer;
use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchPrinterToolsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_tools_for_new_printer()
    {
        Http::fake([
            'bulbasaur.local/api/printer/tool' => Http::response([
                'tool0' => [
                    'actual' => 214.8821,
                    'target' => 220.0,
                    'offset' => 0,
                ],
                'tool1' => [
                    'actual' => 25.3,
                    'target' => null,
                    'offset' => 0,
                ],
            ]),
        ]);

        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST_API_KEY',
            'status' => 'operational',
        ]);

        FetchPrinterTools::dispatch($printer);

        $this->assertCount(2, $printer->fresh()->tools);
    }

    /** @test */
    public function tools_not_created_for_printer_with_bad_status()
    {
        $printer = Printer::factory()->createQuietly([
            'url' => 'http://bulbasaur.local',
            'api_key' => 'TEST_API_KEY',
            'status' => 'error',
        ]);

        FetchPrinterTools::dispatch($printer);

        $this->assertCount(0, $printer->fresh()->tools);
    }

    /** @test */
    public function new_tools_are_added_to_existing_ones()
    {
        Http::fake([
            'bulbasaur.local/api/printer/tool' => Http::response([
                'tool0' => [
                    'actual' => 214.8821,
                    'target' => 220.0,
                    'offset' => 0,
                ],
                'tool1' => [
                    'actual' => 25.3,
                    'target' => null,
                    'offset' => 0,
                ],
            ]),
        ]);

        $printer = Printer::factory()
            ->has(Tool::factory())
            ->createQuietly([
                'url' => 'http://bulbasaur.local',
                'api_key' => 'TEST_API_KEY',
                'status' => 'operational',
            ]);

        FetchPrinterTools::dispatch($printer);

        $this->assertCount(2, $printer->fresh()->tools);
    }

    /** @test */
    public function old_tools_are_deleted_to_from_ones()
    {
        Http::fake([
            'bulbasaur.local/api/printer/tool' => Http::response([
                'tool0' => [
                    'actual' => 214.8821,
                    'target' => 220.0,
                    'offset' => 0,
                ],
            ]),
        ]);

        $printer = Printer::factory()
            ->has(Tool::factory()->count(2)->sequence(fn ($sequence) => ['name' => 'tool' . $sequence->index]))
            ->createQuietly([
                'url' => 'http://bulbasaur.local',
                'api_key' => 'TEST_API_KEY',
                'status' => 'operational',
            ]);

        FetchPrinterTools::dispatch($printer);

        $this->assertCount(1, $printer->fresh()->tools);
    }
}
