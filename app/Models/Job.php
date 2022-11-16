<?php

namespace App\Models;

use App\Traits\HasTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;
    use HasTeam;

    protected $guarded = ['id'];

    protected $casts = [
        'files' => 'array',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function printer(): BelongsTo
    {
        return $this->belongsTo(Printer::class);
    }

    public function printType(): BelongsTo
    {
        return $this->belongsTo(PrintType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
