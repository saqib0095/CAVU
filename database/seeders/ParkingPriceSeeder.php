<?php

namespace Database\Seeders;

use App\Models\ParkingPrices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ParkingPrices::insert([
            ['id' => 1, 'season' => 'Winter', 'day_type' => 'Weekday', 'price' => 10],
            ['id' => 2, 'season' => 'Winter', 'day_type' => 'Weekend', 'price' => 12],
            ['id' => 3, 'season' => 'Summer', 'day_type' => 'Weekday', 'price' => 15],
            ['id' => 4, 'season' => 'Summer', 'day_type' => 'Weekend', 'price' => 18],

        ]);
    }
}
