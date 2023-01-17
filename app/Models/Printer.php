<?php

namespace App\Models;

use App\Jobs\FetchPrinterStatus;
use App\Jobs\FetchPrinterTools;
use App\Traits\HasTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;

class Printer extends Model
{
    use HasFactory;
    use HasTeam;

    protected $casts = ['api_key' => 'encrypted'];

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::created(function ($printer) {
            Bus::chain([
                new FetchPrinterStatus($printer),
                new FetchPrinterTools($printer),
            ])->dispatch();
        });

        static::updated(function ($printer) {
            Bus::chain([
                new FetchPrinterStatus($printer),
                new FetchPrinterTools($printer),
            ])->dispatch();
        });
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function currentJob()
    {
        return $this->jobs()->whereNotNull('started_at')->whereNull('completed_at')->whereNull('failed_at');
    }

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'offline' => 'danger',
            'error' => 'danger',
            'closed' => 'warning',
            'operational' => 'success',
            'printing' => 'success',
            null => 'secondary',
        };
    }

    public function getScreenshotAttribute()
    {
        return $this->url . '/webcam/?action=snapshot';
    }

    public function getWebcamAttribute()
    {
        return $this->url . '/webcam/?action=stream';
    }

    public function cancel()
    {
        if ($this->currentJob->isNotEmpty()) {
            $currentJob = $this->currentJob->first();
            $currentJob->copy();
            $currentJob->markAsFailed();
        }

        Http::octoPrint($this)->post('/api/job', [
            'command' => 'cancel',
        ]);

        FetchPrinterStatus::dispatch($this);
    }

    public function currentlyPrinting()
    {
        try {
            $results = Http::octoPrint($this)
                ->get('/api/job')
                ->json();

            return $results;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function files($recursive = true)
    {
        return once(fn () => Http::octoPrint($this)->get("/api/files?recursive={$recursive}")->json('files'));
    }

    public function folders()
    {
        return collect(flattenByKey($this->files(), 'children'))
            ->filter(fn ($item) => $item['type'] === 'folder')
            ->pluck('path', 'path')
            ->toArray();
    }

    public function pause()
    {
        Http::octoPrint($this)->post('/api/job', [
            'command' => 'pause',
            'action' => 'pause',
        ]);

        FetchPrinterStatus::dispatch($this);
    }

    public function printableFiles()
    {
        return collect(flattenByKey($this->files(), 'children'))
            ->filter(fn ($item) => $item['type'] === 'machinecode')
            ->pluck('path', 'path')
            ->toArray();
    }

    public function resume()
    {
        Http::octoPrint($this)->post('/api/job', [
            'command' => 'pause',
            'action' => 'resume',
        ]);

        FetchPrinterStatus::dispatch($this);
    }

    public function safeDelete()
    {
        $this->tools()->delete();

        $this->delete();
    }

    public function saveCurrentlyPrinting($user = null)
    {
        if ($user === null) {
            $user = auth()->user();
        }
        $name = $this->currentlyPrinting()['job']['file']['name'];

        return Job::create([
            'name' => $name,
            'color_hex' => $this->tools()->first()->material->color_hex,
            'files' => [['printer' => $this->id, 'file' => $name]],
            'material_id' => $this->tools()->first()->material_id,
            'printer_id' => $this->id,
            'print_type_id' => $user->currentTeam->printTypes()->orderBy('priority')->first()->id,
            'team_id' => $user->currentTeam->id,
            'user_id' => $user->id,
            'started_at' => now()->subSeconds($this->currentlyPrinting()['progress']['printTime']),
        ]);
    }

    public function uploadFile($filename, $path, $contents, $location = 'local')
    {
        return Http::octoPrint($this)
            ->attach('attachment', $contents, $filename)
            ->post("/api/files/{$location}", [
                ['name' => 'path', 'contents' => $path],
                ['name' => 'file', 'filename' => $filename, 'contents' => $contents],
            ]);
    }
}
