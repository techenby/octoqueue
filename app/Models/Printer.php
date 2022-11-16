<?php

namespace App\Models;

use App\Traits\HasTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Laravel\Jetstream\Jetstream;

class Printer extends Model
{
    use HasFactory;
    use HasTeam;

    protected $casts = ['api_key' => 'encrypted'];

    protected $guarded = ['id'];

    public function files($recursive = true)
    {
        $results = Http::get($this->url . "/api/files?recursive={$recursive}");
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
