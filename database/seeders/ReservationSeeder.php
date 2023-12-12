<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\ParkingSpace;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get the Spaces available
        $parkingSpaces = ParkingSpace::all();

        // Randomly choose a subset of parking spaces to create reservations
        $spacesToReserve = $parkingSpaces->random(rand(1, 10)); // Adjust the range as needed

        // Generate sample reservations for the chosen spaces
        foreach ($spacesToReserve as $space) {
            Reservation::create([
                'space_id' => $space->id,
                'start_time' => Carbon::now()->addDays(1)->addHours(rand(1, 24)), // Random start time within the next 24 hours
                'end_time' => Carbon::now()->addDays(1)->addHours(rand(25, 48)), // Random end time between 25 and 48 hours from now
            ]);
        }
    }
}
