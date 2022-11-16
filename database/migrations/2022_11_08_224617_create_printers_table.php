<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained();
            $table->string('name');
            $table->string('status')->nullable();
            $table->string('model')->nullable();
            $table->string('url')->nullable();
            $table->text('api_key')->nullable();
            $table->timestamps();
        });
    }
};
