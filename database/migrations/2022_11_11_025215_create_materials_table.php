<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained();
            $table->string('brand')->nullable();
            $table->string('cost')->nullable();
            $table->string('color')->nullable();
            $table->string('color_hex')->nullable();
            $table->string('printer_type');
            $table->string('type')->nullable();
            $table->string('diameter')->nullable();
            $table->string('empty')->nullable();
            $table->json('weights')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
