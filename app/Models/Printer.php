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

    public const OPERATIONAL = 'operational';
    public const CLOSED = 'closed';
    public const OFFLINE = 'offline';
    public const ERROR = 'error';

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
        };
    }

    public function files($recursive = true)
    {
        $results = Http::withHeaders([
                'X-Api-Key' => $this->api_key,
            ])
            ->get($this->url . "/api/files?recursive={$recursive}");

        return $results->json('files');
    }

    public function printableFiles()
    {
        return collect(flattenByKey($this->files(), 'children'))
            ->filter(fn ($item) => $item['type'] === 'machinecode')
            ->pluck('path')
            ->toArray();
    }
}
