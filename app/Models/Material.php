<?php

namespace App\Models;

use App\Traits\HasTeam;
use Facades\App\Calculator;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory;
    use HasTeam;
    use SoftDeletes;

    protected $casts = ['weights' => AsCollection::class];

    protected $guarded = ['id'];

    public function printer(): HasOne
    {
        return $this->hasOne(Printer::class);
    }

    public function getCurrentWeightAttribute()
    {
        if ($this->weights === null) {
            return;
        }

        return $this->weights->last()['weight'] - $this->empty;
    }

    public function getCurrentLengthAttribute()
    {
        return Calculator::gramsToLength($this->material, $this->diameter, $this->currentWeight);
    }

    public function getFormattedCurrentWeightAttribute()
    {
        return $this->currentWeight . 'g';
    }

    public function getFormattedCurrentLengthAttribute()
    {
        return $this->currentLength ? $this->currentLength . 'm' : '-';
    }

    public function getNameAttribute()
    {
        return "{$this->brand} {$this->color} ({$this->type})";
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
