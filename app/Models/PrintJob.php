<?php

namespace App\Models;

use App\Calculator;
use App\Traits\ForTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PrintJob extends Model
{
    use HasFactory;
    use ForTeam;

    public $friendly = 'job';

    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime',
        'files' => 'collection',
        'filament_used' => 'double',
        'started_at' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'name' => ['required'],
            'job_type_id' => ['nullable'],
            'color_id' => ['nullable'],
            'files' => ['required'],
        ];
    }

    public function availableSpools()
    {
        return $this->hasMany(Spool::class, 'color_hex', 'color_hex');
    }

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function spool()
    {
        return $this->belongsTo(Spool::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function type()
    {
        return $this->belongsTo(PrintJobType::class, 'job_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvailablePrintersAttribute()
    {
        if ($this->printer_id) {
            return $this->printer->name;
        }

        return $this->files->keys()->map(fn($name) => ucfirst($name))->implode(', ');
    }

    public function getCompletedAttribute()
    {
        return $this->completed_at !== null;
    }

    public function getStartedAttribute()
    {
        return $this->started_at !== null;
    }

    public function completed()
    {
        $data = $this->printer->file($this->files[$this->printer_id]);
        $data['gcodeAnalysis']['filament']['tool0']['length'];
        $length = $data['gcodeAnalysis']['filament']['tool0']['length'] / 1000;

        $this->completed_at = now();
        if ($this->started_at === null) {
            $this->started_at = Carbon::parse($data['prints']['last']['date']);
            $this->completed_at = Carbon::parse($data['prints']['last']['date'])
                ->addSeconds($data['prints']['last']['printTime']);
        }

        $this->filament_used = (new Calculator())->lengthToGrams($this->spool->material, $this->spool->diameter, $length);

        $this->save();
    }

    public function cancel()
    {
        $this->started_at = null;
        $this->save();
    }

    public function duplicate()
    {
        $new = $this->replicate();

        if ($this->files->count() > 1) {
            $new->printer_id = null;
        }

        $new->started_at = null;
        $new->completed_at = null;
        $new->filament_used = null;
        $new->save();

        return $new;
    }

    public function safeDelete()
    {
        if ($this->started && ! $this->completed) {
            return;
        }

        $this->delete();
    }

    public function start($printer)
    {
        $printer->printFile($this->files[$printer->id]);

        if ($printer->status === 'Printing') {
            $this->started_at = now();
            $this->printer_id = $printer->id;
            $this->spool_id = $printer->spool_id;
            $this->save();
        }
    }
}
