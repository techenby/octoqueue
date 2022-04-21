<?php

namespace App\Models;

use App\Calculator;
use App\Traits\ForTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spool extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ForTeam;

    protected $guarded = [];

    protected $casts = [
        'weights' => 'collection',
    ];

    public function printer()
    {
        return $this->hasOne(Printer::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function jobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function getFormattedCostAttribute()
    {
        return '$' . $this->cost;
    }

    public function getCurrentWeightAttribute()
    {
        $spoolWeight = $this->empty;
        $last = $this->weights->last();
        $filamentUsed = $this->jobs()->where('completed_at', '>', $last['timestamp'])->sum('filament_used');

        if ($filamentUsed > 0) {
            return $last['weight'] - $spoolWeight - $filamentUsed;
        } else {
            return $last['weight'] - $spoolWeight;
        }
    }

    public function getCurrentLengthAttribute()
    {
        return (new Calculator())->gramsToLength($this->material, $this->diameter, $this->currentWeight);
    }

    public function getFormattedCurrentWeightAttribute()
    {
        if ($this->jobs()->where('completed_at', '>', $this->weights->last()['timestamp'])->count() > 0) {
            return '~' . $this->currentWeight . 'g';
        } else {
            return $this->currentWeight . 'g';
        }
    }

    public function getFormattedCurrentLengthAttribute()
    {
        if ($this->jobs()->where('completed_at', '>', $this->weights->last()['timestamp'])->count() > 0) {
            return '~' . $this->currentLength . 'm';
        } else {
            return $this->currentLength . 'm';
        }
    }

    public function getNameAttribute()
    {
        return $this->color . ' - ' . $this->brand;
    }

    public function getLocationAttribute()
    {
        return $this->printer->name ?? 'Storage';
    }

    public function addWeight($weight, $save = true)
    {
        $weights = $this->weights;
        $weights[] = ['weight' => $weight, 'timestamp' => now()];
        $this->weights = $weights;

        if ($save) {
            $this->save();
        }
    }
}
