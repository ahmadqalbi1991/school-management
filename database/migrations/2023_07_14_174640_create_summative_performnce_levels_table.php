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
        Schema::create('summative_performnce_levels', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->mediumText('detail')->nullable();
            $table->float('min_point')->nullable()->default(0);
            $table->float('max_point')->nullable()->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->on('users')->references('id');
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
        Schema::dropIfExists('summative_performnce_levels');
    }
};
