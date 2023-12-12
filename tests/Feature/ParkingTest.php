<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParkingTest extends TestCase
{
    use WithFaker,DatabaseTransactions;

    /**
     * Test to check whether getavailability returns dates
     *
     * @return void
     */
    public function test_to_check_availability()
    {        
        //Do POST call to get back availabiltity and prices
        $response = $this->getJson('/api/getAvailability?start_date=2023-08-09&end_date=2023-08-15');
        //Check the response has the following JSON structure
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'results' => [
                         '*' => ['availability'],
                     ],
                 ]);
        
    }
    /**
     * Test to see whether an reserveration can be created
     */
    public function test_to_create_an_reservation()
    {
        //Allocate an random space for testing
        $randomSpace =  rand(1, 10);
        $startDate = $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d H:i:s');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 weeks')->format('Y-m-d H:i:s');
        //Do the Post Call
        $response = $this->postJson('/api/uploadReservation',[
            'parking_space_id' => $randomSpace,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);    

        //get response data in JSON format
        $responseData = $response->json();
        if(isset($responseData['errors'])){
            $this->fail($responseData['message']);
        }
        //if response has created the reservation or has outputted an message
        if($responseData['created']){
            //Request should return an 201 request
            $response->assertStatus(201);
            //retrieve the created reservation
            $reservation = Reservation::find($responseData['reservation_id']);
            // Assert that the reservation was created in the database
            $this->assertTrue(!is_null($reservation), 'The reservation should not be null');
        }elseif($response->assertStatus(400))
        {
            $this->fail('Parking Space '.$randomSpace. ' is not available');
        }
        else{
            //Else the response should be 200 anything else it'll fail
            $response->assertStatus(200);
        }
    }
    /**
     * Test to update reservation
     */
    public function test_to_update_an_reservation()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d H:i:s');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 weeks')->format('Y-m-d H:i:s');
        //get a random ID
        $id = Reservation::inRandomOrder()->first();

        //Do the PUT Call
        $response = $this->putJson('/api/updateReservation/'.$id->reservation_id,[
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
        //return the response in JSON format
        $responseData = $response->json();
        //Fail the test if 400 is received, since update not possible
        if ($response->status() === 400) {
            $this->fail($responseData['error']);
        }

        //check if 200 status is received
        $response->assertStatus(200);
        $reservation = Reservation::find($responseData['reservation_id']);

        //check if the startDate and EndDate have been updated to the ones from DB
        if($responseData['updatedstart_time']){
            $reservation->assertEquals($startDate,$reservation->start_time);
        }
        if($responseData['updatedend_time'])
        {
            $reservation->assertEquals($endDate,$reservation->end_time);
        }
    }
    /**
     * Test to delete an reservation 
     * 
     */
    public function test_to_delete_an_reservation()
    {
        $startDate = $this->faker->dateTimeBetween('+1 week', '+1 week')->format('Y-m-d H:i:s');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 weeks')->format('Y-m-d H:i:s');

        
        //Allocate an random space for testing
        $randomSpace =  rand(1, 10);
        //Do the Post Call
        $response = $this->postJson('/api/uploadReservation',[
            'parking_space_id' => $randomSpace,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);  
        $responseData = $response->json();

        //Created is true from the JSON data if reservation is created
        if($responseData['created']){
            $response->assertStatus(201);
        
            //retrieve the first random ID to delete
            $reservation = Reservation::find($responseData['reservation_id']);

            //Do the delete Call
            $responseDelete = $this->deleteJson('/api/deleteReservation/'.$reservation->reservation_id);
            $responseDelete->assertStatus(200);

            // Attempt to retrieve the deleted reservation from the database
            $deletedReservation = Reservation::find($reservation->reservation_id);

            // Assert that the deleted reservation is null, indicating it was successfully deleted
            $this->assertNull($deletedReservation);
        }else{
            //if the reservation has conflicts send a message.
            $response->assertStatus(400);
            $this->fail('Unable to create an Reservation to delete due to conflicts creating an reservation');
        }
    }
}
