<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpoolsTable extends Migration
{
    public function up()
    {
        Schema::create('spools', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('color_hex')->nullable();
            $table->string('brand')->nullable();
            $table->string('cost')->nullable();
            $table->string('material')->nullable();
            $table->string('diameter')->nullable();
            $table->json('weights');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
