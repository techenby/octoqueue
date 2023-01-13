<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\Printer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessJobFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Job $printJob)
    {
    }

    public function handle()
    {
        if ($this->printJob->files === null || !$this->printJob->files->contains('type', 'upload')) {
            return;
        }

        $files = $this->printJob->files
            ->map(function ($file) {
                if ($file['type'] === 'upload') {
                    $printer = Printer::find($file['data']['printer']);

                    $response = $printer->uploadFile(
                        $filename = $this->getFilename($file['data']),
                        $file['data']['folder'],
                        Storage::get($file['data']['attachment'])
                    );

                    if ($response->successful()) {
                        Storage::delete($file['data']['attachment']);

                        return [
                            'data' => [
                                'printer' => $file['data']['printer'],
                                'file' => $file['data']['folder'] . '/' . $filename,
                            ],
                            'type' => 'existing',
                        ];
                    }
                }

                return $file;
            });

        $this->printJob->update(['files' => $files]);
    }

    private function getFilename($data)
    {
        return $data['maintain_filename'] ? class_basename($data['attachment']) : $data['filename'];
    }
}
