<?php

namespace App\Models;

use App\Traits\ForTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spool extends Model
{
    use HasFactory, SoftDeletes, ForTeam;

    protected $guarded = [];

    protected $casts = [
        'weights' => 'array',
    ];

    public static function rules()
    {
        return [
            'team_id' => ['required'],
            'color_id' => ['required'],
            'brand' => ['nullable'],
            'cost' => ['nullable'],
            'material' => ['nullable'],
            'diameter' => ['nullable'],
            'weights' => ['nullable'],
        ];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }
}
