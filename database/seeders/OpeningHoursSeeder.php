<?php

namespace Database\Seeders;

use App\Models\OpeningHours;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpeningHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OpeningHours::insert([
            ['day_of_week' => 1,'opening_time' => '06:00:00','closing_time' => '19:00:00'],
            ['day_of_week' => 2,'opening_time' => '06:00:00','closing_time' => '19:00:00'],
            ['day_of_week' => 3,'opening_time' => '06:00:00','closing_time' => '19:00:00'],
            ['day_of_week' => 4,'opening_time' => '06:00:00','closing_time' => '19:00:00'],
            ['day_of_week' => 5,'opening_time' => '06:00:00','closing_time' => '19:00:00'],
            ['day_of_week' => 6,'opening_time' => '06:00:00','closing_time' => '19:00:00'],
            ['day_of_week' => 7,'opening_time' => '06:00:00','closing_time' => '19:00:00'],
        ]);
    }
}
