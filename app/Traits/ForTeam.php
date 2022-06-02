<?php

namespace App\Traits;

trait ForTeam
{
    public function scopeForCurrentTeam($query, $team = null)
    {
        return $query->where('team_id', $team->id ?? auth()->user()->currentTeam->id);
    }
}
