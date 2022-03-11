<?php

namespace App\Models;

use App\Traits\ForTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintJobType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ForTeam;

    protected $guarded = [];

    public static function rules()
    {
        return [
            'name' => ['required'],
            'priority' => ['required'],
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
}
