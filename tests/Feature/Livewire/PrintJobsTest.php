<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\PrintJobs;
use Livewire\Livewire;
use Tests\TestCase;

class PrintJobsTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(PrintJobs::class);

        $component->assertStatus(200);
    }
}
