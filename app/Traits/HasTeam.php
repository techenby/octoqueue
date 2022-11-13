<?php

namespace App\Traits;

use Laravel\Jetstream\Jetstream;

trait HasTeam
{
    public function scopeForCurrentTeam($query, $team = null)
    {
        return $query->where('team_id', $team->id ?? auth()->user()->current_team_id);
    }

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}
