<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('team_id')->index();
            $table->unsignedBigInteger('job_type_id')->index()->nullable();
            $table->unsignedBigInteger('printer_id')->index()->nullable();
            $table->unsignedBigInteger('spool_id')->index()->nullable();
            $table->unsignedBigInteger('color_id')->index()->nullable();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->json('files')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->double('filament_used')->nullable();
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
        Schema::dropIfExists('print_jobs');
    }
}
