<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // $data['files'] = $this->processFiles();

        return static::getModel()::create(array_merge($data, [
            'team_id' => auth()->user()->currentTeam->id,
            'user_id' => auth()->id(),
        ]));
    }

    public function getColorOptionsProperty()
    {
        return auth()->user()->currentTeam->materials->pluck('name', 'color_hex');
    }

    public function getPrintersProperty()
    {
        return auth()->user()->currentTeam->printers;
    }

    private function processFiles()
    {
        return collect($this->data['files'])
            ->map(function ($file) {
                if ($file['type'] === 'choose') {
                    $file = $file['data'];
                } elseif ($file['type'] === 'upload') {
                    $attachment = current($file['data']['attachment']);
                    dd($file['data']['attachment'], $attachment);
                    $filename = $attachment->getClientOriginalName();
                    $printer = $this->printers->find($file['data']['printer']);

                    $result = $printer->uploadFile($filename, $file['data']['folder'], $attachment->get());

                    if ($result->isSuccess()) {
                        return [
                            'printer' => $printer->id,
                            'file' => Str::finish($file['data']['folder'], '/') . $filename,
                        ];
                    } else {
                        Notification::make()
                                ->title('Upload failed')
                                ->body($result->getMessage())
                                ->danger()
                                ->send();
                    }

                    $file = [
                        'printer' => $file['data']['printer'],
                        'file' => $file['data']['folder'] . '/' . $file['data']['file']->getClientOriginalName(),
                    ];
                }
            });
    }
}
