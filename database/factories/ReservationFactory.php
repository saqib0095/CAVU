<?php

namespace Database\Factories;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //create fake data
        $faker = Faker::create();
         // Generate a random start date within the next week
        $startDate = $faker->dateTimeBetween('now', '+1 week');

        // Generate a random finish date within 1 day to 2 weeks from the start date
        $endDate = $faker->dateTimeBetween($startDate, '+2 weeks');

        return [
            'space_id' => rand(1,10),
            'start_time' => $startDate,
            'end_time' => $endDate
        ];
    }
}
