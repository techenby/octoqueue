<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('failed_at')->nullable()->after('started_at');
        });
    }
};
