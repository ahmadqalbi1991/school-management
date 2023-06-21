<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [];
        for ($i = 1; $i <= 10; $i++) {
            $classes[] = [
                'class' => 'Grade ' . $i,
                'status' => 1
            ];
        }
        return SchoolClass::insert($classes);
    }
}
