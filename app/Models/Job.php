<?php

namespace App\Models;

use App\Jobs\FetchPrinterStatus;
use App\Jobs\ProcessJobFiles;
use App\Traits\HasTeam;
use Exception;
use Facades\App\Calculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;
use Parental\HasChildren;

class Job extends Model
{
    use HasChildren;
    use HasFactory;
    use HasTeam;

    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
        'files' => 'collection',
    ];

    protected static function booted()
    {
        static::saved(fn ($job) => ProcessJobFiles::dispatch($job));
    }

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

    public function getHasStartedAttribute()
    {
        return $this->started_at !== null;
    }

    public function getIsDoneAttribute()
    {
        return $this->completed_at !== null || $this->failed_at !== null;
    }

    public function copy($colorHex = null)
    {
        $new = $this->replicate([
            'printer_id',
            'material_id',
            'started_at',
            'completed_at',
            'failed_at',
            'material_used',
        ]);
        $new->color_hex = $colorHex ?? $this->color_hex;
        $new->save();

        return $new;
    }

    public function markAsComplete()
    {
        FetchPrinterStatus::dispatch($this->printer);

        // if selected file isn't the file printed get length from gcode
        $length = $this->printer->currentlyPrinting()['job']['filament']['tool0']['length'] / 1000; // convert from cm to m
        $grams = Calculator::lengthToGrams($this->material->type, $this->material->diameter, $length);

        $this->update([
            'completed_at' => now(),
            'material_used' => $grams,
        ]);
    }

    public function markAsFailed()
    {
        FetchPrinterStatus::dispatch($this->printer);

        $current = $this->printer->currentlyPrinting();
        $length = $current['job']['filament']['tool0']['length'] / 1000; // convert from cm to m
        $percentDone = $current['progress']['completion'] / 100;

        $grams = Calculator::lengthToGrams($this->material->type, $this->material->diameter, $length * $percentDone);

        $this->update([
            'failed_at' => now(),
            'material_used' => $grams,
        ]);
    }

    public function print()
    {
        $materials = $this->team->materials()->where('color_hex', $this->color_hex)->get();

        throw_if($materials->isEmpty(), Exception::class, 'No materials found with this color');

        $printers = Printer::query()
            ->where('status', 'operational')
            ->whereIn('material_id', $materials->pluck('id'))
            ->whereIn('id', $this->files->filter(fn ($file) => $file['type'] === 'existing')->pluck('data.printer'))
            ->get();

        throw_if($printers->isEmpty(), Exception::class, 'No printers available');

        try {
            $printer = $printers->first();
            $file = $this->files->firstWhere('data.printer', $printer->id)['data']['file'];

            Http::octoPrint($printer)->post("/api/files/local/{$file}", [
                'command' => 'select',
                'print' => true,
            ]);

            $this->update([
                'printer_id' => $printer->id,
                'material_id' => $printer->material_id,
                'started_at' => now(),
            ]);

            FetchPrinterStatus::dispatch($printer);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
