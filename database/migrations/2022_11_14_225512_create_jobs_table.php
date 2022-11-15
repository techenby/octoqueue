<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->index();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('print_type_id')->constrained();
            $table->foreignId('printer_id')->nullable()->constrained();
            $table->foreignId('material_id')->nullable()->constrained();
            $table->string('name');
            $table->string('color_hex')->nullable();
            $table->json('files')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('failed_at')->nullable();
            $table->double('material_used')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
