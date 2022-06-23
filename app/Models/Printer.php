<?php

namespace App\Models;

use App\Traits\ForTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use TechEnby\OctoPrint\OctoPrint;

class Printer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ForTeam;

    protected $guarded = [];

    public function currentJob()
    {
        return $this->hasOne(PrintJob::class)->whereNotNull('started_at')->whereNull('completed_at');
    }

    public function jobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function nextJob()
    {
            return $this->hasOne(PrintJob::class)
                ->where(function ($query) {
                    $query->when($this->spool, fn($query) => $query->where('color_hex', $this->spool->color_hex))
                        ->orWhereNull('color_hex');
                })
                ->whereNull('started_at')
                ->whereNull('completed_at')
                ->orderBy('job_type_id');
    }

    public function spool()
    {
        return $this->belongsTo(Spool::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getClientAttribute()
    {
        return (new OctoPrint($this->url, $this->api_key));
    }

    public function getHardwareStateAttribute()
    {
        if ($this->status === 'Operational') {
            return $this->client->printer();
        }
    }

    public function getSlugAttribute()
    {
        return strtolower($this->name);
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
        return $this->client->cancel();
    }

    public function files()
    {
        return Cache::remember("printer-{$this->id}-files", 60 * 15, function () {
            return $this->client->files();
        });
    }

    public function file($path, $location = 'local')
    {
        return $this->client->file($location, $path);
    }

    public function printFile($file)
    {
        $this->client->selectFile('local', $file)->start();
        $this->update(['status' => 'Printing']);
    }

    public function upload($path, $contents, $location = 'local')
    {
        return $this->client->uploadFile($location, $path, $contents);
    }
}
