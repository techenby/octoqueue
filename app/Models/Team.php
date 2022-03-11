<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    protected $casts = [
        'personal_team' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'personal_team',
    ];

    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function printers()
    {
        return $this->hasMany(Printer::class);
    }

    public function spools()
    {
        return $this->hasMany(Spool::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function jobTypes()
    {
        return $this->hasMany(PrintJobType::class);
    }
}
