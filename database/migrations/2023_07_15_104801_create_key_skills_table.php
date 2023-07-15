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
        Schema::create('key_skills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_application')->unsigned()->nullable();
            $table->foreign('job_application')->references('id')->on('job_applications')->onDelete('cascade')->onUpdate('cascade');
            $table->string('skill', 255);
            $table->text('level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_skills');
    }
};
