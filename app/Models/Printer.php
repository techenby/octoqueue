<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Jetstream;

class Printer extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}
