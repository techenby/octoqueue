<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function templates()
    {
        return $this->belongsToMany(Template::class);
    }

    public function addToQueue()
    {
        $this->templates->each(function ($template) {
            $data = Arr::only($template->toArray(), ['team_id', 'print_type_id', 'name', 'color_hex', 'files', 'notes']);
            Job::create(array_merge(['user_id' => auth()->id(), 'type' => Job::class], $data));
        });
    }
}
