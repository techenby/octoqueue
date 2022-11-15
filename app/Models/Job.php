<?php

namespace App\Models;

use App\Traits\HasTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    use HasTeam;

    protected $guarded = ['id'];

    protected $casts = [
        'files' => 'array',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    public function printType()
    {
        return $this->belongsTo(PrintType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
