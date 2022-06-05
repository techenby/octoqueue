<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->string('status')->nullable()->after('api_key');
        });
    }
};
