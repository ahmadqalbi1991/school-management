<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLearnersColsInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('admission_number')->nullable();
            $table->string('upi_number')->nullable();
            $table->unsignedBigInteger('stream_id')->nullable();
            $table->foreign('stream_id')->references('id')->on('streams');
            $table->string('parent_name')->nullable();
            $table->string('parent_phone_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admission_number');
            $table->dropColumn('parent_name');
            $table->dropColumn('parent_phone_number');
            $table->dropColumn('stream_id');
            $table->dropColumn('upi_number');
        });
    }
}
