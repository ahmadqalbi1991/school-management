<?php

use App\Models\SchoolClass;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataInSchoolClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $classes = [];
        for ($i = 1; $i <= 12; $i++) {
            $classes[] = [
                'class' => 'Grade ' . $i,
                'status' => 1
            ];
        }

        SchoolClass::insert($classes);
    }
}
