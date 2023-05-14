<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_template', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('job_id');

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('job_id')->references('id')->on('jobs');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_template');
    }
};
