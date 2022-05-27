<?php

namespace App\Models;

use App\Traits\ForTeam;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function getNextJobAttribute()
    {
        if (! $this->spool_id) {
            return;
        }

        return PrintJob::query()
            ->where('color_hex', $this->spool->color_hex)
            ->whereNull('started_at')
            ->whereNull('completed_at')
            ->orderBy('job_type_id')
            ->limit(1)
            ->first();
    }

    public function getSlugAttribute()
    {
        return strtolower($this->name);
    }

    public function getStatusAttribute()
    {
        try {
            return $this->client->state();
        } catch (Exception $e) {
            return 'Connection Error';
        }
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
        return $this->client->files();
    }

    public function file($path, $location = 'local')
    {
        return $this->client->file($location, $path);
    }

    public function printFile($file)
    {
        return $this->client->selectFile('local', $file)->start();
    }

    public function upload($path, $contents, $location = 'local')
    {
        return $this->client->uploadFile($location, $path, $contents);
    }
}
