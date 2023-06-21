<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('stream_id')->nullable();
            $table->unsignedBigInteger('term_id')->nullable();
            $table->unsignedBigInteger('strand_id')->nullable();
            $table->unsignedBigInteger('sub_strand_id')->nullable();
            $table->unsignedBigInteger('learning_activity_id')->nullable();
            $table->unsignedBigInteger('learner_id')->nullable();
            $table->unsignedBigInteger('performance_level_id')->nullable();
            $table->foreign('class_id')->on('school_classes')->references('id');
            $table->foreign('stream_id')->on('streams')->references('id');
            $table->foreign('term_id')->on('terms')->references('id');
            $table->foreign('strand_id')->on('strands')->references('id');
            $table->foreign('sub_strand_id')->on('substrands')->references('id');
            $table->foreign('learning_activity_id')->on('learning_activities')->references('id');
            $table->foreign('learner_id')->on('users')->references('id');
            $table->foreign('performance_level_id')->on('performance_levels')->references('id');
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
        Schema::dropIfExists('student_assessments');
    }
}
