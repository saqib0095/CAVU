<?php

namespace Tests\Unit;

use App\Models\ParkingSpace;
use App\Models\Reservation;
use PHPUnit\Framework\TestCase;

class ParkingTest extends TestCase
{
    /**
     * test to see if creating an reservation is possible
     */
    public function test_can_create_reservation()
    {
        //create an reservation
        $reservation = Reservation::factory(10)->create();
        // Assert
        $this->assertInstanceOf(Reservation::class, $reservation);
        $this->assertTrue($reservation->start_time->isFuture());
        $this->assertTrue($reservation->end_time->isFuture());
    }
    /**
     * Test to see if there's ten parking spaces inserted in DB
     */
    public function test_check_parking_spaces()
    {
        //check to see if there's ten parking IDs
        $parkingSpace = ParkingSpace::all();
        $this->assertCount(10,$parkingSpace);   
    }
    
}
