<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Jetstream;

class Printer extends Model
{
    use HasFactory;

    protected $casts = ['api_key' => 'encrypted'];

    protected $guarded = ['id'];

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}
