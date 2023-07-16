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
        Schema::create('summative_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('stream_id')->nullable();
            $table->unsignedBigInteger('term_id')->nullable();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('learner_id')->nullable();
            $table->unsignedBigInteger('performance_level_id')->nullable();
            $table->float('points')->nullable();
            $table->foreign('subject_id')->on('subjects')->references('id');
            $table->foreign('class_id')->on('school_classes')->references('id');
            $table->foreign('stream_id')->on('streams')->references('id');
            $table->foreign('term_id')->on('terms')->references('id');
            $table->foreign('exam_id')->on('exams')->references('id');
            $table->foreign('learner_id')->on('users')->references('id');
            $table->foreign('performance_level_id')->on('summative_performnce_levels')->references('id');
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
        Schema::dropIfExists('summative_assessments');
    }
};
