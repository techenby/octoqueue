<?php

namespace App\Models;

use Facades\App\Calculator;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Jetstream\Jetstream;

class Material extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = ['weights' => AsCollection::class];

    protected $guarded = ['id'];

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }

    public function getCurrentWeightAttribute()
    {
        return $this->weights->last()['weight'] - $this->empty;
    }

    public function getCurrentLengthAttribute()
    {

        return Calculator::gramsToLength($this->material, $this->diameter, $this->currentWeight);
    }

    public function getFormattedCurrentWeightAttribute()
    {
        return $this->currentWeight.'g';
    }

    public function getFormattedCurrentLengthAttribute()
    {
        return $this->currentLength ? $this->currentLength.'m' : '-';
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
