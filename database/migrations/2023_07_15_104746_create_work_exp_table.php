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
        Schema::create('work_exp', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_application')->unsigned()->nullable();
            $table->foreign('job_application')->references('id')->on('job_applications')->onDelete('cascade')->onUpdate('cascade');
            $table->string('comp_name', 255);
            $table->string('designation', 255);
            $table->date('from_date', 255);
            $table->date('to_date', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_exp');
    }
};
