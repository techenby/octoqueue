<?php

namespace App\Models;

use App\Traits\ForTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Printer extends Model
{
    use HasFactory, SoftDeletes, ForTeam;

    protected $guarded = [];

    public static function rules()
    {
        return [
            'name' => ['required'],
            'model' => ['nullable'],
            'url' => ['nullable'],
            'api_key' => ['nullable'],
            'spool_id' => ['nullable'],
        ];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function getWebcamAttribute()
    {
        return $this->url . '/webcam/?action=stream';
    }

    public function getStatusAttribute()
    {
        return (new \TechEnby\OctoPrint\OctoPrint($this->url, $this->api_key))->state();
    }
}
