<?php

namespace App\Jobs;

use App\Models\Printer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchPrinterTools implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Printer $printer)
    {
    }

    public function handle(): void
    {
        if (! in_array($this->printer->status, ['operational', 'printing'])) {
            return;
        }

        $response = Http::octoPrint($this->printer)
            ->get('/api/printer/tool');

        if ($response->successful()) {
            $tools = array_keys($response->json());

            foreach ($tools as $tool) {
                $this->printer->tools()->firstOrCreate(['name' => $tool]);
            }

            if ($this->printer->fresh()->tools->count() > count($tools)) {
                $this->printer->tools()
                    ->whereNotIn('name', $tools)
                    ->delete();
            }
        }
    }
}
