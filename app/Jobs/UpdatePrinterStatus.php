<?php

namespace App\Jobs;

use App\Models\Printer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePrinterStatus implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle()
    {
        Printer::chunk(100, function ($printers) {
            foreach ($printers as $printer) {
                $originalStatus = $printer->status;

                $printer->update([
                    'status' => $printer->client->state(),
                ]);

                if ($originalStatus === 'Printing' && $printer->status !== 'Printing' && $printer->currentJob) {
                    $printer->currentJob->completed();
                }
            }
        });
    }
}
