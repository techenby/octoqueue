<?php

namespace App\Models;

use App\Traits\ForTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    use HasFactory;
    use ForTeam;

    public $friendly = 'job';

    protected $guarded = [];

    protected $casts = [
        'files' => 'array',
        'filament_used' => 'double',
    ];

    protected $date = ['started_at', 'completed_at'];

    public static function rules()
    {
        return [
            'name' => ['required'],
            'job_type_id' => ['nullable'],
            'color_id' => ['nullable'],
            'files' => ['required'],
        ];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spool()
    {
        return $this->belongsTo(Spool::class);
    }

    public function type()
    {
        return $this->belongsTo(PrintJobType::class, 'job_type_id');
    }

    public function getCompletedAttribute()
    {
        return $this->completed_at !== null;
    }

    public function safeDelete()
    {
        $this->delete();
    }
}
