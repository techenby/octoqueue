<?php

namespace App\Models;

use App\Jobs\FetchPrinterStatus;
use App\Jobs\FetchPrinterTools;
use App\Traits\HasTeam;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function cancel()
    {
        try {
            Http::octoPrint($this)->post("/api/job", [
                'command' => 'cancel',
            ]);

            FetchPrinterStatus::dispatch($this);

            if ($this->currentJob->exists()) {
                $currentJob = $this->currentJob->first();
                $currentJob->duplicate()->save();
                $currentJob->update([
                    'failed_at' => now(),
                ]);
            }
        } catch (Exception $e) {

        }
    }

    public function currentlyPrinting()
    {
        try {
            $results = Http::octoPrint($this)
                ->get("/api/job")
                ->json();

            if ($results['state'] !== 'Printing') {
                FetchPrinterStatus::dispatch($this);
            }

            return $results;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function files($recursive = true)
    {
        $results = Http::octoPrint($this)->get("/api/files?recursive={$recursive}");

        return $results->json('files');
    }

    public function pause()
    {
        Http::octoPrint($this)->post("/api/job", [
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
        Http::octoPrint($this)->post("/api/job", [
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
}
