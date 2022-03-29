<?php

namespace App\Models;

use App\Traits\ForTeam;
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

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function spool()
    {
        return $this->belongsTo(Spool::class);
    }

    public function getHardwareStateAttribute()
    {
        return (new OctoPrint($this->url, $this->api_key))->printer();
    }

    public function getSlugAttribute()
    {
        return strtolower($this->name);
    }

    public function getStatusAttribute()
    {
        return (new OctoPrint($this->url, $this->api_key))->state();
    }

    public function getWebcamAttribute()
    {
        return $this->url . '/webcam/?action=stream';
    }

    public function files()
    {
        return (new OctoPrint($this->url, $this->api_key))->files();
    }
}
