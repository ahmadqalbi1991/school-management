<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddDataInSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $subjects = [
            ['title' => 'Language Activities', 'slug' => Str::slug('Language Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Mathematical Activities', 'slug' => Str::slug('Mathematical Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Environmental Activities', 'slug' => Str::slug('Environmental Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Psychomotor and Creative Activities', 'slug' => Str::slug('Psychomotor and Creative Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Religious Education Activities', 'slug' => Str::slug('Religious Education Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Pre Braille Activities', 'slug' => Str::slug('Pre Braille Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Literacy Activities or Braille Literacy Activities', 'slug' => Str::slug('Literacy Activities or Braille Literacy Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Kiwashili Language Activities', 'slug' => Str::slug('Kiwashili Language Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Kenya Sign Language (for deafs)', 'slug' => Str::slug('Kenya Sign Language (for deafs)'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'English Language Activities', 'slug' => Str::slug('English Language Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Hygiene and Nutrition Activities', 'slug' => Str::slug('Hygiene and Nutrition Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Movement and Creative Activities', 'slug' => Str::slug('Movement and Creative Activities'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'English', 'slug' => Str::slug('English'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Kiwashili or Kenya Sign Language', 'slug' => Str::slug('Kiwashili or Kenya Sign Language'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Home Science', 'slug' => Str::slug('Home Science'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Agriculture', 'slug' => Str::slug('Agriculture'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Science and Technology', 'slug' => Str::slug('Science and Technology'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Mathematics', 'slug' => Str::slug('Mathematics'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Religious Education (CRE or IRE or HRE)', 'slug' => Str::slug('Religious Education (CRE or IRE or HRE)'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Creative Arts', 'slug' => Str::slug('Creative Arts'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Physical and Health Education', 'slug' => Str::slug('Physical and Health Education'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Social Studies', 'slug' => Str::slug('Social Studies'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Visual Arts', 'slug' => Str::slug('Visual Arts'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Performing Arts', 'slug' => Str::slug('Performing Arts'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Computer Arts', 'slug' => Str::slug('Computer Arts'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Foreign Languages (German, French, Mandarian, or Arabic)', 'slug' => Str::slug('Foreign Languages (German, French, Mandarian, or Arabic)'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Kenyan Sign Language', 'slug' => Str::slug('Kenyan Sign Language'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Indigenous  Languages', 'slug' => Str::slug('Indigenous  Languages'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Kiwashili or Kenyan Sign Language', 'slug' => Str::slug('Kiwashili or Kenyan Sign Language'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Integrated Science', 'slug' => Str::slug('Integrated Science'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Health Education', 'slug' => Str::slug('Health Education'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Pre-Technical and Pre-Career Education', 'slug' => Str::slug('Pre-Technical and Pre-Career Education'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Business Studies', 'slug' => Str::slug('Business Studies'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Life Skills', 'slug' => Str::slug('Life Skills'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Sports and Physical Education', 'slug' => Str::slug('Sports and Physical Education'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Legal and Ethical issues in Arts', 'slug' => Str::slug('Legal and Ethical issues in Arts'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['title' => 'Communication Skills', 'slug' => Str::slug('Communication Skills'), 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
        ];

        \App\Models\Subjects::insert($subjects);
    }
}
