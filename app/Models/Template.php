<?php

namespace App\Models;

use App\Jobs\FetchPrinterStatus;
use App\Jobs\ProcessJobFiles;
use App\Traits\HasTeam;
use Exception;
use Facades\App\Calculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;
use Parental\HasParent;

class Template extends Job
{
    use HasParent;
}
