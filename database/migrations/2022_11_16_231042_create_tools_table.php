<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained();
            $table->foreignId('printer_id')->constrained();
            $table->foreignId('material_id')->nullable()->constrained();
            $table->string('name');
            $table->timestamps();
        });
    }
};
