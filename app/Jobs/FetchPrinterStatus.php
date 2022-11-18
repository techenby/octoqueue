<?php

namespace App\Jobs;

use App\Models\Printer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchPrinterStatus implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Printer $printer)
    {}

    public function handle()
    {
        if ($this->printer->url === null || $this->printer->api_key === null) {
            return $this->printer->update([
                'status' => 'error',
            ]);
        }

        try {
            $response = Http::octoPrint($this->printer)
                ->get('/api/connection');
        } catch (ConnectionException $e) {
            return $this->printer->update([
                'status' => 'offline',
            ]);
        }

        if ($response->successful()) {
            return $this->printer->update([
                'status' => strtolower($response->json('current.state')),
            ]);
        }

        if ($response->failed()) {
            return $this->printer->update([
                'status' => 'error',
            ]);

            // notification to users on team
        }
    }
}
