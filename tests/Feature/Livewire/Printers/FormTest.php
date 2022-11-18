<?php

namespace Tests\Feature\Livewire\Printers;

use App\Http\Livewire\Printers\Form;
use App\Jobs\FetchPrinterStatus;
use App\Jobs\FetchPrinterTools;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_printer()
    {
        Bus::fake();

        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->fillForm([
                'name' => 'Pikachu',
                'model' => 'Ender 3',
                'url' => 'http://pikachu.local',
                'api_key' => 'pika-pika',
            ])
            ->call('submit')
            ->assertHasNoFormErrors();

        Bus::assertChained([
            FetchPrinterStatus::class,
            FetchPrinterTools::class,
        ]);
    }

    /** @test */
    public function can_edit_printer()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $printer = Printer::factory()->for($user->currentTeam)->create([
            'name' => 'Pikachu',
            'model' => 'Ender 3',
            'url' => 'http://pikachu.local',
            'api_key' => 'pika-pika',
        ]);

        Livewire::actingAs($user)
            ->test(Form::class, ['printer' => $printer])
            ->fillForm([
                'name' => 'Eevee',
                'model' => 'Ender 3',
                'url' => 'http://eevee.local',
                'api_key' => 'vee-vee',
            ])
            ->call('submit')
            ->assertHasNoFormErrors();

        $printer->refresh();
        $this->assertEquals('Eevee', $printer->name);
        $this->assertEquals('http://eevee.local', $printer->url);
        $this->assertEquals('vee-vee', $printer->api_key);
    }
}
