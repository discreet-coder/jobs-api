<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('edu_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_application')->unsigned()->nullable();
            $table->foreign('job_application')->references('id')->on('job_applications')->onDelete('cascade')->onUpdate('cascade');
            $table->string('board', 255);
            $table->year('pass_year', 255);
            $table->string('result', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edu_detail');
    }
};
